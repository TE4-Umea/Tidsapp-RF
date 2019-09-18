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
    
//split arguments into array.
    $args = explode(" ", $_REQUEST['text']); 

 //Throw error if there are too many arguments.
   if(count($args) > 1) die("Too many arguments.");
   else {

    if($args[0] == ""){
        //TODO: Set user to active on "other".
    } else {

        
        //TODO: Set any active project to inactive.
        //TODO: Set user to active on project.

       
    
    	//Check if there are no arguments.
		include_once 'include/dbinfoExample.php';

		$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sql = "SELECT * FROM projects WHERE name=" . $args[0];
	
		bot_respond($sql);

		$stm = $dbh->query($sql);
	
		bot_respond($stm);
      
		}
	}
	
	/*
		Helper Functions
	*/
	
	// Send information back to slack
	function bot_respond($output){
		echo json_encode($output);
	}
?>