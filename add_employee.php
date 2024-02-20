<?php
session_start();

$allowedUsers = ['Anindhita Prameswari']; // Add other allowed users as needed

if (!isset($_SESSION['user_name']) || !in_array($_SESSION['user_name'], $allowedUsers)) {
    // Redirect or show an error message
    header('Location: index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
</head>
<body>
    <a id="goBackButton" href="#" onclick="goBack()">Back</a>

<h2>Add Employee</h2>

<form action="process_add_employee.php" method="post">
    <label for="name">Name:</label>
    <input type="text" name="name" placeholder="Input Name" required>

    <label for="division">Division:</label>
    <input type="text" name="division" placeholder="Input Division" required>

    <label for="department">Departement:</label>
    <input type="text" name="department" placeholder="Input Departement" required>

    <label for="phone">Phone Number:</label>
    <input type="text" name="phone" placeholder="Input Phone Number" required>

    <label for="id_card">NIK KTP:</label>
    <input type="text" name="id_card" placeholder="Input NIK KTP" required>

    <label for="email">Email:</label>
    <input type="email" name="email" placeholder="Input Email" required>

    <label for="gender">Gender:</label>
    <select name="gender" required>
        <option value="">Choose Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select>

    <label for="password">Password:</label>
    <input type="text" name="password" placeholder="Input Password" value="12345678" readonly>
    
    <label for="manager_name">Manager Name:</label>
    <select name="manager_name" required>
        <option value="">Select Manager</option>
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
    <br>
    <label for="company">Company :</label>
    <select name="company" required>
        <option value="">Select Company</option>
        <option value="Medika">Interskala Medika Indonesia</option>
        <option value="Iss">Interskala Sehat Sejahtera</option>
        <option value="Promed">Produksi Medika</option>
    </select>
    <br>
    <br>
    <button type="submit">Add Employee</button>
</form>

</body>
<style>
body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            text-align: center; /* Center text in the body */
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 50px; /* Add margin-bottom for space below the heading */
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            display: inline-block; /* Display the form as an inline block */
            text-align: left; /* Align text to the left within the form */
            margin-top: 20px; /* Add margin-top to separate the form from the heading */
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input,
select {
    width: 100%;
    padding: 8px;
    margin-bottom: 16px;
    box-sizing: border-box;
}

    button {
        background-color: #4caf50;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }

    .error-message {
        color: #ff0000;
        margin-bottom: 10px;
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
<script>
    function goBack() {
    window.history.back();
    return false; // Prevents the default behavior of the link
}

    function validateForm() {
        var name = document.getElementById('name');
        var division = document.getElementById('division');
        var department = document.getElementById('department');
        var phone = document.getElementById('phone');
        var id_card = document.getElementById('id_card');
        var email = document.getElementById('email');
        var gender = document.getElementById('gender');
        var password = document.getElementById('password');
        var manager_name = document.getElementById('manager_name');

        // Basic validation example, add more as needed
        if (name.value === '' || division.value === '' || department.value === '' || phone.value === '' || id_card.value === '' || email.value === '' || password.value === '' || manager_name.value === '') {
            document.getElementById('errorMessage').innerText = 'All fields are required';
        } else {
            document.getElementById('addEmployeeForm').submit();
        }
    }
</script>
</html>
