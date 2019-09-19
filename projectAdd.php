<?php
	
	// Authorized team tokens that you would need to get when creating a slash command. Same script can serve multiple teams, just keep adding tokens to the array below.
	$tokens = array(
		"P2zoHA16O3ZuQQpQYpE7EC7M"
	);
	
	// check auth.
	if (!in_array($_REQUEST['token'], $tokens)) {
		bot_respond("*Unauthorized token!*");
		die();
	}
	
	$filteredProjectName = filter_input(INPUT_POST, "text", FILTER_SANITIZE_STRING);
	
	// Include database info.
	include_once 'include/dbinfo.php'

	// Check if project already exist.
	$stmt = $dbh->prepare("SELECT * FROM projects");
	$stmt->execute();

	$projects = $stmt->fetch(PDO::FETCH_ASSOC);

	$projectName = array();

	foreach($projects as $project) {
		array_push($projectnames, $project['name']);
	}
	
	if (in_array($filteredProjectName, $projectNames)) {
		bot_respond('Project already exist');
		die();
	}

	// Create a new project with the specified projectname input.
	$stmt = $dbh->prepare("INSERT INTO `projects`(`id`, `name`) VALUES name = :name");
	$stmt->bindParam(':name', $filteredProjectName);
	$stmt->execute();

	// Fetch id from specified projectname input.
	$stmt = $dbh->prepare("SELECT `id` FROM `projects` WHERE name = :name");
	$stmt->bindParam(':name', $filteredProjectName)
	$stmt->execute();

	$id = $stmt->fetch(PDO::FETCH_ASSOC)[0];

	// Create a projectMeta with the same projectId as the id of the specified projectname input.
	$stmt = $dbh->prepare("INSERT * INTO `projectMeta` VALUES projectId = :id");
	$stmt->bindParam(':id', $id);
	$stmt->execute();
    
		
	// Send information back to slack.
	function bot_respond($output){
		echo json_encode($output);
	}
	
?>
