<?php
// Assuming you have established a database connection

// Create connection
$stmt = mysqli_connect("localhost", "root", "", "btr");

// Check connection
if (!$stmt) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_POST['requestor'])) {
    $selectedRequestor = $_POST['requestor'];

    // Prepare and execute a query to fetch the manager name based on the selected requestor
    $query = "SELECT manager_name FROM requestor_forms WHERE requestorName = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $selectedRequestor);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Check if a result is returned
        if ($result->num_rows > 0) {
            // Fetch the manager name
            $row = $result->fetch_assoc();
            $managerName = $row['manager_name'];

            // Return the manager name as the response
            echo $managerName;
        } else {
            // If no result is returned, echo an error message or handle it accordingly
            echo "Manager not found for the selected requestor.";
        }
    } else {
        // If the query execution fails, echo an error message or handle it accordingly
        echo "Error executing query: " . $conn->error;
    }
} else {
    // If requestor is not set in the POST data, echo an error message or handle it accordingly
    echo "No requestor selected.";
}
?>
