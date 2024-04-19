<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        form {
            max-width: 400px;
            margin: 0 auto;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .reset-link {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .reset-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<h2>Forgot Password</h2>
<form method="POST" action="">
    <label>Email:</label>
    <input type="email" name="email" required><br>
    <button type="submit">Submit</button>
</form>
<?php
// PHP code for sending email and processing form submission
// Include database connection and email configuration files
include '../dbcon.php';
//include '../header.php';
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

// Set sender
$mail->setFrom('lewinskysystems@gmail.com', 'Your Name');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get email address from POST request
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    // Check if email is empty
    if (empty($email)) {
        echo "<p>Email field is empty</p>";
        exit; // Stop further execution
    }

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
        $reset_link = "http://localhost/Online-Grocery-Store-master/daraja/reset_password.php?token=$token";
        $subject = "Password Reset";
        $message = "Click the following link to reset your password: <a class='reset-link' href='$reset_link'>Click here</a>";

        // Set recipient
        $mail->addAddress($email);

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
</body>
</html>
