<?php 
    require_once("includes/header.php"); 

    // If there is not data sent to the page then exit
    if(!isset($_POST["uploadButton"])) {
        echo "No file sent to page";
        exit();
    } 

    // 1. Create file upload data

    // 2. Process video data (upload)

    // 3. Check if upload was successful
?>