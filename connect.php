<?php
$name=$_POST['name'];
$email=$_POST['email'];
$location=$_POST['location'];
$company=$_POST['company'];
$message=$_POST['message'];

//DB connection
$conn= new mysqli('localhost','root','','ocean_form');
if ($conn->connect_error) {
    die('Connection Failed:'. $conn->connect_error);
}else{
    $stmt=$conn->prepare("insert into contact(name, email, location, company, message) 
    values(?,?,?,?,?)");
    $stmt->bind_param("sssss", $name, $email, $location, $company, $message);
    $stmt->execute();
    echo "Message sent succesfully";
    $stmt->close();    
    $conn->close();
}
?>
