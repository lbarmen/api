<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset:UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../config/config.php';
include_once '../shared/utilities.php';
include_once '../objects/table.php';

$database =  new Database();
$db = $database->getConnection();
$utilities = new Utilities();
$table =     new Table($db);

$data         = json_decode(file_get_contents("php://input"));
$userEmail    = htmlspecialchars(strip_tags(trim($data->userEmail)));
$newsTitle    = htmlspecialchars(strip_tags(trim($data->newsTitle)));
$newsMessage  = htmlspecialchars(trim($data->newsMessage));

if( !$userEmail OR !$newsTitle OR !$newsMessage ) $utilities->error("no POST data");

$us = $table->select_user($userEmail);
if( !$us['id'] ) $utilities->error("Пользователь не найден");

$dc = $table->duplicate_check($us['id'], $newsTitle, $newsMessage);
if( $dc['ID'] ) $utilities->error("Повторная новость");

if( $table->create_news($us['id'], $newsTitle, $newsMessage) ) {
    echo json_encode(
        array(
            "status" => "ok",
            "messege" => "Спасибо, ваша новость сохранена!"
        )
    );
} else {
    $utilities->error("error sql add");
}
?>