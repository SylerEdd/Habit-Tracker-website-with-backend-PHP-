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

    $pageTitle = "Add New Habit - Habit Tracker";
    $cssFile = "css/indexprofile2.css";
    include('includes/header.php');

    $error = "";
    $success = "";

    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $newHabit = htmlspecialchars($_POST['newHabit'] ?? '');
        $startDate = htmlspecialchars($_POST['startDate']?? '');
        $endDate = htmlspecialchars($_POST['endDate'] ?? '');
        $category = htmlspecialchars($_POST['category'] ?? '');

        if(empty($newHabit) || empty($startDate) || empty($endDate) || empty($category)){
            $error = "All fields are required.";
        }else{
            $userId = $_SESSION['id'];
            $sql = "INSERT INTO habits (user_id, habit, start_date, end_date, category) VALUES(?, ?, ?, ?, ?)";
            $stmt = $db_connection->prepare($sql);

            if($stmt === false){
                $error = "Prepare failed: " . $db_connection->error;
            }else{
                $stmt->bind_param("issss" , $userId, $newHabit, $startDate, $endDate, $category);
                if($stmt->execute()){
                    $success = "Habit added successfully!";
                }else{
                    $error = "Error: ". $stmt->error;
                }
                $stmt->close();
            }
        }
    }

    ?>

    <div class="container">
        <?php 
            if(!empty($error)){
                echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
            }
            if(!empty($success)){
                echo "<p class='success'>" . htmlspecialchars($success) . "</p>";
            }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <legend>Add your Habit</legend>
            <p>
                <label for="newHabit">Habit</label>
                <input type="text" name="newHabit" id="newHabit" placeholder="Add the new habit for track" required autofocus>
            </p>
            <p>
                <label for="startDate">Start Date:</label>
                <input type="date" name="startDate" id="startDate" required>
            </p>
            <p>
                <label for="endDate">End Date:</label>
                <input type="date" name="endDate" id="endDate" required>
            </p>
            <p>
                <label for="catergory">Category:</label>
                <select name="category" id="category">
                    <optgroup label="Exercise">
                        <option value="run">Run</option>
                        <option value="yoga">Yoga</option>
                        <option value="freeWeight">Free Weight</option>
                        <option value="swim">Swim</option>
                        <option value="cardio">Cardio</option>
                    </optgroup>
                    <optgroup label="Personal growth">
                        <option value="read">Read</option>
                        <option value="study">Study</option>
                        <option value="meditate">Meditate</option>
                    </optgroup>
                    <option value="other">Other</option>
                </select>
            </p>
            <p>
                <button type="submit" class="btn btn-primary">Submit</button>
            </p>
        </form>
    </div>
    <?php include('includes/footer.php'); ?>
</body>
</html>