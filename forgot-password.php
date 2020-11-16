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
        $err = "Email Does Not Exist" && header("refresh:1; url=forgot-password.php");
    }
}


require_once('partials/_head.php');
?>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <div class="login-logo">
                    <img height="150" width="150" src="dist/img/logo.jpeg" alt="">
                </div>
                <p class="login-box-msg">Enter Your Email To Reset Password</p>

                <form method="post">
                    <div class="input-group mb-3">
                        <input type="email" required name="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-12">
                            <button type="submit" name="reset_pwd" class="btn btn-primary btn-block">Reset Password</button>
                        </div>
                    </div>
                </form>



                <p class="mb-1">
                    <a href="index.php">I Remembered My Password</a>
                </p>
                <!-- <p class="mb-0">
                    <a href="register.html" class="text-center">Register a new membership</a>
                </p> -->
            </div>
        </div>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>