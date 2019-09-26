<?php

	$filteredTeamName = filter_input(INPUT_POST, "text", FILTER_SANITIZE_STRING);
	
	// Include database and authentication info.
	include_once 'include/dbinfo.php';
	include_once 'include/auth.php';
	
    // Remove team with specified teamname input.
	$stmt = $dbh->prepare("DELETE FROM teams WHERE name = :name");
	$stmt->bindParam(':name', $filteredTeamName);
	$stmt->execute();
	
	// Send information back to slack.
	function bot_respond($output){
		echo json_encode($output);
	}
	
?>
