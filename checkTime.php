<?php

// Authorized team tokens that you would need to get when creating a slash command. Same script can serve multiple teams, just keep adding tokens to the array below.
$tokens = array(
	"P2zoHA16O3ZuQQpQYpE7EC7M"
);
// check auth
if (!in_array($_REQUEST['token'], $tokens)) {
	botRespond("ERROR", "*Unauthorized token!*");
	die();
}

//Check for if the command has something written
if(!isset($_POST['text'])){
    bot_respond('Please write a project name.');
    die();
}
	
$nameCheck = explode(" ", $_POST['text']);
	
if($nameCheck.sizeof() > 1){
    bot_respond('Project names can only be one word.');
}
	
$filteredProjectName = filter_input(INPUT_POST, "text", FILTER_SANITIZE_STRING);

// Include database and authentication info.
include_once 'include/dbinfo.php';
include_once 'include/auth.php';
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

function getConnectionCheckedInAt($pdo, $dbUserId){
    $true = 1;
    $stmt = $pdo->prepare("SELECT checkedInAt FROM projectConnections WHERE userId = :userId AND active = :true");
    $stmt->bindParam(':userId', $dbUserId);
    $stmt->bindParam(':true', $true);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result[0];
}

function getTime($pdo, $dbUserId){
    $true = true;
    $stmt = $pdo->prepare("SELECT timeSpent FROM projectConnections WHERE userId = :userId AND active = :true");
    $stmt->bindParam(':userId', $dbUserId);
    $stmt->bindParam(':true', $true);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result[0];
}

// Send information back to slack
function botRespond($tag, $output)
{
	echo ($tag) . "<br>";
	echo json_encode($output) . "<br><br>";
}

?>