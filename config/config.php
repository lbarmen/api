<?php
/*
    Настройка вывода доступных таблиц, для метода table => read
*/

//Таблицы с открытым доступом
$table_config = array("News", "Session");
//Поиск дополнительных таблиц left join
$table_left_config = array("Session" => "Speaker");
//Поля дополнительных таблиц left join
$table_left_param_config = array("Speaker" => "Speaker.ID AS SpeakerID, Speaker.Name AS SpeakerName");
?>