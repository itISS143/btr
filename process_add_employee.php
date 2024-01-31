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
    $name = strtoupper($_POST['name']);
    $division = strtoupper($_POST['division']);
    $department = strtoupper($_POST['department']);
    $phone = strtoupper($_POST['phone']);
    $id_card = strtoupper($_POST['id_card']);
    $email = strtoupper($_POST['email']);
    $gender = strtoupper($_POST['gender']);
    $password = strtoupper($_POST['password']); // Hash the password
    $manager_name = strtoupper($_POST['manager_name']);

    // Query the current maximum idNumber
    $maxIdResult = $conn->query("SELECT MAX(idNumber) AS maxId FROM requestor_forms");
    $row = $maxIdResult->fetch_assoc();
    $maxId = $row['maxId'];

    // Increment the ID for the new record
    $idNumber = $maxId + 1;

    // Update SQL query to include idNumber
    $sql = "INSERT INTO requestor_forms (idNumber, requestorName, division, departement, phoneNumber, idCard, email, gender, password, manager_name)
            VALUES ('$idNumber', '$name', '$division', '$department', '$phone', '$id_card', '$email', '$gender', '$password', '$manager_name')";

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
