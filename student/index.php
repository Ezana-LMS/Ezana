<?php
/*
 * Created on Thu Jul 01 2021
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
require_once('../config/config.php');

if (isset($_POST['login'])) {
    /* Secure Login */
    $error = 0;
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = mysqli_real_escape_string($mysqli, trim($_POST['email']));
    } else {
        $error = 1;
        $err = "Email Cannot  Be Empty";
    }
    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['password']))));
    } else {
        $error = 1;
        $err = "Password Cannot  Be Empty";
    }
    if (!$error) {
        $ret = mysqli_query($mysqli, "SELECT * FROM ezanaLMS_Students WHERE email='$email'  AND password='$password'");
        $num = mysqli_fetch_array($ret);
        if ($num > 0) {
            /* Load Sessions */
            $_SESSION['id'] = $num['id'];
            $_SESSION['email'] = $email;

            /* Load Login Logs */
            $uip = $_SERVER['REMOTE_ADDR'];
            $User_Rank = 'Student';
            $loginTime = date('Y-m-d');

            /* Persist Auth Logs */
            mysqli_query($mysqli, "INSERT INTO ezanaLMS_UserLog(user_id, name, ip, User_Rank, loginTime) values('" . $_SESSION['id'] . "','" . $_SESSION['email'] . "','$uip', '$User_Rank', '$loginTime')");
            $extra = "dashboard";
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            header("location:http://$host$uri/$extra");
            exit();
        } else {
            $err = "Invalid Authentication Credentials Or User Permission";
        }
    }
}

/* Load Head*/
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
                            <img height="150" width="160" src="../Data/SystemLogo/<?php echo $sys->logo; ?>" alt="wrapkit">
                        </div>
                        <h2 class="mt-3 text-center">Log In</h2>
                        <div class="wrap-input100 validate-input" data-validate="Valid email is required: user@mail.com">
                            <input class="input100" type="email" name="email">
                            <span class="focus-input100"></span>
                            <span class="label-input100">Student Email</span>
                        </div>
                        <div class="wrap-input100 validate-input" data-validate="Password is required">
                            <input class="input100" type="password" name="password">
                            <span class="focus-input100"></span>
                            <span class="label-input100">Password</span>
                        </div>
                        <div class="flex-sb-m w-full p-t-3 p-b-32">
                            <div>
                                <a href="forgot_password" class="txt1">
                                    Forgot Password?
                                </a>
                            </div>
                        </div>
                        <div class="container-login100-form-btn">
                            <button type="submit" name="login" class="login100-form-btn">
                                Login
                            </button>
                        </div>
                    </form>
                    <div class="login100-more" style="background-image: url('../Data/SystemLogo/<?php echo $sys->logo; ?>');">
                    </div>
                </div>
            </div>
        </div>
        <?php require_once("partials/auth_scripts.php"); ?>
    </body>
<?php
} ?>

</html>