<a href="logout.php">
    <button style="
        background-color: red; 
        color: white; 
        border: none; 
        padding: 10px 15px; 
        cursor: pointer; 
        font-size: 16px;
    ">Logout</button>
</a>


<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}
?>