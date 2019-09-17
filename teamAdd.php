<?php
    $tokens = array(
        ""
    );

    if(!in_array($_REQUEST['token'], $tokens)){
        bot_respond("Unauthorized Token!");
        die();
    }

    $teamName = $_POST['text'];

    include_once 'include/dbinfo.php';


    $dbh = new PDO('mysql:host=' . $dbhost . ';dbname=' . $dbname . ';charset=utf8mb4', $dbuser, $dbpass);




    function bot_respond($output){
        echo json_encode($output);
    }
?>
