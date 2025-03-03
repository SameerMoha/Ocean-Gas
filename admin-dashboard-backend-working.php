<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - LakeGas Enterprise</title>

  <style>
  /* Reset default margins & paddings */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* Body Styling */
body {
    display: flex;
    height: 100vh;
    background-color: #f4f4f4;
}

/* Sidebar Navigation */
.sidebar {
    width: 250px;
    background-color: #2c3e50;
    padding: 20px;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 20px;
}

.sidebar a {
    text-decoration: none;
    color: white;
    display: block;
    padding: 10px;
    margin: 10px 0;
    background-color: #34495e;
    text-align: center;
    border-radius: 5px;
    transition: 0.3s;
}

.sidebar a:hover {
    background-color: #1abc9c;
}

/* Logout Button */
.logout-btn {
    background-color: red;
    color: white;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    border-radius: 5px;
    text-align: center;
}

.logout-btn:hover {
    background-color: darkred;
}

/* Main Dashboard */
/* Make iframe full width and height */
iframe#content-frame {
    width: 100%;
    height: 85vh;  /* Adjust height for better fit */
    border: none;
    background: rgb(255, 255, 255);
    display: block;
}


.main-content {
    flex: 1;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.dashboard-header {
    font-size: 28px;
    margin-bottom: 20px;
}

/* Dashboard Buttons */
.dashboard-options {
    display: flex;
    gap: 15px;
}

.btn {
    padding: 15px 20px;
    font-size: 16px;
    cursor: pointer;
    border: none;
    background-color: #2980b9;
    color: white;
    border-radius: 5px;
    transition: 0.3s;
}

.btn:hover {
    background-color: #1a5276;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    body {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
        height: auto;
        flex-direction: row;
        justify-content: space-around;
    }

    .main-content {
        padding: 10px;
    }

    .dashboard-options {
        flex-direction: column;
        align-items: center;
    }

    .btn {
        width: 80%;
    }
}

  </style>
</head>
<body>
  
  <!-- Sidebar Navigation -->
  <div class="sidebar">
    <div>
      <h2>Admin Panel</h2>
      <a href="#" onclick="loadPage('procurement.html')">Procurement Process</a>
      <a href="#" onclick="loadPage('sales.html')">Sales Process</a>
    </div>
    
    <form action="logout.php" method="post">
      <button type="submit" class="logout-btn">Logout</button>
    </form>
  </div>

  <!-- Main Content with iframe -->
  <div class="main-content">
    <h1 class="dashboard-header">Welcome, Admin</h1>
    <iframe id="content-frame" src="procurement.html"></iframe>
  </div>

  <script>
    function loadPage(page) {
        document.getElementById("content-frame").src = page;
    }
  </script>

</body>
</html>
