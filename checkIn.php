<?php


function dumper($request){
    echo "<pre>" . print_r($request, 1) . "</pre>";
}

// Authorized team tokens that you would need to get when creating a slash command. Same script can serve multiple teams, just keep adding tokens to the array below.
$tokens = array(
	"P2zoHA16O3ZuQQpQYpE7EC7M"
);

// check auth
if (!in_array($_REQUEST['token'], $tokens)) {
	botRespond("ERROR", "*Unauthorized token!*");
	die();
}

$slackId = $_REQUEST['user_id'];

// split arguments into array.
$args = explode(" ", $_REQUEST['text']);

botRespond("Time", time());

dumper($_REQUEST['user_id']);

// Throw error if there are too many arguments.
if (count($args) > 1) die("Too many arguments.");
else {
	// Load database info from dbinfo.
	include_once 'include/dbinfo.php';
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$filteredSlackId = filter_input($slackId, FILTER_SANITIZE_STRING);
	botRespond("user_user_id", $filteredSlackId);

	//get user id.
	$user_id = getUserId($dbh, $filteredSlackId);
	if ($user_id == false) {
		botRespond("ERROR", "Could not find user id");
		addNewUser($dbh, $filteredSlackId);
		$user_id = getUserId($dbh, $filteredSlackId);
		if ($user_id == false) die("Could not get user");
		else botRespond("DB", "Added new user.");
	}
	botRespond("user_id", $user_id);


	$project_name;

	if ($args[0] == "") $project_name = "Other"; // Sets project name to other if no arguments are provided.
	else $project_name = filter_var($args[0], FILTER_SANITIZE_STRING); // first argument specifes project name.
	botRespond("project_name", $project_name);

	// get project id.
	$project_id = getProjectId($dbh, $project_name);
	botRespond("project_id", $project_id);

	$connection_id = getProjectConnection($dbh, $user_id, $project_id);
	if ($connection_id == false) {
		createNewProjectConnection($dbh, $user_id, $project_id);
		$connection_id = getProjectConnection($dbh, $user_id, $project_id);
		if ($connection_id == false) die("Could not get connection id.");
	}

	unsetActiveProject($dbh, $user_id);

	setActiveProject($dbh, $user_id, $project_id);
}

/* users table */

/* Create */

// Add a new user to the users table.
function addNewUser($pdo, $userSlackId)
{
	$stmt = $pdo->prepare("INSERT INTO users(userId) VALUES (:userId)");
	$stmt->bindParam(':userId', $userSlackId);
	$stmt->execute();
}

/* Read */

// fetch the id of the user from database using user_id.
function getUserId($pdo, $userSlackId)
{
	$stmt = $pdo->prepare("SELECT id FROM users WHERE userId = :userId");
	$stmt->bindParam(':userId', $userSlackId);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	botRespond("getUserId", $result);
	if ($result == false) return false;
	else return array_values($result)[0];
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
	if ($result == false) return false;
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


	$stmt = $pdo->prepare("SELECT checkedInAt FROM projectConnections WHERE userId = :userId AND active = :true");

	$stmt->bindParam(':userId', $u_id);
	$stmt->bindParam(':true', $true);
	$stmt->execute();
	$chekedInAt = array_values($stmt->fetch(PDO::FETCH_ASSOC))[0];

	$stmt = $pdo->prepare("SELECT timeSpent FROM projectConnections WHERE userId = :userId AND active = :true");

	$stmt->bindParam(':userId', $u_id);
	$stmt->bindParam(':true', $true);
	$stmt->execute();
	$timeSpent = array_values($stmt->fetch(PDO::FETCH_ASSOC))[0];


	$timeSpent += (time() - $chekedInAt);

	botRespond("timeSpent", $timeSpent);

	$stmt = $pdo->prepare("UPDATE projectConnections SET active = :active, timeSpent = :timeSpent WHERE userId = :userId AND active = :true");
	$stmt->bindParam(':userId', $u_id);
	$stmt->bindParam(':true', $true);
	$stmt->bindParam(':active', $active);
	$stmt->bindParam(':timeSpent', $timeSpent);

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
