<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
    <style>
    body {
      background: #f2f2f2;
      font-family: Arial, sans-serif;
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    
    .Streak {
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      text-align: center;
    }
    .Streak h2 {
      margin-bottom: 20px;
      font-size: 1.8rem;
      color: #333;
    }
    .Streak-task {
      border: 1px solid #ddd;
      border-radius: 4px;
      margin: 10px 0;
      padding: 10px;
      cursor: pointer;
      transition: background-color 0.2s ease-in-out;
    }
    .Streak-task:hover {
      background: #fafafa;
    }
    .Streak-task h3,
    .Streak-task h4,
    .Streak-task h5,
    .Streak-task h6 {
      margin-bottom: 10px;
      font-size: 1.1rem;
    }
    .Streak-task progress {
      width: 100%;
      height: 20px;
      margin-bottom: 10px;
    }
    
    .Streak-task.complete {
      background: #ccffcc;
    }
    
    .home {
      width: 100%;
      max-width: 800px;
    }

    .percentage {
      font-size: 1rem;
      font-weight: bold;
      color: #333;
    }
    </style>
</head>
<body>

    <?php
    session_start();

    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit;
    }

    require_once('../../connection.php');
    
    $pageTitle = "Home - Habit Tracker";
    $cssFile = "css/indexprofile2.css";
    include('includes/header.php');
    include('cookie.php');

    $userId = $_SESSION['id'];
    $sql = "SELECT task_id, description, progress, status FROM tasks WHERE user_id = ?";
    $stmt = $db_connection->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $tasks = [];
    while($row = $result->fetch_assoc()){
        $tasks[] = $row;
    }
    $stmt->close();

    ?>
    <section class="home">
        <section class="Streak">
        <h2>Streak</h2>
        <?php foreach ($tasks as $task): ?>
            <article 
                class="Streak-task <?php echo ($task['status'] === 'complete') ? 'complete' : ''; ?>" 
                data-task-id="<?php echo $task['task_id']; ?>" 
                data-current-progress="<?php echo (int)$task['progress']; ?>" 
                onclick="toggleTaskTimer(this)"
            >
            <h3>Task: <?php echo htmlspecialchars($task['description']); ?></h3>
            <progress value="<?php echo (int)$task['progress']; ?>" max="100"></progress>
            <span class="percentage"><?php echo (int)$task['progress']; ?>% Completed</span>
            </article>
        <?php endforeach; ?>
        </section>
    </section>

    <script>



    // Global objects to store timers and progress for each task.
    var timers = {};
    var progressData = {};

    // Function to toggle a task's timer on click.
    function toggleTaskTimer(taskElem) {
      var taskId = taskElem.getAttribute('data-task-id');

      // Initialize progress to 0 if not set.
      if (progressData[taskId] === undefined) {
        progressData[taskId] = parseFloat(taskElem.getAttribute('data-current-progress')) || 0;
      }
      
      if (timers[taskId]) {
        clearInterval(timers[taskId]);
        timers[taskId] = null;
        updateTaskProgress(taskId, progressData[taskId]);
      } else {
        // If the task is already complete, do nothing.
        if (progressData[taskId] >= 100) {
          return;
        }
        
        var duration = 5000; // milliseconds
        var targetIncrement = 25;
        var interval = 100; 
        var increments = duration / interval;
        var incrementPerInterval = targetIncrement / increments; 

        
        var progressBar = taskElem.querySelector('progress');
        var percentageSpan = taskElem.querySelector('.percentage');

        // Start the timer
        timers[taskId] = setInterval(function() {
            progressData[taskId] += incrementPerInterval;
            if (progressData[taskId] >= 100) {
                progressData[taskId] = 100;
                clearInterval(timers[taskId]);
                timers[taskId] = null;


                // mark task as complete visually
                taskElem.classList.add('complete');


            }
            progressBar.value = progressData[taskId];
            percentageSpan.innerText = Math.floor(progressData[taskId])+ "% Completed";

            taskElem.setAttribute('data-current-progress', progressData[taskId]);
            updateTaskProgress(taskId, progressData[taskId]);
        }, interval);
      }
    }

    function updateTaskProgress(taskId, progress){
        fetch('update_task.php',{
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body:JSON.stringify({
                user_id: <?php echo (int)$_SESSION['id']; ?>,
                task_id: taskId,
                progress: progress
            })
        })
        .then(res => res.json())
        .then(data => {console.log('Progress updated:', data);
            if(data.error){
                console.error('Error:', data.error);
            }
        })

        .catch(err => console.error('Fetch error: ',err));
    }
    </script>
    <?php include('includes/footer.php'); ?>
</body>
</html>
