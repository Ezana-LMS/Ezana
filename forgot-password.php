<?php
session_start();
include('configs/config.php');
require_once('configs/codeGen.php');




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