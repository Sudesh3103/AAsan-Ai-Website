<?php
require_once 'mail/phpmailer/Exception.php';
require_once 'mail/phpmailer/PHPMailer.php';
require_once 'mail/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Sanitize inputs
    $fname   = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_SPECIAL_CHARS);
    $lname   = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_SPECIAL_CHARS);
    $phone   = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
    $email   = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);

    $fullName = trim($fname . ' ' . $lname);

    // Validate required fields
    if (empty($fname) || empty($lname) || empty($email) || empty($phone)) {
        echo "<script>
            alert('Please fill all required fields.');
            window.history.back();
        </script>";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sudeshkalokhe3103@gmail.com'; // your email
        $mail->Password   = 'tckk mnvi hwmy ezkt';  // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Email settings
        $mail->setFrom('sudeshkalokhe3103@gmail.com', 'Website Contact Form');
        $mail->addAddress('sudeshkalokhe3103@gmail.com', 'Website Admin');
        $mail->addReplyTo($email, $fullName);

        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Submission';

        // Email body
        $mail->Body = "
        <html>
        <body style='font-family: Arial, sans-serif; background:#f4f6f8; padding:20px;'>
            <div style='max-width:600px;margin:auto;background:#fff;border-radius:10px;padding:25px;'>
                <h2 style='color:#2c3e50;'>New Contact Form Submission</h2>
                <p><strong>Name:</strong> {$fullName}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Phone:</strong> {$phone}</p>
                <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
                <p style='font-size:12px;color:#777;'>Received on " . date('F j, Y, g:i A') . "</p>
            </div>
        </body>
        </html>";

        // Plain text fallback
        $mail->AltBody = "New Contact Form Submission\n
        Name: {$fullName}
        Email: {$email}
        Phone: {$phone}
        Message: {$message}
        Date: " . date('F j, Y, g:i A');

        $mail->send();

        echo "<script>
            alert('Thank you for contacting us. We will get back to you soon.');
            window.location.href = 'index.html';
        </script>";

    } catch (Exception $e) {
        error_log($mail->ErrorInfo);
        echo "<script>
            alert('Message could not be sent. Please try again later.');
            window.history.back();
        </script>";
    }

} else {
    header("Location: index.html");
    exit;
}
?>
