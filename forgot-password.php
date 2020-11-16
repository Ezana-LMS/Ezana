<?php
session_start();
include('configs/config.php');
require_once('configs/codeGen.php');
if (isset($_POST['reset'])) {
    //prevent posting blank value for first name
    if (isset($_POST['user_fname']) && !empty($_POST['user_fname'])) {
        $user_fname = mysqli_real_escape_string($conn, trim($_POST['user_fname']));
    } else {
        $error = 1;
        $err = "Enter your first name";
    }
    //prevent posting blank value for email
    if (isset($_POST['user_email']) && !empty($_POST['user_email'])) {
        $user_email = mysqli_real_escape_string($conn, trim($_POST['user_email']));
    } else {
        $error = 1;
        $err = "Enter your E-mail";
    }

    $user_fname = $_POST['user_fname'];
    $user_email = $_POST['user_email'];
    // check if the user exists
    $query = mysqli_query($conn, "SELECT * from `user` WHERE user_fname='" . $user_fname . "' and user_email='" . $user_email . "'");
    $num_rows = mysqli_num_rows($query);

    if ($num_rows > 0) // check if alredy liked or not condition
    {
        $n = date('y');
        $new_password = bin2hex(random_bytes($n));
        //Insert Captured information to a database table
        $query = "UPDATE user SET  user_password=? WHERE user_fname =? AND user_email =?";
        $stmt = $conn->prepare($query);
        //bind paramaters
        $rc = $stmt->bind_param('sss', $new_password, $user_fname, $user_email);
        $stmt->execute();


        //declare a varible which will be passed to alert function
        if ($stmt) {
            $_SESSION['user_email'] = $user_email;
            $success = "Password reset done" && header("refresh:1; url=user_check_new_password.php");
        } else {
            $err = "Password reset failed";
        }
    } else  // user does not exist
    {
        $err = "User does not Exist" && header("refresh:1; url=index.php");
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