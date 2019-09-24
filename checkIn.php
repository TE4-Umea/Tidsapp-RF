<?php

function dumper($request)
{
    echo "<pre>" . print_r($request, 1) . "</pre>";
}

// Authorized team tokens that you would need to get when creating a slash command. Same script can serve multiple teams, just keep adding tokens to the array below.
$tokens = array(
    "P2zoHA16O3ZuQQpQYpE7EC7M",
);

// check auth
if (!in_array($_REQUEST['token'], $tokens)) {
    botRespond("ERROR", "*Unauthorized token!*");
    die();
}

$slackId = filter_var($_REQUEST['user_id'], FILTER_SANITIZE_STRING);

// split arguments into array.
$args = explode(" ", $_REQUEST['text']);

// Throw error if there are too many arguments.
if (count($args) > 1) {
    die("Too many arguments.");
} else {
    // Load database info from dbinfo.
    include_once 'include/dbinfo.php';
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //  get user id.
    $userId = getUserId($dbh, $slackId);
    if ($userId == false) {
        botRespond("DB", "Could not find user id.");

        addNewUser($dbh, $slackId);
        $userId = getUserId($dbh, $slackId);

        botRespond("DB", "Added new user.");

    }

    $project_name = "Other"; // Default project name, this will be used if no arguments are provided.

    if ($args[0] !== "") {
        $project_name = filter_var($args[0], FILTER_SANITIZE_STRING);
    } // first argument specifes project name.

    //get project id.
    $project_id = getProjectId($dbh, $project_name);

    $connection_id = getProjectConnection($dbh, $userId, $project_id);

    if ($connection_id == false) {
        createNewProjectConnection($dbh, $userId, $project_id);
        $connection_id = getProjectConnection($dbh, $userId, $project_id);

        botRespond("Connection", "Established connection to project.");
    }

    //die("oof");
    try {
        unsetActiveProject($dbh, $userId);
    } catch (Exception $e) {
        botRespond("connection", $e);
    }
    setActiveProject($dbh, $userId, $project_id);

    botRespond("Checked in on project", $project_name);
    botRespond("Current total time", gmdate('H:i:s', getTime($dbh, $userId)));
}

/* users table */

//Add a new user to the users table.
function addNewUser($pdo, $userSlackId){
    $stmt = $pdo->prepare("INSERT INTO users(userId) VALUES (:userId)");

    $stmt->bindParam(':userId', $userSlackId);

    $stmt->execute();
}

// fetch the id of the user from database using user_id.
function getUserId($pdo, $userSlackId){
    $stmt = $pdo->prepare("SELECT id FROM users WHERE userId = :userId");
    $stmt->bindParam(':userId', $userSlackId);
    $stmt->execute();

    $result = $stmt->fetch();
    return $result[0];
}

/* projects table */

// fetch the id of the specified project from database using projectName.
function getProjectId($pdo, $dbProjectName){
    $stmt = $pdo->prepare("SELECT id FROM projects WHERE name = :name");
    $stmt->bindParam(':name', $dbProjectName);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result[0];
}

/* connections */

// Adds a new project connection to the projectConnections table.
function createNewProjectConnection($pdo, $dbUserId, $dbProjectId){

    $now = time();
    $timeSpent = 0;
    $stmt = $pdo->prepare("INSERT INTO projectConnections(userId, projectId, checkedInAt, timeSpent) VALUES (:userId, :projectId, :now, :timeSpent)");
    $stmt->bindParam(':userId', $dbUserId);
    $stmt->bindParam(':projectId', $dbProjectId);
    $stmt->bindParam(':now', $now);
    $stmt->bindParam(':timeSpent', $timeSpent);

    $stmt->execute();
}

function getProjectConnection($pdo, $dbUserId, $dbProjectId){
    $stmt = $pdo->prepare("SELECT id FROM projectConnections WHERE userId = :userId AND projectId = :projectId");
    $stmt->bindParam(':userId', $dbUserId);
    $stmt->bindParam(':projectId', $dbProjectId);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result[0];
}

// Checks in on the specified project.
function setActiveProject($pdo, $dbUserId, $dbProjectId){
    $active = 1;
    $checkedInAt = time();
    $stmt = $pdo->prepare("UPDATE projectConnections SET active = :active, checkedInAt = :checkedInAt WHERE userId = :userId AND projectId = :projectId");
    $stmt->bindParam(':userId', $dbUserId);
    $stmt->bindParam(':projectId', $dbProjectId);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':checkedInAt', $checkedInAt);
    $stmt->execute();

}

// Checks out on any active project
function unsetActiveProject($pdo, $dbUserId){
    $true = 1;
    $active = 0;
    $now = time();
    $stmt = $pdo->prepare("UPDATE projectConnections SET active = :active, timeSpent = (timeSpent + (:now - checkedInAt)) WHERE userId = :userId AND active = :true");
    $stmt->bindParam(':userId', $dbUserId);
    $stmt->bindParam(':true', $true);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':now', $now);
    $stmt->execute();
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

/*
Helper Functions
 */

// Send information back to slack
function botRespond($tag, $output){
    echo ($tag) . ": ";
    echo ($output) . " \n";
}
