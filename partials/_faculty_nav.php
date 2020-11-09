<?php
$id  = $_SESSION['id'];
$ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id ='$id' ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($admin = $res->fetch_object()) {

    $id = $_GET['id'];
    $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$id' ";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute(); //ok
    $res = $stmt->get_result();
    while ($faculty = $res->fetch_object()) {
?>
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="dashboard.php" class="navbar-brand">
                    <img src="dist/img/logo.jpeg" alt="Ezana LMS Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light">Ezana LMS</span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                        </li>
                        <li class="nav-item">
                            <a href="school_calendar.php" class="nav-link">School Calendar</a>
                        </li>
                        <li class="nav-item">
                            <a href="departments.php?faculty=<?php echo $id; ?>" class="nav-link">Departments</a>
                        </li>
                    </ul>
                </div>
                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" title="<?php echo $_SESSION['name']; ?> Profile" href="profile.php"><i class="fa fa-user-secret" aria-hidden="true"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" title="<?php echo $_SESSION['name']; ?> Logout " href="logout.php"><i class="fa fa-power-off" aria-hidden="true"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
<?php
    }
} ?>