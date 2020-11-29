<?php
$id  = $_SESSION['id'];
$ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id ='$id' ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($admin = $res->fetch_object()) {
?>
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
        <div class="container">
            <a href="dashboard.php" class="navbar-brand">
                <img src="dist/img/Main_Logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
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
                    <!--  <li class="nav-item">
                        <a href="school_calendar.php" class="nav-link">School Calendar</a>
                    </li> -
                    <li class="nav-item">
                        <a href="faculties.php" class="nav-link">Faculties</a>
                    </li>
                    <li class="nav-item">
                        <a href="administrators.php" class="nav-link">Administrators</a>
                    </li> 
                    <li class="nav-item dropdown">
                        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Reports</a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                            <li><a href="#" class="dropdown-item"></a></li>
                            <li><a href="#" class="dropdown-item"></a></li>
                        </ul>
                    </li>
                    -->
                </ul>
            </div>
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
} ?>