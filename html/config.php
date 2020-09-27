
<?php
// Запуск сессии
session_start();
// Служит для отладки, показывает все ошибки, предупреждения и т.д.
error_reporting(E_ALL);
// Подключение файлов с функциями
include_once("functions.php");
// В этом массиве далее мы будем хранить сообщения системы, т.е. ошибки.
$messages=array();
// Данные для подключения к БД
$dbhost="192.168.0.83";
$dbhost="localhost";
$dbuser="root";
$dbpass="Sib-stack2020";
//$dbuser="root";
//$dbpass="AMsql456";
$dbname="Library";
// Вызываем функцию подключения к БД
connectToDB();
?>


