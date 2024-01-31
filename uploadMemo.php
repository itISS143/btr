<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file uploads
    $uploadDirectory = 'uploads/';
    $uploadedFiles = [];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "btr");

    // Check if 'reference' is set in the URL
    if (isset($_GET['reference'])) {
        $referenceFromURL = $_GET['reference'];

        foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['files']['name'][$key];
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
                    echo "File updated successfully.";
                    header('Location: closing.php?reference=' . $referenceFromURL);
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
