<?php
// save_trip_data.php

// Assuming you have a database connection established
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "btr";

// Retrieve data from the AJAX request
$rowData = json_decode($_POST['rowData'], true);

// Insert data into the database
$sql = "INSERT INTO trip_routing (submitted_id, trip_from, trip_to, trip_class, flight_date, comments) VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param('isssss', $lastInsertId, $rowData['flightFrom'], $rowData['flightTo'], $rowData['tripClass'], $rowData['flightDate'], $rowData['flightComment']);

    if ($stmt->execute()) {
        // Send a success response back to the client if needed
        echo 'Data saved successfully';
    } else {
        // Send an error response back to the client if needed
        echo 'Error saving data: ' . $stmt->error;
    }

    $stmt->close();
} else {
    // Handle the case where the statement preparation failed
    echo 'Error preparing statement: ' . $conn->error;
}

// Close the database connection
$conn->close();
?>
