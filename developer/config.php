<?php
// Function to get the status of login functionality from the configuration file
function isLoginEnabled() {
    // Read the configuration file
    $config = include('config.ini');
    return isset($config['login_enabled']) ? $config['login_enabled'] : false;
}

// Function to enable/disable login functionality
function setLoginStatus($status) {
    // Read the current configuration
    $config = include('config.ini');
    // Update the login status
    $config['login_enabled'] = $status;
    // Write back to the configuration file
    file_put_contents('config.ini', '<?php return ' . var_export($config, true) . ';');
}
?>
