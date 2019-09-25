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

	//Check for if the command has something written
	if(!isset($_POST['text'])){
        bot_respond('Please write a project name.');
        die();
    }
	
	$nameCheck = explode(" ", $_POST['text']);
	
	if($nameCheck.sizeof() > 1){
        bot_respond('Please make the project name only one word.');
    }
	
	$filteredProjectName = filter_input(INPUT_POST, "text", FILTER_SANITIZE_STRING);
	
	// Include database and authentication info.
	include_once 'include/dbinfo.php';
	include_once 'include/auth.php';

	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
	$stmt = $dbh->prepare("INSERT INTO projects(name) VALUES (:name)");
	$stmt->bindParam(':name', $filteredProjectName);
	$stmt->execute();
	

	/*
	function getProjectId($pdo, $dbProjectName)
	{
    	$stmt = $pdo->prepare("SELECT id FROM projects WHERE name = :name");
    	$stmt->bindParam(':name', $filteredProjectName);
    	$stmt->execute();
    	$result = $stmt->fetch();
    	return $result[0];
	}*/
	
	// Fetch id from specified projectname input.
	/*
	$stmt = $dbh->prepare("SELECT id FROM projects WHERE name = :name");
	$stmt->bindParam(':name', $filteredProjectName);
	$stmt->execute();

	$id = $stmt->fetch(PDO::FETCH_ASSOC)[0];
	*/

	/*
	// Create a projectMeta with the same projectId as the id of the specified projectname input.
	$stmt = $dbh->prepare("INSERT * INTO projectMeta VALUES projectId = :id");
	$stmt->bindParam(':id', $result[0]);
	$stmt->execute();
	*/
		
	// Send information back to slack.
	function bot_respond($output){
		echo json_encode($output);
	}
	
?>
