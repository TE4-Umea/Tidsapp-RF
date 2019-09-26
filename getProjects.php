<?php

include_once 'include/auth.php';
include_once 'include/dbinfo.php';

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $dbh->prepare('SELECT name FROM projects');
$stmt->execute();

botRespond("Project list:");
botRespond("```");
while ($project = $stmt->fetch()) {
    botRespond($project['name']);
};
botRespond("```");

function botRespond($message)
{
    echo ($message . "\n");
}
