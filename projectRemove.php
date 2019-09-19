<?php
	
	// Authorized team tokens that you would need to get when creating a slash command. Same script can serve multiple teams, just keep adding tokens to the array below.
	$tokens = array(
		"P2zoHA16O3ZuQQpQYpE7EC7M"
	);
	
	// Check auth.
	if (!in_array($_REQUEST['token'], $tokens)) {
		bot_respond("*Unauthorized token!*");
		die();
	}

	$filteredProjectName = filter_input(INPUT_POST, "text", FILTER_SANITIZE_STRING);
	
	// Include database info.
	include_once 'include/dbinfo.php'

	// Fetch id from specified projectname input.
	$stmt = $dbh->prepare("SELECT `id` FROM `projects` WHERE name = :name");
	$stmt->bindParam(':name', $filteredProjectName);
	$stmt->execute();

	$id = $stmt->fetch(PDO::FETCH_ASSOC);
	
	// Remove project with specified teamname input.
	$stmt = $dbh->prepare("DELETE * FROM `projects` WHERE name = :name");
	$stmt->bindParam(':name', $filteredProjectName);
	$stmt->execute();

	// Remove projectMeta with same projectId as the id of the specified projectname input.
	$stmt = $dbh->prepare("DELETE * FROM `projectMeta` WHERE projectId = :id");
	$stmt->bindParam(':id', $id);
	$stmt->execute();
    
	
	// Send information back to slack.
	function bot_respond($output){
		echo json_encode($output);
	}
	
?>
