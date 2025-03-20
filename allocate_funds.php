<?php
session_start();

// Database connection
$host = 'localhost';
$db   = 'myapp_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
$note = isset($_POST['note']) ? trim($_POST['note']) : '';

if ($amount <= 0) {
    die("Please enter a valid amount.");
}

$host = 'localhost';
$db   = 'myapp_db';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO procurement_funds (allocated_amount, note) VALUES (?, ?)");
$stmt->bind_param("ds", $amount, $note);
if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: procurement-dashboard.php?message=Funds+allocated+successfully");
    exit();
} else {
    die("Error allocating funds: " . $conn->error);
}
?>
