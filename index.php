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
                        <h2 class="mt-3 text-center">Log In</h2>
                        <p class="text-center">Enter Your Email Address And Password</p>
                        <div class="wrap-input100 validate-input" data-validate="Valid email is required: user@mail.com">
                            <input class="input100" type="email" name="email">
                            <span class="focus-input100"></span>
                            <span class="label-input100">Email</span>
                        </div>
                        <div class="wrap-input100 validate-input" data-validate="Password is required">
                            <input class="input100" type="password" name="password">
                            <span class="focus-input100"></span>
                            <span class="label-input100">Password</span>
                        </div>
                        <div class="flex-sb-m w-full p-t-3 p-b-32">
                            <div class="contact100-form-checkbox">
                                <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
                                <label class="label-checkbox100" for="ckb1">
                                    Remember me
                                </label>
                            </div>
                            <div>
                                <a href="reset_password.php" class="txt1">
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