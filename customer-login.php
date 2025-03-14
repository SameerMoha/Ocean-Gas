<?php
session_start();

$host = 'localhost';
$db   = 'myapp_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['login'])) {
    $cust_name = isset($_POST['cust_name']) ? trim($_POST['cust_name']) : '';
    $password  = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($cust_name) || empty($password)) {
        $error = "Please fill in all required fields.";
    } else {
        // Check customer credentials using name instead of email
        $stmt = $conn->prepare("SELECT cust_id, cust_name, password FROM customers WHERE cust_name = ?");
        $stmt->bind_param("s", $cust_name);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($cust_id, $dbCustName, $dbPassword);
            $stmt->fetch();

            if (password_verify($password, $dbPassword)) {
                session_regenerate_id(true);
                $_SESSION['cust_id']   = $cust_id;
                $_SESSION['cust_name'] = $dbCustName;

                // Redirect to shop after successful login
                header("Location: shop.php");
                exit();
            } else {
                $error = "Invalid name or password.";
            }
        } else {
            $error = "Invalid name or password.";
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
    <title>Customer Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        .login-container {
            width: 300px;
            margin: 80px auto;
            background: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
        .login-container label {
            display: block;
            margin-top: 10px;
        }
        .login-container input[type="name"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .login-container .required {
            color: red;
        }
        .error {
            color: red;
            font-size: 0.9em;
            text-align: center;
        }
        .login-container button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin-top: 15px;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        .register-link, .forgot-password {
            text-align: center;
            margin-top: 15px;
        }
        .register-link a, .forgot-password a {
            color: #008CBA;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Customer Login</h2>
        <?php if (isset($error)) { echo "<p class='error'>{$error}</p>"; } ?>

        <form method="POST" action="customer-login.php">
            <label for="cust_name">
                Name: <span class="required">*</span>
            </label>
            <input type="name" id="cust_name" name="cust_name">

            <label for="password">
                Password: <span class="required">*</span>
            </label>
            <input type="password" id="password" name="password">

            <button type="submit" name="login">Login</button>
        </form>

        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </div>
        <div class="forgot-password">
            <p>Forgot password? <br><a href="forgot-password.php">Click here</a> to reset your password</p>
        </div>
    </div>
</body>
</html>
