<!DOCTYPE html>
<?php
    date_default_timezone_set("America/Denver");
    include 'database.php';
    $conn = getConnection();

    if (isset($_GET['err'])) {
        $errorMessage = htmlspecialchars($_GET['err']);
    } else {
        $errorMessage = "";
    }
?> 
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="index.css" />
        <link rel="stylesheet" type="text/css" href="common.css" />
        <title>Login</title>
    </head>
    <?php
        include('header.php');
    ?>
    <body>

        <div class="login-container">
            <form action="login.php" method="post">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <br>
                <input type="submit" value="Login" class="login-button">

            </form>
            <p id="invalid"><?php echo $errorMessage; ?></p>
            <div class="options">
                <label for="remember"><input type="checkbox" id="remember" name="remember"> Remember Me</label>
                <a href="#" class="forgot-password">Forgot Password?</a>
            </div>
        </div>

        <p class="guest-link">
            <a href="store.php" class="guest-button">Continue as Guest</a>
        </p>
    </body>
    <?php
        include('footer.php');
    ?>
</html>
