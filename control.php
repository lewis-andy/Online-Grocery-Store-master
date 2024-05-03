<?php
// Include the configuration file
include('dbcon.php');

// Handle form submission to enable/disable login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['enable_login'])) {
        setLoginStatus(true);
    } elseif (isset($_POST['disable_login'])) {
        setLoginStatus(false);
    }
}

// Get the current status of login functionality
$loginEnabled = isLoginEnabled();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>
<h1>Admin Panel</h1>
<form method="post" action="">
    <input type="submit" name="enable_login" value="Enable Login">
    <input type="submit" name="disable_login" value="Disable Login">
</form>
<p>Login Functionality is <?php echo $loginEnabled ? "Enabled" : "Disabled"; ?></p>
</body>
</html>
