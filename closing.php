<?php
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

// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_name'])) {
    // Redirect to the index page if not logged in
    header('Location: index.php');
    exit();
}

$isHome = isset($_GET['home']);

// Fetch the username from the session
$userName = $_SESSION['user_name'];

$referenceFromURL = isset($_GET['reference']) ? $_GET['reference'] : null;
$validateSls = getFinalStatusFromDatabase($referenceFromURL);
$requestorName = isset($_GET['requestorName']) ? $_GET['requestorName'] : '';

// Fetch the existing comment from the database
    $conn = new mysqli("localhost", "root", "", "btr");
$sqlComment = "SELECT closingComment FROM submitted_requestorform WHERE reference = ?";
$stmtComment = $conn->prepare($sqlComment);
$stmtComment->bind_param("i", $referenceFromURL);
$stmtComment->execute();
$stmtComment->bind_result($existingComment);
$stmtComment->fetch();
$stmtComment->close();
$conn->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Form</title>
</head>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<body>
    <a id="goBackButton" href="#" onclick="goBack()" style="text-decoration:none;">Back</a>

    <h2>File Upload Form - Closing</h2>
    <?php if ($validateSls === null && ($userName === 'Wiwiet Widya Ningrum' || $userName === $requestorName)) : ?>
    <form action="uploadClosing.php?reference=<?php echo $referenceFromURL; ?>" method="post" enctype="multipart/form-data">
        <label for="file">Select files to upload (multiple files allowed):</label>
        <input type="file" name="files[]" id="file" multiple accept=".pdf, .doc, .docx">
        <br>
        <input type="submit" value="Upload Files">
    </form>
<?php endif; ?>
<br>
<label for="comment">Comment:</label>
<textarea id="comment" name="comment" rows="4" cols="50"><?php echo htmlspecialchars($existingComment); ?></textarea>

    <h3>Uploaded Files:</h3>
    <div>
        <?php
    $conn = new mysqli("localhost", "root", "", "btr");

        $sqlFiles = "SELECT closing FROM submitted_requestorform WHERE reference = ?";
        $stmtFiles = $conn->prepare($sqlFiles);
        $stmtFiles->bind_param("i", $referenceFromURL);  
        $stmtFiles->execute();
        $resultFiles = $stmtFiles->get_result();

        if ($resultFiles->num_rows > 0) {
            while ($rowFiles = $resultFiles->fetch_assoc()) {
                $fileContent = $rowFiles['closing'];

                echo "<div class='file-frame'>";
                $fileType = mime_content_type("data://text/plain;base64," . base64_encode($fileContent));

                if (strpos($fileType, 'pdf') !== false) {
                    echo "<iframe src='data:application/pdf;base64," . base64_encode($fileContent) . "'></iframe>";
                } else {
                    echo "Unsupported file type.";
                }
                echo "</div>";
            }
        } else {
            echo "<p>No files found for this request.</p>";
        }

        $stmtFiles->close();

        $conn->close();
        ?>
    </div>

</body>
<script>
$(document).ready(function () {
        // Fetch the existing comment from the database
        var existingComment = "<?php echo htmlspecialchars($existingComment); ?>";

        // Set the initial value of the comment textarea
        $('#comment').val(existingComment);

        // Listen for changes in the comment field
        $('#comment').on('input', function () {
            // Get the comment value
            var commentValue = $(this).val();

            // Get the reference from the URL
            var referenceFromURL = "<?php echo isset($_GET['reference']) ? $_GET['reference'] : ''; ?>";
            var validateSls = "<?php echo isset($_GET['validateSls']) ? $_GET['validateSls'] : ''; ?>";

            // Make an AJAX request to update the comment
            $.ajax({
                type: 'POST',
                url: 'uploadClosing.php?reference=' + referenceFromURL + '&validateSls=' + validateSls,
                data: { comment: commentValue },
                success: function (response) {
                    // Handle the response if needed
                    console.log(response);
                },
                error: function (error) {
                    console.error('Error updating comment:', error);
                }
            });
        });
    });

    function goBack() {
    window.history.back();
    return false; // Prevents the default behavior of the link
}
</script>
<style>
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

    .file-frame {
        border: 1px solid #ccc;
        padding: 20px; 
        margin: 10px; 
        border-radius: 5px;
        overflow: hidden; 
        max-width: 100%; 
        box-sizing: border-box; 
    }

    .file-frame iframe {
        width: 100%; 
        height: 500px; 
        display: block; 
        margin: 0 auto; 
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

    #comment {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }
</style>
</html>
