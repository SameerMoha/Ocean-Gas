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
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role']; // Get selected role

    if (empty($username) || empty($password)) {
        $error = "Please fill in all required fields.";
    } else {
        // Verify user credentials
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ? AND role = ?");
        $stmt->bind_param("ss", $username, $role);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $dbUsername, $dbPassword, $dbRole);
            $stmt->fetch();

            if (password_verify($password, $dbPassword)) {
                session_regenerate_id(true);
                $_SESSION['username'] = $dbUsername;
                $_SESSION['role'] = $dbRole;

                // Redirect to respective dashboards
                switch ($dbRole) {
                    case 'admin':
                        header("Location: admin-dashboard.php");
                        break;
                    case 'sales':
                        header("Location: sales.html");
                        break;
                    case 'procurement':
                        header("Location: procurement-dashboard.php");
                        break;
                    case 'support':
                        header("Location: support-dashboard.php");
                        break;
                    default:
                        header("Location: index.php");
                }
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
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
    <title>Select Role</title>
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
        .role-container {
            width: 400px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            text-align: center;
        }
        .role-container h2 {
            margin-bottom: 20px;
        }
        .role-buttons {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .role-buttons button {
            flex: 1;
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .role-buttons button:hover {
            background-color: #0056b3;
        }
        .login-form {
            display: none;
            margin-top: 20px;
        }
        .login-form input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-form button {
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
    <script>
        function showLoginForm(role) {
            document.getElementById('roleTitle').innerText = role.charAt(0).toUpperCase() + role.slice(1) + " Login";
            document.getElementById('roleInput').value = role;
            document.getElementById('loginForm').style.display = 'block';
        }
    </script>
</head>
<body>
    <div class="role-container">
        <h2>Select Your Role</h2>
        <div class="role-buttons">
            <button onclick="showLoginForm('admin')"><i class="fas fa-user-shield"></i> Admin</button>
            <button onclick="showLoginForm('sales')"><i class="fas fa-chart-line"></i> Sales</button>
            <button onclick="showLoginForm('procurement')"><i class="fas fa-shopping-cart"></i> Procurement</button>
            <button onclick="showLoginForm('support')"><i class="fas fa-headset"></i> Support</button>
        </div>

        <div class="login-form" id="loginForm">
            <h2 id="roleTitle"></h2>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            <form method="POST" action="">
                <input type="hidden" name="role" id="roleInput">
                <label for="username">Username:</label>
                <input type="text" name="username" required>
                <label for="password">Password:</label>
                <input type="password" name="password" required>
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
