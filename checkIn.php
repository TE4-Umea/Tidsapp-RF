<?php

// Authorized team tokens that you would need to get when creating a slash command. Same script can serve multiple teams, just keep adding tokens to the array below.
$tokens = array(
	"tW0wHpLokfme5zJppcZSJUDg"
);

// check auth
if (!in_array($_REQUEST['token'], $tokens)) {
	botRespond("*Unauthorized token!*");
	die();
}

$user_user_id = $_REQUEST['user_id'];

//split arguments into array.
$args = explode(" ", $_REQUEST['text']);

//Throw error if there are too many arguments.
if (count($args) > 1) die("Too many arguments.");
else {
	// Load database info from dbinfo.
	include_once 'include/dbinfoExample.php';
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//get user id.
	$user_id = getUserId($dbh, $user_user_id);
	if ($user_id == false) {
		bot_respond("Could not find user id");
		addNewUser($dbh, $user_user_id);
		$user_id = getUserId($dbh, $user_user_id);
		if($user_id == false) die("Could not get user");
		else bot_respond("Added new user.");
	}
	botRespond($user_id);


	$user_meta = getUserMeta($dbh, $user_id);
	if($user_meta == false){

	}
	botRespond($user_meta);

	// Check if there are no arguments.
	if ($args[0] == "") {
		//TODO: Set user to active on "other".
	} else {

		//TODO: Set any active project to inactive.
		//TODO: Set user to active on project.
		$proj_name = filter_var($args[0], FILTER_SANITIZE_STRING); // first argument specifes project name.
		botRespond($proj_name);

		// get project id.
		$proj_id = getProjectId($dbh, $proj_name);
		botRespond($proj_id);

		// get project meta.
		$proj_meta = getProjectMeta($dbh, $proj_id);
		botRespond($proj_meta);
	}
}

/*
	User
*/

/* Create */

function addNewUser($pdo, $user_user_id){
	$stmt = $pdo->prepare("INSERT INTO users(id, userId) VALUES (userId = :userId)");
	$stmt->bindParam(':userId', $user_user_id);
	$stmt->execute();
}

function addNewUserMeta($pdo, $user_id){
	$stmt = $pdo->prepare("INSERT INTO userMeta(id, userId, metaKey, value) VALUES (userId = :userId, metaKey = :metaKey, value = :value");
	$stmt->bindParam(':userId', $pdo, $user_id);
	$stmt->bindParam(':metaKey', time());
	$stmt->bindParam(':value', 0);
	$stmt->execute();
}

/* Read */

// fetch the id of the user from database using user_id.
function getUserId($pdo, $user_user_id)
{
	$stmt = $pdo->prepare("SELECT id FROM users WHERE userId = :userId");
	$stmt->bindParam(':userId', $user_user_id);
	$stmt->execute();
	$result =  array_values($stmt->fetch(PDO::FETCH_ASSOC))[0];
	bot_respond($result);
	return $result;
}

// fetch the projectMeta of the specified project from database using projectId.
function getUserMeta($pdo, $user_id)
{
	$sql = "SELECT * FROM userMeta WHERE userId = :userId";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':userId', $user_id);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	//if ($result == false) die("Could not find user meta.");
	//else
	 return $result;
}

/*
	Project
*/

// fetch the id of the specified project from database using projectName.
function getProjectId($pdo, $project_name)
{
	$sql = "SELECT id FROM projects WHERE name = :name";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $project_name);
	$stmt->execute();
	$result =  array_values($stmt->fetch(PDO::FETCH_ASSOC))[0];
	if ($result == false) die("Could not find project id");
	else return $result;
}

// fetch the projectMeta of the specified project from database using projectId.
function getProjectMeta($pdo, $project_id)
{
	$sql = "SELECT * FROM projectMeta WHERE projectId = :projectId";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':projectId', $project_id);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($result == false) die("Could not find project meta.");
	else return $result;
}

/*
	Helper Functions
*/

// Send information back to slack
function botRespond($output)
{
	echo ($output) . "<br>";
	echo json_encode($output) . "<br><br>";
}
