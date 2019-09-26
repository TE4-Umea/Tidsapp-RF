<?php

    include_once 'include/auth.php';
    include_once 'include/dbinfo.php';

    $stmt = $dbh->prepare('SELECT names FROM projects');
    $stmt->execute();

    $projects = $stmt->fetch();

    botRespond($projects);


    function botRespond($message){
        echo $message;
    }

?>
