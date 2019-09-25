<?php

// Include database and authentication info.
include_once 'include/dbinfo.php';
include_once 'include/auth.php';

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//Check for if the command has something written
if(!isset($_REQUEST['text'])){
    bot_respond('Please write a project name.');
    die();
}
	
$projectName = explode(" ", filter_var($_REQUEST['text'], FILTER_SANITIZE_STRING))[0];

$slackId = filter_var($_REQUEST['user_id'], FILTER_SANITIZE_STRING);
	
if($nameCheck.sizeof() > 1){
    bot_respond('Project names can only be one word.');
    die();
}

$dbUserId = getUserId($dbh, $slackId);

botRespond("userId", $dbUserId);

$dbProjectId = getProjectId($dbh, $projectName);

botRespond("projectId", $dbProjectId);

$dbConnectionId = getConnectionId($dbh, $dbUserId, $dbProjectId);

botRespond("connectionId", $dbConnectionId);

if(getConnectionActive($dbh, $dbConnectionId)){
    botRespond("ye", "ye");
    updateTime($dbh, $dbConnectionId);
}

botRespond("Time", gmdate('H:i:s', getTime($dbh, $dbConnectionId)));


botRespond("yeet", "yeet");
die();

// fetch the id of the user from database using user_id.
function getUserId($pdo, $userSlackId){
    $stmt = $pdo->prepare("SELECT id FROM users WHERE userId = :userId");
    $stmt->bindParam(':userId', $userSlackId);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result[0];
}

// fetch the id of the specified project from database using projectName.
function getProjectId($pdo, $dbProjectName){
    $stmt = $pdo->prepare("SELECT id FROM projects WHERE name = :name");
    $stmt->bindParam(':name', $dbProjectName);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result[0];
}

function getConnectionId($pdo, $dbUserId, $dbProjectId){
    $stmt = $pdo->prepare("SELECT id FROM projectConnections WHERE userId = :userId AND projectId = :projectId");
    $stmt->bindParam(':userId', $dbUserId);
    $stmt->bindParam(':projectId', $dbProjectId);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result[0];
}

function getConnectionActive($pdo, $dbConnectionId){
    $stmt = $pdo->prepare("SELECT active FROM projectConnections WHERE id = :connectionId");
    $stmt->bindParam(':connectionId', $dbConnectionId);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result[0];
}

function updateTime($pdo, $dbConnectionId){
    $active = 1;
    $now = time();
    $stmt = $pdo->prepare("UPDATE projectConnections SET timeSpent = (timeSpent + (:now - checkedInAt)), checkedInAt = :now WHERE id = :connectionId AND active = :active");
    $stmt->bindParam(':connectionId', $dbConnectionId);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':now', $now);
    $stmt->execute();
}

function getTime($pdo, $dbConnectionId){
    $stmt = $pdo->prepare("SELECT timeSpent FROM projectConnections WHERE id = :connectionId");
    $stmt->bindParam(':connectionId', $dbConnectionId);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result[0];
}

// Send information back to slack
function botRespond($tag, $output){
    echo ($tag) . ": ";
    echo ($output) . " \n";
}

?>