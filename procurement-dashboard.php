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
    $stmt->bind_result($p_Name);
    $stmt->fetch();
    $stmt->close();
    
    

// Query the stock table for current inventory data
$stock_sql = "SELECT id, product, quantity FROM stock";
$stock_result = $conn->query($stock_sql);
$stocks = [];
if ($stock_result->num_rows > 0) {
    while ($row = $stock_result->fetch_assoc()) {
        $stocks[] = $row;
    }
}

// Query purchase history joining suppliers and users for detailed report
// Also calculates the total cost using supplier's cost for 6kg or 12kg
$history_sql = "SELECT ph.purchase_date, ph.product, ph.quantity, s.name AS supplier, 
                       u.username AS purchased_by,
                       (CASE 
                          WHEN ph.product = '6kg' THEN s.cost_6kg 
                          ELSE s.cost_12kg 
                        END * ph.quantity) AS total_cost
                FROM purchase_history ph
                JOIN suppliers s ON ph.supplier_id = s.id
                JOIN users u ON ph.purchased_by = u.id
                ORDER BY ph.purchase_date DESC";
$history_result = $conn->query($history_sql);
$purchase_history = [];
if ($history_result && $history_result->num_rows > 0) {
    while ($row = $history_result->fetch_assoc()) {
        $purchase_history[] = $row;
    }
}

// Query financial summary using separate queries
$sql_allocated = "SELECT IFNULL(SUM(allocated_amount),0) AS total_allocated FROM procurement_funds";
$allocated_result = $conn->query($sql_allocated);
$allocated_data = $allocated_result->fetch_assoc();
$total_allocated = $allocated_data['total_allocated'];

$sql_used = "SELECT IFNULL(SUM(amount),0) AS total_used FROM funds_deductions";
$used_result = $conn->query($sql_used);
$used_data = $used_result->fetch_assoc();
$total_used = $used_data['total_used'];

$balance = $total_allocated - $total_used;

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Procurement Dashboard - Admin</title>
  <link rel="stylesheet" href="styles.css">
  <style>
      body {
          font-family: 'Arial', sans-serif;
          background-color: #eef2f7;
          margin: 0;
          padding: 0;
      }
      header {
          background-color: #2c3e50;
          color: white;
          padding: 20px;
          text-align: center;
          width: 100%;
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
          position: relative;
      }
      header form.logout {
          position: absolute;
          top: 20px;
          right: 20px;
      }
      .dashboard {
          width: 90%;
          max-width: 1200px;
          margin: 20px auto;
          display: flex;
          flex-direction: column;
          gap: 20px;
      }
      .cards-container {
          display: flex;
          flex-wrap: wrap;
          gap: 15px;
          justify-content: space-between;
      }
      .card {
          background: white;
          padding: 20px;
          border-radius: 12px;
          box-shadow: 0 4px 6px rgba(0,0,0,0.1);
          text-align: center;
          flex: 1;
          min-width: 250px;
      }
      .value {
          font-size: 2em;
          color: #34495e;
      }
      .charts-container {
          display: flex;
          flex-wrap: wrap;
          gap: 20px;
          justify-content: space-between;
      }
      .chart {
          background: white;
          padding: 20px;
          border-radius: 12px;
          box-shadow: 0 4px 6px rgba(0,0,0,0.1);
          flex: 1;
          min-width: 280px;
          text-align: center;
      }
      canvas {
          width: 100% !important;
          height: auto !important;
      }
      .history-table {
          width: 100%;
          border-collapse: collapse;
      }
      .history-table th, .history-table td {
          border: 1px solid #ccc;
          padding: 10px;
          text-align: left;
      }
      .financial-section {
          background: #fff;
          padding: 20px;
          border-radius: 12px;
          box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      }
      .financial-section h3 {
          margin-top: 0;
      }
      .financial-summary {
          display: flex;
          gap: 20px;
          margin-bottom: 20px;
      }
      .financial-summary div {
          flex: 1;
          text-align: center;
      }
      .allocate-form input[type="number"] {
          padding: 8px;
          width: 100%;
          margin-bottom: 10px;
      }
      .allocate-form button {
          padding: 10px 20px;
          background-color: #3498db;
          color: #fff;
          border: none;
          border-radius: 5px;
          cursor: pointer;
      }
      .allocate-form button:hover {
          background-color: #2980b9;
      }
  </style>
