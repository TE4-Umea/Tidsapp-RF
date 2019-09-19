<?php

// Authorized team tokens that you would need to get when creating a slash command. Same script can serve multiple teams, just keep adding tokens to the array below.
$tokens = array(
	"tW0wHpLokfme5zJppcZSJUDg"
);

// check auth
if (!in_array($_REQUEST['token'], $tokens)) {
	bot_respond("*Unauthorized token!*");
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
	$user_id = get_user_id($dbh, $user_user_id);
	bot_respond($user_id);

	$user_meta = get_user_meta($dbh, $user_id);
	bot_respond($user_meta);

	// Check if there are no arguments.
	if ($args[0] == "") {
		//TODO: Set user to active on "other".
	} else {

		//TODO: Set any active project to inactive.
		//TODO: Set user to active on project.
		$proj_name = filter_var($args[0], FILTER_SANITIZE_STRING); // first argument specifes project name.
		bot_respond($proj_name);

		// get project id.
		$proj_id = get_project_id($dbh, $proj_name);
		bot_respond($proj_id);

		// get project meta.
		$proj_meta = get_project_meta($dbh, $proj_id);
		bot_respond($proj_meta);
	}
}
// fetch the id of the user from database using user_id.
function get_user_id($pdo, $user_user_id){
	$sql = "SELECT id FROM users WHERE userId = :userId";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':userId', $user_user_id);
	$stmt->execute();
	$result =  array_values($stmt->fetch(PDO::FETCH_ASSOC))[0];
	if ($result == false) die("Could not find user id");
	else return $result;
}

// fetch the projectMeta of the specified project from database using projectId.
function get_user_meta($pdo, $user_id)
{
	$sql = "SELECT * FROM userMeta WHERE userId = :userId";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':userId', $user_id);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($result == false) die("Could not find user meta.");
	else return $result;
}


// fetch the id of the specified project from database using projectName.
function get_project_id($pdo, $project_name)
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
function get_project_meta($pdo, $project_id)
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
function bot_respond($output)
{
	echo ($output) . "<br>";
	echo json_encode($output) . "<br><br>";
}
