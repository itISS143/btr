<?php
session_start(); // Start the session

// Replace these with your actual database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "btr";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Define the sanitizeInput function outside of the conditional block
function sanitizeInput($input)
{
    return isset($input) ? $input : '';
}

// Check if the user is logged in
if (!isset($_SESSION['user_name'])) {
    // Redirect to the index page if not logged in
    header('Location: index.php');
    exit();
}

$allSubmittedData = [];
$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
$company = isset($_SESSION['company']) ? $_SESSION['company'] : '';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the POST values exist before accessing them
    $reference = isset($_POST['referenceNumber']) ? sanitizeInput($_POST['referenceNumber']) : '';
    $date = isset($_POST['Date']) ? sanitizeInput($_POST['Date']) : '';
    $status = isset($_POST['status']) ? sanitizeInput($_POST['status']) : '';
    $title = isset($_POST['title']) ? sanitizeInput($_POST['title']) : '';
    $purpose = isset($_POST['purpose']) ? sanitizeInput($_POST['purpose']) : '';
    $requestor = isset($_POST['requestor']) ? sanitizeInput($_POST['requestor']) : '';
    $email = isset($_POST['Email']) ? sanitizeInput($_POST['Email']) : '';
    $division = isset($_POST['division']) ? sanitizeInput($_POST['division']) : '';
    $departement = isset($_POST['Departement']) ? sanitizeInput($_POST['Departement']) : '';
    $idCard = isset($_POST['idCard']) ? sanitizeInput($_POST['idCard']) : '';
    $phoneNumber = isset($_POST['phoneNumber']) ? sanitizeInput($_POST['phoneNumber']) : '';
    $managerName = isset($_POST['manager']) ? sanitizeInput($_POST['manager']) : '';
    $startDate = isset($_POST['startDate']) ? sanitizeInput($_POST['startDate']) : '';
    $returnDate = isset($_POST['returnDate']) ? sanitizeInput($_POST['returnDate']) : '';
    $destination = isset($_POST['destination']) ? sanitizeInput($_POST['destination']) : '';
    $totalDays = isset($_POST['totalDays']) ? sanitizeInput($_POST['totalDays']) : '';
    $amount = isset($_POST['amount']) ? sanitizeInput($_POST['amount']) : '';
    $currency = isset($_POST['currency']) ? sanitizeInput($_POST['currency']) : '';
    $passComm = isset($_POST['passengerComment']) ? sanitizeInput($_POST['passengerComment']) : '';
    $hotelBooking = isset($_POST['hotel']) ? sanitizeInput($_POST['hotel']) : '';
    $flightFrom = isset($_POST['flightFrom']) ? sanitizeInput($_POST['flightFrom']) : '';
    $flightTo = isset($_POST['flightTo']) ? sanitizeInput($_POST['flightTo']) : '';
    $tripClass = isset($_POST['tripClass']) ? sanitizeInput($_POST['tripClass']) : '';
    $flightDate = isset($_POST['flightDate']) ? sanitizeInput($_POST['flightDate']) : '';
    $flightComment = isset($_POST['flightComment']) ? sanitizeInput($_POST['flightComment']) : '';
    $accAmount = isset($_POST['accomodationAmount']) ? sanitizeInput($_POST['accomodationAmount']) : '';
    $meaAmount = isset($_POST['mealAmount']) ? sanitizeInput($_POST['mealAmount']) : '';
    $traAmount = isset($_POST['transportationAmount']) ? sanitizeInput($_POST['transportationAmount']) : '';
    $othAmount = isset($_POST['otherAmount']) ? sanitizeInput($_POST['otherAmount']) : '';
    $totAmount = isset($_POST['totalAmount']) ? sanitizeInput($_POST['totalAmount']) : '';
    $accRemark = isset($_POST['accomodationRemark']) ? sanitizeInput($_POST['accomodationRemark']) : '';
    $meaRemark = isset($_POST['mealRemark']) ? sanitizeInput($_POST['mealRemark']) : '';
    $traRemark = isset($_POST['transportationRemark']) ? sanitizeInput($_POST['transportationRemark']) : '';
    $othRemark = isset($_POST['otherRemark']) ? sanitizeInput($_POST['otherRemark']) : '';
    $totRemark = isset($_POST['totalRemark']) ? sanitizeInput($_POST['totalRemark']) : '';
    $hotelName = isset($_POST['hotelName']) ? sanitizeInput($_POST['hotelName']) : '';
    $hotelAddress = isset($_POST['hotelAddress']) ? sanitizeInput($_POST['hotelAddress']) : '';
    $hotelPhone = isset($_POST['hotelPhone']) ? sanitizeInput($_POST['hotelPhone']) : '';
    $hotelRemark = isset($_POST['hotelRemark']) ? sanitizeInput($_POST['hotelRemark']) : '';
    $initiatedBy = isset($rowRequestorName['requestorName']) ? $rowRequestorName['requestorName'] : '';
    $idNumber = isset($_POST['idNumber']) ? sanitizeInput($_POST['idNumber']) : '';
    // Fetch the requestorName based on idNumber
    $requestorNameQuery = 'SELECT requestorName FROM requestor_forms WHERE idNumber = ?';
    $stmtRequestorName = $conn->prepare($requestorNameQuery);
    $stmtRequestorName->bind_param('s', $idNumber);
    
    if ($stmtRequestorName->execute()) {
        $resultRequestorName = $stmtRequestorName->get_result();
        $rowRequestorName = $resultRequestorName->fetch_assoc();

        if ($rowRequestorName) {
            // Use the requestorName as needed
            $initiatedBy = $rowRequestorName['requestorName'];
        } else {
            echo 'Data for requestor not found.';
            exit();
        }
    } else {
        echo 'Failed to execute query: ' . $stmtRequestorName->error;
        exit();
    }

    $stmtRequestorName->close();

    $requestorIdQuery = 'SELECT idNumber FROM requestor_forms WHERE requestorName = ?';
    $stmt = $conn->prepare($requestorIdQuery);
    $stmt->bind_param('s', $requestor);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            $requestorId = $row['idNumber'];
        }
    }

    // Create a DateTime object using the specified format
    $dateTime = DateTime::createFromFormat('m/d/Y g:i:s A', $date);
    $DateAndTime = $dateTime->format('Y-m-d H:i:s'); // Output in the desired format

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

