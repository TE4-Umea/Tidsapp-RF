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

		$stmt = $dbh->query($sql);
		
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
			echo $v;
		}


		if ($stm->num_rows > 0) {
			// output data of each row
			while($row = $stm->fetch_assoc()) {
				echo "id: " . $row["id"]. " - Name: " . $row["name"];
			}
		} else {
			echo "0 results";
		}
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