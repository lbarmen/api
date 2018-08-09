<?php
if( !defined( 'E_DEPRECATED' ) ) {

    @error_reporting ( E_ALL ^ E_NOTICE );
    @ini_set ( 'error_reporting', E_ALL ^ E_NOTICE );

} else {

    @error_reporting ( E_ALL ^ E_DEPRECATED ^ E_NOTICE );
    @ini_set ( 'error_reporting', E_ALL ^ E_DEPRECATED ^ E_NOTICE );

}

@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if( $_GET['id'] == '1' ) {
    $url = '/api/table/read.php';
    $jsonData = array(
        'id'    => '',
        'table' => 'Session'
    );
} elseif( $_GET['id'] == '2' ) {
    $url = '/api/sessionsubscribe/create.php';
    $jsonData = array(
        'sessionId'  => '2',
        'userEmail'  => 'llbarmenll@ya.ru'
    );
} elseif( $_GET['id'] == '3' ) {
    $url = '/api/postnews/create.php';
    $jsonData = array(
        'userEmail'    => 'llbarmenll@ya.ru',
        'newsTitle'    => 'Новость2',
        'newsMessage'  => 'моя новость'
    );
}

if( $_GET['id'] ) {
    $ch = curl_init($url);

    $jsonDataEncoded = json_encode($jsonData);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $result = curl_exec($ch);
} else {
echo "Файловая структура
├─ api/
├─── config/
├────── database.php - подключение к бд
├────── table.php - настройка доступности таблиц
├─── objects/
├────── Table.php - работа с бд
├─── SessionSubscribe/
├────── create.php - запись на сессию *sessionId=?, *userEmail=?
├─── PostNews/
├────── create.php - вставить новость *userEmail=?, *newsTitle=?, *newsMessage=?
├─── shared/
├────── Utilities.php - вспомогательная функция показа ошибок
├─── table/
├────── read.php - получение данных из таблиц id=?, *table=?

* обязательные данные

Настройки:
Таблицы доступные для метода Table => read.php, написаны в настройках config => config.php

SQL:
/api/radio.sql

Пример работы:
/api/test.php?id=1 - /api/table/read.php показ таблицы Session
/api/test.php?id=2 - /api/sessionsubscribe/create.php запись на сессию
/api/test.php?id=3 - /api/postnews/create.php отправка новости
";
}
?>