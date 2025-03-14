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

// Fetch products from database
$query = "SELECT * FROM cylinder";
$result = mysqli_query($conn, $query);

// Fetch cart items for logged-in user
$cart_items = [];
$cart_count = 0;
$total_amount = 0;
if (isset($_SESSION['cust_id'])) {
    $cust_id = $_SESSION['cust_id'];
    $cart_query = "SELECT c.*, cyl.size, cyl.price FROM cart c JOIN cylinder cyl ON c.cylinder_id = cyl.cylinder_id WHERE c.cust_id = '$cust_id'";
    $cart_result = mysqli_query($conn, $cart_query);
    while ($row = mysqli_fetch_assoc($cart_result)) {
        $cart_items[] = $row;
        $cart_count += $row['quantity'];
        $total_amount += $row['quantity'] * $row['price'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - OceanGas Enterprise</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar a, .navbar .cart-icon {
            color: white;
        }
        .product-card {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .checkout-btn {
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
        }
        .cart-icon i {
            font-size: 24px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Shop - OceanGas Enterprise</a>
            <div class="ms-auto d-flex align-items-center">
                <a href="#" class="me-3 cart-icon" data-bs-toggle="modal" data-bs-target="#cartModal">
                    <i class="fas fa-shopping-cart"></i> <span class="badge bg-danger"><?php echo $cart_count; ?></span>
                </a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <h2 class="fw-bold">Products</h2>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-md-4 mb-3">
                    <div class="product-card p-3">
                        <img src="<?php echo $row['image_url']; ?>" class="img-fluid" alt="<?php echo $row['size']; ?>">
                        <h5 class="mt-2">Total <?php echo $row['size']; ?> Gas Cylinder</h5>
                        <p class="text-primary fw-bold">Price: Ksh <?php echo number_format($row['price']); ?></p>
                        <button class="btn btn-primary" onclick="addToCart(<?php echo $row['cylinder_id']; ?>)">Add to Cart</button>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="text-center mt-3">
            <a href="checkout.php" class="btn checkout-btn">Checkout</a>
        </div>
    </div>

    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Shopping Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (empty($cart_items)) { ?>
                        <p>Your cart is empty.</p>
                    <?php } else { ?>
                        <ul class="list-group">
                            <?php foreach ($cart_items as $item) { ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo $item['size']; ?> - <?php echo $item['quantity']; ?> x Ksh <?php echo number_format($item['price']); ?>
                                    <span class="fw-bold">Total: Ksh <?php echo number_format($item['quantity'] * $item['price']); ?></span>
                                    <button class="btn btn-danger btn-sm" onclick="removeFromCart(<?php echo $item['cylinder_id']; ?>)">Remove</button>
                                </li>
                            <?php } ?>
                        </ul>
                        <div class="mt-3 text-end fw-bold">Grand Total: Ksh <?php echo number_format($total_amount); ?></div>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addToCart(cylinder_id) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'cylinder_id=' + cylinder_id
            }).then(response => response.text()).then(data => {
                location.reload();
            });
        }
        
        function removeFromCart(cylinder_id) {
    fetch('remove_from_cart.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'cylinder_id=' + cylinder_id
    }).then(response => response.text()).then(data => {
        updateCartDisplay(); // Update the cart dynamically
    });
}

function updateCartDisplay() {
    fetch('fetch_cart.php') // Create a new PHP file to return cart HTML
        .then(response => response.text())
        .then(data => {
            document.querySelector('.modal-body').innerHTML = data;
        });
}

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
