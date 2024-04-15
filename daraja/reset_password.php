<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
<h2>Reset Password</h2>
<form method="POST" action="">
    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
    <label for="new_password">New Password:</label>
    <input type="password" name="new_password" id="new_password" required>
    <button type="submit">Submit</button>
</form>
</body>
</html>

<?php
include '../dbcon.php';
include'email_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    $query = "SELECT * FROM password_reset WHERE token = '$token' AND expired_at > NOW()";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];

        $update_query = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
        mysqli_query($conn, $update_query);

        // Delete token from password_reset table
        $delete_query = "DELETE FROM password_reset WHERE token = '$token'";
        mysqli_query($conn, $delete_query);

        echo "<p>Password reset successful</p>";
    } else {
        echo "<p>Invalid or expired token</p>";
    }
}
?>
