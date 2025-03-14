<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "myapp_db";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Ensure customer is logged in
if (!isset($_SESSION['cust_id'])) {
    die("Error: Customer not logged in.");
}
$cust_id = $_SESSION['cust_id'];

// ✅ Fetch latest order for the logged-in customer
$order_query = "SELECT order_id FROM orders WHERE cust_id = '$cust_id' ORDER BY order_date DESC LIMIT 1";
$order_result = $conn->query($order_query);
if (!$order_result || $order_result->num_rows === 0) {
    die("Error: No recent order found for this customer.");
}
$order = $order_result->fetch_assoc();
$order_id = $order['order_id']; // Latest order ID

// ✅ Fetch order items
$order_items = [];
$total_amount = 0;
$order_items_query = "SELECT oi.*, cyl.size, cyl.price FROM order_items oi 
                      JOIN cylinder cyl ON oi.cylinder_id = cyl.cylinder_id 
                      WHERE oi.order_id = '$order_id'";
$order_items_result = mysqli_query($conn, $order_items_query);
while ($row = mysqli_fetch_assoc($order_items_result)) {
    $order_items[] = $row;
    $total_amount += $row['quantity'] * $row['price'];
}

// ✅ Fetch customer details
$customer_query = "SELECT * FROM customer_details WHERE order_id = '$order_id'";
$customer_result = $conn->query($customer_query);
if (!$customer_result || $customer_result->num_rows === 0) {
    die("Error: Customer details not found for this order.");
}
$customer = $customer_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmation - OceanGas Enterprise</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <header class="bg-white shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
      <img src="images/logo.png" alt="Logo" style="height: 50px; margin-right: 10px;" />
      <h1 class="text-3xl font-bold text-gray-800">Order Confirmation</h1>
      <a href="shop.php" class="text-blue-500 hover:text-blue-700">Back to Shop</a>
    </div>
  </header>

  <div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
      <h2 class="text-2xl font-bold mb-6 text-gray-800">Thank You for Your Order!</h2>
      <p class="text-gray-700 mb-4">Your order has been successfully placed.</p>
      
      <!-- ✅ Order Number -->
      <h3 class="text-xl font-semibold text-gray-700 mb-4">Order Number: <span class="text-blue-500">#<?php echo $order_id; ?></span></h3>

      <!-- ✅ Order Summary -->
      <h3 class="text-xl font-semibold text-gray-700 mb-4">Order Summary</h3>
      <ul class="list-disc pl-5 text-gray-700">
        <?php foreach ($order_items as $item) { ?>
          <li><?php echo $item['quantity']; ?> x <?php echo $item['size']; ?> - Ksh <?php echo number_format($item['price']); ?> each</li>
        <?php } ?>
      </ul>
      <p class="mt-4 font-bold text-gray-800">Total: Ksh <?php echo number_format($total_amount); ?></p>

      <!-- ✅ Customer Details -->
      <h3 class="text-xl font-semibold text-gray-700 mt-6 mb-4">Delivery Details</h3>
      <p><strong>Name:</strong> <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?></p>
      <p><strong>Phone:</strong> <?php echo $customer['phone_number']; ?></p>
      <p><strong>Delivery Location:</strong> <?php echo $customer['delivery_location']; ?></p>
      <p><strong>Apartment Number:</strong> <?php echo $customer['apartment_number']; ?></p>

      <!-- ✅ Back to Shop Button -->
      <div class="mt-6">
        <a href="shop.php" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded">Continue Shopping</a>
      </div>
    </div>
  </div>
</body>
</html>
