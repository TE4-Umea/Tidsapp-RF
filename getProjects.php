<?php

    include_once 'include/auth.php';
    include_once 'include/dbinfo.php';

    $stmt = $dbh->prepare('SELECT names FROM projects');
    $stmt->execute();

    $projects = $stmt->fetch();

    bot_respond($projects);


    function bot_respond($message){
        echo json_encode($message);
    }

?>
