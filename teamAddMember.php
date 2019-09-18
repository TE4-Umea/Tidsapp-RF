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

    

?>
