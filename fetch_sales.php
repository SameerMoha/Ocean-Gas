<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "myapp_db"; // Update to match your database

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get sales data per month
$sql = "SELECT 
            DATE_FORMAT(sale_date, '%Y-%m') AS month, 
            SUM(quantity_6kg) AS total_6kg, 
            SUM(quantity_12kg) AS total_12kg,
            SUM(quantity_6kg * price_6kg + quantity_12kg * price_12kg) AS total_sales
        FROM sales 
        GROUP BY month
        ORDER BY month";

$result = $conn->query($sql);

$salesData = [];
while ($row = $result->fetch_assoc()) {
    $salesData[] = $row;
}

// Query to get total sales of each cylinder type
$sql_total = "SELECT 
                SUM(quantity_6kg) AS grand_total_6kg, 
                SUM(quantity_12kg) AS grand_total_12kg 
              FROM sales";

$result_total = $conn->query($sql_total);
$totalSales = $result_total->fetch_assoc();

// Merge data
$response = [
    "monthlySales" => $salesData,
    "totalSales" => $totalSales
];

// Close connection
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
