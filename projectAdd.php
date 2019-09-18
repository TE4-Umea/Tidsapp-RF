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

	$projects = stmt->fetch(PDO::FETCH_ASSOC);

	$projectName = array();

	foreach($projects as $project) {
		array_push($projectnames, $project['name']);
	}
	
	if (in_array($filteredProjectName, $projectNames)) {
		bot_respond('Project already exist');
		die();
	}

	//Creates a new project in projects and a new projectMeta with the same projectId as the id of the project in projects.
	$stmt = $dbh->prepare("INSERT INTO `projects`(`id`, `name`) VALUES name = :name");
	$stmt->bindParam(':name', $filteredProjectName);
	$stmt->execute();

	$stmtid = $dbh->prepare("SELECT `id` FROM `projects` WHERE name = :name");
	$stmtid->bindParam(':name', $filteredProjectName)
	$stmtid->execute();

	$stmt = $dbh->prepare("INSERT * INTO `projectMeta` VALUES projectId = :id");
	$stmt->bindParam(':id', $stmtid);
	$stmt->execute();
    
		
	// Send information back to slack
	function bot_respond($output){
		echo json_encode($output);
	}
	
?>
