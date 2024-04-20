<?php
session_start();

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if references, status, and username are set and not empty
    if (isset($_POST['references']) && isset($_POST['status']) && isset($_POST['username'])) {
        // Retrieve data from POST request
        $references = $_POST['references'];
        $status = $_POST['status'];
        $username_post = $_POST['username'];

        // Create database connection
        $conn = new mysqli("localhost", "root", "", "btr");

        // Check database connection
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        // Prepare and execute SQL update statement
        $updateStatusQuery = 'UPDATE submitted_requestorform SET approval = ?, status_date_time = NOW(), approvedBy = ? WHERE reference = ?';
        $stmtUpdateStatus = $conn->prepare($updateStatusQuery);

        foreach ($references as $reference) {
            $stmtUpdateStatus->bind_param('sss', $status, $username_post, $reference);
            if ($stmtUpdateStatus->execute()) {
                // Handle success if needed
            } else {
                echo 'Failed to execute query: ' . $stmtUpdateStatus->error;
                exit();
            }
        }

        // Close statement and database connection
        $stmtUpdateStatus->close();
        $conn->close();

        // Redirect to kirimEmail.php with updated references
        header('Location: kirimEmail.php?references=' . urlencode(json_encode($references)));
        
        echo 'Status updated successfully.';
    } else {
        echo 'Invalid references, status, or username.';
    }
} else {
    echo 'Invalid request method.';
}
?>
