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
	//includes database info
	include_once 'include/dbinfo.php'
	
	// Basic use of post data slack sends
    $args = explode(" ", $_REQUEST['text']);
    if(count($args) > 1) bot_respond("Too many arguments.");
        else {
        bot_respond($args[0]);
        }
    $idOfProject = "SELECT id FROM projects WHERE name='$args[0]'";
    $projectRemove = "DELETE FROM projects WHERE name='$args[0]'";
    $projectMetaRemove = "DELETE FROM projectMeta WHERE projectId=$idOfProject";
    
	// This can used for debugging
	dumper($_REQUEST);
	
	
	/*
		Helper Functions
	*/
	
	// Send information back to slack
	function bot_respond($output){
		echo json_encode($output);
	}
	
	function dumper($request){
        echo "<pre>" . print_r($request, 1) . "</pre>";
    }
	
?>
