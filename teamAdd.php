<?php
    //Check for if the command has something written
    if(!isset($_POST['text'])){
        bot_respond('Please write a team name.');
        die();
    }
    $nameCheck = explode(" ", $_POST['text']);
    if($nameCheck.sizeof() > 1){
        bot_respond('Please make the team name only one word.');
    }
    $filteredTeamName = filter_input(INPUT_POST, "text", FILTER_SANITIZE_STRING);
    include_once 'include/dbinfo.php';
    include_once 'include/auth.php';
    $stmt = $dbh->prepare("SELECT * FROM teams");
    $stmt->execute();
    $teams = $stmt->fetch(PDO::FETCH_ASSOC);

    $teamNames = array();
    
    foreach($teams as $team){
        array_push($teamNames, $team['name']);
    }
    //Check if the team already exist
    if(in_array($filteredTeamName, $teamNames)){
        bot_respond('Team already exist');
        die();
    }
    //Create team in database
    $stmt = $dbh->prepare("INSERT INTO teams(name) VALUES (:name)");
    $stmt->bindParam(':name', $filteredTeamName);
    $stmt->execute();
    bot_respond('Team "' . $filteredTeamName . '" sucessfully created.');
    die();

    function bot_respond($output){
        echo json_encode($output);
    }
?>