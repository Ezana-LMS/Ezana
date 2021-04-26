<?php
/*
 * Created on Mon Apr 26 2021
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
        $sql = "SELECT * FROM  ezanaLMS_Lecturers  WHERE email = '$email'";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($new_password != $confirm_password) {
                $err = "Password Does Not Match";
            } else {
                $email = $_SESSION['email'];
                $new_password  = sha1(md5($_POST['new_password']));
                $query = "UPDATE ezanaLMS_Lecturers SET  password =? WHERE email =?";
                $stmt = $mysqli->prepare($query);
                $rc = $stmt->bind_param('ss', $new_password, $email);
                $stmt->execute();
                if ($stmt && $mail->send()) {
                    $success = "Password Changed" && header("refresh:1; url=lec_index.php");
                } else {
                    $err = "Please Try Again Or Try Later";
                }
            }
        }
    }
}
include __DIR__ . "/public/partials/_authhead.php";
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
                            <img height="150" width="160" src="public/dist/img/<?php echo $sys->logo; ?>" alt="wrapkit">
                        </div>
                        <h2 class="mt-3 text-center">Password Reset</h2>
                        <?php
                        $email  = $_SESSION['email'];
                        $ret = "SELECT * FROM  ezanaLMS_Lecturers  WHERE email = '$email'";
                        $stmt = $mysqli->prepare($ret);
                        $stmt->execute(); //ok
                        $res = $stmt->get_result();
                        while ($row = $res->fetch_object()) {
                        ?>
                            <h5 class="mt-3 text-center">
                                <span class="badge badge-success">
                                    <small>
                                        Token: <?php echo $row->password; ?>
                                    </small>
                                </span>
                            </h5>
                            <p class="text-center"><?php echo $row->name; ?> Please Enter New Password</p>
                        <?php
                        } ?> <div class="wrap-input100 validate-input" data-validate="Password is required">
                            <input class="input100" type="password" name="new_password">
                            <span class="focus-input100"></span>
                            <span class="label-input100">New Password</span>
                        </div>
                        <div class="wrap-input100 validate-input" data-validate="Password is required">
                            <input class="input100" type="password" name="confirm_password">
                            <span class="focus-input100"></span>
                            <span class="label-input100">Confirm Password</span>
                        </div>
                        <div class="container-login100-form-btn">
                            <button type="submit" name="reset_pwd" class="login100-form-btn">
                                Confirm Password
                            </button>
                        </div>
                    </form>
                    <div class="login100-more" style="background-image: url('public/dist/img/<?php echo $sys->logo; ?>');">
                    </div>
                </div>
            </div>
        </div>
        <?php require_once("public/partials/_authscripts.php"); ?>
    </body>
<?php
} ?>

</html>