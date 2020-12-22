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
        $err = "Email Does Not Exist";
    }
}

include __DIR__ . "/public/partials/_authhead.php"
?>

<body style="background-color: #666666;">
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form method="POST" class="login100-form validate-form">
                    <div class="text-center">
                        <img height="150" width="160" src="public/dist/img/logo.png" alt="wrapkit">
                    </div>
                    <h2 class="mt-3 text-center">Reset Password</h2>
                    <p class="text-center">Enter Your Address To Reset Password</p>
                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: user@mail.com">
                        <input class="input100" type="email" name="email">
                        <span class="focus-input100"></span>
                        <span class="label-input100">Email</span>
                    </div>
                    <div class="flex-sb-m w-full p-t-3 p-b-32">
                        <div>
                            <a href="index.php" class="txt1">
                                Remembered Password?
                            </a>
                        </div>
                    </div>
                    <div class="container-login100-form-btn">
                        <button type="submit" name="reset_pwd" class="login100-form-btn">
                            Reset Password
                        </button>
                    </div>
                </form>
                <div class="login100-more" style="background-image: url('public/dist/img/logo.png');">
                </div>
            </div>
        </div>
    </div>
    <?php require_once("public/partials/_authscripts.php"); ?>
</body>

</html>