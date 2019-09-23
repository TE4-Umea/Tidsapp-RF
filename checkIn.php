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

botRespond("Time", time());

botRespond("slackId", $slackId);

dumper($_REQUEST['user_id']);

// Throw error if there are too many arguments.
if (count($args) > 1) {
    die("Too many arguments.");
} else {
    // Load database info from dbinfo.
    include_once 'include/dbinfo.php';
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //print_r($dbh);

    //    get user id.
    $userId = getUserId($dbh, $slackId);
    if (!$userId) {
        botRespond("ERROR", "Could not find user id");
        //addNewUser($dbh, $filteredSlackId);
        //$userId = getUserId($dbh, $filteredSlackId);
        if ($userId == "") {
            die("Could not get user");
        }

        //else botRespond("DB", "Added new user.");
    }
    botRespond("user_id", $userId);

     $project_name = "Other"; // Default project name, this will be used if no arguments are provided.

     if ($args[0] !== "") {
         $project_name = filter_var($args[0], FILTER_SANITIZE_STRING);
     } // first argument specifes project name.
     botRespond("project_name", $project_name);

     //get project id.
     $project_id = getProjectId($dbh, $project_name);
     botRespond("project_id", $project_id);

     $connection_id = getProjectConnection($dbh, $userId, $project_id);
     if ($connection_id == false) {
        createNewProjectConnection($dbh, $userId, $project_id);
        if ($connection_id == false) die("Could not get connection id.");
    }

    unsetActiveProject($dbh, $userId);

    setActiveProject($dbh, $userId, $project_id);
}

/* users table */

/* Create */

//Add a new user to the users table.
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
    botRespond("getslackid", $userSlackId);
    $stmt = $pdo->prepare("SELECT id FROM users WHERE userId = :userId");
    $stmt->bindParam(':userId', $userSlackId);
    $stmt->execute();

    $result = $stmt->fetch();
    botRespond("USER ID", $result[0]);
    return $result[0];
}

/* projects table */

// fetch the id of the specified project from database using projectName.
function getProjectId($pdo, $dbProjectName)
{
    $stmt = $pdo->prepare("SELECT id FROM projects WHERE name = :name");
    $stmt->bindParam(':name', $dbProjectName);
    $stmt->execute();
    $result = $stmt->fetch();
    botRespond("PROJECT ID", $result[0]);
    return $result[0];
}

/* Check in */

// Checks if there is
function getProjectConnection($pdo, $dbUserId, $dbProjectId)
{
    $stmt = $pdo->prepare("SELECT id FROM projectConnections WHERE userId = :userId AND projectId = :projectId");
    $stmt->bindParam(':userId', $dbUserId);
    $stmt->bindParam(':projectId', $dbProjectId);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result[0];
}

// Adds a new project connection to the projectConnections table.
function createNewProjectConnection($pdo, $dbUserId, $dbProjectId)
{
    $stmt = $pdo->prepare("INSERT INTO projectConnections(userId, projectId) VALUES (:userId, :projectId)");
    $stmt->bindParam(':userId', $dbUserId);
    $stmt->bindParam(':projectId', $dbProjectId);
    $stmt->execute();
}

// Checks in on the specified project.
function setActiveProject($pdo, $dbUserId, $dbProjectId)
{
    $active = 1;
    $time = time();
    $stmt = $pdo->prepare("UPDATE projectConnections SET active = :active, checkedInAt = :checkedInAt WHERE userId = :userId AND projectId = :projectId");
    $stmt->bindParam(':userId', $dbUserId);
    $stmt->bindParam(':projectId', $dbProjectId);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':checkedInAt', $time);
    $stmt->execute();
}

// Checks out on any active project
function unsetActiveProject($pdo, $dbUserId)
{
    $true = 1;
    $active = 0;

    $stmt = $pdo->prepare("SELECT 'checkedInAt' FROM projectConnections WHERE userId = :userId AND active = :true");

    $stmt->bindParam(':userId', $dbUserId);
    $stmt->bindParam(':true', $true);
    $stmt->execute();
    $chekedInAt = $stmt->fetch()[0];

    $stmt = $pdo->prepare("SELECT 'timeSpent' FROM projectConnections WHERE userId = :userId AND active = :true");

    $stmt->bindParam(':userId', $dbUserId);
    $stmt->bindParam(':true', $true);
    $stmt->execute();
    $timeSpent = $stmt->fetch()[0];
    $timeSpent += (time() - $chekedInAt);

    botRespond("timeSpent", $timeSpent);

    $stmt = $pdo->prepare("UPDATE projectConnections SET active = :active, timeSpent = :timeSpent WHERE userId = :userId AND active = :true");
    $stmt->bindParam(':userId', $dbUserId);
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
