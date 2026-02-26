<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(403);
    exit("Forbidden");
}

// Sanitize inputs
$name    = htmlspecialchars(trim($_POST['name'] ?? ''));
$email   = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$service = htmlspecialchars(trim($_POST['service'] ?? ''));
$message = htmlspecialchars(trim($_POST['message'] ?? ''));

if (!$name || !$email || !$service || !$message) {
    http_response_code(400);
    exit("All fields are required");
}
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.yourmailserver.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info@yourdomain.com';
    $mail->Password   = 'EMAIL_PASSWORD';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('info@yourdomain.com', 'Chandramari Group Website');
    $mail->addAddress('admin@yourdomain.com');

    $mail->isHTML(true);
    $mail->Subject = "New Contact Enquiry - $service";
    $mail->Body = "
        <b>Name:</b> $name <br>
        <b>Email:</b> $email <br>
        <b>Service:</b> $service <br>
        <b>Message:</b><br>$message
    ";

    $mail->send();
    echo "success";
} catch (Exception $e) {
    http_response_code(500);
    echo "Mailer Error";
}
