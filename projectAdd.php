<?php

	//Check for if the command has something written
	if(!isset($_POST['text'])){
        botRespond('Please write a project name.');
        die();
    }
	
	$nameCheck = explode(" ", $_POST['text']);
	$filteredProjectName = filter_input(INPUT_POST, "text", FILTER_SANITIZE_STRING);
	$stringProjectName = strval($filteredProjectName);
	
	if($nameCheck.sizeof() > 1){
        botRespond('Please make the project name only one word.');
	} else if(strlen($stringProjectName) > 15) {
		die('Project names can not exceed 16 characters.');
	}

	
	// Include database and authentication info.
	include_once 'include/dbinfo.php';
	include_once 'include/auth.php';



	
	/*
	// Check if project already exist.
	$stmt = $dbh->prepare("SELECT * FROM projects");
	$stmt->execute();

	$projects = $stmt->fetch(PDO::FETCH_ASSOC);

	$projectName = array();

	foreach($projects as $project) {
		array_push($projectnames, $project['name']);
	}
	
	if (in_array($filteredProjectName, $projectNames)) {
		botRespond('Project already exists');
		die();
	}
	*/

	if(getProjectId($dbh, $filteredProjectName) == false){
	
	// Create a new project with the specified projectname input.
	$stmt = $dbh->prepare("INSERT INTO projects(name) VALUES (:name)");
	$stmt->bindParam(':name', $filteredProjectName);
	$stmt->execute();

	botRespond('Added project: ' . $filteredProjectName);
	} else {
		die("Project already exists");
	}

	// fetch the id of the specified project from database using projectName.
function getProjectId($pdo, $dbProjectName){
    $stmt = $pdo->prepare("SELECT id FROM projects WHERE name = :name");
    $stmt->bindParam(':name', $dbProjectName);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result[0];
}

		
	// Send information back to slack
	function botRespond($output){
		echo ($output);
	}
	
?>