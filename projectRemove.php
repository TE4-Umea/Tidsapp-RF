<?php
	
	// Authorized team tokens that you would need to get when creating a slash command. Same script can serve multiple teams, just keep adding tokens to the array below.
	$tokens = array(
		"P2zoHA16O3ZuQQpQYpE7EC7M"
	);
	
	// check auth
	if (!in_array($_REQUEST['token'], $tokens)) {
		bot_respond("*Unauthorized token!*");
		die();
	}

	$filteredProjectName = filter_input(INPUT_POST, "text", FILTER_SANITIZE_STRING);
	
	//includes database info
	include_once 'include/dbinfo.php'

	// Basic use of post data slack sends

	$stmt = $dbh->prepare(SELECT * FROM projects);
	$stmt->execute();

	$projects = $stmt->fetch(PDO::FETCH_ASSOC);

	//Removes project and the projectMeta created with the same projectId as the project.
	$stmt = $dbh->prepare("SELECT `id` FROM `projects` WHERE name = :name");
	$stmt->bindParam(':name', $filteredProjectName);
	$stmt->execute();

	$id = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$stmt = $dbh->prepare("DELETE * FROM `projects` WHERE name = :name");
	$stmt->bindParam(':name', $filteredProjectName);
	$stmt->execute();

	$stmt = $dbh->prepare("DELETE * FROM `projectMeta` WHERE projectId = :id");
	$stmt->bindParam(':id', $id);
	$stmt->execute();
    
	
	// Send information back to slack
	function bot_respond($output){
		echo json_encode($output);
	}
	
?>
