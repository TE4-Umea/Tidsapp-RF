<?php

	// Include database and authentication info.
	include_once 'include/dbinfo.php';
    include_once 'include/auth.php';

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    $slackId = filter_var($_REQUEST['user_id'], FILTER_SANITIZE_STRING);

    $dbUserId = getUserId($dbh, $slackId);

    unsetActiveProject($dbh, $dbUserId);

    botRespond("You have been checked out");


    // fetch the id of the user from database using user_id.
    function getUserId($pdo, $userSlackId){
    $stmt = $pdo->prepare("SELECT id FROM users WHERE userId = :userId");
    $stmt->bindParam(':userId', $userSlackId);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result[0];
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

    // Send information back to slack.
	function bot_respond($output){
		echo json_encode($output);
	}

?>