<?php

    $tokens = array(
        "P2zoHA16O3ZuQQpQYpE7EC7M"
    );
    //Checks if token is correct
    if(!in_array($_REQUEST['token'], $tokens)){
        bot_respond("Unauthorized Token!");
        die();
    }

    if(!isset($_POST['text'])){
        bot_respond('Please write a team and user name.');
        die();
    }

    $textCheck = explode(' ', $_POST['text']);
    //Checks id command is written correctly
    if($textCheck.sizeof() > 2){
        bot_respond('Please only use one word for each of the inputs');
        die();
    }
    if($textCheck.sizeof() < 2){
        bot_respond('Please input both a team and user name.');
        die();
    }

    $filteredTeamName = filter_var($textCheck[0], FILTER_SANITIZE_STRING);
    $filteredUserName = filter_var($textCheck[1], FILTER_SANITIZE_STRING);
    $metaKey = 'Member';
    include_once 'include/dbinfo.php';

    $stmt = $dbh->prepare("SELECT id FROM teams WHERE name = :name");
    $stmt->bindParam(':name', $filteredTeamName);
    $stmt->execute();

    $id = $stmt->fetch(PDO::FETCH_ASSOC)[0];
    //Checks if the team written exist
    if($id == false){
        bot_respond('That team does not exist.');
        die();
    }
    //Adds member to team in database
    $stmt = $dbh->prepare("INSERT INTO teamMeta(id, teamId, metaKey value) VALUES (teamId = :teamId, metaKey = :metaKey, value = :value)");
    $stmt->bindParam(':teamId', $id);
    $stmt->bindParam(':metaKey', $metaKey);
    $stmt->bindParam(':value', $filteredUserName);
    $stmt->execute();

    function bot_respond($output){
        echo json_encode($output);
    }

?>