// File Upload Code (moved outside the POST check)
$targetDir = "uploads/";

if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$targetFile = $targetDir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Check file size (adjust as needed)
if ($_FILES["file"]["size"] > 500000000) {
    echo "File size is too large.";
    $uploadOk = 0;
}

// Allow only certain file formats (you can adjust the types as needed)
if ($imageFileType == "") {
    $uploadOk = 1;
}else if (
    $imageFileType != "jpg" &&
    $imageFileType != "png" &&
    $imageFileType != "jpeg" &&
    $imageFileType != "gif" &&
    $imageFileType != "pdf"
) {
    echo "";
    $uploadOk = 0;
} 

// Handle form submissions
if (isset($_POST['approve'])) {
    $status = 'Approved';
} elseif (isset($_POST['reject'])) {
    $status = 'Rejected';
} else {
    $status = 'Pending';
}

$filepath = ""; // Define $filepath before using it

// Check if the reference number already exists
$checkReferenceQuery = 'SELECT COUNT(*) as count FROM submitted_requestorform WHERE reference = ?';
$stmtCheckReference = $conn->prepare($checkReferenceQuery);
$stmtCheckReference->bind_param('s', $reference);

if ($stmtCheckReference->execute()) {
    $resultCheckReference = $stmtCheckReference->get_result();
    $rowCheckReference = $resultCheckReference->fetch_assoc();

    if ($rowCheckReference['count'] > 0) {
        // Reference number already exists, handle accordingly
    } else if ($uploadOk == 0) {
        // File upload failed, handle accordingly
    } else {
        // Continue with the insert operation
        if ($_FILES["file"]["name"] != "" && move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                // File uploaded successfully, set the filepath
                $filepath = $targetFile;
            } elseif ($_FILES["file"]["name"] == "") {
                // File attachment is null, set filepath to an empty string or null as per your database schema
                $filepath = ""; // or null
            } else {
                echo "Error uploading file.";
                $uploadOk = 0;
            }

        if ($uploadOk == 1) {
                // Continue with the insert operation
                $sql = 'INSERT INTO submitted_requestorform (initiated_by_id, date_initiated_by, reference, approval, requestor_id, document_title, trip_purpose, manager_name, start_date, return_date, destination, total_days, advance_amounts, currency, hotel_booking, comments, accomodation_amount, meals_amount, transportation_amount, others_amount, total_amount, accommodation_remarks, meals_remarks, transportation_remarks, others_remarks, tfap_remarks, attachment_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $stmt = $conn->prepare($sql);

                if ($stmt) {
                // Assuming all parameters are strings ('s'). You can change the types as needed.
                $stmt->bind_param('ssissssssssiisssiiiiissssss', $idNumber, $DateAndTime, $reference, $status, $requestorId, $title, $purpose, $managerName, $startDate, $returnDate, $destination, $totalDays, $amount, $currency, $hotelBooking, $passComm, $accAmount, $meaAmount, $traAmount, $othAmount, $totAmount, $accRemark, $meaRemark, $traRemark, $othRemark, $totRemark, $filepath);

                if ($stmt->execute()) {
                    // File uploaded and data inserted successfully
                    $lastInsertId = $stmt->insert_id;

// Assuming $tripDataArray is an array of arrays
if (isset($_POST['tripData']) && is_array($_POST['tripData'])) {
    $tripDataArray = $_POST['tripData'];

    if ($tripDataArray) {
    $flightFrom = $tripDataArray['flightFrom'];
    $flightTo = $tripDataArray['flightTo'];
    $tripClass = $tripDataArray['tripClass'];
    $flightDate = $tripDataArray['flightDate'];
    $flightComment = $tripDataArray['flightComment'];


    foreach ($flightFrom as $index => $value) {
        // Access corresponding values from other arrays using the same index
        $currentFlightFrom = $flightFrom[$index];
        $currentFlightTo = $flightTo[$index];
        $currentTripClass = $tripClass[$index];
        $currentFlightDate = $flightDate[$index];
        $currentFlightComment = $flightComment[$index];

        if ($tripDataArray) {
    // Make sure flightFrom is not null
    if ($flightFrom !== null) {
        // Continue with the insert operation for trip data
        $tripSql = 'INSERT INTO trip_routing (submitted_id, trip_from, trip_to, trip_class, flight_date, comments) VALUES (?, ?, ?, ?, ?, ?)';
        $tripStmt = $conn->prepare($tripSql);

        if ($tripStmt) {
            $tripStmt->bind_param('isssss', $lastInsertId, $flightFrom[$index], $flightTo[$index], $tripClass[$index], $flightDate[$index], $flightComment[$index]);


            if (!$tripStmt->execute()) {
                echo 'Failed to execute trip query: ' . $tripStmt->error;
            }

            $tripStmt->close();
        } else {
            echo 'Failed to prepare trip statement: ' . $conn->error;
        }
    } else {
        echo 'Error: flightFrom is null';
    }
}}}}

// Continue with the insert operation for hotel
if (isset($_POST['hotelData']) && is_array($_POST['hotelData'])) {
$hotelDataArray = $_POST['hotelData'];

if ($hotelDataArray) {
    $hotelName = $hotelDataArray['hotelName'];
    $hotelAddress = $hotelDataArray['hotelAddress'];
    $hotelPhone = $hotelDataArray['hotelPhone'];
    $hotelRemark = $hotelDataArray['hotelRemark'];

    foreach ($hotelName as $index => $value) {
        $currentHotelName = $hotelName[$index];
        $currentHotelAddress = $hotelAddress[$index];
        $currentHotelPhone = $hotelPhone[$index];
        $currentHotelRemark = $hotelRemark[$index];

        if ($hotelDataArray) {
            if ($hotelName !== null) {
                $hotelSql = 'INSERT INTO hotel_information (submitted_id, hotel_name, hotel_address, hotel_phonenumber, remarks) VALUES (?, ?, ?, ?, ?)';
                $hotelStmt = $conn->prepare($hotelSql);

                if ($hotelStmt) {
                    $hotelStmt->bind_param('issss', $lastInsertId, $hotelName[$index], $hotelAddress[$index], $hotelPhone[$index], $hotelRemark[$index]);

                    if (!$hotelStmt->execute()) {
                echo 'Failed to execute trip query: ' . $hotelStmt->error;
            }
                }
            }
        }
    }


} else {
echo 'Failed to execute query: ' . $stmt->error;
}

$stmt->close();
}
}
} else {
echo 'Failed to execute query: ' . $stmtCheckReference->error;
}

header('Location: test.php?reference=' . urlencode($reference) . '&managerName=' . urlencode($managerName) . '&user_name=' . urlencode($userName) . '&requestor_id=' . urlencode($requestorId) . '&total_amount=' . urlencode($totAmount) . '&status=' . urlencode($status));

$stmtCheckReference->close();
}
}
}
}
}


