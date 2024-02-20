<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have a database connection here

    // Get data from the request
    $reference = $_GET['reference'];
    $approval = json_decode(file_get_contents('php://input'), true)['approval'];

    // Update the database
    $conn = new mysqli("localhost", "root", "", "btr");
    $sql = "UPDATE submitted_requestorforms SET approvalSls = ? WHERE reference = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $approval, $reference);
    $stmt->execute();

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Send a response (you can customize this based on your needs)
    echo json_encode(['success' => true]);
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method']);
}
?>
