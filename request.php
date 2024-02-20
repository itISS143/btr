<?php
session_start();
// Replace these values with your actual database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "btr";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_name'])) {
    // Redirect to the index page if not logged in
    header("Location: index.php");
    exit();
}
$userName = $_SESSION['user_name'];
$idNumber = $_SESSION['id_number'];
$company = isset($_SESSION['company']) ? $_SESSION['company'] : '';

// Function to get the latest reference number
function getLatestReferenceNumber($conn) {
    $latestReferenceQuery = "SELECT MAX(reference) AS maxReference FROM submitted_requestorform";
    $result = $conn->query($latestReferenceQuery);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['maxReference'];
    } else {
        return 0; // If no reference number found, start from 0 or change as needed
    }
}

// Get the latest reference number
$latestReference = getLatestReferenceNumber($conn);

// Increment the reference number for the new submission
$newReference = $latestReference + 1;

// Assuming the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Continue with the insert operation
    $DateAndTime = date('Y-m-d H:i:s'); // Assuming you want to use the current date and time
    $status = "Ongoing"; // You need to define the appropriate status
    $requestorId = $_POST['requestor']; // Assuming the requestor is selected from the form
    $title = $_POST['title'];
    $managerName = $_POST['manager'];

    // Move the rest of the variables initialization here...

    // Fetch the last reference number from the database
    $getLastReferenceQuery = "SELECT reference FROM submitted_requestorform ORDER BY id DESC LIMIT 1";
    $stmtGetLastReference = $conn->prepare($getLastReferenceQuery);

    if ($stmtGetLastReference->execute()) {
        $resultLastReference = $stmtGetLastReference->get_result();
        $rowLastReference = $resultLastReference->fetch_assoc();

        if ($rowLastReference) {
            // Increment the last reference number
            $lastReference = $rowLastReference['reference'];
            $newReference = intval($lastReference) + 1;
        } else {
            // Set a default reference number if no records are found
            $newReference = 1;
        }
    } else {
        echo "Failed to execute query: " . $stmtGetLastReference->error;
    }

    $stmtGetLastReference->close();

    // Continue with the insert operation
    $sql = "INSERT INTO submitted_requestorform (initiated_by_id, date_initiated_by, reference, approval, requestor_id, document_title, manager_name, start_date, return_date, destination, total_days, advance_amounts, currency, hotel_booking, comments, flight_from, flight_to, trip_class, flight_date, flight_comments, accomodation_amount, meals_amount, transportation_amount, others_amount, accommodation_remarks, meals_remarks, transportation_remarks, others_remarks, tfap_remarks, hotel_name, hotel_address, hotel_phonenumber, hotel_remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Assuming all parameters are strings ('s'). You can change the types as needed.
        $stmt->bind_param("ssisssssssiissssssssiiiisssssssss", $idNumber, $DateAndTime, $newReference, $status, $requestorId, $title, $managerName, $startDate, $returnDate, $destination, $totalDays, $amount, $currency, $hotelBooking, $passComm, $flightFrom, $flightTo, $tripClass, $flightDate, $flightComment, $accAmount, $meaAmount, $traAmount, $othAmount, $accRemark, $meaRemark, $traRemark, $othRemark, $totRemark, $hotelName, $hotelAddress, $hotelPhone, $hotelRemark);

        if ($stmt->execute()) {
            // Handle success if needed
        } else {
            echo "Failed to execute query: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request</title>
    <link rel="stylesheet" href="request1.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.3/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js"></script>
</head>
<body>
    <a id="goBackButton" href="#" onclick="goBack()" style="text-decoration:none;">Back</a>

    <div class="container" id="pdf-content">
    <div id="gambar-container"></div>
    <td><input type="text" name="company" id="company" readonly></td>
    <h2>Business Travel Request</h2>
    <form id="formRequest" action="home.php" enctype="multipart/form-data" method="POST">
        <table class="request" name="tableRequest" id="tableRequest" border="1">
            <input type="hidden" id="idNumber" name="idNumber" value="<?php echo $idNumber; ?>">
            <tbody>
                <tr>
                    <th class="reference">Reference</th>
                    <td><input name="referenceNumber" value="<?php echo $newReference?>" readonly></td>
                    <th class="initiated">Initiated By</th>
                    <td id="initiatedName"><?php echo $userName; ?></td>
                </tr>
            <tr>
                <th class="approval">Status</th>
                <td><input id="idStatus" name="status" value="Ongoing" readonly></td>
                <th class="dateInitiated">Date Initiated</th>
                <td><input type="text" id="dateId" name="Date" readonly></td>
            </tr>
            <tr>
                <th class="title">Document Title</th>
                <td><input type="text" id="titleId" name="title" value="" placeholder="Input Document Title" required></td>
            </tr>
            <tr>
                <th class="purpose">Trip Purpose</th>
                <td><input type="text" id="purposeId" name="purpose" value="" placeholder="Input Trip Purpose" required></td>
            </tr>
            <tr>
                <th class="requestor">Requestor</th>
                <td>
                    <select class="select-style" name="requestor" id="requestor" onchange="detail()" required>
                        <option value="">Choose Requestor</option>
                        <?php
                        include "ambil_data.php";
                        $query = mysqli_query($Open, "SELECT * FROM requestor_form ORDER BY requestorName");
                        while ($data = mysqli_fetch_array($query)) {
                        ?>
                        <option value="<?php echo $data['requestorName']; ?>"><?php echo $data['requestorName']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </td>
                <th class="email">Email Address</th>
                <td><input type="text" name="Email" id="Email" readonly></td>
            </tr>
            <tr>
                <th class="division">Division</th>
                <td><input type="text" name="division" id="division" readonly></td>
                <th class="departement">Departement</th>
                <td><input type="text" name="Departement" id="Departement" readonly></td>
            </tr>
            <tr>
                <th class="idCard">NIK KTP</th>
                <td><input type="text" name="idCard" id="idCard" readonly></td>
                <th class="phoneNumber">Phone Number</th>
                <td><input type="text" name="phoneNumber" id="phoneNumber" readonly></td>
            </tr>
            <tr>
                <th class="managerName">Manager Name</th>
                <td><select class="select-style" aria-label="State" id="manDropdown" name="manager" required>
                    <option value="">Select Manager Name</option>
                    <option value="Adinda Yuliawati">Adinda Yuliawati</option>
                    <option value="Anindhita Prameswari">Anindhita Prameswari</option>
                    <option value="Cecep Iman">Cecep Iman</option>
                    <option value="Hendrawanto">Hendrawanto</option>
                    <option value="Heriyanto">Heriyanto</option>
                    <option value="Rian Andrian">Rian Andrian</option>
                    <option value="Robby Ardyan">Robby Ardyan</option>
                    <option value="Santono">Santono</option>
                    <option value="Suwarno">Suwarno</option>
                 </select>
                </td>
            </tr>
            <tr>
                <th>Start Date</th>
                <td><input type="date" id="startDate" name="startDate" required></td>
                <th>Return Date</th>
                <td><input type="date" id="returnDate" name="returnDate" required></td>
            </tr>
            <tr>
                <th class="destination">Destination</th>
                <td><select class="select-style" aria-label="State" id="desDropdown" name="destination" required>
                    <option value="">Choose Your Destination</option>
                    <option value="International">International</option>
                    <option value="Local">Local</option>
                 </select>
                </td>
                <th>Total Days</th>
                <td><input type="text" id="totalDays" name="totalDays" readonly></td>
            </tr>
            <tr>
                <th class="amount">Advance Amount</th>
                <td><input type="number" id="totalAmount" name="amount" value="0" readonly></td>
                <th class="currency">Currency</th>
                <td><select class="select-style" name="currency" id="currency" onchange="change()" required>
                    <option value="">Choose Currency</option>
                    <?php
                    include "ambil_data.php";
                    
                    $query = mysqli_query($Open, "SELECT * FROM currency");
                    while($data = mysqli_fetch_array($query)){
                    ?>
                    <option value="<?php echo $data['Currency']; ?>"><?php echo $data['Currency']; ?></option>
                    <?php
                    }
                    ?>
                </select>
                </td>
            </tr>
    </tbody>
</table>
<br>

<h2>PASSENGER</h2>
<div>
    <table class="passenger" id="tablePassenger" border="1">
    <thead>
        <th class="namaPassenger">Full Name</th>
        <th class="type">Division</th>
        <th class="gender">Gender</th>
        <th class="hotel">Hotel Booking</th>
        <th class="comment1">Comment</th>
    </thead>
    <tbody>
        <tr>
        <td><input type="text" name="Requestor" id="Requestor"></td>
        <td><input type="text" name="Division" id="Division"></td>
        <td><input type="text" name="gender" id="gender"></td>
        <td>
            <div class="radio-container">
                <input type="radio" id="hotelYes" name="hotel" value="YES" class="custom-radio" onclick="toggleHotelField(true)">
                <label for="hotelYes" class="radio-label">Yes</label>
                <input type="radio" id="hotelNo" name="hotel" value="NO" class="custom-radio" onclick="toggleHotelField(false)">
                <label for="hotelNo" class="radio-label">No</label>
            </div>            
        </td>
        <td><input name="passengerComment" type="text" id="comment1Id" placeholder="Input Your Comments"></td>
    </tr>
    </tbody>
</table>
<br>
</div>

<h2>Trip Routing</h2>

<table class="routing" id="tableRouting" border="1">
    <thead>
        <th class="from">From</th>
        <th class="to">To</th>
        <th class="class">Trip Class</th>
        <th class="flightDate">Flight Date</th>
        <th class="comment2">Comment</th>
    </thead>
    <tbody>
        <tr class="tripRoutingRow">
            <td><input name="tripData[flightFrom][]" type="text" placeholder="Input From Where" required></td>
            <td><input name="tripData[flightTo][]" type="text" placeholder="Input To Where" required></td>
            <td>
                <select class="select-style" name="tripData[tripClass][]" required>
                    <option value="">Choose Trip</option>
                    <option value="Train - Economy">Train - Economy</option>
                    <option value="Airplane - Economy">Airplane - Economy</option>
                    <option value="Private Transportation">Private Transportation</option>
                    <option value="Public Transportation (Bus)">Public Transportation (Bus)</option>
                </select>
            </td>
            <td><input name="tripData[flightDate][]" type="date" placeholder="Input Flight Date" oninput="validateFlightDate(this)" required></td>
            <td><input name="tripData[flightComment][]" type="text" placeholder="Input Your Comments"></td>
        </tr>
    </tbody>
</table>
<br>
<div>
    <button type="button" onclick="tambahRou()"> + Trip Routing</button>
</div>
<br>

<h2>Estimated Cost</h2>

<table class="cost" id="tableCost" border="1">
    <thead>
        <th class="category">Category</th>
        <th class="amount">Amount</th>
        <th class="currency">Currency</th>
        <th class="remarks1">Remarks</th>
    </thead>
    <tbody>
        <tr>
            <td>Accomodation
            </td>
            <td><input name="accomodationAmount" type="number" class="amountInput" id="amountId0" placeholder="Input Cost Amount" min="0">
            </td>
            <td><input type="text" name="Currency" id="Currency">
            </td>
            <td><input name="accomodationRemark" type="text" id="remarksId0">
            </td>
        </tr>
        <tr>
            <td>Meal
            </td>
            <td><input name="mealAmount" type="number" class="amountInput" id="amountId1" placeholder="Input Cost Amount" min="0">
            </td>
            <td><input type="text" name="Currency1" id="Currency1">
            </td>
            <td><input name="mealRemark" type="text" id="remarksId1">
            </td>
        </tr>
        <tr>
            <td>Transportation
            </td>
            <td><input name="transportationAmount" type="number" class="amountInput" id="amountId2" placeholder="Input Cost Amount" min="0">
            </td>
            <td><input type="text" name="Currency2" id="Currency2">
            </td>
            <td><input name="transportationRemark" type="text" id="remarksId2">
            </td>
        </tr>
        <tr>
            <td>Others
            </td>
            <td><input name="otherAmount" type="number" class="amountInput" id="amountId3" placeholder="Input Cost Amount" min="0">
            </td>
            <td><input type="text" name="Currency3" id="Currency3">
            </td>
            <td><input name="otherRemark" type="text" id="remarksId3">
            </td>
        </tr>
        <tr>
            <td>Total Amount
            </td>
            <td><input name="totalAmount" type="text" id="totalAmount1" readonly>
            </td>
            <td><input type="text" name="Currency4" id="Currency4">
            </td>
            <td><input name="totalRemark" type="text" id="remarks1Id">
            </td>
        </tr>
    </tbody>
</table>
<br>

<div id="hotelInformation" style="display: none;">
<h2>Hotel Information</h2>
<table class="hotel" id="tableHotel" border="1">
    <thead>
        <th class="hotelName">Hotel Name</th>
        <th class="hotelAddress">Hotel Address</th>
        <th class="telephone">Hotel Phone Number</th>
        <th class="Remarks2">Remarks</th>
    </thead>
<tbody>
    <tr class="hotelRow">
        <td><input name="hotelData[hotelName][]" id="hotelName" type="text" class="hotelName" placeholder="Input Hotel Name" required></td>
        <td><input name="hotelData[hotelAddress][]" id="hotelAddress" type="text" class="hotelAddress" placeholder="Input Hotel Address" required></td>
        <td><input name="hotelData[hotelPhone][]" id="hotelPhone" type="string" class="telephone" placeholder="Input Hotel Phone Number" required></td>
        <td><input name="hotelData[hotelRemark][]" id="hotelRemark" type="text"></td>
    </tr>
</tbody>
</table>
<br>
<div>
    <button type="button" onclick="tambahHot()"> + Hotel Information</button>
</div>
</div>
<br>
<label class="custom-file-upload" for="file">
    Choose a file
    <span id="file-display"></span>
</label>
<input type="file" name="file" id="file" onchange="displayFileName()" value="">
<br>
<br>
    <button type="submit">Submit</button>
</form>
</div>
<br>

<script src="request.js"></script>

</body>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to update the logo based on the company value
    function updateLogo(company) {
        const img = document.createElement('img');
        img.alt = 'Deskripsi gambar';
        const container = document.getElementById('gambar-container');
        
        // Determine the image source based on the company value
        if (company === 'Medika' || company === 'Promed') {
            img.src = 'logo-imi medika.png';
            img.classList.add('medika-logo');
        } else if (company === 'Iss') {
            img.src = 'logo-ISS.png';
        } else {
            // Set default logo or handle other companies
            img.src = 'default-logo.png';
        }

        // Clear existing logo before adding the new one
        container.innerHTML = '';
        container.appendChild(img);
    }

    // Get the company input field
    const companyInput = document.getElementById('company');

    // Initial update based on the current value
    updateLogo(companyInput.value);

    // Add event listener to update the logo when the company value changes
    companyInput.addEventListener('input', function() {
        updateLogo(this.value);
    });
});

function goBack() {
    window.history.back();
    return false; // Prevents the default behavior of the link
}

</script>

<style>
body{
    margin-top: 40px;
}

img {
    max-width: 300px;
    width: 90%;
    margin-left: 10px;
}

.medika-logo {
    max-width: 180px;
    width: 70%;
    margin-left: 10px;
    padding: 10px
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