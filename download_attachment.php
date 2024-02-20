<?php
// download_attachment.php

session_start(); // Start the session if not started already

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "btr";

// Validate user authentication if needed

if (isset($_GET['id'])) {
    $attachmentId = $_GET['id'];

    // Fetch the attachment from the database based on the ID
    $attachmentQuery = 'SELECT attachment_file FROM submitted_requestorforms WHERE id = ?';
    $stmtAttachment = $conn->prepare($attachmentQuery);
    $stmtAttachment->bind_param('i', $attachmentId);

    if ($stmtAttachment->execute()) {
        $resultAttachment = $stmtAttachment->get_result();
        $rowAttachment = $resultAttachment->fetch_assoc();

        if ($rowAttachment) {
            // Set appropriate headers for file download
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="attachment_file.txt"');

            // Output the attachment file content
            echo base64_decode($rowAttachment['attachment_file']);
            exit();
        } else {
            echo 'Attachment not found.';
            exit();
        }
    } else {
        echo 'Failed to execute attachment query: ' . $stmtAttachment->error;
        exit();
    }
}

// Redirect to home page if no ID is provided
header('Location: home.php');
exit();
?>
