<?php require_once("includes/header.php"); ?>

<?php 
session_destroy();
    if (isset($_SESSION["userLoggedIn"])) {
        echo "user loged as " . $_SESSION["userLoggedIn"];
    }

?>

<?php require_once("includes/footer.php"); ?>
            