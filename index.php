<?php
session_start();
include('configs/config.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = sha1(md5($_POST['password'])); //double encrypt to increase security
    $stmt = $mysqli->prepare("SELECT email, password, id, name  FROM ezanaLMS_Admins  WHERE email =? AND password =?");
    $stmt->bind_param('ss', $email, $password); //bind fetched parameters
    $stmt->execute(); //execute bind 
    $stmt->bind_result($email, $password, $id, $name); //bind result
    $rs = $stmt->fetch();
    $_SESSION['id'] = $id;
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $name;
    if ($rs) {
        header("location:dashboard.php");
    } else {
        $err = "Access Denied Please Check Your Credentials";
    }
}
include __DIR__ . "/public/partials/_authhead.php"
?>

<body>
    <div class="main-wrapper">
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative" style="background:url(../assets/images/big/auth-bg.jpg) no-repeat center center;">
            <div class="auth-box row">
                <div class="col-lg-7 col-md-5 modal-bg-img img-thumbnail"  style="background-image: url(public/dist/img/logo.png);">
                </div>
                <div class="col-lg-5 col-md-7 bg-white">
                    <div class="p-3">
                        <div class="text-center">
                            <img height="100" width="110" src="public/dist/img/logo.png" alt="wrapkit">
                        </div>
                        <h2 class="mt-3 text-center">Log In</h2>
                        <p class="text-center">Enter Your Email Address And Password</p>
                        <form method="post" class="mt-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="uname">Email</label>
                                        <input class="form-control" id="uname" type="email" name="email">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="pwd">Password</label>
                                        <input class="form-control" id="pwd" name="password" type="password">
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <button type="submit" name="login" class="btn btn-block btn-dark">Sign In</button>
                                </div>
                                <div class="col-lg-12 text-center mt-5">
                                    Forgot Password? <a href="reset_password.php" class="text-primary">Reset Password</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("public/partials/_authscripts.php"); ?>
</body>

</html>