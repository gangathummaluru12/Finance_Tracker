<?php
session_start();
require 'send_email.php';
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finance_tracker";
$port=10061;

$conn = new mysqli($servername, $username, $password, $dbname,$port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

$description = $data['description'];
$amount = $data['amount'];
$type = $data['type'];
$date = $data['date'];
$category = $data['category'];

$sql = "INSERT INTO expenses (user_id, description, category, amount, type, date) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issdss", $user_id, $description,$category, $amount, $type, $date);

if ($stmt->execute()) {
    $last_id = $stmt->insert_id;

    $incomeTotal = getTotalAmount($conn, $user_id, 'income');
    $expenseTotal = getTotalAmount($conn, $user_id, 'expense');
$subject = 'New Transaction Added';
    $message = "A new transaction has been added to your account.\n\nDetails:\nType: $type\nDescription: $description\nAmount: $$amount\nDate: $date\n\nCurrent Balance:\nIncome: $$incomeTotal\nExpenses: $$expenseTotal";

    // Send email
    $emailStatus = sendEmail($user_id, $subject, $message);
    
    echo json_encode([
        'success' => true,
        'id' => $last_id,
        'incomeTotal' => $incomeTotal,
        'expenseTotal' => $expenseTotal
    ]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
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
?>
