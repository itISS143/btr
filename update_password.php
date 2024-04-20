<?php
session_start();


$conn = new mysqli("localhost", "root", "", "btr");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $currentPassword = $_POST["current_password"];
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    // Check if new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        echo "New password and confirm password do not match.";
        exit;
    }

    // Get the username from the session
    $username = $_SESSION['user_name'];

    // Fetch user data using the username
    $sql = "SELECT * FROM requestor_forms WHERE requestorName = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Check if the 'idNumber' key exists in the result
        if (array_key_exists("idNumber", $row)) {
            $userId = $row["idNumber"]; // Replace with the actual column name for the user ID
            $storedPassword = $row["password"]; // Replace with the actual column name for the password

            // Verify the current password
            if ($currentPassword === $storedPassword) {

                // Update the password in the database
                $updateSql = "UPDATE requestor_forms SET password = '$newPassword' WHERE idNumber = $userId";
                if ($conn->query($updateSql) === TRUE) {
                    // Password updated successfully
                    $_SESSION['password_updated'] = true;
                    header('Location: home.php');
                    exit();
                } else {
                    echo "Error updating password: " . $conn->error;
                }
            } else {
                echo "Incorrect current password.";
            }
        } else {
            echo "User ID not found in the result.";
        }
    } else {
        echo "User not found.";
    }

    $conn->close();
}
?>
