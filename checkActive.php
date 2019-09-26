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

    // Load database info from dbinfo.
    include_once 'include/dbinfo.php';
    include_once 'include/auth.php';
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //  get user id.
    $userId = getUserId($dbh, $slackId);
    if ($userId == false) {
       // botRespond("DB", "Could not find user id.");

        addNewUser($dbh, $slackId);
        $userId = getUserId($dbh, $slackId);

     //   botRespond("DB", "Added new user.");

    }

    //get project id.
    $project_id = getProjectId($dbh, $userId);


    botRespond("You are currently checked in on", getProjectName($dbh, $project_id));


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
function getProjectId($pdo, $dbUserId){
    $true = 1;
    $stmt = $pdo->prepare("SELECT projectId FROM projectConnections WHERE userId = :userId AND active = :active");
    $stmt->bindParam(':userId', $dbUserId);
    $stmt->bindParam(':active', $true);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result[0];
}

function getProjectName($pdo, $dbProjectId){
    $stmt = $pdo->prepare("SELECT name FROM projects WHERE id = :projectId");
    $stmt->bindParam(':projectId', $dbProjectId);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result[0];
}

/* connections */

function getProjectConnection($pdo, $dbUserId, $dbProjectId){
    $stmt = $pdo->prepare("SELECT id FROM projectConnections WHERE userId = :userId AND projectId = :projectId");
    $stmt->bindParam(':userId', $dbUserId);
    $stmt->bindParam(':projectId', $dbProjectId);
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
