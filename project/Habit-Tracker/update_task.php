<?php

    session_start();
    require_once('../../connection.php');

    //Reaad JSON input
    $data = json_decode(file_get_contents('php://input'), true);
    if(!$data){
        echo json_encode(['error' => 'No data received']);
        exit;
    }

    if(!isset($data['user_id'], $data['task_id'], $data['progress'])){
        echo json_encode(['error' => 'Missing required parameters']);
        exit;
    }

    $userId = $data['user_id'];
    $taskId = $data['task_id'];
    $progress = (int) $data['progress'];

    $sql = "UPDATE tasks SET progress = ?, status = CASE WHEN ? >= 100 THEN 'complete' ELSE 'incomplete' END, updated_at = NOW() WHERE task_id = ? AND user_id = ?";
    $stmt = $db_connection->prepare($sql);

    if(!$stmt){
        echo json_encode(['error'=>$db_connection->error]);
        exit;
    }

    $stmt->bind_param("iiii", $progress, $progress, $taskId, $userId);
    if(!$stmt->execute()){
        echo json_encode(['error'=> $stmt->error]);
        exit;
    }

    echo json_encode(['success' => true, 'progress' => $progress]);

    $stmt->close();
?>