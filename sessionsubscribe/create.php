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
$sessionId    = intval($data->sessionId);
$userEmail    = htmlspecialchars(strip_tags(trim($data->userEmail)));

if( !$sessionId OR !$userEmail ) $utilities->error("no POST data");

$row = $table->select_Session($userEmail, $sessionId);

if( !$row ) $utilities->error("Не найдена сессия");
if( !$row['email'] ) $utilities->error("Пользователь не найден");
if( $row['session_id'] ) $utilities->error("Вы уже записаны");
if( $row['max_users'] <= $row['count_session'] ) {
    die(json_encode(
        array(
                "status" => "ok",
                "message" => "Извините, все места заняты"
            )
    ));
}

if( $table->create_session_record($row['user_id'], $sessionId) ) {
    echo json_encode(
        array(
            "status" => "ok",
            "messege" => "Спасибо, вы успешно записаны!"
        )
    );
} else {
    $utilities->error("error sql add");
}
?>