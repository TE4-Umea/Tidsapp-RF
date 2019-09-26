<?php

	$filteredProjectName = filter_input(INPUT_POST, "text", FILTER_SANITIZE_STRING);
	
	// Include database and authentication info.
	include_once 'include/dbinfo.php';
	include_once 'include/auth.php';


	// Remove project with specified teamname input.
	$stmt = $dbh->prepare("DELETE FROM projects WHERE name = :name");
	$stmt->bindParam(':name', $filteredProjectName);
	$stmt->execute();

	botRespond("Removed project", $filteredProjectName);
	
	// Send information back to slack.
	function botRespond($output){
		echo json_encode($output);
	}
	
?>
