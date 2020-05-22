<?php require_once("includes/header.php"); ?>

<?php 
    if (isset($_SESSION["userLoggedIn"])) {
        echo "user loged as " . $userLoggedInObj->getFullName();
    } else {
        echo 'not logged in';
    }

?>

<?php require_once("includes/footer.php"); ?>
            