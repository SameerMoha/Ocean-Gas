<?php
header("Content-Type: application/json");

// Database connection setup
$host = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbname = "myapp_db";

$conn = new mysqli($host, $dbUser, $dbPassword, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Fetch orders from customer_details that are not hidden
$query = "SELECT * FROM customer_details WHERE is_hidden = 0 ORDER BY order_date DESC";
$result = $conn->query($query);
$orders = [];
while ($row = $result->fetch_assoc()) {
    $order_id = $row['order_id'];
    // Fetch order items for each order
    $itemsQuery = "SELECT * FROM order_items WHERE order_id = $order_id";
    $itemsResult = $conn->query($itemsQuery);
    $items = [];
    while ($item = $itemsResult->fetch_assoc()) {
        $items[] = $item;
    }
    $row['items'] = $items;
    // Calculate total sales (sum of quantity * price per item)
    $totalSales = 0;
    foreach ($items as $item) {
        $totalSales += $item['quantity'] * $item['price'];
    }
    $row['total_sales'] = $totalSales;
    $orders[] = $row;
}
echo json_encode($orders);
$conn->close();
?>
