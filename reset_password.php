<?php
require_once("auth/partials/_head.php");
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
                        <input type="submit" value="Reset Password" class="login100-form-btn">
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
    require_once('auth/partials/_scripts.php');
    ?>
</body>

</html>