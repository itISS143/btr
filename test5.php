<?php
include "ambil_data.php";

$currency = isset($_POST['currency']) ? $_POST['currency'] : null;

if ($currency !== null) {
    // Prevent SQL injection using prepared statements
    $query = mysqli_prepare($Open, "SELECT * FROM currency WHERE Currency = ?");
    
    mysqli_stmt_bind_param($query, "s", $currency);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);
    
    if ($result) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Query error']);
    }
} else {
    echo json_encode(['error' => 'Currency is not set']);
}
?>
