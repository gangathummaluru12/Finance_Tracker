<?php
session_start();
require 'send_email.php';
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finance_tracker";
$port=10061;

$conn = new mysqli($servername, $username, $password, $dbname,$port);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection error']);
    exit();
}

$transaction_id = $_POST['id'];
$user_id = $_SESSION['user_id'];

$sql = "DELETE FROM expenses WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $transaction_id, $user_id);

if ($stmt->execute()) {

$incomeTotal = getTotalAmount($conn, $user_id, 'income');
        $expenseTotal = getTotalAmount($conn, $user_id, 'expense');

        // Prepare email subject and message
        $subject = 'Transaction Deleted';
        $message = "A transaction has been deleted from your account.\n\nDetails:\nType: {$transaction['type']}\nDescription: {$transaction['description']}\nAmount: $ {$transaction['amount']}\nDate: {$transaction['date']}\n\nUpdated Balance:\nIncome: $$incomeTotal\nExpenses: $$expenseTotal";

        // Send email
        sendEmail($user_id, $subject, $message);
echo json_encode(['success' => true, 'message' => 'Transaction deleted successfully']);
 
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete transaction']);
}

$stmt->close();
$conn->close();
function getTotalAmount($conn, $user_id, $type) {
    $sql = "SELECT SUM(amount) as total FROM expenses WHERE user_id = ? AND type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $type);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}
