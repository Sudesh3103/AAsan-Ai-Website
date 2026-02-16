<?php
require_once 'mail/phpmailer/Exception.php';
require_once 'mail/phpmailer/PHPMailer.php';
require_once 'mail/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobTitle = filter_input(INPUT_POST, 'job_title', FILTER_SANITIZE_SPECIAL_CHARS);
    $name = filter_input(INPUT_POST, 'applicantName', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'applicantEmail', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'applicantPhone', FILTER_SANITIZE_SPECIAL_CHARS);
    $resumeText = filter_input(INPUT_POST, 'applicantResume', FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($jobTitle) || empty($name) || empty($email) || empty($phone)) {
        echo 'error';
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sudeshkalokhe3103@gmail.com';
        $mail->Password = 'tckk mnvi hwmy ezkt';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('sudeshkalokhe3103@gmail.com', 'Career Application');
        $mail->addAddress('sudeshkalokhe3103@gmail.com', 'Website Admin');
        $mail->addReplyTo($email, $name);

        if (isset($_FILES['resumeFile']) && $_FILES['resumeFile']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['resumeFile']['tmp_name'];
            $fileName = basename($_FILES['resumeFile']['name']);
            $fileSize = $_FILES['resumeFile']['size'];
            $fileType = mime_content_type($fileTmpPath);

            $allowedExtensions = ['pdf', 'doc', 'docx'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if ($fileSize > 0 && $fileSize <= 5 * 1024 * 1024 && in_array($fileExtension, $allowedExtensions, true)) {
                $mail->addAttachment($fileTmpPath, $fileName);
            }
        }

        $mail->isHTML(true);
        $mail->Subject = 'New Career Application: ' . $jobTitle;

        $mail->Body = "
        <html>
        <body style='font-family: Arial, sans-serif; background:#f4f6f8; padding:20px;'>
            <div style='max-width:600px;margin:auto;background:#fff;border-radius:10px;padding:25px;'>
                <h2 style='color:#2c3e50;'>New Career Application</h2>
                <p><strong>Position:</strong> {$jobTitle}</p>
                <p><strong>Name:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Phone:</strong> {$phone}</p>
                <p><strong>Resume Details:</strong><br>" . nl2br($resumeText) . "</p>
                <p style='font-size:12px;color:#777;'>Received on " . date('F j, Y, g:i A') . "</p>
            </div>
        </body>
        </html>";

        $mail->AltBody = "New Career Application\n
        Position: {$jobTitle}
        Name: {$name}
        Email: {$email}
        Phone: {$phone}
        Resume Details: {$resumeText}
        Date: " . date('F j, Y, g:i A');

        $mail->send();
        echo 'success';
    } catch (Exception $e) {
        error_log($mail->ErrorInfo);
        echo 'error';
    }
} else {
    header('Location: career.html');
    exit;
}
?>
