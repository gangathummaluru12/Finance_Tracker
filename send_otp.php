<?php
session_start();
require 'vendor/autoload.php'; // Assuming you're using PHPMailer for email

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// Get email from the form submission
$email = $_POST['email'];

try {
    // Generate OTP and expiration time
    $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $otp_expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    // Store OTP and expiration in the session (or cache if using a distributed setup)
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expires'] = $otp_expires;
    $_SESSION['email'] = $email; // Store email for verification later

    // Configure PHPMailer for sending email
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'gopilakshmisetty29@gmail.com';
    $mail->Password = 'eguk vdqu axmh wpis';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('gopilakshmisetty29@gmail.com', 'Finance Tracker');
    $mail->addAddress($email);
    $mail->Subject = 'Your OTP Code';
    $mail->Body = "Your OTP code is $otp. It will expire in 10 minutes.";

    if ($mail->send()) {
        echo json_encode(['success' => true, 'message' => 'OTP sent to your email.']);
    } else {
        throw new Exception("Failed to send OTP email.");
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
