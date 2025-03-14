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

$cart_items = [];
$total_amount = 0;
if (isset($_SESSION['cust_id'])) {
    $cust_id = $_SESSION['cust_id'];
    $cart_query = "SELECT c.*, cyl.size, cyl.price FROM cart c JOIN cylinder cyl ON c.cylinder_id = cyl.cylinder_id WHERE c.cust_id = '$cust_id'";
    $cart_result = mysqli_query($conn, $cart_query);
    while ($row = mysqli_fetch_assoc($cart_result)) {
        $cart_items[] = $row;
        $total_amount += $row['quantity'] * $row['price'];
    }
}

if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo '<ul class="list-group">';
    foreach ($cart_items as $item) {
        echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
        echo $item['size'] . ' - ' . $item['quantity'] . ' x Ksh ' . number_format($item['price']);
        echo '<span class="fw-bold">Total: Ksh ' . number_format($item['quantity'] * $item['price']) . '</span>';
        echo '<button class="btn btn-danger btn-sm" onclick="removeFromCart(' . $item['cylinder_id'] . ')">Remove</button>';
        echo '</li>';
    }
    echo '</ul>';
    echo '<div class="mt-3 text-end fw-bold">Grand Total: Ksh ' . number_format($total_amount) . '</div>';
}
?>