// Fetch the manager_name of the logged-in user
$loggedInUserName = $_SESSION['user_name'];


// Fetch the manager_name based on the user_name
$managerNameQuery = 'SELECT manager_name FROM requestor_forms WHERE requestorName = ?';
$stmtManagerName = $conn->prepare($managerNameQuery);
$stmtManagerName->bind_param('s', $loggedInUserName);

if ($stmtManagerName->execute()) {
    $resultManagerName = $stmtManagerName->get_result();
    $rowManagerName = $resultManagerName->fetch_assoc();

    if ($rowManagerName) {
        // Use the manager name fetched from the database
        $loggedInManagerName = $rowManagerName['manager_name'];

        // Fetch all data for users with the same manager name and requestor name
        if (!empty($loggedInManagerName)) { // Check if manager name is not empty
            $fetchAllDataQuery = "SELECT s.*, r.requestorName 
                FROM submitted_requestorform s
                JOIN requestor_forms r ON s.requestor_id = r.idNumber
                WHERE s.manager_name = ? OR r.requestorName = ?";
            $stmtFetchData = $conn->prepare($fetchAllDataQuery);
            $stmtFetchData->bind_param('ss', $loggedInManagerName, $loggedInUserName);
        } else {
            // If manager_name is null, fetch all data for the requestor name
            $fetchAllDataQuery = "SELECT s.*, r.requestorName 
                FROM submitted_requestorform s
                JOIN requestor_forms r ON s.requestor_id = r.idNumber";
            $stmtFetchData = $conn->prepare($fetchAllDataQuery);
        }
    } else {
        // Handle the case where manager name is empty
        echo 'Manager name is empty for the logged-in user.';
        exit();
    }

    if ($stmtFetchData->execute()) {
        $resultFetchData = $stmtFetchData->get_result();
        $allSubmittedData = [];

        while ($row = $resultFetchData->fetch_assoc()) {
            // Fetch initiated name based on initiated_by_id
            $initiatedById = $row['initiated_by_id'];

            // Add a query to get the initiated name
            $initiatedNameQuery = 'SELECT requestorName FROM requestor_forms WHERE idNumber = ?';
            $stmtInitiatedName = $conn->prepare($initiatedNameQuery);
            $stmtInitiatedName->bind_param('s', $initiatedById);

            if ($stmtInitiatedName->execute()) {
                $resultInitiatedName = $stmtInitiatedName->get_result();
                $rowInitiatedName = $resultInitiatedName->fetch_assoc();

                if ($rowInitiatedName) {
                    $initiatedName = $rowInitiatedName['requestorName'];
                    // Add the initiated name to the $row array
                    $row['initiated_name'] = $initiatedName;
                }
            } else {
                echo 'Failed to execute query: ' . $stmtInitiatedName->error;
                exit();
            }

            $stmtInitiatedName->close();

            $allSubmittedData[] = $row;
        }
        $resultFetchData->close();
    } else {
        echo 'Failed to execute query: ' . $stmtFetchData->error;
        exit();
    }
} else {
    echo 'Failed to execute query: ' . $stmtManagerName->error;
    exit();
}

