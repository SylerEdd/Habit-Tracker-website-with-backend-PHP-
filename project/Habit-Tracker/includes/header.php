<?php

if (!isset($pageTitle)) {
  $pageTitle = "Habit Tracker";
}
if (!isset($cssFile)) {
  $cssFile = "css/style.css";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageTitle; ?></title>
  <link rel="stylesheet" href="<?php echo $cssFile; ?>">
  <style>
    header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background-color: #ff8c00;
      color: #fff;
      padding: 1.5rem 2%;
      z-index: 1000;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      text-align: center;
    }
    header .logo {
      font-size: 2.5rem;
      margin: 0 auto;
      display: block;
    }
    nav.navbar {
      display: flex;
      justify-content: center;
      margin-top: 0.5rem;
    }
    nav.navbar a {
      font-size: 1.2rem;
      color: #fff;
      margin: 0 1rem;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    nav.navbar a:hover {
      text-decoration: underline;
    }

    body {
      padding-top: 80px; 
    }
  </style>
</head>
<body>
  <header>
    <h1 class="logo">Habit Tracker</h1>
    <nav class="navbar">
      <a href="home.php">Home</a>
      <a href="profile.php">Profile</a>
      <a href="habit.php">Habit</a>
      <a href="notify.php">Notifications</a>
    </nav>
  </header>
