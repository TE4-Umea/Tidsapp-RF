<?php
	
	// Authorized team tokens that you would need to get when creating a slash command. Same script can serve multiple teams, just keep adding tokens to the array below.
	$tokens = array(
		"tW0wHpLokfme5zJppcZSJUDg"
	);
	
	// check auth
	if (!in_array($_REQUEST['token'], $tokens)) {
		bot_respond("*Unauthorized token!*");
		die();
	}
    

    // Basic use of post data slack sends
    $args = explode(" ", $_REQUEST['text']); //split arguments into array.

   if(count($args) > 1) dieWithMessage("Too many arguments."); //Check if 
   else {
    if($args[0] == ""){
        //TODO: Set user to active on "other".
    } else {
        //TODO: Set any active project to inactive.
        //TODO: Set user to active on project.
    }
      // $sql = "SELECT * FROM projects WHERE name=$args[0]";
   }


   function dieWithMessage($message){
       bot_respond($message);
       die();
   }
	
	/*
		Helper Functions
	*/
	
	// Send information back to slack
	function bot_respond($output){
		echo json_encode($output);
	}
?>