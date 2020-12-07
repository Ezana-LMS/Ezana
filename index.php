<?php
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
                        Ezana LMS - Login
                    </span>
                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                        <input class="input100" type="text" name="email" placeholder="Email">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="pass" placeholder="Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="container-login100-form-btn">
                        <input type="submit" value="Login" class="login100-form-btn">
                    </div>
                    <div class="text-center p-t-12">
                        <span class="txt1">
                            Forgot
                        </span>
                        <a class="txt2" href="reset_password.php">
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