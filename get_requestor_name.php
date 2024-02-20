<?php
// get_requestor_name.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reference'])) {
    $reference = $_POST['reference'];

    // Perform the first database query to get requestor_id based on $reference
    $conn = new mysqli("localhost", "root", "", "btr");

    $sql1 = "SELECT requestor_id FROM submitted_requestorform WHERE reference = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("i", $reference); 
    $stmt1->execute();
    $stmt1->bind_result($requestor_id);
    $stmt1->fetch();
    $stmt1->close();

    // Perform the second database query to get requestorName based on requestor_id
    $conn2 = new mysqli("localhost", "root", "", "btr");

    $sql2 = "SELECT requestorName FROM requestor_form WHERE idNumber = ?";
    $stmt2 = $conn2->prepare($sql2);
    $stmt2->bind_param('s', $requestor_id);
    $stmt2->execute();
    $stmt2->bind_result($requestorName);
    $stmt2->fetch();
    $stmt2->close();

    echo $requestorName;
} else {
    echo 'Invalid request';
}
?>
