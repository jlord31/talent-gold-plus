<?php

// Load Composer's autoloader
require __DIR__ . '\vendor\autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$emailTo = $_ENV['TO'];
$username = $_ENV['USERNAME'];
$password = $_ENV['PASSWORD'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$EmailTo = $emailTo;
$Subject = "New Message From Talent Gold+ Website";

$success = false;
$MSG = array();
#$name = $email = $phone = $message = $subject = null;
$fields = array(
    'name' => "Full name is required ",
    'email' => "Email is required ",
    'phone' => "Phone is required ",
    'message' => "Message is required "
);

foreach($fields as $key => $e_message) {
    if (empty($_POST[$key])) {
        $MSG[] = $e_message;
    } else {
        $$key = $_POST[$key];
    }
}

// prepare email body text
$Body = null;
$Body .= "<p><b>Name:</b> {$name}</p>";
$Body .= "<p><b>Email:</b> {$email}</p>";
$Body .= "<p><b>Phone:</b> {$phone}</p>";
$Body .= "<p><b>Message:</b> </p><p>{$message}</p>";

if (!empty($MSG)) {
    $MSG[] = "Something went wrong :(";
}

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = 0; // Disable verbose debug output
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->Username   = $username;
    $mail->Password   = $password;

    // Recipients
    $mail->setFrom($email, $name);
    $mail->addAddress($EmailTo);

    // Content
    $mail->isHTML(true);
    $mail->Subject = $Subject;
    $mail->Body    = $Body;

    $mail->send();
    $success = true;
    $MSG[] = "Email sucessfully sent";
} catch (Exception $e) {
    $MSG[] = "Mailer Error: {$mail->ErrorInfo}";
}

// Ensure no output before the JSON response
header('Content-Type: application/json');
echo json_encode(array(
    'success' => $success,
    'message' => $MSG
));

die();

?>



