<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php 
    // require_once('session.php');
    session_start();

    // If the user isn't logged in, redirect to login.
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit;
    }

    require_once('../../connection.php');

    $pageTitle = "Profile - Habit Tracker";
    $cssFile = "css/indexprofile2.css";
    include('includes/header.php');

    $id = $_SESSION['id'];
    $sql = "SELECT username, email, dob, created_at FROM users WHERE id = ?";
    $stmt = $db_connection->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0){
        echo "User not found.";
        exit;
    }

    $user = $result->fetch_assoc();
    $stmt->close();
    ?>

    

    <div class="container">
        <section class="profile-section">
            <img src="img/profile.avif" alt="User Profile" class="profile-pic">
            <h2><?php echo htmlspecialchars($user['username']); ?></h2>
            <p class="date">Joined <?php echo htmlspecialchars($user['created_at']); ?></p>
            </section>

            <section class="user-info">
            <p><strong>Email:</strong> <span class="email"><?php echo htmlspecialchars($user['email']); ?></span></p>
            <hr>
            <p><strong>Birthday:</strong> <span class="info"><?php echo htmlspecialchars($user['dob']); ?></span></p>
            <hr>
            </section>

            <section class="buttons">
            <button class="edit"><a href="edit.php">Edit Profile</a></button>
            <button class="logout"><a href="logout.php">Log out</a></button>
            <button class="delete"><a href="delete_account.php" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">Delete Account</a></button>
            
        </section>
    </div>
    



    <?php include('includes/footer.php'); ?>
</body>

</html>