<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['references']) && isset($_POST['status'])) {
        $references = $_POST['references'];
        $status = $_POST['status'];

        // Update the session data with the new status
        foreach ($references as $reference) {
            $_SESSION['status'][$reference] = $status;
        }

        echo 'Status updated successfully in session.';
        exit();
    }
}

echo 'Invalid request.';
exit();
