<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>
<body>
    <?php
    
    session_start();
    if(!isset($_SESSION['id'])){
        header("Location: login.php");
        exit;
    }

    require_once('../../connection.php');
    $pageTitle = "Habit - Habit Tracker";
    
    
    $cssFile = "css/indexprofile2.css";
    include('includes/header.php');

    $userId = $_SESSION['id'];
    $sql = "SELECT habit_id, habit, start_date, end_date, category FROM habits WHERE user_id = ?";
    $stmt = $db_connection->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    //initialize habits array
    $habits = [];

    while($row = $result->fetch_assoc()){

        $habits[] = $row;
    }
    $stmt->close();
    
    ?>

    <main class="container">

        <ul class="habit-list">
            <legend>Last records:</legend>
            <?php if(count($habits)>0): ?>
                <?php foreach($habits as $habit): ?>
                    <li>
                        <summary><?php echo htmlspecialchars($habit['habit']); ?></summary>
                        <p>
                            Start Date: <time datetime="<?php echo htmlspecialchars($habit['start_date']); ?>"><?php echo htmlspecialchars($habit['start_date']); ?></time><br>
                            End Date: <time datetime="<?php echo htmlspecialchars($habit['end_date']); ?>"><?php echo htmlspecialchars($habit['end_date']); ?></time><br>
                            Category: <?php echo htmlspecialchars($habit['category']); ?> 
                        </p>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No habits recorded yet.</li>
            <?php endif; ?>    
        </ul>
        <hr>

        <section>
            <p>
                <button class="add-record-button"><a href="newRecord.php">+</a></button>
            </p>
        </section>
    </main>


    <?php include('includes/footer.php'); ?>
</body>
</html>