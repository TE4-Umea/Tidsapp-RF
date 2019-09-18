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

	$filteredTeamName = filter_input(INPUT_POST, "text", FILTER_SANITIZE_STRING);
	
	//includes database info
	include_once 'include/dbinfo.php'

	// Basic use of post data slack sends

	$stmt = $dbh->prepare(SELECT * FROM teams);
	$stmt->execute();

	$teams = $stmt->fetch(PDO::FETCH_ASSOC);

	//Removes team and the teamMeta created with the same teamId as the team.
	$stmt = $dbh->prepare("SELECT `id` FROM `teams` WHERE name = :name");
	$stmt->bindParam(':name', $filteredTeamName);
	$stmt->execute();

	$id = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$stmt = $dbh->prepare("DELETE * FROM `teams` WHERE name = :name");
	$stmt->bindParam(':name', $filteredTeamName);
	$stmt->execute();

	$stmt = $dbh->prepare("DELETE * FROM `teamMeta` WHERE teamId = :id");
	$stmt->bindParam(':id', $id);
	$stmt->execute();
    
	
	// Send information back to slack
	function bot_respond($output){
		echo json_encode($output);
	}
	
?>
