<?php

ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Xampp Database Connection
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASSWORD', ''); //enter your password
     define('DB_NAME', 'habit_tracker'); //enter your DB_Name(same as student login)

    // //Knuth Database Connection
    // define('DB_HOST', 'localhost');
    // define('DB_USER', 's3112121');
    // define('DB_PASSWORD', 'tiveleme'); //enter your password
    // define('DB_NAME', 'habit_tracker'); //enter your DB_Name (same as student login)
    
    $db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if(!$db_connection){
        die("Database connection failed: ". mysqli_connect_error());
    }
    


?>