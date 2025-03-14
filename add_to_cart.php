<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost"; // Change if necessary
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$database = "myapp_db"; // Change to your actual database name

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['cust_id'])) {
    die("Error: User not logged in.");
}

$cust_id = $_SESSION['cust_id'];

// Check if cylinder_id is provided
if (!isset($_POST['cylinder_id']) || empty($_POST['cylinder_id'])) {
    die("Error: Cylinder ID is missing.");
}

$cylinder_id = intval($_POST['cylinder_id']);

// Check if product exists
$product_check_query = "SELECT * FROM cylinder WHERE cylinder_id = '$cylinder_id'";

$product_check_result = mysqli_query($conn, $product_check_query);
if (!$product_check_result) {
    die("Error in product query: " . mysqli_error($conn));
}

if (mysqli_num_rows($product_check_result) == 0) {
    die("Error: Product does not exist.");
}

// Add to cart (insert or update quantity)
$query = "INSERT INTO cart (cust_id, cylinder_id, quantity) VALUES ('$cust_id', '$cylinder_id', 1) 
          ON DUPLICATE KEY UPDATE quantity = quantity + 1";

if (mysqli_query($conn, $query)) {
    echo "Added to cart successfully.";
} else {
    echo "Error: " . mysqli_error($conn);
}

$conn->close();
?>
