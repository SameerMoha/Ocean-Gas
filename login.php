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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Verify user credentials
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $dbUsername, $dbPassword, $dbRole);
            $stmt->fetch();

            if (password_verify($password, $dbPassword)) {
                // Store user info in session
                session_regenerate_id(true);
                $_SESSION['username'] = $dbUsername;
                $_SESSION['role'] = $dbRole;

                // Redirect to the correct dashboard
                switch ($dbRole) {
                    case 'admin':
                        header("Location: admin-dashboard.php");
                        break;
                    case 'sales':
                        header("Location: sales.html");
                        break;
                    case 'procurement':
                        header("Location: procurement.html");
                        break;
                    case 'support':
                        header("Location: support-dashboard.php");
                        break;
                    default:
                        header("Location: index.php");
                }
                exit();
            } else {
                $error = "Incorrect username or password.";
            }
        } else {
            $error = "Incorrect username or password.";
        }
        $stmt->close();
    }
}

// If already logged in, redirect to the dashboard
if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            header("Location: admin-dashboard.php");
            exit();
        case 'sales':
            header("Location: sales.html");
            exit();
        case 'procurement':
            header("Location: procurement.html");
            exit();
        case 'support':
            header("Location: support-dashboard.php");
            exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            width: 400px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-container button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            margin-top: 10px;
            border: none;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Staff Login</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
            Forgot Password? <a href="forgot-password.php"> click here </a>
        </form>
    </div>
</body>
</html>
