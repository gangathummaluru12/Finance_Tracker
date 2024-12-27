<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finance_tracker";
$port = 10061;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

// Verify email
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Check if password matches
    if (password_verify($password, $user['password'])) {
        // Set session variables and log the user in
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Redirect to the dashboard or home page
        header("Location: dashboard.php");
        exit();
    } else {
        // Password is incorrect
        echo "Invalid password.";
    }
} else {
    // Email not found
    echo "No user found with that email.";
}

$stmt->close();
$conn->close();
?>
