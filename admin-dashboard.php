<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            background: #6a008a;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin: 5px 0;
        }
        .sidebar a:hover {
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
            flex-grow: 1;
        }
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .topbar-icons {
            display: flex;
            gap: 15px;
        }
        .topbar-icons i {
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    $host = 'localhost';
    $db   = 'myapp_db';
    $user = 'root';
    $pass = '';
    
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
    
    $username = $_SESSION['username'];
    $sql = "SELECT username FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($adminName);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
    ?>
    
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="#"><i class="fas fa-box"></i> Stock</a>
        <a href="#"><i class="fas fa-shopping-cart"></i> Sales</a>
        <a href="procurement.html"><i class="fas fa-truck"></i> Procurement</a>
        <a href="#"><i class="fas fa-cogs"></i> Settings</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="content">
        <div class="topbar">
            <h1>Welcome, <?php echo htmlspecialchars($adminName); ?></h1>
            <div class="topbar-icons">
                <i class="fas fa-envelope"></i>
                <i class="fas fa-bell"></i>
                <i class="fas fa-user"></i>
            </div>
        </div>
        <p>This is the admin dashboard where you can manage users, view reports, and configure settings.</p>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card p-3">
                    <h5>Total Users</h5>
                    <p>1,254</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h5>Active Sessions</h5>
                    <p>234</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h5>Pending Requests</h5>
                    <p>12</p>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5>Bar Graph</h5>
                    <canvas id="barChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5>Pie Chart</h5>
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>


        <div class="card p-3 mt-4">
    <h5>Editable Stock Table</h5>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Product</th>
                <th>Stock</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td contenteditable="true">Product 1</td>
                <td contenteditable="true">50</td>
                <td contenteditable="true">$20</td>
                <td>
                    <button class="btn btn-success btn-sm save-row">Save</button>
                    <button class="btn btn-danger btn-sm delete-row">Delete</button>
                </td>
            </tr>
            <tr>
                <td contenteditable="true">Product 2</td>
                <td contenteditable="true">30</td>
                <td contenteditable="true">$15</td>
                <td>
                    <button class="btn btn-success btn-sm save-row">Save</button>
                    <button class="btn btn-danger btn-sm delete-row">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>
    <button class="btn btn-primary" id="addRow">Add New Row</button>
        </div>

    </div>

    <script>
        const ctxBar = document.getElementById('barChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'User Growth',
                    data: [50, 75, 150, 200, 300, 400],
                    backgroundColor: 'rgba(106, 0, 138, 0.6)'
                }]
            },
            options: {
                responsive: true
            }
        });

        const ctxPie = document.getElementById('pieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Admin', 'Users', 'Guests'],
                datasets: [{
                    data: [10, 60, 30],
                    backgroundColor: ['#6a008a', '#28a745', '#ffc107']
                }]
            },
            options: {
                responsive: true
            }
        });
        
    document.addEventListener("DOMContentLoaded", function() {
        // Add new row
        document.getElementById("addRow").addEventListener("click", function() {
            let table = document.querySelector("table tbody");
            let newRow = document.createElement("tr");
            newRow.innerHTML = `
                <td contenteditable="true">New Product</td>
                <td contenteditable="true">0</td>
                <td contenteditable="true">$0</td>
                <td>
                    <button class="btn btn-success btn-sm save-row">Save</button>
                    <button class="btn btn-danger btn-sm delete-row">Delete</button>
                </td>
            `;
            table.appendChild(newRow);
        });

        // Save and Delete functionality
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("save-row")) {
                let row = event.target.closest("tr");
                let data = {
                    product: row.cells[0].innerText,
                    stock: row.cells[1].innerText,
                    price: row.cells[2].innerText
                };
                console.log("Saved Data:", data);
                alert("Row data saved! (Check console for details)");
            }

            if (event.target.classList.contains("delete-row")) {
                event.target.closest("tr").remove();
            }
        });
    });
    </script>
</body>
</html>
