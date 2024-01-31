<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "btr";

$loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $user_password = $_POST["password"];
    
    try {
        $conn = new mysqli($servername, $username, $db_password, $dbname);

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT email, password, requestorName, idNumber FROM requestor_forms WHERE email = ?");
        if (!$stmt) {
            throw new Exception("Error in SQL statement: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();

        if ($stmt->error) {
            throw new Exception("Error executing the query: " . $stmt->error);
        }
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($dbEmail, $dbPassword, $name, $idNumber);
            $stmt->fetch();

            // Verify the email and password
            if ($email === $dbEmail && $user_password === $dbPassword) {

    $_SESSION['email'] = $dbEmail;
    $_SESSION['user_name'] = $name; // Store the username in the session
    $_SESSION['id_number'] = $idNumber;
    header("Location: home.php");
    exit();
            } else {
                $loginError = "Login failed. Please check your email and password.";
            }
        } else {
            $loginError = "Login failed. Please check your email and password.";
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form id="login-form" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Input Email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Input Password" required>
            <input type="checkbox" id="show-password"> Show Password
            <br>
            <br>
            <button type="submit">Login</button>
        </form>

        <?php if (!empty($loginError)) : ?>
            <p style="color: red;"><?php echo $loginError; ?></p>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const showPasswordCheckbox = document.getElementById("show-password");
            const emailInput = document.getElementById("email");
            const passwordInput = document.getElementById("password");
    
            // Show password
            showPasswordCheckbox.addEventListener("change", function () {
                if (showPasswordCheckbox.checked) {
                    passwordInput.type = "text";
                } else {
                    passwordInput.type = "password";
                }
            });
    
            // Convert inputs to uppercase
            convertToUppercase("email");
            convertToUppercase("password");
        });
    </script>
    
        
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button[type="submit"] {
            background-color: #0074d9;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        #password {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 3px;
            width: 90%;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</body>
</html>