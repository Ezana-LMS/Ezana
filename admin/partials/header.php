<?php
$id  = $_SESSION['id'];
$email = $_SESSION['email'];
$ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id ='$id' || email = '$email' ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($admin = $res->fetch_object()) {
?>
    <!-- Navbar -->
    <nav class="main-header navbar  navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" data-enable-remember="true" href="#"><i class="text-primary fas fa-bars"></i></a>
            </li>
        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">

            <!-- Emails Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" target="_blank" href="../webmail/">
                    <i class="text-primary far fa-envelope"></i>
                </a>
            </li>
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="text-primary far fa-bell"></i>
                    <?php
                    /* Count Unread Notifications */
                    $query = "SELECT COUNT(*)  FROM `ezanaLMS_Notifications` WHERE status = 'Unread' ";
                    $stmt = $mysqli->prepare($query);
                    $stmt->execute();
                    $stmt->bind_result($unread);
                    $stmt->fetch();
                    $stmt->close(); ?>
                    <span class="badge badge-success navbar-badge"><?php echo $unread; ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header"><?php echo $unread; ?> Notifications <a href="notifications?notification='Read'" class="badge bg-danger"> Mark All Read </a></span>
                    <div class="dropdown-divider"></div>
                    <?php
                    /* Load Notifications On Order Created */
                    $ret = "SELECT * FROM `ezanaLMS_Notifications` WHERE status = 'Unread' ORDER BY `created_at` DESC ";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->execute(); //ok
                    $res = $stmt->get_result();
                    while ($notification = $res->fetch_object()) {
                    ?>
                        <a href="notifications" class="dropdown-item">
                            <?php echo  substr($notification->notification_detail, 0, 30) . "..."; ?>
                            <span class="float-right text-success text-sm"><?php echo date('d M Y g:ia', strtotime($notification->created_at)); ?></span>
                            <br>
                        </a>
                    <?php
                    }
                    ?>

                    <div class="dropdown-divider"></div>
                    <a href="notifications" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="text-primary fas fa-user"></i>
                    Hello <i><?php echo $admin->name; ?></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="profile" class="dropdown-item">
                        <i class="text-primary fas fa-user mr-2"></i>
                        Profile
                    </a>
                    <a href="calendar" class="dropdown-item">
                        <i class="text-primary fas fa-calendar mr-2"></i>
                        Calendar
                    </a>

                    <div class="dropdown-divider"></div>
                    <a href="logout" class="dropdown-item">
                        <i class="text-primary fas fa-power-off mr-2"></i>
                        Log Out
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->
<?php
} ?>