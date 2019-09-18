<?php
    $tokens = array(
        "P2zoHA16O3ZuQQpQYpE7EC7M"
    );

    if(!in_array($_REQUEST['token'], $tokens)){
        bot_respond("Unauthorized Token!");
        die();
    }

    if(!isset($_POST['text'])){
        bot_respond('Please write a team name.');
        die();
    }

    $filteredTeamName = filter_input(INPUT_POST, "text", FILTER_SANITIZE_STRING);

    include_once 'include/dbinfo.php';


    $stmt = $dbh->prepare("INSERT INTO `teams`(`id`, `name`) VALUES name = :name");
    $stmt->bindParam(':name', $filteredTeamName);
    $stmt->execute();

    bot_respond('Team "' . $filteredTeamName . '" sucessfully created.');
    die();




    function bot_respond($output){
        echo json_encode($output);
    }
?>
