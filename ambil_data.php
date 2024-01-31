<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "btr";

// Create connection
$Open = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$Open) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
