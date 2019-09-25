<?php

    $tokens = array(
        "P2zoHA16O3ZuQQpQYpE7EC7M"
    );
    //Check if token matches
    if(!in_array($_REQUEST['token'], $tokens)){
        bot_respond("Unauthorized Token!");
        die();
    }

    include_once 'include/dbinfo.php';

    $userSlackId = filter_var($_REQUEST['user_id'], FILTER_SANITIZE_STRING);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE userId = :userId");
    $stmt->bindParam(':userId', $userSlackId);
    $stmt->execute();

    $u_id = $stmt->fetch();
    
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

    $stmt = $dbh->prepare("UPDATE projectConnections SET active = :active, timeSpent = :timeSpent WHERE userId = :userId AND active = :true");
    $stmt->bindParam(':userId', $u_id);
    $stmt->bindParam(':true', $true);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':timeSpent', $timeSpent);
    $stmt->execute();

    bot_respond('Sucessfully checked out.');

    function bot_respond($message){
        echo json_encode($message);
    }

?>
