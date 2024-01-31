<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reference = $_POST['reference'];
    $newDate = $_POST['newDate'];

    $conn = new mysqli("localhost", "root", "", "btr");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE submitted_requestorform SET validateKas = '$newDate' WHERE reference = '$reference'";
    $result = $conn->query($sql);

    if ($result) {
        echo "Date updated successfully";
    } else {
        echo "Error updating date: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request method";
}
?>
