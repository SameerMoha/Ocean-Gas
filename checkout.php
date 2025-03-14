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

// ✅ Ensure user is logged in
if (!isset($_SESSION['cust_id'])) {
    die("Error: Customer not logged in.");
}
$cust_id = $_SESSION['cust_id'];

// ✅ Fetch cart items from the database
$cart_items = [];
$cart_total = 0;
$cart_query = "SELECT c.cylinder_id, c.quantity, cyl.size, cyl.price 
               FROM cart c 
               JOIN cylinder cyl ON c.cylinder_id = cyl.cylinder_id 
               WHERE c.cust_id = '$cust_id'";
$cart_result = $conn->query($cart_query);

if ($cart_result->num_rows == 0) {
    header("Location: shop.php?error=CartIsEmpty");
    exit();
}

while ($row = $cart_result->fetch_assoc()) {
    $cart_items[] = $row;
    $cart_total += $row['quantity'] * $row['price'];
}

// ✅ Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $delivery_location = $_POST['delivery_location'];
    $apartment_number = $_POST['apartment_number'];

    // ✅ Insert order into `orders`
    $order_date = date('Y-m-d H:i:s');
    $insert_order_query = "INSERT INTO orders (cust_id, order_date) VALUES ('$cust_id', '$order_date')";
    if ($conn->query($insert_order_query) === TRUE) {
        $order_id = $conn->insert_id;

        // ✅ Insert customer details
        $insert_customer_query = "INSERT INTO customer_details (order_id, cust_id, first_name, last_name, phone_number, delivery_location, apartment_number, order_date) 
                                  VALUES ('$order_id', '$cust_id', '$first_name', '$last_name', '$phone_number', '$delivery_location', '$apartment_number', '$order_date')";
        if ($conn->query($insert_customer_query) !== TRUE) {
            die("Error inserting customer details: " . $conn->error);
        }

        // ✅ Insert items into `order_items`
        foreach ($cart_items as $item) {
            $insert_item_query = "INSERT INTO order_items (order_id, cylinder_id, quantity, price) 
                                  VALUES ('$order_id', '{$item['cylinder_id']}', '{$item['quantity']}', '{$item['price']}')";
            if ($conn->query($insert_item_query) !== TRUE) {
                die("Error inserting order item: " . $conn->error);
            }
        }

        // ✅ Clear the cart after order
        $clear_cart_query = "DELETE FROM cart WHERE cust_id = '$cust_id'";
        $conn->query($clear_cart_query);

        // ✅ Redirect to `place_order.php`
        header("Location: place_order.php");
        exit();
    } else {
        die("Error inserting order: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - OceanGas Enterprise</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Checkout</h2>

            <?php if (isset($_GET['error']) && $_GET['error'] == "CartIsEmpty") { ?>
                <p class="text-red-500">Error: Your cart is empty. Please add items before checkout.</p>
            <?php } ?>

            <!-- ✅ Order Summary -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-bold mb-2">Order Summary</h3>
                <ul>
                    <?php foreach ($cart_items as $item) { ?>
                        <li class="flex justify-between border-b py-2">
                            <span><?php echo $item['size']; ?> x <?php echo $item['quantity']; ?></span>
                            <span>Ksh <?php echo number_format($item['quantity'] * $item['price']); ?></span>
                        </li>
                    <?php } ?>
                </ul>
                <div class="font-bold text-right mt-2">Total: Ksh <?php echo number_format($cart_total); ?></div>
            </div>

            <!-- ✅ Checkout Form -->
            <form method="POST" action="" class="mt-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700">First Name</label>
                        <input type="text" name="first_name" required class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-gray-700">Last Name</label>
                        <input type="text" name="last_name" required class="w-full p-2 border rounded">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-gray-700">Phone Number</label>
                    <input type="text" name="phone_number" required class="w-full p-2 border rounded">
                </div>

                <div class="mt-4">
                    <label class="block text-gray-700">Delivery Location</label>
                    <input type="text" name="delivery_location" required class="w-full p-2 border rounded">
                </div>

                <div class="mt-4">
                    <label class="block text-gray-700">Apartment Number</label>
                    <input type="text" name="apartment_number" required class="w-full p-2 border rounded">
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded">Place Order</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

