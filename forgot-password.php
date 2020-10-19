<?php
require_once('configs/codeGen.php');
if (isset($_POST['reset_pwd'])) {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $err = 'Invalid Email';
    }
    $checkEmail = mysqli_query($mysqli, "SELECT `email` FROM `ezanaLMS_Admins` WHERE `email` = '" . $_POST['email'] . "'") or exit(mysqli_error($mysqli));
    if (mysqli_num_rows($checkEmail) > 0) {
        //exit('This email is already being used');
        //Reset Password
        $token = $_POST['token'];
        $email = $_POST['email'];
        $new_pass =$_POST['new_pass'];
        $acc_type = $_POST['acc_type'];
        $status = $_POST['status'];
        $query = "INSERT INTO ezanaLMS_PasswordResets (token, email, new_pass, acc_type, status) VALUES (?,?,?,?,?)";
        $reset = $mysqli->prepare($query);
        $rc = $reset->bind_param('sssss', $token, $email, $new_pass, $acc_type, $status);
        $reset->execute();
        if ($reset) {
            $success = "Password Reset Instructions Sent To Your Email";
            // && header("refresh:1; url=index.php");
        } else {
            $err = "Please Try Again Or Try Later";
        }
    } else {
        $err = "No account with that email";
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
                        <!-- Hidden Values -->
                        <input type="hidden" required name="token" value="<?php echo $checksum; ?>" class="form-control" placeholder="Email">
                        <input type="hidden" required name="new_pass" value="<?php echo $rc; ?>" class="form-control" placeholder="Email">
                        <input type="hidden" required name="acc_type" value="System Admin" class="form-control" placeholder="Email">
                        <input type="hidden" required name="status" value="Pending" class="form-control" placeholder="Email">
                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-12">
                            <button type="submit" name="login" class="btn btn-primary btn-block">Reset Password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <!--  <div class="social-auth-links text-center mb-3">
                    <p>- OR -</p>
                    <a href="#" class="btn btn-block btn-primary">
                        <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                    </a>
                    <a href="#" class="btn btn-block btn-danger">
                        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                    </a>
                </div> -->

                <p class="mb-1">
                    <a href="index.php">I Remembered My Password</a>
                </p>
                <!-- <p class="mb-0">
                    <a href="register.html" class="text-center">Register a new membership</a>
                </p> -->
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>