<?php
session_start();
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

$user_id = $_SESSION['user_id'];

$sql = "SELECT description, SUM(amount) AS total_amount FROM transactions WHERE user_id = ? AND type = 'expense' GROUP BY description";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['description'];
    $data[] = $row['total_amount'];
}

$stmt->close();
$conn->close();

echo json_encode(['success' => true, 'labels' => $labels, 'data' => $data]);


