<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">

</head>
<body>
    
    <?php 
    
    // require_once('session.php');
    session_start();
    require_once('../../connection.php');
    $error = '';

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])){

        $email = trim($_POST['email']);
        $password = trim($_POST['password']);


        //validate if email is empty
        if(empty($email)){
            $error .= "<p class='error'>Please enter email.</p>";
        }
        if(empty($password)){
            $error .= "<p class='error'>Please enter your password.</p>";
        }

        if($password)
        if(empty($error)){
            // prepare and execute the SELECT query
            $stmt = $db_connection->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){

                //get the user data from the database
                $row = $result->fetch_assoc();

                //verify the password
                if(password_verify($password, $row['password'])){
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    
                    // Redirect the user to home page
                    header('location: home.php');
                    exit;
                }else{
                    $error .= "<p class='error'>The password is not valid.</p>";
                }
            }else{
                $error .= "<p class='error'>No User exist with that email address.</p>";
            }
            $stmt->close();

        }
        //Close connection
        mysqli_close($db_connection);
    }

    function display_error($error){
        echo $error;
    }
    ?>
    <div class="container">
        <h2>Sign in</h2>
        <p>Please fill in your email and password</p>
        <?php echo $error; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <button class="btn" type="submit" name="submit">Login</button>
        </form>
        <a href="signup.php" class="signup-link">Don't have an account? Sign up</a>
    </div>



</body>
</html>