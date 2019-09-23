<?php

    include_once 'include/dbinfo.php';

    $u_id = filter_var($_REQUEST['user_id'], FILTER_SANITIZE_STRING);
   
    $true = 1;
    $active = 0;

    $stmt = $dbh->prepare("SELECT checkedInAt FROM projectConnections WHERE userId = :userId AND active = :true");
    $stmt->bindParam(':userId', $u_id);
    $stmt->bindParam(':true', $true);
    $stmt->execute();

    $chekedInAt = array_values($stmt->fetch(PDO::FETCH_ASSOC))[0];

    $stmt = $dbh->prepare("SELECT timeSpent FROM projectConnections WHERE userId = :userId AND active = :true");
    $stmt->bindParam(':userId', $u_id);
    $stmt->bindParam(':true', $true);
    $stmt->execute();

    $timeSpent = array_values($stmt->fetch(PDO::FETCH_ASSOC))[0];

    $timeSpent += (time() - $chekedInAt);

    botRespond("timeSpent", $timeSpent);

    $stmt = $dbh->prepare("UPDATE projectConnections SET active = :active, timeSpent = :timeSpent WHERE userId = :userId AND active = :true");
    $stmt->bindParam(':userId', $u_id);
    $stmt->bindParam(':true', $true);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':timeSpent', $timeSpent);
    $stmt->execute();

?>
