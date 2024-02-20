<?php
include "ambil_data.php";

$requestorName = isset($_POST['requestor']) ? $_POST['requestor'] : null;

if ($requestorName !== null) {
    $query = mysqli_query($Open, "SELECT * FROM requestor_form WHERE requestorName = '$requestorName'");
    $data = mysqli_fetch_array($query);
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'requestor is not set']);
}
?>
