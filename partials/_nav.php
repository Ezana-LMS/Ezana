<?php
$id  = $_SESSION['id'];
$ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id ='$id' ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($admin = $res->fetch_object()) {
    ?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg text-center dropdown-menu-right">
                <span class="dropdown-item dropdown-header">Hello <?php echo $admin->name; ?></span>
                <div class="dropdown-divider"></div>
                <a href="profile.php" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i>Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="logout.php" class="dropdown-item dropdown-footer">Log Out</a>
            </div>
        </li>
    </ul>
</nav>
<?php
} ?>