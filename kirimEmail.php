<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Assuming you have a database connection, replace these values with your actual database credentials
$host = "localhost";
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

    // Retrieve data from the URL
    $references = isset($_GET['references']) ? json_decode(urldecode($_GET['references']), true) : [];

    // Loop through the references
    foreach ($references as $reference) {
        // Query to get manager_name, status, and requestor_id based on reference from submitted_requestorform
        $stmt = $pdo->prepare("SELECT s.manager_name, s.approval, s.requestor_id, r.idNumber, r.email as requestorEmail, r.requestorName, r.password
                            FROM submitted_requestorform s
                            JOIN requestor_forms r ON s.requestor_id = r.idNumber
                            WHERE s.reference = :reference");
        $stmt->bindParam(':reference', $reference, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new Exception("Manager name, status, or requestor details not found for the given reference.");
        }

        $managerName = $result['manager_name'];
        $status = $result['approval'];
        $requestorId = $result['requestor_id'];
        $idNumber = $result['idNumber'];
        $requestorName = $result['requestorName'];
        $requestorEmail = $result['requestorEmail'];
        $requestorPassword = $result['password'];

        // You can customize these display names as needed
        $managerDisplayName = "Manager " . $managerName;
        $requestorDisplayName = "Requestor " . $requestorName;

        // Sending email to requestor
        $mail->setFrom('itiss2024@gmail.com', 'admin');
        $mail->addAddress($requestorEmail, $requestorName);
        $mail->isHTML(true);
        $mail->Subject = 'BTR';

        if ($status == 'Approved') {
            $mail->Body = 'Hello ' . $requestorDisplayName . '<br><br>' .
                'Your BTR has been approved by ' . $managerDisplayName . '.<br><br>' .
                'To check, click the following link:<br>' .
                '<a href="https://btr.issmedika.com/index.php?email=' . urlencode($requestorEmail) . '&password=' . urlencode($requestorPassword) . '">Login to BTR Portal</a><br><br>';
            $mail->send();
        } elseif ($status == 'Rejected') {
            $mail->Body = 'Hello ' . $requestorDisplayName . '<br><br>' .
                'Your BTR has been declined by ' . $managerDisplayName . '.<br><br>' .
                'To check, click the following link:<br>' .
                '<a href="https://btr.issmedika.com/index.php?email=' . urlencode($requestorEmail) . '&password=' . urlencode($requestorPassword) . '">Login to BTR Portal</a><br><br>';
            $mail->send();
        } else {
            // Handle other status scenarios here
        }

        // Fetching password for Iyanju
        $stmt = $pdo->prepare("SELECT password FROM requestor_forms WHERE email = 'purchasing_btr@issmedika.com'");
        $stmt->execute();
        $iyanjuResult = $stmt->fetch(PDO::FETCH_ASSOC);
        $iyanjuPassword = $iyanjuResult['password'];

        // Sending email to Iyanju
        $mail->clearAddresses();
        $mail->addAddress('purchasing_btr@issmedika.com');
        $mail->isHTML(true);
        $mail->Subject = 'BTR';

        if ($status == 'Approved') {
            $mail->Body = 'Hello Purchasing Team,<br><br>' .
                'A BTR has been approved by ' . $managerDisplayName . '.<br><br>' .
                'To check, click the following link:<br>' .
                '<a href="https://btr.issmedika.com/index.php?email=purchasing_btr@issmedika.com&password=' . urlencode($iyanjuPassword) . '">Login to BTR Portal</a><br><br>';
            $mail->send();
        } elseif ($status == 'Rejected') {
            $mail->Body = 'Hello Purchasing Team,<br><br>' .
                'A BTR has been declined by ' . $managerDisplayName . '.<br><br>' .
                'To check, click the following link:<br>' .
                '<a href="https://btr.issmedika.com/index.php?email=purchasing_btr@issmedika.com&password=' . urlencode($iyanjuPassword) . '">Login to BTR Portal</a><br><br>';
            $mail->send();
        } else {
            // Handle other status scenarios here
        }
        
        // Fetching password for Ludi
        $stmt = $pdo->prepare("SELECT password FROM requestor_forms WHERE email = 'finance_btr@issmedika.com'");
        $stmt->execute();
        $dwiResult = $stmt->fetch(PDO::FETCH_ASSOC);
        $dwiPassword = $dwiResult['password'];

        // Sending email to Dwi
        $mail->clearAddresses();
        $mail->addAddress('finance_btr@issmedika.com');
        $mail->isHTML(true);
        $mail->Subject = 'BTR';

        if ($status == 'Approved') {
            $mail->Body = 'Hello Finance Team,<br><br>' .
                'A BTR has been approved by ' . $managerDisplayName . '.<br><br>' .
                'To check, click the following link:<br>' .
                '<a href="https://btr.issmedika.com/index.php?email=finance_btr@issmedika.com&password=' . urlencode($dwiPassword) . '">Login to BTR Portal</a><br><br>';
            $mail->send();
        } elseif ($status == 'Rejected') {
            $mail->Body = 'Hello Finance Team,<br><br>' .
                'A BTR has been declined by ' . $managerDisplayName . '.<br><br>' .
                'To check, click the following link:<br>' .
                '<a href="https://btr.issmedika.com/index.php?email=finance_btr@issmedika.com&password=' . urlencode($dwiPassword) . '">Login to BTR Portal</a><br><br>';
            $mail->send();
        } else {
            // Handle other status scenarios here
        }
        
        // Fetching password for Darwati
        $stmt = $pdo->prepare("SELECT password FROM requestor_forms WHERE email = 'Kasir@issmedika.com'");
        $stmt->execute();
        $dwiResult = $stmt->fetch(PDO::FETCH_ASSOC);
        $dwiPassword = $dwiResult['password'];

        // Sending email to Dwi
        $mail->clearAddresses();
        $mail->addAddress('Kasir@issmedika.com');
        $mail->isHTML(true);
        $mail->Subject = 'BTR';

        if ($status == 'Approved') {
            $mail->Body = 'Hello Darwati,<br><br>' .
                'A BTR has been approved by ' . $managerDisplayName . '.<br><br>' .
                'To check, click the following link:<br>' .
                '<a href="https://btr.issmedika.com/index.php?email=Kasir@issmedika.com&password=' . urlencode($dwiPassword) . '">Login to BTR Portal</a><br><br>';
            $mail->send();
        } elseif ($status == 'Rejected') {
            $mail->Body = 'Hello Darwati,<br><br>' .
                'A BTR has been declined by ' . $managerDisplayName . '.<br><br>' .
                'To check, click the following link:<br>' .
                '<a href="https://btr.issmedika.com/index.php?email=Kasir@issmedika.com&password=' . urlencode($dwiPassword) . '">Login to BTR Portal</a><br><br>';
            $mail->send();
        } else {
            // Handle other status scenarios here
        }
    }

    header('Location: home.php');
    exit();

} catch (Exception $e) {
    echo 'Mailer Error: ' . $e->getMessage();
}
?>