if (isset($_SESSION['password_updated']) && $_SESSION['password_updated'] == true) {
    echo '<script>alert("Password updated successfully!");</script>';
    // Clear the session variable to avoid showing the alert on page refresh
    unset($_SESSION['password_updated']);
}

if (isset($_SESSION['add_employee']) && $_SESSION['add_employee'] === true) {
    echo '<script>alert("New record created successfully");</script>';
    // Reset the session variable to avoid showing the alert multiple times
    $_SESSION['add_employee'] = false;
}

function getFinalStatusText($row)
{
    if (!is_null($row['validateSls']) && !is_null($row['validateFin']) && !is_null($row['validateKas'])) {
        return 'Finalize';
    } else {
        return '';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</head>

<body>
    <header>
        <nav class="navbar bg-dark border-bottom border-body" data-bs-theme="dark">
            <div class="container-fluid">
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav text-black">
                      <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="request.php">Request</a>

                      <li class="nav-item">
                        <a class="nav-link" href="history.php">Show History</a>
                      </li>
                      <li class="nav-item">
                        <?php if ($userName === 'Anindhita Prameswari') : ?>
                        <a class="nav-link" aria-current="page" href="add_employee.php">Add Employee</a>
                        <?php endif; ?>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="change_password.php">Change Password</a>
                      </li>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="index.php" id="logoutLink">Log Out</a>
                    </li>
                </ul>
              </div>
            </div>
        </nav>
    </header>
    <main>
        <div class="container">
          <div id="gambar-container"></div>
            <h2>Home</h2>
            <br>
            <h3 id="welcomeMessage">Welcome, <?php echo $userName; ?></h3>
            <br>
            <div class="search-container">
                <label for="searchInput">Search:</label>
                <input type="text" id="searchInput" oninput="performSearch()">
            </div>
            <div>
                <div id="container" class="scrollable-container">
                    <table id="tableDisplay" class="table table-bordered table-width">
                        <thead class="table-dark">
                            <tr class="text-nowrap">
                                <th style="text-align: center;"><input type="checkbox" id="selectAllCheckbox"> Select All</th>
                                <th style="text-align: center;" class="initiated">Initiated By</th>
                                <th style="text-align: center;" class="approval">Status</th>
                                <th style="text-align: center;" class="dateInitiated">Date Initiated</th>
                                <th style="text-align: center;" class="title">Document Title</th>
                                <th style="text-align: center;" class="requestor">Requestor</th>
                                <th style="text-align: center;" class="manager">Manager Name</th>
                                <th style="text-align: center;" class="time">Time Approved / Declined</th>
                                <th style="text-align: center;">Detail</th>
                                <th style="text-align: center;">Estimated Cost</th>
                                <th style="text-align: center;">Purchase Form</th>
                                <th style="text-align: center;">Closing</th>
                                <th style="text-align: center;">Validate Admin Sales</th>
                                <th style="text-align: center;">Validate Finance</th>
                                <th style="text-align: center;">Validate Kasir</th>
                                <th style="text-align: center;">Final Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allSubmittedData as $row) : ?>
                            <?php if ((is_null($row['validateSls']) || is_null($row['validateFin']) || is_null($row['validateKas'])) && !($row['approval'] === 'Rejected') || ($row['approval'] === 'Pending')) : ?>
                                <tr class="text-nowrap">
                                <td class="checkbox-td"><input type="checkbox" data-reference="<?php echo $row['reference']; ?>" /></td>
                                <td><?php echo $row['initiated_name']; ?></td>
                                <td class="approval" data-status="<?php echo $row['approval']; ?>"><?php echo $row['approval'] ?? ''; ?></td>
                                <td><?php echo $row['date_initiated_by'] ?? ''; ?></td>
                                <td><?php echo $row['document_title'] ?? ''; ?></td>
                                <td><?php echo $row['requestorName']; ?></td>
                                <td><?php echo $row['manager_name']; ?></td>
                                <td class="time"><?php echo $row['status_date_time'] ?? ''; ?></td>
                                <td><button type="button" class="btn btn-sm btn-primary detail-button" onclick="showDetail('<?php echo $row['id']; ?>')">Detail</button></td>
                                    <td class="<?php echo (($userName === 'Lisna Suradi' || $userName === 'Ludi Krisnanda' || $userName === 'Wiwiet Widya Ningrum' || $userName === 'Darwati' || $userName === 'Rian Andrian' || $userName === $row['requestorName'] || $userName === $row['manager_name']) && $row['approval'] === 'Approved') ? 'show-cell' : 'hide-cell'; ?>">
                                        <?php if (($userName === 'Lisna Suradi' || $userName === 'Ludi Krisnanda' || $userName === 'Wiwiet Widya Ningrum' || $userName === 'Darwati' || $userName === 'Rian Andrian' || $userName === $row['requestorName'] || $userName === $row['manager_name']) && $row['approval'] === 'Approved') : ?>
                                            <button type="button" class="btn btn-sm btn-primary internal-memo-button" onclick="redirectToInternalMemo('<?php echo $row['reference']; ?>')">Upload Internal Memo</button>
                                        <?php else: ?>
                                            <td colspan="1"></td>
                                        <?php endif; ?>
                                    </td>
                                    <td class="<?php echo (($userName === 'Lisna Suradi' || $userName === 'Ludi Krisnanda' || $userName === 'Wiwiet Widya Ningrum' || $userName === 'Adimas Ali Rizaqi' || $userName === 'Dwi Agustina' || $userName === 'Iyanju Manurung' || $userName === 'Rian Andrian' || $userName === $row['requestorName'] || $userName === $row['manager_name']) && $row['approval'] === 'Approved') ? 'show-cell' : 'hide-cell'; ?>">
                                        <?php if (($userName === 'Lisna Suradi' || $userName === 'Ludi Krisnanda' || $userName === 'Wiwiet Widya Ningrum' || $userName === 'Adimas Ali Rizaqi' || $userName === 'Dwi Agustina' || $userName === 'Iyanju Manurung' || $userName === 'Rian Andrian' || $userName === $row['requestorName'] || $userName === $row['manager_name']) && $row['approval'] === 'Approved') : ?>
                                            <button type="button" class="btn btn-sm btn-primary form-pr-button" onclick="redirectToFormPR('<?php echo $row['reference']; ?>')">Upload Form PR</button>
                                        <?php else: ?>
                                            <td colspan="1"></td>
                                        <?php endif; ?>
                                    </td>                            
                                    <td class="<?php echo (($userName === 'Lisna Suradi' || $userName === 'Ludi Krisnanda' || $userName === 'Wiwiet Widya Ningrum' || $userName === 'Darwati' || $userName === 'Rian Andrian' || $userName === $row['requestorName'] || $userName === $row['manager_name']) && $row['approval'] === 'Approved') ? 'show-cell' : 'hide-cell'; ?>">
                                        <?php if (($userName === 'Lisna Suradi' || $userName === 'Ludi Krisnanda' || $userName === 'Wiwiet Widya Ningrum' || $userName === 'Darwati' || $userName === 'Rian Andrian' || $userName === $row['requestorName'] || $userName === $row['manager_name']) && $row['approval'] === 'Approved') : ?>
                                            <button type="button" class="btn btn-sm btn-primary closing-button" onclick="redirectToClosing('<?php echo $row['reference']; ?>')">Closing</button>
                                        <?php else: ?>
                                            <td colspan="1"></td>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if ($row['closing'] === null): ?>
                                            <?php else: ?>
                                                <?php if ($row['validateSls'] === null): ?>
                                                    <?php if ($userName === 'Wiwiet Widya Ningrum') : ?>
                                                    <?php
                                                    $elementId = 'penyerahanDateInput' . $row['reference'];
                                                    $onclick = "updateTerima('$elementId', '{$row['reference']}')";
                                                    ?>
                                                    <input type="button" class="validate-button" value="Validate" onclick="<?php echo $onclick; ?>">
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                <?php echo date('d/m/Y', strtotime($row['validateSls'])); ?>
                                                <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if ($row['validateSls'] === null): ?>
                                            <?php else: ?>
                                                <?php if ($row['validateFin'] === null): ?>
                                                    <?php if ($userName === 'Lisna Suradi' || $userName === 'Ludi Krisnanda') : ?>
                                                    <?php
                                                    $elementId = 'penyerahanDateInput' . $row['reference'];
                                                    $onclick = "updateTerima1('$elementId', '{$row['reference']}')";
                                                    ?>
                                                    <input type="button" class="validate-button" value="Validate" onclick="<?php echo $onclick; ?>">
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                <?php echo date('d/m/Y', strtotime($row['validateFin'])); ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if ($row['validateFin'] === null): ?>
                                            <?php else: ?>
                                                <?php if ($row['validateKas'] === null): ?>
                                                    <?php if ($userName === 'Darwati') : ?>
                                                    <?php
                                                    $elementId = 'penyerahanDateInput' . $row['reference'];
                                                    $onclick = "updateTerima2('$elementId', '{$row['reference']}')";
                                                    ?>
                                                    <input type="button" class="validate-button" value="Validate" onclick="<?php echo $onclick; ?>">
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                <?php echo date('d/m/Y', strtotime($row['validateKas'])); ?>
                                                <?php endif; ?>
                                        <?php endif; ?>
                                    </td>                    
                                    <td class="<?php echo (!is_null($row['validateSls']) && !is_null($row['validateFin']) && !is_null($row['validateKas'])) ? 'finalize-row' : 'non-finalize-row'; ?>"><?php echo getFinalStatusText($row); ?></td>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                        </tbody>
                    </table>
                </div>
            
                <div class="button-container">
                    <button type="button" id="approveButton" onclick="updateStatus('Approved')" data-status="Approved" class="accept-button">Approve</button>
                    <button type="button" id="declineButton" onclick="updateStatus('Rejected')" data-status="Rejected" class="reject-button">Reject</button>
                </div>
            </div>
        </div>
    </main>

    <div class="fixed-button-container">
        <a href="request.php" class="fixed-button">+ Request</a>
    </div>
    
    <script>
  document.addEventListener('DOMContentLoaded', function () {
        const welcomeMessage = document.getElementById('welcomeMessage');
        const rtlStyle = { direction: 'rtl', unicodeBidi: 'bidi-override', textAlign: 'left' };
        const ltrStyle = { direction: 'ltr', unicodeBidi: 'normal', textAlign: 'right' };
        const easterEggFlag = 'easterEggTriggered';

        // Check if the easter egg has been triggered before
        const isEasterEggTriggered = localStorage.getItem(easterEggFlag);

        if (!isEasterEggTriggered) {
            // Generate a random number (either 0 or 1)
            const randomNumber = Math.floor(Math.random() * 100);

            // Apply RTL style if random number is 1
            if (randomNumber === 1) {
                Object.assign(welcomeMessage.style, rtlStyle);
                // Set the easter egg flag in local storage
                localStorage.setItem(easterEggFlag, 'true');
            }
        } else {
            // Reset the easter egg flag if it's already triggered
            localStorage.removeItem(easterEggFlag);
        }
    });

     // Function to perform search
    function performSearch() {
        const searchInput = document.getElementById('searchInput');
        const rows = document.querySelectorAll('#tableDisplay tbody tr');

        rows.forEach(row => {
            let isMatch = false;

            row.querySelectorAll('td').forEach(cell => {
                const cellText = cell.textContent.toLowerCase();
                const searchTerm = searchInput.value.toLowerCase();

                if (cellText.includes(searchTerm)) {
                    isMatch = true;
                }
            });

            row.style.display = isMatch ? 'table-row' : 'none';
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const approvedUsernames = [
            'Rian Andrian', 'Robby Ardyan', 'Santono',
            'Heriyanto', 'Anindhita Prameswari',
            'Suwarno', 'Cecep Iman', 'Hendrawanto', 'Adinda Yuliawati'
        ];

        const userName = '<?php echo $userName; ?>';
        const approveButton = document.querySelector('button[data-status="Approved"]');
        const rejectButton = document.querySelector('button[data-status="Rejected"]');
        const searchContainer = document.querySelector('.search-container');

        // Check if user is in the approved list
        if (approvedUsernames.includes(userName)) {
            if (approveButton && approveButton.style) {
                approveButton.style.display = 'block';
            }

            if (rejectButton && rejectButton.style) {
                rejectButton.style.display = 'block';
            }

            if (searchContainer && searchContainer.style) {
                searchContainer.style.display = 'block';
            }
        } else {
            if (approveButton && approveButton.style) {
                approveButton.style.display = 'none';
            }

            if (rejectButton && rejectButton.style) {
                rejectButton.style.display = 'none';
            }

            if (searchContainer && searchContainer.style) {
                searchContainer.style.display = 'none';
            }
        }

        // Attach the performSearch function to the input event
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', performSearch);
        }
    });
    
        function logout() {
            const userConfirmed = window.confirm('Apakah Anda yakin ingin logout?');
            if (userConfirmed) {
                window.location.href = 'index.php';
            }
        }

        const logoutLink = document.getElementById('logoutLink');
        if (logoutLink) {
            logoutLink.addEventListener('click', function(event) {
                event.preventDefault();
                logout();
            });
        }

function updateTerima(elementId, reference) {
    var currentDate = new Date().toISOString().split('T')[0];
    var element = document.getElementById(elementId);

    // Check if the element exists before setting the value
    if (element) {
        element.value = currentDate;
    }

    // Send an AJAX request to update the date in the database
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'updateTerima.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText);
            location.reload(); // You may or may not want to reload here
        }
    };
    xhr.send('reference=' + reference + '&newDate=' + currentDate);
}
    
