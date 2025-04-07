<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php

if ($_SERVER['SERVER_NAME'] == 'knuth.griffith.ie') {
    // Path for the Knuth server
    $path_to_mysql_connect = '../../connection.php';
} else {
    // Path for the local XAMPP server
    $path_to_mysql_connect = '../../connection.php';
}

// Require the mysql_connect.php file using the determined path
require $path_to_mysql_connect;




//just in case if the code above is not working use this
//require '../../connection.php';
// Check if connection is successful
// if (!$db_connection) {
//     die("Connection failed: " . mysqli_connect_error());
// }

// Start the session 
session_start();
// if the user is already logged in then redirect user to welcome page
if (isset($_SESSION["email"]) && $_SESSION["email"] === true) {
header("location: home.php"); 
exit;
}
?>
</body>

</html>