<?php
/*
 * Created on Tue May 04 2021
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

$id  = $_SESSION['id'];
$ret = "SELECT * FROM `ezanaLMS_Students` WHERE id ='$id' ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($student = $res->fetch_object()) {
?>
    <!-- Navbar -->
    <nav class="main-header navbar  navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" data-enable-remember="true" href="#"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">

            <!-- Emails Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" target="_blank" href="ezanamail/">
                    <i class="far fa-envelope"></i>
                </a>
            </li>
            <!-- Notifications Dropdown Menu -->
            <!-- <li class="nav-item dropdown">
                 <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
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
                    <span class="dropdown-item dropdown-header"><?php echo $unread; ?> Notifications <a href="lec_dashboard.php?notification='Read'" class="badge bg-danger"> Mark All Read </a></span>
                    <div class="dropdown-divider"></div>
                    <?php
                    /* Load Notifications On Order Created */
                    $ret = "SELECT * FROM `ezanaLMS_Notifications` WHERE status = 'Unread' ORDER BY `created_at` DESC ";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->execute(); //ok
                    $res = $stmt->get_result();
                    while ($notification = $res->fetch_object()) {
                    ?>
                        <a href="lec_notifications.php" class="dropdown-item">
                            <?php echo  substr($notification->notification_detail, 0, 30) . "..."; ?>
                            <span class="float-right text-success text-sm"><?php echo date('d M Y g:ia', strtotime($notification->created_at)); ?></span>
                            <br>
                        </a>
                    <?php
                    }
                    ?>

                    <div class="dropdown-divider"></div>
                    <a href="lec_notifications.php" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li> -->

            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-user"></i>
                    Hello <i><?php echo $student->name; ?></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="std_profile.php" class="dropdown-item">
                        <i class="fas fa-user-tag mr-2"></i>
                        Profile
                    </a>
                    <a href="std_calendar.php" class="dropdown-item">
                        <i class="fas fa-calendar mr-2"></i>
                        Calendar
                    </a>
                    <a href="std_requests.php" class="dropdown-item">
                        <i class="fas fa-user-clock mr-2"></i>
                        User Requests
                    </a>
                    <a href="std_report_bug.php" class="dropdown-item">
                        <i class="fas fa-bug mr-2"></i>
                        Bug Reports
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="std_logout.php" class="dropdown-item">
                        <i class="fas fa-power-off mr-2"></i>
                        Log Out
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->
<?php
} ?>