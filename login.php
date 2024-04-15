<?php
include 'dbcon.php';
session_start();
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $page = $_GET['page'].'.php';
} else {
    $page = 'index.php';
}

if (isset($_SESSION['USER_ID']) && !empty($_SESSION['USER_ID'])) {
    header("Location: $page");
    exit;
}
$msg = '';

if (isset($_POST['submit'])) {
    $n = $_POST['Name'];
    $m = $_POST['Mobile'];
    $a1 = $_POST['Address'];
    $c = $_POST['City'];
    $g = $_POST['Gn'];
    $e = $_POST['Email']; // Added email field
    $u = $_POST['Username'];
    $p = $_POST['Password'];
    // Hash the password
    $hashed_password = password_hash($p, PASSWORD_DEFAULT);

    // Check if the email is already registered
    $check_email_sql = "SELECT * FROM user WHERE email = '$e'";
    $check_email_result = $conn->query($check_email_sql);
    if ($check_email_result->num_rows > 0) {
        $msg = 'Email already exists';
    } else {
        // Insert user data into the database
        $sql = "INSERT INTO user(name, mobile, address1, gender, email, username, password) 
        VALUES ('$n', '$m' ,'$a1','$g','$e','$u','$hashed_password')";
        if ($conn->query($sql)) {
            header('Location:login.php');
            exit;
        } else {
            echo 'Error: '.$sql.'<br>'.$conn->error;
        }
    }
}

if (isset($_POST['login'])) {
    $un = $_POST['User'];
    $pw = $_POST['Pass'];
    $sql = "SELECT uid, password FROM user WHERE username = '$un'";
    $result = $conn->query($sql);
    if ($result->num_rows) {
        $row = $result->fetch_assoc();
        if ($row['password'] != $pw) {
            $msg = 'Wrong Password';
        } else {
            $_SESSION['USER_ID'] = $row['uid'];
            $_SESSION['USER_NAME'] = $un;
            header("Location: $page");
            exit;
        }
    } else {
        $msg = 'Wrong Username';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Grocery Store</title>
</head>
<body>
<?php include 'header.php' ?>
<div class="w3l_banner_nav_right">
    <!-- login -->
    <div class="w3_login">
        <h3>Sign In & Sign Up</h3>
        <div class="w3_login_module">
            <div class="module form-module">
                <div class="toggle"><i class="fa fa-times fa-pencil"></i>
                    <div class="tooltip">Click Me</div>
                </div>

                <div class="form">
                    <h2>Login to your account</h2>
                    <span class="text-danger"><?=$msg?></span>
                    <form action="" method="post">
                        <input type="text" name="User" placeholder="Username" required>
                        <input type="password" name="Pass" placeholder="Password" required>
                        <input type="submit" value="Login" name="login">
                    </form>
                    <div class="cta" style="margin-top: 18px">
                        <a href="forgot_password.php">Forgot your password?</a> <!-- Added forgot password link -->
                    </div>
                </div>

                <div class="form">
                    <h2>Create an account</h2>
                    <span class="text-danger"><?=$msg?></span>
                    <form action="" method="post">
                        <input type="text" name="Name" placeholder="Name" required>
                        <input type="text" name="Mobile" placeholder="Mobile No" required pattern="[0-9]{10}" title="must be 10 characters">
                        <input type="text" name="Address" placeholder="Address" required>
                        <input type="text" name="City" placeholder="City" style="text-align:center:">
                        <input type="email" name="Email" placeholder="Email" required> <!-- Added email field -->
                        <input type="radio" name="Gn" placeholder="" required>Male
                        <input type="radio" name="Gn" placeholder="" required>Female
                        <input type="text" name="Username" placeholder="Username" required>
                        <input type="password" name="Password" placeholder="Password" required pattern=".{8,}" title="Password must be at least 8 characters long">
                        <input type="submit" value="Register" name="submit">
                    </form>
                </div>
            </div>
        </div>
			<script>
				$('.toggle').click(function(){
				  // Switches the Icon
				  $(this).children('i').toggleClass('fa-pencil');
				  // Switches the forms  
				  $('.form').animate({
					height: "toggle",
					'padding-top': 'toggle',
					'padding-bottom': 'toggle',
					opacity: "toggle"
				  }, "slow");
				});
			</script>
		</div>
        
<!-- //login -->
		</div>
		<div class="clearfix"></div>
	</div>
<?php include 'footer.php' ?>
</body>
</html>