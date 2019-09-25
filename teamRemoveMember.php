<?php    

	// Include database info.
    include_once 'include/dbinfo.php';
    include_once 'include/auth.php';

    // Check if your command input is correct and matches an existing teamname and username.
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
    
    // Filter the first and second input, teamname and username.
    $filteredTeamName = filter_var($textCheck[0], FILTER_SANITIZE_STRING);
    $filteredUserName = filter_var($textCheck[1], FILTER_SANITIZE_STRING);
    
    $metaKey = 'Member';
    
	// Include database and authentication info.
	include_once 'include/dbinfo.php';
	include_once 'include/auth.php'
    
    // fetch and check if the teamname input exist, throw error message if not.
    $stmt = $dbh->prepare("SELECT id FROM teams WHERE name = :name");
    $stmt->bindParam(':name', $filteredTeamName);
    $stmt->execute();
    
    $id = $stmt->fetch(PDO::FETCH_ASSOC)[0];
    
    if($id == false){
        bot_respond('That team does not exist.');
        die();
    }
    
    // Remove the specified user from the specified team.
    $stmt = $dbh->prepare("DELETE FROM teamMeta(id, teamId, metaKey, value) WHERE (teamId = :teamId, metaKey = :metaKey, value = :value)");
    $stmt->bindParam(':teamId', $id);
    $stmt->bindParam(':metaKey', $metaKey);
    $stmt->bindParam(':value', $filteredUserName);
    $stmt->execute();
    
    // Send information back to slack.
    function bot_respond($output){
        echo json_encode($output);
    }
?>