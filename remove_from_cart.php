<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "myapp_db";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cylinder_id']) && isset($_SESSION['cust_id'])) {
    $cylinder_id = intval($_POST['cylinder_id']);
    $cust_id = $_SESSION['cust_id'];

    // Remove one quantity of the selected item
    $remove_query = "DELETE FROM cart WHERE cust_id = '$cust_id' AND cylinder_id = '$cylinder_id' LIMIT 1";
    if ($conn->query($remove_query)) {
        echo "Item removed";
    } else {
        echo "Error removing item: " . $conn->error;
    }
}

$conn->close();
?>
