<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/signup.css">

</head>
<body>
    <?php

    //database connection
    require_once('../../connection.php');
    

    // Initialize variables for form data
    $username = $password = $confirm_password = "";
    $error = "";
    $success= "";

    //Check if the from is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        //Validate form data
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        $email = htmlspecialchars($_POST['email']);
        $date_of_birth = htmlspecialchars($_POST['dob']);
        $confirm_password = $_POST['confirm_password'];
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $insertQuery = null;

        

        

        if($query = $db_connection->prepare("SELECT * FROM users WHERE email = ?")){
            $query->bind_param('s', $email);
            $query->execute();
            $query->store_result();

            if($query->num_rows > 0){
                $error .= '<p class="error">The email address is already registered!</p>';
            }else{
                // validate email
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $error .= '<p class="error">Invalid email format.</p>';
                }
                //Validate date of brith ()
                $date_format = 'Y-m-d';
                $d = DateTime::createFromFormat($date_format, $date_of_birth);
                if(!($d && $d->format($date_format) === $date_of_birth)){
                    $error .= '<p class="error">Invalid date of birth format. Please use YYYY-MM-DD.</p>';
                }
                //Validate username
                if (strlen($username)<4){
                    $error .= '<p class="error">Username must have atleast 4 characters. ';
                }
                //Validate password
                if (strlen($password)<8){
                    $error .= '<p class="error">Password must have atleast 8 characters. ';
                }
                if (!preg_match('/[A-Z]/', $password)) {
                    $error .= '<p class="error">Password must include at least one uppercase letter.</p>';
                }
                if(empty($confirm_password)){
                    $error .= '<p class="error">Please enter confirm password.</p>';
                } else{
                    if(empty($error) && ($password != $confirm_password)){
                        $error .='<p class="error">Password did not match.</p>';
                    }
                }
                if(empty($error)){
                    $insertQuery = $db_connection->prepare("INSERT INTO users (username, email, dob, password) VALUES (?, ?, ?, ?);");
                    $insertQuery->bind_param("ssss", $username,$email, $date_of_birth, $password_hash);
                    $result = $insertQuery->execute();
                    if($result){
                        // Get the newly created user ID
                        $newUserId = $insertQuery->insert_id;

                        // deifne your default tasks (descriptions)
                        $defaultTasks = [
                            'Workout for 20 Min',
                            'Study for 10 Min',
                            'Read for 10 Min',
                            'Do Plank 2 Min',
                            'Meditate for 10 minutes'
                        ];

                        //prepare insert statement for tasks
                        $taskInsertStmt = $db_connection->prepare("INSERT INTO tasks (user_id, description, progress, status) VALUES (?, ?, 0, 'incomplete')");

                        foreach($defaultTasks as $desc){
                            $taskInsertStmt->bind_param("is", $newUserId, $desc);
                            $taskInsertStmt->execute();
                        }
                        $taskInsertStmt->close();


                        $success .= '<p class="success">Your registration was successful, default tasks have been assigned! Please login to continue.</p>';
                    }else{
                        $error .= '<p class="error">Something went wrong: '.$insertQuery->error.'</p>';
                    }
                }
            }
            $query->close();
        }
       
    }
    ?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Register</h2>
                <p>Please fill this form to create an account</p>
                <?php echo $error; ?>
                <?php echo $success; ?>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    <label for="username">Username</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    <label for="email">Email</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="dob" name="dob" placeholder="Date of Birth" required>
                    <label for="dob">Date of Birth</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                    <label for="confirm_password">Confirm Password</label>
                </div>

                <button type="submit" class="btn btn-primary">Register</button>
                
                </form>
                <a href="login.php" class="btn btn-secondary">Back to Login</a>

            </div>
        </div>
    </div>

</body>
</html>