function updateTerima1(elementId, reference) {
    var currentDate = new Date().toISOString().split('T')[0];
    var element = document.getElementById(elementId);

    // Check if the element exists before setting the value
    if (element) {
        element.value = currentDate;
    }

    // Send an AJAX request to update the date in the database
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'updateTerima1.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText);
            location.reload(); // You may or may not want to reload here
        }
    };
    xhr.send('reference=' + reference + '&newDate=' + currentDate);
}

function updateTerima2(elementId, reference) {
    var currentDate = new Date().toISOString().split('T')[0];
    var element = document.getElementById(elementId);

    // Check if the element exists before setting the value
    if (element) {
        element.value = currentDate;
    }

    // Send an AJAX request to update the date in the database
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'updateTerima2.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText);
            location.reload(); // You may or may not want to reload here
        }
    };
    xhr.send('reference=' + reference + '&newDate=' + currentDate);
}


function redirectToInternalMemo(reference, validateSls) {
    $.ajax({
        url: 'get_requestor_name.php', // Create a PHP file to handle the request and return requestorName
        type: 'POST',
        data: { reference: reference },
        success: function(requestorName) {
            window.location.href = 'internalMemo.php?reference=' + reference + '&validateSls=' + validateSls + '&requestorName=' + requestorName;
        },
        error: function(xhr, status, error) {
            console.error('Error fetching requestorName:', error);
            // Handle the error as needed
        }
    });
}

