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
    
    // Checks if your command input is correct and matches an existing team and name.
    if(!isset($_POST['text'])){
        bot_respond('Please write a team and user name.');
        die();
    }
    
    $textCheck = explode(' ', $_POST['text']);
    
    if($textCheck.sizeof() > 2){
        bot_respond('Please only use one word for each of the inputs');
        die();
    }
    
    if($textCheck.sizeof() < 2){
        bot_respond('Please input both a team and user name.');
        die();
    }
    
    // Removes the specified user from the specified team.
    $filteredTeamName = filter_var($textCheck[0], FILTER_SANITIZE_STRING);
    $filteredUserName = filter_var($textCheck[1], FILTER_SANITIZE_STRING);
    
    $metaKey = 'Member';
    
    include_once 'include/dbinfo.php';
    
    $stmt = $dbh->prepare("SELECT id FROM teams WHERE name = :name");
    $stmt->bindParam(':name', $filteredTeamName);
    $stmt->execute();
    
    $id = $stmt->fetch(PDO::FETCH_ASSOC)[0];
    
    if($id == false){
        bot_respond('That team does not exist.');
        die();
    }
    
    $stmt = $dbh->prepare("DELETE FROM teamMeta(id, teamId, metaKey value) WHERE (teamId = :teamId, metaKey = :metaKey, value = :value)");
    $stmt->bindParam(':teamId', $id);
    $stmt->bindParam(':metaKey', $metaKey);
    $stmt->bindParam(':value', $filteredUserName);
    $stmt->execute();
    
    function bot_respond($output){
        echo json_encode($output);
    }
?>