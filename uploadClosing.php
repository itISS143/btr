<?php
// Check if the finalize button is clicked
if (isset($_POST['finalize'])) {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "btr");

    // Check if 'reference' is set in the URL
    if (isset($_GET['reference'])) {
        $referenceFromURL = $_GET['reference'];

        // Update the status to "finalize" in the database
        $sqlUpdateStatus = "UPDATE submitted_requestorform SET finalStatus = 'Finalize' WHERE reference = ?";
        $stmtUpdateStatus = $conn->prepare($sqlUpdateStatus);

        // Assuming 'reference' is an integer; adjust the type if necessary
        $stmtUpdateStatus->bind_param("i", $referenceFromURL);

        if ($stmtUpdateStatus->execute()) {
            $stmtUpdateStatus->close();
            $conn->close();

            // Redirect back to home.php
            header("Location: home.php");
            exit();
        } else {
            echo "Error updating status: " . $stmtUpdateStatus->error;
            $stmtUpdateStatus->close();
            $conn->close();
        }
    } else {
        echo "Reference not provided in the URL.";
    }
}

// Continue with the rest of the file upload handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch the comment from the form
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';

    // Database connection
    $conn = new mysqli("localhost", "root", "", "btr");

    // Check if 'reference' is set in the URL
    if (isset($_GET['reference'])) {
        $referenceFromURL = $_GET['reference'];

        // Update the comment in the database
        $stmtUpdateComment = $conn->prepare("UPDATE submitted_requestorform SET closingComment = ? WHERE reference = ?");
        $stmtUpdateComment->bind_param("si", $comment, $referenceFromURL);

        if ($stmtUpdateComment->execute()) {
            header("Location: home.php");
        } else {
            echo "Error updating comment: " . $stmtUpdateComment->error;
        }

        $stmtUpdateComment->close();

        foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['files']['name'][$key];
            $uploadDirectory = 'uploads/';
            $uploadPath = $uploadDirectory . basename($file_name);

            // Check if the file is uploaded successfully
            if ($_FILES['files']['name'][$key] != "" && move_uploaded_file($tmp_name, $uploadPath)) {
                // File uploaded successfully, set the filepath
                $filepath = $uploadPath;

                // Update the file content in the database based on the fetched reference
                $fileContent = file_get_contents($filepath);

                $stmt = $conn->prepare("UPDATE submitted_requestorform SET closing = ? WHERE reference = ?");
                $stmt->bind_param("si", $fileContent, $referenceFromURL); // 'b' indicates a blob type, 'i' for integer

                // Check if the SQL query is executed successfully
                if ($stmt->execute()) {
                    header("Location: home.php");
                    exit();
                } else {
                    echo "Error updating file: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Error uploading file.";
            }
        }
    } else {
        echo "Reference not provided in the URL.";
    }

    $conn->close(); // Close the database connection
}
?>
