<?php
session_start();

$host = 'localhost'; // Database host
$db   = 'myapp_db'; // Database name
$user = 'root';     // Database username
$pass = '';         // Database password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['register'])) {
    $cust_name        = trim($_POST['cust_name']);
    $cust_email       = trim($_POST['cust_email']);
    $password         = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($cust_name) || empty($cust_email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert customer details into the database
        $stmt = $conn->prepare("INSERT INTO customers (cust_name, cust_email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $cust_name, $cust_email, $hashedPassword);

        if ($stmt->execute()) {
            // Redirect to the customer login page
            header("Location: customer-login.php");
            exit();
        } else {
            $error = "Registration failed. Please try again.";
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
    <title>Customer Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        .register-container {
            width: 300px;
            margin: 80px auto;
            background: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
        .register-container label {
            display: block;
            margin-top: 10px;
        }
        .register-container input[type="text"],
        .register-container input[type="email"],
        .register-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .register-container .required {
            color: red;
        }
        .error {
            color: red;
            font-size: 0.9em;
            text-align: center;
        }
        .register-container button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin-top: 15px;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
        .login-link a {
            color: #008CBA;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Customer Registration</h2>
        <?php if (isset($error)) { echo "<p class='error'>{$error}</p>"; } ?>
        
        <form method="POST" action="register.php">
            <label for="cust_name">
                Full Name: <span class="required">*</span>
            </label>
            <input type="text" id="cust_name" name="cust_name">
            
            <label for="cust_email">
                Email: <span class="required">*</span>
            </label>
            <input type="email" id="cust_email" name="cust_email">
            
            <label for="password">
                Password: <span class="required">*</span>
            </label>
            <input type="password" id="password" name="password">
            
            <label for="confirm_password">
                Confirm Password: <span class="required">*</span>
            </label>
            <input type="password" id="confirm_password" name="confirm_password">
            
            <button type="submit" name="register">Register</button>
        </form>

        <div class="login-link">
            <p>Already have an account? <a href="customer-login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
