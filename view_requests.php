<?php
session_start();

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

if (!isset($_SESSION['user_name'])) {
    // Redirect to the index page if not logged in
    header('Location: index.php');
    exit();
}

$userName = $_SESSION['user_name'];
$idNumber = $_SESSION['id_number'];

$reference = $_GET['ref'];

// Fetch and display travel requests from the database
$sql = "SELECT 
    s.*,
    r.requestorName as initiatedByName,
    r1.requestorName as requestorName,
    r1.email as requestorEmail,
    r1.division as requestorDivision,
    r1.departement as requestorDepartement,
    r1.phoneNumber as requestorPhoneNumber,
    r1.idCard as requestorIdCard,
    r1.gender as requestorGender,
    r1.company as requestorCompany
FROM submitted_requestorform s
LEFT JOIN requestor_forms r ON s.initiated_by_id = r.idNumber
LEFT JOIN requestor_forms r1 ON s.requestor_id = r1.idNumber
WHERE s.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $reference);

if ($stmt->execute()) {
    $result = $stmt->get_result();

if ($result->num_rows > 0) {
        // Display requests in a table
        echo "<a id='goBackButton' href='#' onclick='goBack()' style='text-decoration:none;'>Back</a>";
        echo "<div class='container' id='pdf-content'>";
        echo "<div id='gambar-container'></div>";
        echo "<h1>Business Travel Request</h1>";
        echo '<table>';
        while ($row = $result->fetch_assoc()) {
            $approval = $row['approval'];
            $attachmentFilePath = $row['attachment_file'];
            
            // Check the company from requestorName and set image source accordingly
            $companyLogo = '';
            if (($row['requestorCompany'] === 'Medika' || $row['requestorCompany'] === 'Promed') && $row['requestorCompany'] !== 'Iss') {
                $companyLogo = 'logo-imi medika.png';
            } elseif ($row['requestorCompany'] === 'Iss' && ($row['requestorCompany'] !== 'Medika' || $row['requestorCompany'] !== 'Promed')) {
                $companyLogo = 'logo-ISS.png';
            }
            
            echo "<script>
                    const img = document.createElement('img');
                    img.src = '$companyLogo';
                    img.alt = 'Company Logo';
                    document.getElementById('gambar-container').appendChild(img);
                  </script>";
                  
            echo "<tr>
                    <th>Reference</th>
                    <td>{$row['reference']}</td>
                    <th>Initiated By</th>
                    <td>{$row['initiatedByName']}</td>
                  </tr>
                  <tr>
                    <th>Status</th>
                    <td>{$row['approval']}</td>
                    <th>Date Initiated</th>
                    <td>" . date('d-m-Y H:i:s', strtotime($row['date_initiated_by'])) . "</td>
                  </tr>
                  <tr>
                  <th>Document Title</th>
                  <td>{$row['document_title']}</td>
              </tr>
              <tr>
                  <th>Trip Purpose</th>
                  <td>{$row['trip_purpose']}</td>
              </tr>
              <tr>
                  <th>Requestor</th>
                  <td>{$row['requestorName']}</td>
                  <th>Email Address</th>
                  <td>{$row['requestorEmail']}</td>
              </tr>
              <tr>
                  <th>Division</th>
                  <td>{$row['requestorDivision']}</td>
                  <th>Departement</th>
                  <td>{$row['requestorDepartement']}</td>
              </tr>
              <tr>
                  <th>NIK KTP</th>
                  <td>{$row['requestorIdCard']}</td>
                  <th>Phone Number</th>
                  <td>{$row['requestorPhoneNumber']}</td>
              </tr>
              <tr>
                  <th> Manager Name</th>
                  <td>{$row['manager_name']}</td>
              </tr>
              <tr>
                  <th>Start Date</th>
                  <td>" . date('d-m-Y', strtotime($row['start_date'])) . "</td>
                  <th>Return Date</th>
                  <td>" . date('d-m-Y', strtotime($row['return_date'])) . "</td>
              </tr>
              <tr>
                  <th>Destination</th>
                  <td>{$row['destination']}</td>
                  <th>Total Days</th>
                  <td>{$row['total_days']}</td>
              </tr>
              <tr>
                  <th>Advance Amount</th>
                  <td>{$row['advance_amounts']}</td>
                  <th>Currency</th>
                  <td>{$row['currency']}</td>
              </tr>";
            echo '</table>';
            echo '<h2>Passenger</h2>';
            echo '<table>';
            echo "<thead>
                    <th>Full Name</th>
                    <th>Division</th>
                    <th>Gender</th>
                    <th>Hotel Booking</th>
                    <th>Comment</th>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{$row['requestorName']}</td>
                      <td>{$row['requestorDivision']}</td>
                      <td>{$row['requestorGender']}</td>
                      <td>{$row['hotel_booking']}</td>
                      <td>{$row['comments']}</td>
                    </tr>
                  </tbody>";
            echo '</table>';
            echo '<br>';

            // Fetch and display travel requests from the database
            $sql2 = "SELECT * FROM trip_routing WHERE submitted_id = ?";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param('s', $reference);
            if ($stmt2->execute()) {
                $result2 = $stmt2->get_result();

                echo '<h2 class="estimated-cost-page">Trip Routing</h2>';
                echo '<table>';
                echo '<thead>';
                echo "<th>From</th>
                    <th>To</th>
                    <th>Trip Class</th>
                    <th>Flight Date</th>
                    <th>Comment</th>";
                echo '</thead>';
                echo '<tbody>';
                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        echo '<tr>';
                        echo "<td>{$row2['trip_from']}</td>
                        <td>{$row2['trip_to']}</td>
                        <td>{$row2['trip_class']}</td>
                        <td>" . date('d-m-Y', strtotime($row2['flight_date'])) . "</td>
                        <td>{$row2['comments']}</td>";
                        echo '</tr>';
                    }
                }
            }
            echo '</tbody>';
            echo '</table>';

            $sql4 = "SELECT * FROM travel_cost WHERE submitted_id = ?";
            $stmt4 = $conn->prepare($sql4);
            $stmt4->bind_param('s', $reference);
            if ($stmt4->execute()) {
                $result4 = $stmt4->get_result();

            echo '<h2>Travel Cost</h2>';
            echo '<table>';
            echo '<thead>';
            echo "<th>Category</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Remarks</th>";
            echo '</thead>';
            echo '<tbody>';
            if ($result4->num_rows > 0) {
                while ($row4 = $result4->fetch_assoc()) {
                    echo '<tr>';
                    echo "<td>{$row4['category']}</td>
                    <td>{$row4['amount']}</td>
                    <td>{$row4['currency']}</td>
                    <td>{$row4['remark']}</td>";
                    echo '</tr>';
                }
            }
        }
            echo "<tr>
                    <td>Total Amount</td>
                    <td>{$row['advance_amounts']}</td>
                    <td>{$row['currency']}</td>
                    <td>{$row['tfap_remarks']}</td>
                    </tr>";
            echo '<tbody>';
            echo '</table>';

            // Fetch and display travel requests from the database
            $sql3 = "SELECT * FROM hotel_information WHERE submitted_id = ?";
            $stmt3 = $conn->prepare($sql3);
            $stmt3->bind_param('s', $reference);
            if ($stmt3->execute()) {
                $result3 = $stmt3->get_result();
                echo '<h2>Hotel Information</h2>';
                echo '<table>';
                echo '<thead>';
                echo "<th>Hotel Name</th>
                    <th>Hotel Address</th>
                    <th>Hotel Phone Number</th>
                    <th>Remarks</th>";
                echo '</thead>';
                echo '<tbody>';
                if ($result3->num_rows > 0) {
                    while ($row3 = $result3->fetch_assoc()) {
                        echo '<tr>';
                        echo "<td>{$row3['hotel_name']}</td>
                    <td>{$row3['hotel_address']}</td>
                    <td>{$row3['hotel_phonenumber']}</td>
                    <td>{$row3['remarks']}</td>";
                    }
                }
                echo '<tbody>';
                echo '</table>';
            }
            echo "Time Created : {$row['date_initiated_by']} <br>";
            echo "Created By : {$row['initiatedByName']} <br>";
            echo "Time {$row['approval']} : {$row['status_date_time']} <br>";
            echo "{$row['approval']} By : {$row['approvedBy']}";
        }

        echo '</table>';
        echo "</div>";
        if (!empty($attachmentFilePath)) {
            // Check if the file is an image
            $imageExtensions = array('jpg', 'jpeg', 'png', 'gif');
            $fileExtension = pathinfo($attachmentFilePath, PATHINFO_EXTENSION);

            if (in_array(strtolower($fileExtension), $imageExtensions)) {
                // Display image with width 500px
                echo '<img src=' . $attachmentFilePath . '" alt="Uploaded File" style="width: 500px;">';
            } elseif (strtolower($fileExtension) === 'pdf') {
                // Display PDF using an iframe
                echo '<iframe src="' . $attachmentFilePath . '" width="1200" height="570" style="border: none;"></iframe>';
            } else {
                // Display other file types (e.g., non-image files)
                echo '<img src="' . $attachmentFilePath . '" alt="Uploaded File">';
            }
        } else {
            echo "No files found for this request.";
        }
        echo "<div id='watermark'>";
        if ($approval == 'Approved') {
            echo '<img src="approved_logo.jpg" alt="Approved Watermark">';
        } elseif ($approval == 'Rejected') {
            echo '<img src="rejected logo.webp" alt="Rejected Watermark">';
        } else {
        }
        echo "</div>";

        echo "<button type='button' id='submit-pdf-button' onclick='generatePdf(\"$approval\")'>Generate PDF</button>";
    } else {
        echo 'No data found in the database';
    }
} else {
    echo 'Failed to execute query: ' . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<link rel="stylesheet" href="request.css">
<script src="pdfbutton.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.3/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js"></script>

<script>
    function goBack() {
    window.history.back();
    return false; // Prevents the default behavior of the link
}

</script>

<style>
    img1 {
        max-width: 170px;
        width: 90%;
        margin-left: 10px;
    }
    
    .medika-logo {
        max-width: 160px;
        width: 70%;
        margin-left: 10px;
        padding: 10px
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th {
        text-align: center;
    }

    td {
        padding: 8px;
        text-align: center;
        width: 1px;
    }

    /* Existing styles */
    #gambar-container {
        width: 30%;
    }

    #gambar-container img {
        margin-left: 7px;
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
