<?php 
require_once("includes/config.php");

if (isset($_POST["submitButton"])) {
    $firstName = $_POST["firstName"];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sign In - VideoTube</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" href="./assets/css/styles.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script src="./assets/js/commonActions.js"></script>
</head>
<body>

<div class="signInContainer">

    <div class="column">

        <div class="header">
            <img src="./assets/images/icons/VideoTubeLogo.png" title="logo" alt="Site logo">   
            <h3>Sign in</h3> 
            <span>to continue to VideoTube</span>
        </div>

        <div class="loginForm">
            <form action="signIn.php" method="POST">
                <input type="text" name="username" placeholder="Username" autocomplete="off" required>
                <input type="password" name="password" placeholder="Password" autocomplete="off" required>
                <input type="submit" name="submitButton" value="SUBMIT"> 
            </form>
        </div>

        <a class="signInMessage" href="signUp.php">Need an account? Sign up here</a>

    </div>

</div>

</body>
</html>