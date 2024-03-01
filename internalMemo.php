<?php

session_start();
if (!isset($_SESSION['user_name'])) {
    // Redirect to the index page if not logged in
    header('Location: index.php');
    exit();
}

// Fetch the userName from the session
$userName = $_SESSION['user_name'];

function getFinalStatusFromDatabase($reference) {
    $conn = new mysqli("localhost", "root", "", "btr");

    $sql = "SELECT validateSls FROM submitted_requestorform WHERE reference = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reference); 
    $stmt->execute();
    $stmt->bind_result($finalStatus);
    $stmt->fetch();

    $stmt->close();
    $conn->close();

    return $finalStatus;
}

$referenceFromURL = isset($_GET['reference']) ? $_GET['reference'] : null;
$validateSls = getFinalStatusFromDatabase($referenceFromURL);
$requestorName = isset($_GET['requestorName']) ? $_GET['requestorName'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Form</title>
</head>
<body>
    <a id="goBackButton" href="#" onclick="goBack()" style="text-decoration:none;">Back</a>

    <h2>File Upload Form - Internal Memo</h2>

    <?php if ($validateSls === null && ($userName === 'Wiwiet Widya Ningrum')): ?>
    <form action="uploadMemo.php?reference=<?php echo $referenceFromURL; ?>" method="post" enctype="multipart/form-data" style="<?php echo $isFinalized ? 'display: none;' : ''; ?>">
        <label for="file">Select files to upload (multiple files allowed):</label>
        <input type="file" name="files[]" id="file" multiple accept=".pdf, .doc, .docx">
        <br>
        <input type="submit" value="Upload Files">
    </form>
    <?php endif; ?>

    <h3>Uploaded Files:</h3>
    <div>
        <?php
        // Database connection
    $conn = new mysqli("localhost", "root", "", "btr");

        // Fetch and display uploaded files from the database
        $sqlFiles = "SELECT internalMemo FROM submitted_requestorform WHERE reference = ?";
        $stmtFiles = $conn->prepare($sqlFiles);
        $stmtFiles->bind_param("i", $referenceFromURL);  // Assuming 'reference' is an integer; adjust the type if necessary
        $stmtFiles->execute();
        $resultFiles = $stmtFiles->get_result();

        if ($resultFiles->num_rows > 0) {
            while ($rowFiles = $resultFiles->fetch_assoc()) {
                $fileContent = $rowFiles['internalMemo'];

                // Display the file based on its type
                echo "<div class='file-frame'>";
                $fileType = mime_content_type("data://text/plain;base64," . base64_encode($fileContent));

                if (strpos($fileType, 'pdf') !== false) {
                    echo "<iframe src='data:application/pdf;base64," . base64_encode($fileContent) . "'></iframe>";
                } elseif (strpos($fileType, 'image') !== false) {
                    echo "<img src='data:" . $fileType . ";base64," . base64_encode($fileContent) . "' alt='Image'>";
                } else {
                    echo "Unsupported file type.";
                }
                echo "</div>";
            }
        } else {
            echo "<p>No files found for this request.</p>";
        }

        // Close the statement
        $stmtFiles->close();

        // Close the connection
        $conn->close();
        ?>
    </div>

</body>
<script>
     function goBack() {
            window.history.back();
            return false; // Prevents the default behavior of the link
        }
</script>
<style>
    /* Add CSS styles for the form */
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        margin-top: 50px;
    }

    h2 {
        color: #333;
    }

    form {
        margin-top: 20px;
    }

    label {
        display: block;
        margin-bottom: 10px;
    }

    input[type="file"] {
        margin-bottom: 10px;
    }

    /* Add CSS styles for the button */
    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    /* Add CSS styles for the uploaded files */
    .file-frame {
        border: 1px solid #ccc;
        padding: 20px; /* Increase the padding to make the frame bigger */
        margin: 10px; /* Increase the margin to add more space */
        border-radius: 5px;
        overflow: hidden; /* Ensure the frame doesn't overflow */
        max-width: 100%; /* Set the maximum width for the frame to 100% of its container */
        box-sizing: border-box; /* Include padding and border in the element's total width and height */
    }

    .file-frame iframe {
        width: 100%; /* Make sure the iframe fills the frame horizontally */
        height: 500px; /* Set the height of the iframe to your desired value */
        display: block; /* Remove extra space below the iframe */
        margin: 0 auto; /* Center the iframe within the frame */
    }

    #goBackButton {
            position: fixed;
            top: 10px;
            left: 10px;
            background-color: #ff3e3e;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
</style>
</html>
