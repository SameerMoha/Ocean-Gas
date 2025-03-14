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
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .dashboard {
            display: flex;
            gap: 20px;
            max-width: 1200px;
            width: 100%;
        }
        .sidebar {
            background-color: #1b1e36;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 250px;
        }
        .profile {
            border-bottom: 1px solid white;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .profile img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 3px solid white;
        }
        .profile h3 {
            margin: 10px 0 5px;
        }
        .profile p {
            font-size: 14px;
            color: #aaa;
        }
        .menu button {
            display: block;
            width: 100%;
            margin-top: 10px;
            background-color: #1e90ff;
            color: white;
            border: none;
            padding: 12px;
            cursor: pointer;
            font-size: 1em;
            border-radius: 8px;
            transition: 0.3s;
        }
        .menu button:hover {
            background-color: #1565c0;
            transform: scale(1.05);
        }
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .sales-summary {
            display: flex;
            gap: 20px;
        }
        .summary-card {
            flex: 1;
            background-color: #1b1e36;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            transition: 0.3s;
        }
        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.2);
        }
        .charts {
            display: flex;
            gap: 20px;
        }
        .chart-container {
            flex: 1;
            background-color: #1b1e36;
            padding: 20px;
            border-radius: 10px;
            height: 300px;
        }
    </style>
</head>
<body>
    <h1>Sales Dashboard</h1>
    <div class="dashboard">
        <div class="sidebar">
            <div class="profile">
    <img src="no-profile.jpeg" alt="Profile Picture">
    <h3>John Doe</h3>
    <p>Admin</p>
            </div>

            <div class="menu">
                <a href="inventory.html"><button>Inventory</button></a>
                <a href="sales_process.php"><button>Sales</button></a>
                <form action="logout.php" method="post">
            <button type="submit" class="logout-btn">Logout</button>
        </form>

            </div>
        </div>
        <div class="main-content">
            <div class="sales-summary">
                <div class="summary-card">
                    <h3>Monthly Sales</h3>
                    <p><b>$550K</b></p>
                </div>
                <div class="summary-card">
                    <h3>Daily Sales</h3>
                    <p><b>$18K</b></p>
                </div>
            </div>
            <div class="charts">
    <div class="chart-container">
        <h3>6kg Cylinders Sold Per Month</h3>
        <canvas id="sales6kgChart"></canvas>
    </div>
    <div class="chart-container">
        <h3>12kg Cylinders Sold Per Month</h3>
        <canvas id="sales12kgChart"></canvas>
    </div>
</div>

<div class="charts">
    <div class="chart-container">
        <h3>Total Sales Per Month</h3>
        <canvas id="totalSalesChart"></canvas>
    </div>
    <div class="chart-container">
        <h3>Total 6kg vs 12kg Cylinders Sold</h3>
        <canvas id="sizeSalesChart"></canvas>
    </div>
</div>

            <!--<div class="charts">
                <div class="chart-container">
                    <h3>Total Sales This Year</h3>
                    <canvas id="totalSalesChart"></canvas>
                </div>
                <div class="chart-container">
                    <h3>Sales by Size (6kg vs 12kg)</h3>
                    <canvas id="sizeSalesChart"></canvas>
                </div>
            </div> -->
        </div>
    </div>
    <script>
        async function fetchSalesData() {
    try {
        const response = await fetch("fetch_sales.php"); 
        const data = await response.json();

        let months = [];
        let sales6kg = [];
        let sales12kg = [];
        let totalSales = [];

        // Process monthly data
        data.monthlySales.forEach(sale => {
            months.push(sale.month);
            sales6kg.push(parseInt(sale.total_6kg));
            sales12kg.push(parseInt(sale.total_12kg));
            totalSales.push(parseFloat(sale.total_sales));
        });

        // Process total sales data (for pie chart)
        let totalCylinders = {
            "6kg": parseInt(data.totalSales.grand_total_6kg),
            "12kg": parseInt(data.totalSales.grand_total_12kg)
        };

        // Render all charts
        renderBarChart("sales6kgChart", months, sales6kg, "6kg Cylinders Sold", "#1e90ff");
        renderBarChart("sales12kgChart", months, sales12kg, "12kg Cylinders Sold", "#ff4500");
        renderBarChart("totalSalesChart", months, totalSales, "Total Sales (Revenue)", "#28a745");
        renderPieChart(totalCylinders);
    } catch (error) {
        console.error("Error fetching sales data:", error);
    }
}

// Function to create bar charts
function renderBarChart(canvasId, labels, data, label, color) {
    const ctx = document.getElementById(canvasId).getContext("2d");
    
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: color
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { ticks: { color: "white" } },
                y: { ticks: { color: "white" }, beginAtZero: true }
            },
            plugins: {
                legend: { labels: { color: "white" } }
            }
        }
    });
}

// Function to create a pie chart
function renderPieChart(salesBySize) {
    const ctx = document.getElementById("sizeSalesChart").getContext("2d");

    new Chart(ctx, {
        type: "pie",
        data: {
            labels: ["6kg", "12kg"],
            datasets: [{
                label: "Total Cylinders Sold",
                data: [salesBySize["6kg"], salesBySize["12kg"]],
                backgroundColor: ["#1e90ff", "#ff4500"]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { labels: { color: "white" } }
            }
        }
    });
}

// Fetch and render data when page loads
fetchSalesData();

    </script>
</body>
</html>