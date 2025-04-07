<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/editprofile.css">


</head>
<body>
    
    <?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


    // require_once('session.php');
    session_start();
    require_once('../../connection.php');
    
    

    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit;
    }
   
    $errors = [];
    $error = "";
    $success = "";
    $id = $_SESSION['id'];

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $db_connection->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0){
        die("User not found.");
    }

    $row = $result->fetch_assoc();
    $username = $row["username"];
    $email = $row["email"];
    $passwordStored = $row["password"];
    $date_of_birth = $row["dob"];
    $stmt->close();

    function validateInputs($input){

        global $db_connection;
        $username = $input['username'] ?? '';
        $email = $input['email']?? '';
        $password = $input['password']?? '';
        $date_of_birth = $input['dob']?? '';
        $errors = [];

        if(empty($username)){
            $errors[] = "Please enter a username";
        }else{
            
            if(strlen($username)<4){
                $errors[] = "Username must have atleast 4 characters. ";
            }else{
                //Checking if the username already exists in different account
                $sql = "SELECT username FROM users WHERE username = ? AND id !=?";
                $stmt = $db_connection->prepare($sql);
                $stmt->bind_param("si", $username, $_SESSION['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result && $result->num_rows > 0){
                    $errors[] = "Username '$username' already exists in the database.";
                }
                $stmt->close();
            }
        }

        // validate email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[] = "Invalid email format.";
        }
        //Validate date of brith ()
        $date_format = 'Y-m-d';
        $d = DateTime::createFromFormat($date_format, $date_of_birth);
        if(!($d && $d->format($date_format) === $date_of_birth)){
            $errors[] = '<p class="error">Invalid date of birth format. Please use YYYY-MM-DD.</p>';
        }
        //Validate password
        if(!empty($password)){
            if (strlen($password)<8){
                $errors[] = '<p class="error">Password must have atleast 8 characters. ';
            }
            if (!preg_match('/[A-Z]/', $password)) {
                $errors[] = "Password must include at least one uppercase letter.</p>";
            }
            if (isset($input['confirm_password'])) {
                if ($input['password'] !== $input['confirm_password']) {
                    $errors[] = "Passwords do not match.";
                }
            }
        }
        return $errors;
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])){
        $username = htmlspecialchars($_POST["username"] ?? '');
        $password = htmlspecialchars($_POST["password"]?? '');
        $email = htmlspecialchars($_POST["email"]?? '');
        $date_of_birth = htmlspecialchars($_POST["dob"]?? '');

        //Validate form inputs
        $errors = validateInputs($_POST);

        if(empty($errors)){

            if(!empty($passwordInput)){
                $passwordHashed = password_hash($passwordInput, PASSWORD_BCRYPT);
            }else{
                $passwordHashed = $passwordStored;
            }
            $sql = "UPDATE users SET username = ?, password = ?, email = ?, dob = ? WHERE id = ?";
            $stmt = $db_connection->prepare($sql);
            $stmt->bind_param("ssssi", $username, $passwordHashed, $email, $date_of_birth, $id);
            $result = $stmt->execute();

            if($result){
                $successMessage = "Your details updated successfully";
            }else{
                $errorMessage = "Error updating profile " . $db_connection->error;
            }
            $stmt->close();
        }else{
            $error = implode("<br>", $errors);
        }
    }
    ?>
    <script>
    // Toggle readonly state for a given input.
    function toggleInput(id) {
      var input = document.getElementById(id);
      // Toggle readonly attribute.
      if (input.hasAttribute("readonly")) {
        input.removeAttribute("readonly");
      } else {
        input.setAttribute("readonly", "readonly");
      }
      // Update button text.
      var btn = document.getElementById(id + "-btn");
      btn.innerText = input.hasAttribute("readonly") ? "Edit" : "Lock";
    }
    
    // Toggle readonly state for both password fields.
    function togglePasswordInputs() {
      var passwordInput = document.getElementById('password');
      var confirmPasswordInput = document.getElementById('confirm_password');
      
      if (passwordInput.hasAttribute("readonly")) {
        passwordInput.removeAttribute("readonly");
        confirmPasswordInput.removeAttribute("readonly");
      } else {
        passwordInput.setAttribute("readonly", "readonly");
        confirmPasswordInput.setAttribute("readonly", "readonly");
      }
      var btn = document.getElementById('password-btn');
      btn.innerText = passwordInput.hasAttribute("readonly") ? "Edit" : "Lock";
    }
    </script>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Edit Profile</h2>
                <?php if(!empty($error)){
                    echo "<p class='error'>$error</p>";
                }
                if(!empty($success)){
                    echo "<p class='success'>$success</p>";
                }
                
                ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($username) ?>" readonly>
                    <label for="username">Username</label>
                    <button type="button" class="edit-btn" id="username-btn" onclick="toggleInput('username')">Edit</button>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($email) ?>" readonly>
                    <label for="email">Email</label>
                    <button type="button" class="edit-btn" id="email-btn" onclick="toggleInput('email')">Edit</button>
                </div>

                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="dob" name="dob" placeholder="Date of Birth" required value="<?php echo htmlspecialchars($date_of_birth) ?>" readonly>
                    <label for="dob">Date of Birth</label>
                    <button type="button" class="edit-btn" id="dob-btn" onclick="toggleInput('dob')">Edit</button>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="New Password" readonly>
                    <label for="password">New Password</label>
                    <button type="button" class="edit-btn" id="password-btn" onclick="togglePasswordInputs() ">Edit</button>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" readonly>
                    <label for="confirm_password">Confirm Password</label>
                    
                </div>

                <button type="submit" class="btn btn-primary" name="submit">Save</button>
                
                </form>
                <a href="profile.php" class="btn btn-secondary">Back</a>

            </div>
        </div>
    </div>


</body>
</html>