function redirectToClosing(reference, validateSls) {
    $.ajax({
        url: 'get_requestor_name.php', // Create a PHP file to handle the request and return requestorName
        type: 'POST',
        data: { reference: reference },
        success: function(requestorName) {
            window.location.href = 'closing.php?reference=' + reference + '&validateSls=' + validateSls + '&requestorName=' + requestorName;
        },
        error: function(xhr, status, error) {
            console.error('Error fetching requestorName:', error);
            // Handle the error as needed
        }
    });
}

function redirectToFormPR(reference, validateSls) {
    $.ajax({
        url: 'get_requestor_name.php', // Create a PHP file to handle the request and return requestorName
        type: 'POST',
        data: { reference: reference },
        success: function(requestorName) {
            window.location.href = 'formPr.php?reference=' + reference + '&validateSls=' + validateSls + '&requestorName=' + requestorName;
        },
        error: function(xhr, status, error) {
            console.error('Error fetching requestorName:', error);
            // Handle the error as needed
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Get the company from session (using PHP to pass the value to JavaScript)
    const sessionCompany = "<?php echo isset($_SESSION['company']) ? $_SESSION['company'] : ''; ?>";

    // Create the image element
    const img = document.createElement('img');

    // Determine the image source based on the company from session
    if (sessionCompany === 'Medika' || sessionCompany === 'Promed') {
        img.src = 'logo-imi medika.png';
        img.classList.add('medika-logo'); // Add a class if the company is Medika
    } else if (sessionCompany === 'Iss') {
        img.src = 'logo-ISS.png';
    } else {
    }

    // Add the image to the container
    const container = document.getElementById('gambar-container');
    container.appendChild(img);

    let isAfk = false; // Assume user is AFK initially
    let rotationTimer; // Variable to store timer reference

    // Function to start rotation after 1 minute of inactivity
    function startRotation() {
        const container = document.getElementById('gambar-container');
        if (container && isAfk) {
            container.classList.add('rotate-image'); // Apply rotation animation if AFK
        }
    }

    // Function to reset rotation after user activity
    function resetRotation() {
        isAfk = false; // User is active
        clearTimeout(rotationTimer); // Clear previous timer
        const container = document.getElementById('gambar-container');
        if (container) {
            container.classList.remove('rotate-image'); // Remove rotation animation
        }
        rotationTimer = setTimeout(() => {
            isAfk = true; // Set back to AFK after 1 minute of inactivity
            startRotation(); // Start rotation if still AFK after timeout
        }, 60000); // 1 minute in milliseconds
    }

    // Start the timer initially
    rotationTimer = setTimeout(() => {
        startRotation(); // Start rotation after 1 minute if still AFK
    }, 60000); // 1 minute in milliseconds

    // Event listeners for user activity
    document.addEventListener('mousemove', resetRotation);
    document.addEventListener('keypress', resetRotation);
    document.addEventListener('scroll', resetRotation);

    // Start rotation after DOMContentLoaded
    startRotation();
});

        // Add the following script for handling the "Select All" checkbox
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const checkboxes = document.querySelectorAll('#tableDisplay tbody input[type="checkbox"]');

            // Add an event listener to the "Select All" checkbox
            selectAllCheckbox.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });

            // Add event listeners to individual checkboxes to update "Select All" checkbox
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    selectAllCheckbox.checked = checkboxes.length === document.querySelectorAll(
                        '#tableDisplay tbody input[type="checkbox"]:checked').length;
                });
            });
        });

        function showDetail(reference) {
            window.location.href = `view_requests.php?ref=${reference}`
        }

