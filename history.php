<?php
session_start();

$conn = new mysqli("localhost", "root", "", "btr");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_name'])) {
    // Redirect to the index page if not logged in
    header('Location: index.php');
    exit();
}

// Fetch the userName from the session
$userName = $_SESSION['user_name'];

// Define the limit for pagination
$limit = 100; // Number of items per page

// Assuming you have a function to fetch all rows from the database
function getAllDataFromDatabase() {
    global $conn, $limit;

    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number, default is 1
    $start = ($page - 1) * $limit; // Starting index for fetching data

    // Fetch all rows from the database with a join to get requestor name
    $sql = "SELECT sr.*, rf.requestorName AS requestor_name, rf2.requestorName AS initiated_name
            FROM submitted_requestorform sr
            LEFT JOIN requestor_forms rf ON sr.requestor_id = rf.idNumber
            LEFT JOIN requestor_forms rf2 ON sr.initiated_by_id = rf2.idNumber LIMIT $start, $limit";
    $result = $conn->query($sql);

    // Check if there are any rows returned
    if ($result->num_rows > 0) {
        // Fetch data into an associative array
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        // If no rows are returned, return an empty array
        $data = [];
    }

    // Return data and page number
    return ['data' => $data, 'page' => $page];
}

// Fetch all data from the database
$dataAndPage = getAllDataFromDatabase();
$allData = $dataAndPage['data'];
$page = $dataAndPage['page'];

function getFinalStatusText($row)
{
    if (!is_null($row['validateSls']) && !is_null($row['validateFin']) && !is_null($row['validateKas'])) {
        return 'Finalize';
    } else {
        return '';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>
    <a id="goBackButton" href="#" onclick="goBack()" style="text-decoration:none;">Back</a>

<main>
    <div class="container">
    <h2>HISTORY</h2>

            <div class="export-button-container">
            <a href="export.php" class="text-nowrap button" style="font-size: 13px;">Export To Excel</a>
            </div>

        <div class="search-container">
            <label for="searchInput">Search:</label>
            <input type="text" id="searchInput" oninput="performSearch()">
        </div>

        <div class="scrollable-container">
            <table id="tableDisplay" class="table table-bordered table-width">
                <thead class="table-dark">
                    <tr class="text-nowrap">
                        <th class="initiated">Initiated By</th>
                        <th class="approval">Status</th>
                        <th class="dateInitiated">Date Initiated</th>
                        <th class="title">Document Title</th>
                        <th class="requestor">Requestor</th>
                        <th class="manager">Manager Name</th>
                        <th class="time">Time Approved / Declined</th>
                        <th>Detail</th>
                        <th>Estimated Cost Form</th>
                        <th>Purchase Form</th>
                        <th>Closing</th>
                        <th>Validate Admin Sales</th>
                        <th>Validate Finance</th>
                        <th>Validate Kasir</th>
                        <th>Final Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allData as $row) : ?>
                        <tr class="text-nowrap">
                            <td><?php echo $row['initiated_name']; ?></td>
                            <td class="approval" data-status="<?php echo $row['approval']; ?>"><?php echo $row['approval'] ?? ''; ?></td>
                            <td><?php echo $row['date_initiated_by'] ?? ''; ?></td>
                            <td><?php echo $row['document_title'] ?? ''; ?></td>
                            <td><?php echo $row['requestor_name']; ?></td>
                            <td><?php echo $row['manager_name']; ?></td>
                            <td class="time"><?php echo $row['status_date_time'] ?? ''; ?></td>
                            <td><button type="button" class="btn btn-sm btn-primary detail-button" onclick="showDetail('<?php echo $row['id']; ?>')">Detail</button></td>
                            <td>
                                <?php if (($row['internalMemo'] === null)): ?>
                                    <button type="button" class="btn btn-sm btn-primary internal-memo-button" onclick="redirectToInternalMemo('<?php echo $row['reference']; ?>')">Upload Internal Memo</button>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (($row['formPr'] === null)): ?>
                                    <button type="button" class="btn btn-sm btn-primary form-pr-button" onclick="redirectToFormPR('<?php echo $row['reference']; ?>')">Upload Form PR</button>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!($row['closing'] === null)): ?>
                                    <button type="button" class="btn btn-sm btn-primary closing-button" onclick="redirectToClosing('<?php echo $row['reference']; ?>')">Closing</button>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['closing'] === null): ?>

                                <?php else: ?>
                                    <?php if ($row['validateSls'] === null && ($userName === 'Wiwiet Widya Ningrum')) : ?>
                                        <?php $elementId = 'penyerahanDateInput' . $row['reference']; ?>
                                        <?php $onclick = "updateTerima('$elementId', '{$row['reference']}')"; ?>
                                        <input type="button" class="validate-button" value="Terima" onclick="<?php echo $onclick; ?>">
                                    <?php else: ?>
                                        <?php echo date('d/m/Y', strtotime($row['validateSls'])); ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['validateSls'] === null): ?>
                                    <?php else: ?>
                                        <?php if ($row['validateFin'] === null): ?>
                                            <?php if ($userName === 'Lisna Suradi' || $userName === 'Ludi Krisnanda') : ?>
                                            <?php
                                            $elementId = 'penyerahanDateInput' . $row['reference'];
                                            $onclick = "updateTerima1('$elementId', '{$row['reference']}')";
                                            ?>
                                            <input type="button" class="validate-button" value="Terima" onclick="<?php echo $onclick; ?>">
                                            <?php endif; ?>
                                        <?php else: ?>
                                        <?php echo date('d/m/Y', strtotime($row['validateFin'])); ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['validateFin'] === null): ?>
                                    <?php else: ?>
                                        <?php if ($row['validateKas'] === null): ?>
                                            <?php if ($userName === 'Darwati') : ?>
                                            <?php
                                            $elementId = 'penyerahanDateInput' . $row['reference'];
                                            $onclick = "updateTerima2('$elementId', '{$row['reference']}')";
                                            ?>
                                            <input type="button" class="validate-button" value="Terima" onclick="<?php echo $onclick; ?>">
                                            <?php endif; ?>
                                        <?php else: ?>
                                        <?php echo date('d/m/Y', strtotime($row['validateKas'])); ?>
                                        <?php endif; ?>
                                <?php endif; ?>
                            </td>                    
                            <td class=" <?php echo (!is_null($row['validateSls']) && !is_null($row['validateFin']) && !is_null($row['validateKas'])) ? 'bg-warning' : 'non-finalize-row'; ?> ">
                                <?php echo getFinalStatusText($row); ?>
                            </td> 
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        $sql = "SELECT COUNT(*) AS total FROM submitted_requestorform";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $total_records = $row['total'];
        $total_pages = ceil($total_records / $limit);
        $prev_page = max(1, $page - 1);
        $next_page = min($total_pages, $page + 1);
        ?>
    
        <div class="pagination-wrapper">
            <div class="pagination-container">
                <a href="?page=<?php echo $prev_page; ?>" class="pagination-link">Previous</a>
                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active_class = ($page == $i) ? 'active' : '';
                    echo "<a href='?page=$i' class='pagination-link $active_class'>$i</a>";
                }
                ?>
                <a href="?page=<?php echo $next_page; ?>" class="pagination-link">Next</a>
            </div>
        </div>
    </div>  
