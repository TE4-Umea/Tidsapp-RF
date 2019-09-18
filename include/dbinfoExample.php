<?php

$dbuser = '';
$dbpass = '';
$dbhost = '';
$dbname = '';

$dbh = new PDO('mysql:host=' . $dbhost . ';dbname=' . $dbname . ';charset=utf8mb4', $dbuser, $dbpass);

?>