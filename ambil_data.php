<?php

// Create connection
$Open = mysqli_connect("localhost", "root", "", "btr");

// Check connection
if (!$Open) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
