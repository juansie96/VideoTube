<?php 
require_once("includes/header.php"); 
require_once("includes/classes/VideoPlayer.php");

if (!isset($_GET["id"])) {
    echo "No url passed into page";
    exit();
}

$video = new Video($con, $_GET["id"], $userLoggedInObj);
$video->incrementViews();

?>

<div class="watchLeftColumn">
</div>

<?php require_once("includes/footer.php"); ?>
            