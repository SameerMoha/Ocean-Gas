<?php 
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$password = "";
$dbname = "myapp_db";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($input['order_id']) || !is_numeric($input['order_id'])) {
        echo json_encode(["error" => "Invalid order ID"]);
        exit;
    }

    $order_id = intval($input['order_id']);

    // Begin Transaction
    $conn->begin_transaction();

    try {
        // Fetch order details from order_items for the given order
        $query = "SELECT cylinder_id, quantity FROM order_items WHERE order_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order_items = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Update cylinder stock by reducing quantity for each order item
        foreach ($order_items as $item) {
            $updateQuery = "UPDATE cylinder SET quantity = quantity - ? WHERE cylinder_id = ? AND quantity >= ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("iii", $item['quantity'], $item['cylinder_id'], $item['quantity']);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                throw new Exception("Not enough stock for Cylinder ID: " . $item['cylinder_id']);
            }
            $stmt->close();
        }

        // Move order details to approved_orders table using an INSERT ... SELECT query
        $moveQuery = "INSERT INTO approved_orders SELECT * FROM customer_details WHERE order_id = ?";
        $stmt = $conn->prepare($moveQuery);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();

        // Delete the order from customer_details (removing it from pending orders)
        $deleteQuery = "DELETE FROM customer_details WHERE order_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();

        // Commit Transaction
        $conn->commit();
        echo json_encode(["success" => true, "message" => "Order approved successfully."]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}

$conn->close();
?>
