<?php
/*
 * Created on Mon Jun 21 2021
 *
 * The MIT License (MIT)
 * Copyright (c) 2021 MartDevelopers Inc
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 * TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

session_start();
include('../config/config.php');
require_once('../config/codeGen.php');

if (isset($_POST['reset'])) {
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = mysqli_real_escape_string($mysqli, trim($_POST['email']));
    } else {
        $error = 1;
        $err = "Enter  E-mail";
    }
    $query = mysqli_query($mysqli, "SELECT * from `ezanaLMS_Admins` WHERE email='" . $email . "'");
    $num_rows = mysqli_num_rows($query);

    if ($num_rows > 0) {
        /* Mail User Plain Password */
        $mailed_password = $defaultPass;
        /* Hash Password  */
        $hashed_password = sha1(md5($mailed_password));
        $query = "UPDATE ezanaLMS_Admins SET  password =? WHERE  email =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ss', $hashed_password, $email);
        $stmt->execute();
        /* Load Mailer */
        require_once('../config/password_reset_mailer.php');
        if ($stmt && $mail->send()) {
            $success = "Password Reset Instructions Sent To Your Mail";
        } else {
            $err = "Password Reset Failed!, Try again $mail->ErrorInfo";
        }
    } else {
        /* User Does Not Exist */
        $err = "Sorry, User Account With That Email Does Not Exist";
    }
}

/* Load Head */
require_once('partials/auth_head.php');

/* Persisit System Settings On Auth */
$ret = "SELECT * FROM `ezanaLMS_Settings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($sys = $res->fetch_object()) {
?>

    <body style="background-color: #666666;">
        <div class="limiter">
            <div class="container-login100">
                <div class="wrap-login100">
                    <form method="POST" class="login100-form validate-form">
                        <div class="text-center">
                            <img height="150" width="160" src="../Data/SystemLogo/<?php echo $sys->login_logo; ?>" alt="wrapkit">
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
                                <a href="index" class="txt1">
                                    Remembered Password?
                                </a>
                            </div>
                        </div>
                        <div class="container-login100-form-btn">
                            <button type="submit" name="reset" class="login100-form-btn">
                                Reset Password
                            </button>
                        </div>
                    </form>
                    <div class="login100-more" style="background-image: url('../Data/SystemLogo/<?php echo $sys->login_logo; ?>');">
                    </div>
                </div>
            </div>
        </div>
        <?php require_once("partials/auth_scripts.php"); ?>
    </body>
<?php
} ?>

</html>