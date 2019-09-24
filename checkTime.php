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

function getProjectId($pdo, $dbProjectName)
{
    $stmt = $pdo->prepare("SELECT id FROM projects WHERE name = :name");
    $stmt->bindParam(':name', $dbProjectName);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result[0];
}

function getProjectTime($pdo, $dbUserId)
{   
    $stmt = $pdo->prepare("SELECT 'checkedInAt' FROM projectConnections WHERE userId = :userId");
    $stmt->bindParam(':userId', $dbUserId);
    $stmt->execute();
    
    $chekedInAt = $stmt->fetch();
    
    $stmt = $pdo->prepare("SELECT 'timeSpent' FROM projectConnections WHERE userId = :userId");
    $stmt->bindParam(':userId', $dbUserId);
    $stmt->bindParam(':true', $true);
    $stmt->execute();
    
    $timeSpent = $stmt->fetch();
    $timeSpent[0] += (time() - $chekedInAt[0]);
    
    botRespond("timeSpent", $timeSpent[0]);
    
    $stmt = $pdo->prepare("UPDATE projectConnections SET timeSpent = :timeSpent WHERE userId = :userId");
    $stmt->bindParam(':userId', $dbUserId);
    $stmt->bindParam(':timeSpent', $timeSpent[0]);
    $stmt->execute();
}

funtion 

// Send information back to slack
function botRespond($tag, $output)
{
	echo ($tag) . "<br>";
	echo json_encode($output) . "<br><br>";
}

?>