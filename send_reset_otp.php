<?php
session_start();
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

$email = $_POST['email'];
$otp = rand(100000, 999999);

// Store OTP and email in session
$_SESSION['reset_otp'] = $otp;
$_SESSION['reset_email'] = $email;

try {
    // Set up PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'gopilakshmisetty29@gmail.com'; // Your email
    $mail->Password = 'eguk vdqu axmh wpis'; // Your email password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('gopilakshmisetty29@gmail.com', 'Finance Tracker');
    $mail->addAddress($email);
    $mail->Subject = 'Your OTP Code for Password Reset';
    $mail->Body = "Your OTP code is: $otp. Please use this code to reset your password.";

    if ($mail->send()) {
        echo json_encode(['success' => true, 'message' => 'OTP sent to your email.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send OTP email.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
