<?php
session_start();
include('configs/config.php');
require_once('configs/codeGen.php');
if (isset($_POST['reset_pwd'])) {
    //prevent posting blank value for first name
    $error = 0;
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = mysqli_real_escape_string($mysqli, trim($_POST['email']));
    } else {
        $error = 1;
        $err = "Enter Your Email";
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $err = 'Invalid Email';
    }
    $checkEmail = mysqli_query($mysqli, "SELECT `email` FROM `ezanaLMS_Admins` WHERE `email` = '" . $_POST['email'] . "'") or exit(mysqli_error($mysqli));
    if (mysqli_num_rows($checkEmail) > 0) {

        $n = date('y');
        $new_password = bin2hex(random_bytes($n));
        //Insert Captured information to a database table
        $query = "UPDATE ezanaLMS_Admins SET  password=? WHERE email =?";
        $stmt = $mysqli->prepare($query);
        //bind paramaters
        $rc = $stmt->bind_param('ss', $new_password, $email);
        $stmt->execute();

        //declare a varible which will be passed to alert function
        if ($stmt) {
            $_SESSION['email'] = $email;
            $success = "Confim Your Password" && header("refresh:1; url=confirm_password.php");
        } else {
            $err = "Password reset failed";
        }
    } else  // user does not exist
    {
        $err = "Email Does Not Exist" ;
    }
}



include __DIR__ . "/public/partials/_authhead.php"
?>

<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="auth/images/logo.png" alt="IMG">
                </div>
                <form method="post" class="login100-form validate-form">
                    <span class="login100-form-title">
                        Ezana LMS - Reset Password
                    </span>
                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                        <input class="input100" type="text" name="email" placeholder="Email">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="container-login100-form-btn">
                        <input type="submit" name="reset_pwd" value="Reset Password" class="login100-form-btn">
                    </div>
                    <div class="text-center p-t-12">
                        <span class="txt1">
                            Remembered
                        </span>
                        <a class="txt2" href="index.php">
                            Username / Password?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    include __DIR__ . "/public/partials/_authscripts.php"
    ?>
</body>

</html>