// Move the updateStatus function declaration to the top
function updateStatus(status) {
    const references = getSelectedReferences();

    if (references.length > 0) {
        // Check the current status of the selected requests
        const currentStatuses = getCurrentStatuses(references);

        // Check if any request has a status other than 'Pending'
        if (currentStatuses.some(s => s !== 'Pending')) {
            alert('Selected request already have a final status and cannot be changed.');
            return;
        }

        // Send an AJAX request to update the status, date, and time in the database
        $.ajax({
            type: 'POST',
            url: 'update_status.php',
            data: {
                references: references,
                status: status,
            },
            success: function(response) {
                // Update the UI
                updateUI(references, status);
                location.reload();
            },
            error: function() {
                alert('Error updating status.');
            }
        });
    } else {
        alert('Please select at least one request.');
    }
}

function getCurrentStatuses(references) {
    const statuses = [];

    references.forEach(reference => {
        const checkbox = document.querySelector(`#tableDisplay tbody input[data-reference="${reference}"]`);
        if (checkbox) {
            const row = checkbox.closest('tr');
            if (row) {
                const approvalCell = row.querySelector('.approval');
                if (approvalCell) {
                    statuses.push(approvalCell.getAttribute('data-status') || '');
                }
            }
        }
    });

    return statuses;
}

        function getSelectedReferences() {
            const checkboxes = document.querySelectorAll('#tableDisplay tbody input[type="checkbox"]:checked');
            const references = [];

            checkboxes.forEach(checkbox => {
                const reference = checkbox.getAttribute('data-reference');
                references.push(reference);
            });

            return references;
        }

        function updateUI(references, status) {
            // Update the status column in the table for selected references
            references.forEach(reference => {
                const checkbox = document.querySelector(`#tableDisplay tbody input[data-reference="${reference}"]`);
                if (checkbox) {
                    const row = checkbox.closest('tr');
                    if (row) {
                        const approvalCell = row.querySelector('.approval');
                        if (approvalCell) {
                            approvalCell.textContent = status;
                            approvalCell.setAttribute('data-status', status);
                        }
                    }
                }
            });
        }

