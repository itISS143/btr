<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "btr";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if references and status are set and not empty
    if (isset($_POST['references']) && isset($_POST['status'])) {
        $references = $_POST['references'];
        $status = $_POST['status'];

        // Validate status (adjust the allowed status values as needed)
        $allowedStatusValues = ['Approved', 'Rejected', 'Pending'];
        if (!in_array($status, $allowedStatusValues)) {
            echo 'Invalid status value.';
            exit();
        }

        // Update the status and set the current date and time
        $updateStatusQuery = 'UPDATE submitted_requestorform SET approval = ?, status_date_time = NOW() WHERE reference = ?';
        $stmtUpdateStatus = $conn->prepare($updateStatusQuery);

        foreach ($references as $reference) {
            $stmtUpdateStatus->bind_param('ss', $status, $reference);
            if ($stmtUpdateStatus->execute()) {
                // Handle success if needed
            } else {
                echo 'Failed to execute query: ' . $stmtUpdateStatus->error;
                exit();
            }
        }

        $stmtUpdateStatus->close();

        // Redirect to kirimEmail.php with updated references
        header('Location: kirimEmail.php?references=' . urlencode(json_encode($references)));

    } else {
        echo 'Invalid references or status.';
    }
} else {
    echo 'Invalid request method.';
}

$conn->close();
?>
