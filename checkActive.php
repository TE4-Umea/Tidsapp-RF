<?php
    include_once 'include/dbinfo.php';

    $userId = $_POST['user_id'];

    $stmt = $dbh->prepare('SELECT projectId FROM projectConnections(id, userId, projectId, active, checkedInAt, timeSpent) WHERE (userId = :userId, active = :active)');
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':active', 1);
    $stmt->execute();

    $projectId = $stmt->fetch(PDO::FETCH_ASSOC)[0];

    if($projectId == false){
       bot_respond('No currently active projects.');
       die(); 
    }

    $stmt = $dbh->prepare('SELECT `name` FROM projects WHERE id = :id');
    $stmt->bindParam(':id', $projectId);
    $stmt->execute();

    $projectName = $stmt->fetch(PDO::FETCH_ASSOC)[0];

    if($projectName == false){
        bot_respond('No currently active projects');
        die();
    }

    bot_respond('Currently active project is: ' . $projectName);

    function bot_respond($message){
        echo json_encode($message);
    }
    
?>
