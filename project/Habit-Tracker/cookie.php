<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cookies.css">

</head>
<body>
<?php

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cookieConsent'])){

        setcookie("cookiesConsent", $_POST['cookieConsent'], time() + 86400 * 7, "/");
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
?>
<?php if(!isset($_COOKIE['cookiesConsent'])):  ?>
    <div id="cookie-banner">
        <h1>Welcome To Our Website</h1>
        <p>Our site uses cookies to ensure you get the best experience.
            By clicking "Allow all" you consent to the use of all cookies.
        </p>
        <form method="post" action="">
            <button type="submit" name="cookieConsent" value="allow">Allow all</button>
            <button type="submit" name="cookieConsent" value="deny">Deny</button>
        </form>
    </div>
<?php endif; ?>
</body>
</html>