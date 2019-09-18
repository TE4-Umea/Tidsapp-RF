<?php

$dbuser = 'rfBot';
$dbpass = 'rfBotPass';
$dbhost = 'localhost';
$dbname = 'testDB';

$dbh = new PDO('mysql:host=' . $dbhost . ';dbname=' . $dbname . ';charset=utf8mb4', $dbuser, $dbpass);
if ($dbh->connect_error) {
    die("Connection failed: " . $dbh->connect_error);
}
?>