<?php

    $tokens = array(
        "P2zoHA16O3ZuQQpQYpE7EC7M"
    );

    if(!in_array($_REQUEST['token'], $tokens)){
        bot_respond("Unauthorized Token!");
        die();
    }

    if(!isset($_POST['text'])){
        bot_respond('Please write a team and user name.');
        die();
    }

    $textCheck = explode(' ', $_POST['text']);

    if($textCheck.sizeof() > 2){
        bot_respond('Please only use one word for each of the inputs');
        die();
    }


    function bot_respond($output){
        echo json_encode($output);
    }

?>