document.addEventListener('DOMContentLoaded', function () {
    // Get all rows in the table body
    var rows = document.querySelectorAll('#tableDisplay tbody tr');

    // Iterate over each row
    rows.forEach(function (row) {
        // Get the status element in the row
        var statusElement = row.querySelector('.approval');

        // Check if the status element is found
        if (statusElement) {
            // Get the status and buttons in the row
            var status = statusElement.getAttribute('data-status');
            var formPRButton = row.querySelector('.form-pr-button');
            var internalMemoButton = row.querySelector('.internal-memo-button');
            var closingButton = row.querySelector('.closing-button');

            // Check if the buttons are found before modifying their styles
            if (formPRButton && internalMemoButton && closingButton) {
                // Check the status and set button visibility
                if (status === 'Approved') {
                    formPRButton.style.display = 'inline-block'; // Show the "Form PR" button
                    internalMemoButton.style.display = 'inline-block'; // Show the "Internal Memo" button
                    closingButton.style.display = 'inline-block'; // Show the "Closing" button
                } else {
                    formPRButton.style.display = 'none'; // Hide the "Form PR" button
                    internalMemoButton.style.display = 'none'; // Hide the "Internal Memo" button
                    closingButton.style.display = 'none'; // Hide the "Closing" button
                }
            } else {
                console.error('One or more buttons not found in the row:', row);
            }
        } else {
            console.error('Status element not found in the row:', row);
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Get all rows in the table body
    var rows = document.querySelectorAll('#tableDisplay tbody tr');

    // Iterate over each row
    rows.forEach(function (row) {
        // Your existing code to handle rows

        // After appending the image to the container
        const container = document.getElementById('gambar-container');
        container.appendChild(img);

        // Add the rotate-image class to the image element
        img.classList.add('rotate-image');
    });
});

    </script>

    <style>

@keyframes rotate360 {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Apply rotation animation to the image */
.rotate-image {
    animation: rotate360 10s linear infinite; /* Change duration and timing function as needed */
}

/* Stop rotation on hover */
.rotate-image:hover {
    animation-play-state: paused;
}

.medika-logo {
    max-width: 200px;
    width: 70%;
    margin-left: 10px;
    padding: 10px
}
        img {
            max-width: 300px;
            width: 90%;
            margin-left: 10px;
        }

        body {
            background-color: aliceblue;
        }

        table {
            background-color: white;
        }

        .button-container {
    display: flex;
    margin-top: 5px; /* Adjust margin as needed */
}

.export-button-container {
  top: 170px; /* Adjust the top position as needed */
  right: 170px; /* Adjust the right position as needed */
  z-index: 1; /* Set a lower z-index to position it behind other elements */
}

.button {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 10px 20px;
  font-size: 16px;
  text-align: center;
  text-decoration: none;
  cursor: pointer;
  border: 1px solid #3498db;
  color: #ffffff;
  background-color: #3498db;
  border-radius: 5px;
  transition: background-color 0.3s;
  margin-left: auto; /* Align to the right */
  position: absolute;
  z-index: 2; /* Set a higher z-index to position it above other elements */
  right: 20px;
}


/* Change the background color on hover */
.button:hover {
  background-color: #2980b9;
}

.fixed-button-container {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 999; /* Set a high z-index to ensure it's above other elements */
}

/* Add styles for the fixed button */
.fixed-button {
  background-color: #007bff; /* Blue background color */
  color: white; /* White text color */
  padding: 10px 20px; /* Add some padding */
  border: none; /* Remove border */
  border-radius: 5px; /* Add border radius */
  cursor: pointer; /* Add cursor on hover */
  font-size: 16px; /* Set font size */
  text-decoration: none; /* Remove underline for links */
}

.fixed-button:hover {
  background-color: #0056b3; /* Darker blue on hover */
}

.detail-button:hover,
.internal-memo-button:hover,
.form-pr-button:hover,
.closing-button:hover {
  background-color: rgb(142, 219, 197); /* Darker green on hover */
}

.scrollable-container {
    max-height: 400px; /* Adjust the height as needed */
    overflow-y: auto; /* Enable vertical scrolling */
    padding: 10px; /* Add padding to improve the appearance */
    background-color: white; /* Add background color for better visibility */
    border: 1px solid #ddd; /* Add border for better visualization */
}

.finalize-row {
    background-color: rgb(0, 215, 0);
}

.non-finalize-row {
    background-color: rgb(255, 225, 119);
}

.validate-button {
        padding: 5px 10px;
        background-color: #4CAF50; /* Green */
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .validate-button:hover {
        background-color: #45a049;
    }

.accept-button {
    background-color: #66be69; /* Green background color */
    color: white; /* White text color */
    padding: 10px 20px; /* Add some padding */
    border: none; /* Remove border */
    border-radius: 5px; /* Add border radius */
    cursor: pointer; /* Add cursor on hover */
    margin: 5px; /* Add some margin */
    font-size: 16px; /* Set font size */
}

.checkbox-td {
    text-align: center; /* Center the content horizontally */
    vertical-align: middle; /* Center the content vertically */
}

.checkbox-td input {
    margin: 0; /* Remove any default margin */
}

.hide-cell {
        display: none;
    }

    .show-cell {
        display: table-cell;
    }

.accept-button:hover {
    background-color: #45a049; /* Darker green on hover */
}

.search-container {
    margin-bottom: 10px;
}

.search-container input[type="text"]{
    width: 250px;
}

.table-width {
    width: 100%; /* You can adjust the percentage or use other units like pixels */
    margin: 0 auto; /* Center the table on the page, if desired */
}


 /* Add styles for the "reject-button" class */
.reject-button {
    background-color: #ff3333; /* Red background color */
    color: white; /* White text color */
    padding: 10px 20px; /* Add some padding */
    border: none; /* Remove border */
    border-radius: 5px; /* Add border radius */
    cursor: pointer; /* Add cursor on hover */
    margin: 5px; /* Add some margin */
    font-size: 16px; /* Set font size */
}

.approval[data-status="Approved"] {
            background-color: #33e560; /* Light green for Approved */
        }

        .approval[data-status="Rejected"] {
            background-color: #e82b2b; /* Light red for Rejected */
        }

        .approval[data-status="Pending"] {
            background-color: #f6f65d; /* Light yellow for Pending */
        }

.reject-button:hover {
    background-color: #e60000; /* Darker red on hover */
}

    </style>

</body>

</html>
