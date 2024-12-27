<?php
require 'vendor/autoload.php'; // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($user_id, $subject, $message) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "finance_tracker";
    $port = 10061;

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    if ($conn->connect_error) {
        return ['success' => false, 'error' => 'Database connection failed: ' . $conn->connect_error];
    }

    // Fetch user email
    $sql = "SELECT email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $email = $row['email'];
        $conn->close();

        // Configure PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'gopilakshmisetty29@gmail.com';
            $mail->Password = 'eguk vdqu axmh wpis';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('gopilakshmisetty29@gmail.com', 'Finance Tracker');
            $mail->addAddress($email); // Recipient
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Mailer Error: ' . $e->getMessage()];
        }
    } else {
        $conn->close();
        return ['success' => false, 'error' => 'User not found'];
    }
}
?>
