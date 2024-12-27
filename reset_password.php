<?php
session_start();
header('Content-Type: application/json');

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finance_tracker";
$port = 10061;

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

// Check if the OTP matches the one stored in session
if ($_POST['otp'] == $_SESSION['reset_otp']) {
    $email = $_POST['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Update the user's password in the database
    $sql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_password, $email);

    if ($stmt->execute()) {
        // Clear the OTP from the session after successful reset
        unset($_SESSION['reset_otp'], $_SESSION['reset_email']);
        echo json_encode(['success' => true, 'message' => 'Password reset successful.']);
header("Location: reset_success.html");
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to reset password.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid OTP.']);
}

$stmt->close();
$conn->close();
?>
