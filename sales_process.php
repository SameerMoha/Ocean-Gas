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

// Get admin name
$username = $_SESSION['username'];
$sql = "SELECT username FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($adminName);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0c0f1f;
            color: white;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: row;
        }
        .sidebar {
            background-color: #1b1e36;
            padding: 20px;
            width: 250px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 10px;
        }
        .profile {
            margin-bottom: 20px;
            text-align: center;
        }
        .profile h3 {
            margin-bottom: 5px;
        }
        .menu button, .logout-btn {
            width: 100%;
            margin-top: 10px;
            background-color: #1e90ff;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 1em;
            border-radius: 8px;
            transition: 0.3s;
        }
        .menu button:hover, .logout-btn:hover {
            background-color: #1565c0;
        }
        .content {
            flex: 1;
            padding-left: 20px;
        }
        .orders-section {
            margin-top: 20px;
            width: 100%;
            background-color: #1b1e36;
            padding: 20px;
            border-radius: 10px;
        }
        .order-form {
            background-color: #252a48;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
        }
        .order-form label {
            display: block;
            margin-top: 5px;
        }
        .order-form input {
            width: 100%;
            padding: 5px;
            margin-top: 3px;
            border-radius: 5px;
            border: none;
        }
        .approve-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }
        .approve-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="profile">
    <h3>Welcome <?php echo htmlspecialchars($adminName); ?></h3>
        <p>Sales</p>
    </div>
    <div class="menu">
        <button onclick="navigateTo('inventory.html')">Inventory</button>
        <button onclick="navigateTo('sales.html')">Sales</button>
        <form action="logout.php" method="post">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</div>

<div class="content">
    <h1>Sales Dashboard</h1>
    <div class="orders-section">
        <h2>New Orders</h2>
        <div id="ordersContainer"></div>
    </div>
</div>

<script>
    async function fetchOrders() {
        try {
            const response = await fetch("fetch_orders.php");
            const orders = await response.json();
            if (!Array.isArray(orders)) {
                throw new Error("Invalid data format received");
            }
            let ordersContainer = document.getElementById("ordersContainer");
            ordersContainer.innerHTML = "";
            orders.forEach(order => {
                let orderForm = document.createElement("div");
                orderForm.classList.add("order-form");
                orderForm.innerHTML = `
                    <h3>Order #${order.order_id}</h3>
                    <form>
                        <label>Customer Name:</label>
                        <input type="text" value="${order.first_name} ${order.last_name}" readonly>
                        
                        <label>Phone Number:</label>
                        <input type="text" value="${order.phone_number}" readonly>
                        
                        <label>Delivery Location:</label>
                        <input type="text" value="${order.delivery_location}, Apt: ${order.apartment_number}" readonly>
                        
                        <label>Order Date:</label>
                        <input type="text" value="${order.order_date}" readonly>
                        
                        <label>Ordered Cylinders:</label>\n                        <input type=\"text\" value=\"${formatCylinders(order.items)}\" readonly>\n                        \n                        <label>Total Price:</label>\n                        <input type=\"text\" value=\"Ksh ${order.total_sales}\" readonly>\n                        \n                        <button type=\"button\" class=\"approve-btn\" onclick=\"approveOrder(${order.order_id})\">Approve Order</button>\n                `;
                ordersContainer.appendChild(orderForm);
            });
        } catch (error) {
            console.error("Error fetching orders:", error);
        }
    }

    function formatCylinders(items) {
        let cylinderSizes = {1: "6kg", 2: "12kg"};
        return items.map(item => `${cylinderSizes[item.cylinder_id] || "Unknown"} x ${item.quantity}`).join(", ");
    }

    function approveOrder(orderId) {
        if (!confirm("Are you sure you want to approve this order?")) return;
        fetch("approve_order.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ order_id: orderId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Order approved and stock updated!");
                fetchOrders();
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch(error => console.error("Error approving order:", error));
    }

    function navigateTo(page) {
        window.location.href = page;
    }

    fetchOrders();
</script>

</body>
</html>