</head>
<body>
    <header>
        <h1>Welcome to the Procurement Dashboard, <?php echo htmlspecialchars($p_Name); ?></h1>
        <p>Track and manage procurement activities efficiently.</p>
        <form action="/OceanGas/staff/logout.php" method="post" class="logout">
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </header>
    <main class="dashboard">
        <!-- Current Stock Section -->
        <section class="section">
            <h2>Current Stock</h2>
            <div class="cards-container">
                <?php foreach($stocks as $stock): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($stock['product']); ?></h3>
                        <p class="value"><?php echo htmlspecialchars($stock['quantity']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        
        <!-- Procurement Analytics Section -->
        <section class="section">
            <h2>Procurement Analytics</h2>
            <div class="charts-container">
                <div class="chart">
                    <h3>Budget vs Actual (Bar Chart)</h3>
                    <canvas id="barChart" width="400" height="300"></canvas>
                </div>
                <div class="chart">
                    <h3>Procurement Trend (Line Chart)</h3>
                    <canvas id="lineChart" width="400" height="300"></canvas>
                </div>
            </div>
        </section>
        
        <!-- Purchase History & Reports Section -->
        <section class="section">
            <h2>Purchase History & Reports</h2>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Purchase Date</th>
                        <th>Supplier Name</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total Cost (KES)</th>
                        <th>Procurement Staff</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($purchase_history) > 0): ?>
                        <?php foreach($purchase_history as $history): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($history['purchase_date']); ?></td>
                                <td><?php echo htmlspecialchars($history['supplier']); ?></td>
                                <td><?php echo htmlspecialchars($history['product']); ?></td>
                                <td><?php echo htmlspecialchars($history['quantity']); ?></td>
                                <td>KES <?php echo number_format($history['total_cost'], 2); ?></td>
                                <td><?php echo htmlspecialchars($history['purchased_by']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No purchase history found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
        
        <!-- Financial Summary Section -->
        <section class="section financial-section">
            <h2>Financial Summary (in Kenyan Shillings)</h2>
            <div class="financial-summary">
                <div>
                    <h3>Allocated</h3>
                    <p class="value">KES <?php echo number_format($total_allocated, 2); ?></p>
                </div>
                <div>
                    <h3>Used</h3>
                    <p class="value">KES <?php echo number_format($total_used, 2); ?></p>
                </div>
                <div>
                    <h3>Balance</h3>
                    <p class="value">KES <?php echo number_format($balance, 2); ?></p>
                </div>
            </div>
            <h3>Allocate Additional Funds</h3>
            <form class="allocate-form" action="allocate_funds.php" method="POST">
                <input type="number" name="amount" step="0.01" min="0" placeholder="Enter amount in KES" required>
                <input type="text" name="note" placeholder="Optional note">
                <button type="submit">Allocate Funds</button>
            </form>
        </section>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Bar Chart: Budget vs Actual
            const barCtx = document.getElementById('barChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr'],
                    datasets: [
                        {
                            label: 'Budget',
                            data: [25000, 30000, 28000, 32000],
                            backgroundColor: '#3498db'
                        },
                        {
                            label: 'Actual',
                            data: [24000, 29000, 27000, 31000],
                            backgroundColor: '#e74c3c'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
            
            // Line Chart: Procurement Trend
            const lineCtx = document.getElementById('lineChart').getContext('2d');
            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [
                        {
                            label: '6kg Cylinders',
                            data: [20, 25, 22, 30],
                            borderColor: '#2ecc71',
                            fill: false,
                            tension: 0.1
                        },
                        {
                            label: '12kg Cylinders',
                            data: [15, 18, 20, 17],
                            borderColor: '#9b59b6',
                            fill: false,
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
</body>
</html>
