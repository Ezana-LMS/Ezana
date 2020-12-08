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

<body>
    <div class="main-wrapper">
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative" style="background:url(../assets/images/big/auth-bg.jpg) no-repeat center center;">
            <div class="auth-box row">
                <div class="col-lg-7 col-md-5 modal-bg-img img-thumbnail" style="background-image: url(public/dist/img/logo.png);">
                </div>
                <div class="col-lg-5 col-md-7 bg-white">
                    <div class="p-3">
                        <div class="text-center">
                            <img height="100" width="100" src="public/dist/img/logo.png" alt="wrapkit">
                        </div>
                        <h2 class="mt-3 text-center">Log In</h2>
                        <p class="text-center">Enter Your Email Address And Password</p>
                        <form method="post" class="mt-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="uname">Email</label>
                                        <input class="form-control" id="uname" type="email" name="email">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="pwd">Password</label>
                                        <input class="form-control" id="pwd" name="password" type="password">
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <button type="submit" name="login" class="btn btn-block btn-dark">Sign In</button>
                                </div>
                                <div class="col-lg-12 text-center mt-5">
                                    Forgot Password? <a href="reset_password.php" class="text-primary">Reset Password</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("public/partials/_authscripts.php"); ?>
</body>

</html>