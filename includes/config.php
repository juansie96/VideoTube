<?php 
ob_start(); //Turns on ouput buffering
session_start();

date_default_timezone_set("America/Argentina/Buenos_Aires");

// Make connection to MySQL DB
try {
    $con = new PDO("mysql:dbname=VideoTube;host=localhost", "root", "");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>