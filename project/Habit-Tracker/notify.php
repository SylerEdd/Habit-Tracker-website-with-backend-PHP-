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

    $pageTitle = "Notifications - Habit Tracker";
    $cssFile = "css/indexnotify.css";
    include('includes/header.php');

    $userId = $_SESSION['id'];
    $sql = "SELECT task_id, description, progress, status FROM tasks WHERE user_id = ?";
    $stmt = $db_connection->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];

    while($row = $result-> fetch_assoc()){
        $notifications[] = $row;
    }

    $stmt->close();
    
    ?>

    <main>
        <section class="notification-text">
            <h3>Improve Your Habit Progress</h3>
            <p>Keep pushing forward-here's a summary of your current tasks: </p>
        </section>

        <div class="image-wrapper"></div>
        <div id="notifications-container">
            <?php if(empty($notifications)): ?>
                <p>No notifications available at the moment.</p>
            <?php else: ?>
                <?php foreach($notifications as $note): ?>
                    <?php if($note['progress'] < 100): ?>
                        <section class="notification-box" >
                            <h3><?php echo htmlspecialchars($note['description']); ?></h3>
                            <p>Your progress is <?php echo (int)$note['progress']; ?>%. Time for a workout to boost your progress!</p>
                        </section>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <br>
        <button class = "btn-snooze" onclick="snoozeNotifications()">Snooze</button>

    </main>
    <?php include('includes/footer.php'); ?>

    <script>

        function snoozeNotifications(){
            var container = document.getElementById("notifications-container");
            if(!container) return;

            var snoozeUntil = Date.now() + 3600000; // this is 1 hour in ms
            localStorage.setItem("snoozeUntil", snoozeUntil)

            container.style.display= "none";



            setTimeout(function(){
                container.style.display = "block";
                localStorage.removeItem("snoozeUntil");
            }, 3600000);
        }

        window.addEventListener("load", function(){
            var container = document.getElementById("notifications-container");
            var snoozeUntil = localStorage.getItem("snoozeUntil");
            if(snoozeUntil && Date.now() <parseInt(snoozeUntil)){
                container.style.display = "none";
                var remaining = parseInt(snoozeUntil ) - Date.now();
                setTimeout(function(){
                    container.style.display = "block";
                    localStorage.removeItem("snoozeUntil");
                }, remaining); 
            }
        });

    </script>



</body>
</html>