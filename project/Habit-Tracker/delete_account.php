

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php

session_start();
require_once('../../connection.php');
$pageTitle = "Delete Account - Habit Tracker";
$cssFile = "css/delete_account.css";
include('includes/header.php');

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['id'];

$sql = "SELECT password FROM users WHERE id = ?";
$stmt = $db_connection->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();
$stmt->close();

$error = "";
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $password = $_POST['confirm_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if(empty($password) || empty($confirm_password)){
        $error = "Please fill out both fields";
    }elseif($password !== $confirm_password){
        $error = "Passwordds do not match!";
    }elseif(!password_verify($password, $user['password'])){
        $error = "Incorrect password.";
    }else{
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $db_connection->prepare($sql);
        $stmt->bind_param("i", $userId);
        if($stmt->execute()){
            $stmt->close();
            session_destroy();
            header("Location: login.php");
            exit;
        }else{
            $error = "Error deleting account: " . $stmt->error;
            $stmt->close();
        }
    }

}

?>

    <div class="container">
        <h2>Delete Account</h2>
        <p>Please confirm your password to delete your account.</p>
        <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-danger">Delete Account</button>
        </form>
        <a href="profile.php" class="btn btn-secondary">Cancel</a>
    </div>
</body>
</html>