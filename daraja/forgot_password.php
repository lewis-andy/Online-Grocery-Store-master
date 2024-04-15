<?php
// Include database connection and email configuration files
include '../dbcon.php';
include 'email_config.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

// Create a PHPMailer instance
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Instantiate PHPMailer
$mail = new PHPMailer(true);

// Set SMTP server and port
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'lewinskysystems@gmail.com';
$mail->Password = 'ajeu uzbg yohh pixq';
$mail->SMTPSecure = 'tls';

// Set sender and recipient
$mail->setFrom('lewinskysystems@gmail.com', 'Your Name');
$mail->addAddress($_POST['email']); // Add recipient email address

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get email address from POST request
    $email = $_POST['email'];

    // Check if email exists in the database
    $query = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Fetch the user's ID
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['uid'];

        // Generate unique token
        $token = bin2hex(random_bytes(32));

        // Store token in the database with user's ID
        $query = "INSERT INTO password_reset (user_id, email, token, created_at, expired_at) 
                  VALUES ('$user_id', '$email', '$token', NOW(), DATE_ADD(NOW(), INTERVAL 1 HOUR))";
        mysqli_query($conn, $query);

        // Send password reset email
        $reset_link = "http://example.com/reset_password.php?token=$token";
        $subject = "Password Reset";
        $message = "Click the following link to reset your password: $reset_link";

        // Set email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Try to send the email
        if ($mail->send()) {
            echo "<p>Password reset link sent to your email</p>";
        } else {
            echo "<p>Error sending email</p>";
            error_log("Email sending failed: " . $mail->ErrorInfo);
        }
    } else {
        // Return error message if email not found
        echo "<p>Email not found</p>";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
<h2>Forgot Password</h2>
<form method="POST" action="">
    <label>Email:</label>
    <input type="email" name="email" required>
    <button type="submit">Submit</button>
</form>
</body>
</html>
