<?php

// Authorized team tokens that you would need to get when creating a slash command. Same script can serve multiple teams, just keep adding tokens to the array below.
$tokens = array(
	"tW0wHpLokfme5zJppcZSJUDg"
);

// check auth
if (!in_array($_REQUEST['token'], $tokens)) {
	botRespond("ERROR", "*Unauthorized token!*");
	die();
}

$req_user_user_id = $_REQUEST['user_id'];

// split arguments into array.
$args = explode(" ", $_REQUEST['text']);

botRespond("Time", time());

// Throw error if there are too many arguments.
if (count($args) > 1) die("Too many arguments.");
else {
	// Load database info from dbinfo.
	include_once 'include/dbinfoExample.php';
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$user_user_id = filter_var($req_user_user_id, FILTER_SANITIZE_STRING);
	botRespond("user_user_id", $user_user_id);

	//get user id.
	$user_id = getUserId($dbh, $user_user_id);
	if ($user_id == false) {
		botRespond("ERROR", "Could not find user id");
		addNewUser($dbh, $user_user_id);
		$user_id = getUserId($dbh, $user_user_id);
		if ($user_id == false) die("Could not get user");
		else botRespond("DB", "Added new user.");
	}
	botRespond("user_id", $user_id);


	$user_meta = getUserMeta($dbh, $user_id);
	if ($user_meta == false) { }
	botRespond("user_meta", $user_meta);

	// Check if there are no arguments.
	if ($args[0] == "") {
		//TODO: Set user to active on "other".

	} else {

		//TODO: Set any active project to inactive.
		//TODO: Set user to active on project.
		$project_name = filter_var($args[0], FILTER_SANITIZE_STRING); // first argument specifes project name.
		botRespond("project_name", $project_name);

		// get project id.
		$project_id = getProjectId($dbh, $project_name);
		botRespond("project_id", $project_id);

		// get project meta.
		//$project_meta = getProjectMeta($dbh, $project_id);
		//botRespond("project_meta", $project_meta);


		//if("PROJECT IS ACTIVE")checkoutActiveProject();

		$connection_id = getProjectConnection($dbh, $user_id, $project_id);
		if ($connection_id == false) {
			createNewProjectConnection($dbh, $user_id, $project_id);
			$connection_id = getProjectConnection($dbh, $user_id, $project_id);
			if ($connection_id == false) die("Could not get connection id.");
		}

 		unsetActiveProject($dbh, $user_id);


		setActiveProject($dbh, $user_id, $project_id);
	}
}

/* users table */

/* Create */

// Add a new user to the users table.
function addNewUser($pdo, $user_user_id)
{
	$stmt = $pdo->prepare("INSERT INTO users(userId) VALUES (:userId)");
	$stmt->bindParam(':userId', $user_user_id);
	$stmt->execute();
}

/* Read */

// fetch the id of the user from database using user_id.
function getUserId($pdo, $user_user_id)
{
	$stmt = $pdo->prepare("SELECT id FROM users WHERE userId = :userId");
	$stmt->bindParam(':userId', $user_user_id);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	botRespond("getUserId", $result);
	if ($result == false) return false;
	else return array_values($result)[0];
}

/* userMeta table */


// Add a new userMeta to the userMeta table.
function addNewUserMeta($pdo, $user_id)
{
	$stmt = $pdo->prepare("INSERT INTO userMeta(userId, metaKey, value) VALUES (userId = :userId, metaKey = :metaKey, value = :value");
	$stmt->bindParam(':userId', $user_id);
	$stmt->bindParam(':metaKey', time());
	$stmt->bindParam(':value', 0);
	$stmt->execute();
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

/* projects table */

// fetch the id of the specified project from database using projectName.
function getProjectId($pdo, $project_name)
{
	$sql = "SELECT id FROM projects WHERE name = :name";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $project_name);
	$stmt->execute();
	$result =  $stmt->fetch(PDO::FETCH_ASSOC);
	if ($result == false) return false;
	else return array_values($result)[0];
}
/* projectMeta table */

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


/* Check in */

// Checks if there is 
function getProjectConnection($pdo, $u_id, $p_id)
{
	$sql = "SELECT id FROM projectConnections WHERE userId = :userId AND projectId = :projectId";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':userId', $u_id);
	$stmt->bindParam(':projectId', $p_id);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	if($result == false) return false;
	else return array_values($result)[0];
}

// Adds a new project connection to the projectConnections table.
function createNewProjectConnection($pdo, $u_id, $p_id)
{
	$stmt = $pdo->prepare("INSERT INTO projectConnections(userId, projectId) VALUES (:userId, :projectId)");
	$stmt->bindParam(':userId', $u_id);
	$stmt->bindParam(':projectId', $p_id);
	$stmt->execute();
}

// Checks in on the specified project.
function setActiveProject($pdo, $u_id, $p_id)
{
	$active = 1;
	$time = time();
	$stmt = $pdo->prepare("UPDATE projectConnections SET active = :active, checkedInAt = :checkedInAt WHERE userId = :userId AND projectId = :projectId");
	$stmt->bindParam(':userId', $u_id);
	$stmt->bindParam(':projectId', $p_id);
	$stmt->bindParam(':active', $active);
	$stmt->bindParam(':checkedInAt', $time);
	$stmt->execute();
}

// Checks out on any active project
function unsetActiveProject($pdo, $u_id)
{
	$true = 1;
	$active = 0;
	
	$time = time();
	$stmt = $pdo->prepare("SELECT checkedInAt FROM projectConnections  WHERE userId = :userId AND active = :true");
	
	$stmt->bindParam(':userId', $u_id);
	$stmt->bindParam(':true', $true);
	$stmt->execute();
	$chekedInAt = $stmt->fetch(PDO::FETCH_ASSOC);

	$addedTime = $time - $chekedInAt;

	$stmt = $pdo->prepare("UPDATE projectConnections SET active = :active, timeSpent = timeSpent + :addedTime WHERE userId = :userId AND active = :true");
	$stmt->bindParam(':userId', $u_id);
	$stmt->bindParam(':true', $true);
	$stmt->bindParam(':active', $active);
	$stmt->bindParam(':addedTime', $addedTime);
	
	$stmt->execute();
}


/*
	Helper Functions
*/

// Send information back to slack
function botRespond($tag, $output)
{
	echo ($tag) . "<br>";
	echo json_encode($output) . "<br><br>";
}
