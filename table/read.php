<?php
/*
=====================================================
 https://webarmen.com/
-----------------------------------------------------
 Copyright (c) 2018
=====================================================
 Данный код защищен авторскими правами
=====================================================
 Файл: read.php
-----------------------------------------------------
 Назначение: вывод данных из таблиц
=====================================================
*/

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
$id           = intval($data->id);
$table_mysql  = htmlspecialchars(strip_tags(trim($data->table)));

if( !in_array($table_mysql, $table_config) ) $utilities->error("Данная таблица не доступна, пожалуйста посмотрите доступные таблицы ".json_encode($table_config));

$table_left = $table_left_config[$table_mysql];
$table_left_param = $table_left_param_config[$table_left];

$products_arr            = array("status" => "ok");
$products_arr["payload"] = array();
$products_arr["payload"] = $table->read_table($id, $table_mysql, $products_arr["payload"], $table_left, $table_left_param);
if( !$products_arr["payload"] ) $utilities->error("no data in table");

echo json_encode($products_arr);
?>