<?php
session_start();
header('Content-Type: application/json');

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finance_tracker";
$port = 10061;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

// Get user inputs
$email = $_SESSION['email']; // Retrieve email from session
$input_otp = $_POST['otp'];
$username = $_POST['username'];
$user_password = $_POST['password'];

try {
    // Check if OTP is valid and not expired
    if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_expires'])) {
        echo json_encode(['success' => false, 'message' => 'OTP session expired. Please request a new OTP.']);
        exit();
    }

    if ($_SESSION['otp'] !== $input_otp) {
        echo json_encode(['success' => false, 'message' => 'Invalid OTP.']);
        exit();
    }

    if (new DateTime() > new DateTime($_SESSION['otp_expires'])) {
        echo json_encode(['success' => false, 'message' => 'OTP has expired.']);
        exit();
    }

    // If OTP is valid, store user details in the database
    $hashed_password = password_hash($user_password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        // Clear OTP session data after successful registration
        unset($_SESSION['otp'], $_SESSION['otp_expires'], $_SESSION['email']);
        
        echo json_encode(['success' => true, 'message' => 'User registered successfully.']);
    } else {
        throw new Exception("Failed to register user.");
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>
