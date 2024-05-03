<?php
// Include the configuration file
include('developer/config.php');

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Add custom styles here */
    </style>
</head>
<body class="bg-green-50">
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-center text-green-800">Admin Panel</h1>
    <form method="post" action="" class="flex justify-center mb-6">
        <button type="submit" name="enable_login" class="bg-green-500 text-white px-4 py-2 rounded-md mr-4 hover:bg-green-600 transition-colors">Enable Login</button>
        <button type="submit" name="disable_login" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition-colors">Disable Login</button>
    </form>
    <p class="text-lg text-center text-gray-700">Login Functionality is <?php echo $loginEnabled ? "Enabled" : "Disabled"; ?></p>
</div>
</body>
</html>
