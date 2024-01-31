<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Assuming you have a database connection, replace these values with your actual database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "btr";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set PDO to throw exceptions on errors
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'itiss2024@gmail.com';
    $mail->Password = 'ayqf acbv sjkf duxd';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $selectedManagerName = isset($_GET['managerName']) ? $_GET['managerName'] : '';
    $userName = isset($_GET['user_name']) ? $_GET['user_name'] : '';
    $requestorId = isset($_GET['requestor_id']) ? $_GET['requestor_id'] : '';
    $totAmount = isset($_GET['total_amount']) ? $_GET['total_amount'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : '';

    // First query to get manager email and requestor_id
    $stmt = $pdo->prepare("SELECT r.email, s.requestor_id
                           FROM requestor_forms r
                           JOIN submitted_requestorform s ON r.requestorName = s.manager_name
                           WHERE s.manager_name = :selectedManagerName");
    $stmt->bindParam(':selectedManagerName', $selectedManagerName, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        throw new Exception("Manager email or requestor_id not found for the selected manager name.");
    }

    $managerEmail = $result['email'];
    $requestorId = $result['requestor_id'];

    // Second query to get requestorName based on requestor_id
    $stmt = $pdo->prepare("SELECT r.email, r.requestorName
                           FROM requestor_forms r
                           WHERE r.idNumber = :requestorId");
    $stmt->bindParam(':requestorId', $requestorId, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        throw new Exception("Requestor name not found for the given requestor ID.");
    }

    $requestorName = $result['requestorName'];

    // Sending email to manager
    $mail->setFrom('itiss2024@gmail.com', 'admin');
    $mail->addAddress($managerEmail, $selectedManagerName); // Manager becomes the recipient

    $mail->isHTML(true);
    $mail->Subject = 'BTR';

    // Check totAmount and add appropriate message
    if ($totAmount > 20000000) {
        // If totAmount is more than 20000000, send to rian.andrian@issmedika.com as well
        $mail->addAddress('rian_andrian@issmedika.com', 'Rian Andrian');
        $mail->Body = 'Hello Rian Andrian' . ',<br><br>' .
                      'User ' . $userName . ' has made a BTR with a total amount of more than 20,000,000. The requestor is ' . $requestorName . '.<br><br>' .
                      'Visit <a href="https://btr.issmedika.com">BTR Portal</a> for more details.';
    } else {
        // If totAmount is not more than 20000000, send the standard message
        $mail->Body = 'Hello ' . $selectedManagerName . ',<br><br>' .
                      'User ' . $userName . ' has made a BTR. The requestor is ' . $requestorName . '.<br><br>' .
                      'Visit <a href="https://btr.issmedika.com">BTR Portal</a> for more details.';
    }

    $mail->send();

    header('Location: home.php');
    exit();

} catch (Exception $e) {
    echo 'Mailer Error: ' . $e->getMessage();
}
?>
