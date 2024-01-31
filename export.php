<?php
session_start(); // Start the session
// Load the database configuration file 
include_once 'ambil_data.php'; 
 
// Filter the excel data 
function filterData(&$str) { 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
}

 
// Excel file name for download 
$fileName = "Business Travel Request" . date('Y-m-d') . ".xls"; 
 
// Column names 
$fields = array('Initiated By', 'Date Initiated', 'Reference', 'Approval Status', 'Document Title', 'Trip Purpose', 'Requestor Name', 'Requestor Email', 'Requestor Division', 'Requestor Departement', 'Requestor NIK KTP', 'Requestor Phone Number', 'Manager Name', 'Start Date', 'Return Date', 'Destination', 'Total Days', 'Advance Amount', 'Currency', 'Passenger Name', 'Passenger Division', 'Passenger Gender', 'Hotel Booking', 'Passenger Comment', 'Flight From', 'Flight To', 'Flight Class', 'Flight Date', 'Flight Comment', 'Accomodation Amount', 'Accomodation Remark', 'Meal Amount', 'Meal Remark', 'Transportation Amount', 'Transportation Remark', 'Other Amount', 'Other Remark', 'Total Amount', 'Total Remark', 'Hotal Name', 'Hotel Address', 'Hotel Phone Number', 'Hotel Remark', 'Approved Or Rejected Time');
 
// Display column names as the first row 
$excelData = implode("\t", array_values($fields)) . "\n"; 
 
// Fetch records from the database with joins
$query = $Open->query("SELECT s.*, r.requestorName as initiatedByName,
                        r1.requestorName as requestorName,
                        r1.email as requestorEmail,
                        r1.division as requestorDivision,
                        r1.departement as requestorDepartement,
                        r1.phoneNumber as requestorPhoneNumber,
                        r1.idCard as requestorIdCard,
                        r1.gender as requestorGender,
                        t.trip_from, t.trip_to, t.trip_class, t.flight_date, t.comments as trip_comments,
                        h.hotel_name, h.hotel_address, h.hotel_phonenumber, h.remarks as hotel_remarks
                        FROM submitted_requestorform s
                        LEFT JOIN requestor_forms r ON s.initiated_by_id = r.idNumber
                        LEFT JOIN requestor_forms r1 ON s.requestor_id = r1.idNumber
                        LEFT JOIN trip_routing t ON s.id = t.submitted_id
                        LEFT JOIN hotel_information h ON s.id = h.submitted_id
                        ORDER BY s.reference ASC"); 
if ($query->num_rows > 0) { 
    // Output each row of the data 
    while ($row = $query->fetch_assoc()) { 
        $lineData = array(
            $row['initiatedByName'], 
            $row['date_initiated_by'], 
            $row['reference'], 
            $row['approval'], 
            $row['document_title'], 
            $row['trip_purpose'], 
            $row['requestorName'],
            $row['requestorEmail'], 
            $row['requestorDivision'], 
            $row['requestorDepartement'], 
            $row['requestorIdCard'],
            $row['requestorPhoneNumber'], 
            $row['manager_name'], 
            $row['start_date'], 
            $row['return_date'], 
            $row['destination'], 
            $row['total_days'], 
            $row['advance_amounts'], 
            $row['currency'],
            $row['requestorName'],
            $row['requestorDivision'],
            $row['requestorGender'],
            $row['hotel_booking'],
            $row['comments'],
            $row['trip_from'],
            $row['trip_to'], 
            $row['trip_class'], 
            $row['flight_date'], 
            $row['trip_comments'],
            $row['accomodation_amount'],
            $row['accommodation_remarks'],
            $row['meals_amount'],
            $row['meals_remarks'],
            $row['transportation_amount'],
            $row['transportation_remarks'],
            $row['others_amount'],
            $row['others_remarks'],
            $row['advance_amounts'],
            $row['tfap_remarks'],
            $row['hotel_name'],
            $row['hotel_address'],
            $row['hotel_phonenumber'],
            $row['hotel_remarks'],
            $row['status_date_time']
        );

        array_walk($lineData, 'filterData'); 
        $excelData .= implode("\t", array_values($lineData)) . "\n"; 
    } 
} else { 
    $excelData .= 'No records found...'. "\n"; 
} 
 
// Headers for download 
header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
 
// Render excel data 
echo $excelData; 
exit;
?>