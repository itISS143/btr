<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "btr";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and convert to uppercase
    $name = $_POST['name'];
    $division = $_POST['division'];
    $department = $_POST['department'];
    $phone = $_POST['phone'];
    $id_card = $_POST['id_card'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $password = $_POST['password']; // Hash the password
    $manager_name = $_POST['manager_name'];
    $company = $_POST['company'];

    // Query the current maximum idNumber
    $maxIdResult = $conn->query("SELECT MAX(idNumber) AS maxId FROM requestor_form");
    $row = $maxIdResult->fetch_assoc();
    $maxId = $row['maxId'];

    // Increment the ID for the new record
    $idNumber = $maxId + 1;

    // Update SQL query to include idNumber
    $sql = "INSERT INTO requestor_form (idNumber, requestorName, division, departement, phoneNumber, idCard, email, gender, password, manager_name, company)
            VALUES ('$idNumber', '$name', '$division', '$department', '$phone', '$id_card', '$email', '$gender', '$password', '$manager_name', '$company')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['add_employee'] = true;
        echo '<script>alert("New record created successfully");</script>';
        header('Location: home.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    // Handle invalid request method
    echo "Invalid request method";
}
?>
