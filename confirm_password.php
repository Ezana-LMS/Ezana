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
                        Ezana LMS Confirm Password
                    </span>
                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="pass" placeholder="New Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="pass" placeholder="Confirm New Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="container-login100-form-btn">
                        <input type="submit" value="Change Password" class="login100-form-btn">
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