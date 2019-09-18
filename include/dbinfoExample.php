<?php

$dbuser = 'rfBot';
$dbpass = 'rfBotPass';
$dbhost = 'localhost';
$dbname = 'testDB';

$dbh = new PDO('mysql:host=' . $dbhost . ';dbname=' . $dbname . ';charset=utf8mb4', $dbuser, $dbpass);

?>