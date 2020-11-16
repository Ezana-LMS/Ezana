<?php
session_start();
include('configs/config.php');
require_once('configs/codeGen.php');
if (isset($_POST['reset_pwd'])) {
    $error = 0;
    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $new_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['new_password']))));
    } else {
        $error = 1;
        $err = "New Password Cannot Be Empty";
    }
    if (isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])) {
        $confirm_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['confirm_password']))));
    } else {
        $error = 1;
        $err = "Confirmation Password Cannot Be Empty";
    }

    if (!$error) {
        $email = $_SESSION['email'];
        $sql = "SELECT * FROM  ezanaLMS_Admins  WHERE email = '$email'";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($new_password != $confirm_password) {
                $err = "Password Does Not Match";
            } else {
                $email = $_SESSION['email'];
                $new_password  = sha1(md5($_POST['new_password']));
                $query = "UPDATE ezanaLMS_Admins SET  password =? WHERE email =?";
                $stmt = $mysqli->prepare($query);
                $rc = $stmt->bind_param('ss', $new_password, $email);
                $stmt->execute();
                if ($stmt) {
                    $success = "Password Changed" && header("refresh:1; url=index.php");
                } else {
                    $err = "Please Try Again Or Try Later";
                }
            }
        }
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
                <?php

                $email  = $_SESSION['email'];
                $ret = "SELECT * FROM  ezanaLMS_Admins  WHERE email = '$email'";
                $stmt = $mysqli->prepare($ret);
                $stmt->execute(); //ok
                $res = $stmt->get_result();
                while ($row = $res->fetch_object()) {
                ?>
                    <p class="login-box-msg">
                        <span class="badge badge-success">Token: <?php echo $row->password; ?></span>
                        <br>
                        Please <?php echo $row->name; ?>
                        <br>
                        Change Your Password
                    </p>
                <?php
                } ?>
                <form method="post">
                    <div class="input-group mb-3">
                        <input type="password" required name="new_password" class="form-control" placeholder="New Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" required name="confirm_password" class="form-control" placeholder="Confirm New Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-12">
                            <button type="submit" name="reset_pwd" class="btn btn-primary btn-block">Reset Password</button>
                        </div>
                    </div>
                </form>
                <!-- <p class="mb-1">
                    <a href="index.php">I Remembered My Password</a>
                </p> -->
                
            </div>
        </div>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>