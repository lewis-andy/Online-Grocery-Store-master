<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="password"] {
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
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<h2>Reset Password</h2>
<form method="POST" action="">
    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
    <label for="new_password">New Password:</label>
    <input type="password" name="new_password" id="new_password" required><br>
    <label for="confirm_password">Confirm Password:</label>
    <input type="password" name="confirm_password" id="confirm_password" required><br>
    <button type="submit">Submit</button>
</form>
</body>
</html>

<?php
include '../dbcon.php';
include 'email_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate if new password and confirm password match
    if ($new_password !== $confirm_password) {
        echo "<p>Passwords do not match</p>";
        exit; // Stop further execution
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Retrieve email associated with the token
    $query = "SELECT email FROM password_reset WHERE token = '$token' AND expired_at > NOW()";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];

        // Update the password for the corresponding email
        $update_query = "UPDATE user SET password = '$hashed_password' WHERE email = '$email'";
        $update_result = mysqli_query($conn, $update_query);

        if ($update_result) {
            // Delete token from password_reset table
            $delete_query = "DELETE FROM password_reset WHERE token = '$token'";
            mysqli_query($conn, $delete_query);

            echo "<p>Password reset successful</p>";
        } else {
            echo "<p>Error updating password</p>";
        }
    } else {
        echo "<p>Invalid or expired token</p>";
    }
}
?>
