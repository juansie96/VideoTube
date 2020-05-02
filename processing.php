<?php 
    require_once("includes/header.php"); 
    require_once("includes/classes/VideoUploadData.php"); 
    require_once("includes/classes/VideoProcessor.php"); 

    // If there is not data sent to the page then exit
    if(!isset($_POST["uploadButton"])) {
        echo "No file sent to page";
        exit();
    } 

    // 1. Create file upload data
    $videoUploadData = new VideoUploadData($_FILES["fileInput"],
                        $_POST["titleInput"],
                        $_POST["descriptionInput"],
                        $_POST["privacyInput"],
                        $_POST["categoryInput"],
                        "REPLACE-THIS" );

    // 2. Process video data (upload)
    $videoProcessor = new VideoProcessor($con);
    $wasSuccessful = $videoProcessor->upload($videoUploadData);

    // echo $wasSuccessful;


    // 3. Check if upload was successful

?>