</main>

</body>
<script>
    function redirectToInternalMemo(reference) {
        window.location.href = 'internalMemo.php?reference=' + reference;
        }

        function redirectToFormPR(reference) {
            window.location.href = 'formPr.php?reference=' + reference;
        }

        function redirectToClosing(reference) {
            window.location.href = 'closing.php?reference=' + reference;
        }

        function showDetail(reference) {
            window.location.href = `view_requests.php?ref=${reference}`
        }

        function performSearch() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#tableDisplay tbody tr');

            rows.forEach(row => {
                let isMatch = false;

                row.querySelectorAll('td').forEach(cell => {
                    const cellText = cell.textContent.toLowerCase();
                    if (cellText.includes(searchInput)) {
                        isMatch = true;
                    }
                });

                row.style.display = isMatch ? 'table-row' : 'none';
            });
        };

        function goBack() {
            window.history.back();
            return false; // Prevents the default behavior of the link
        }
        
</script>
<style>

.pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .pagination-container {
        display: inline-block;
    }

    .pagination-link {
        padding: 5px 10px;
        margin-right: 5px;
        border: 1px solid #ccc;
        border-radius: 3px;
        text-decoration: none;
        color: #333;
    }

    .pagination-link:hover {
        background-color: #f0f0f0;
    }

    .pagination-link.active {
        background-color: #007bff;
        color: #fff;
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
  top: 50px;
}


/* Change the background color on hover */
.button:hover {
  background-color: #2980b9;
}

body {
    margin-top: 60px;
    background-color: aliceblue;
}

table {
    background-color: white;
}

th {
    background-color: aquamarine;
}

.button-container {
    display: flex;
    margin-top: 5px; /* Adjust margin as needed */
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

.hide-cell {
    display: none;
}

.show-cell {
    display: table-cell;
}

.search-container {
    margin-bottom: 10px;
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

.search-container input[type="text"]{
    width: 250px;
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