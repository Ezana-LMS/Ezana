<?php
/*
 * Created on Thu Apr 01 2021
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
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('public/partials/_nav.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_UserLog` WHERE id= '$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($logs = $res->fetch_object()) {
        ?>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <?php require_once('public/partials/_brand.php'); ?>
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item">
                                <a href="dashboard.php" class=" active nav-link">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="faculties.php" class="nav-link">
                                    <i class="nav-icon fas fa-university"></i>
                                    <p>
                                        Faculties
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="departments.php" class="nav-link">
                                    <i class="nav-icon fas fa-building"></i>
                                    <p>
                                        Departments
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="courses.php" class="nav-link">
                                    <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                    <p>
                                        Courses
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="modules.php" class="nav-link">
                                    <i class="nav-icon fas fa-chalkboard"></i>
                                    <p>
                                        Modules
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="non_teaching_staff.php" class="nav-link">
                                    <i class="nav-icon fas fa-user-secret"></i>
                                    <p>
                                        Non Teaching Staff
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="lecturers.php" class="nav-link">
                                    <i class="nav-icon fas fa-user-tie"></i>
                                    <p>
                                        Lecturers
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="students.php" class="nav-link">
                                    <i class="nav-icon fas fa-user-graduate"></i>
                                    <p>
                                        Students
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-cogs"></i>
                                    <p>
                                        System Settings
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="reports.php" class="nav-link">
                                            <i class="fas fa-angle-right nav-icon"></i>
                                            <p>Reports</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="system_settings.php" class="nav-link">
                                            <i class="fas fa-angle-right nav-icon"></i>
                                            <p>System Settings</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </nav>
                </div>
            </aside>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">User Login Activity Details</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item active">Logs</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card card-widget widget-user-2">
                                                <div class="widget-user-header text-center bg-primary">
                                                    <h3 class="widget-user-username">User Details</h3>
                                                </div>
                                                <div class="card-footer p-0">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Email Address : <span class="float-right "><?php echo $logs->name; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Date Logged In : <span class="float-right "><?php echo $logs->loginTime; ?></span>
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card card-widget widget-user-2">
                                                <div class="widget-user-header text-center bg-primary">
                                                    <h3 class="widget-user-username">System Details</h3>
                                                </div>
                                                <div class="card-footer p-0">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        IP Address : <span class="float-right"><?php echo $logs->ip; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        User Rank: <span class="float-right "><?php echo $logs->User_Rank; ?></span>
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-md-12">
                                            <ul class="nav flex-column">
                                                <li class="nav-item">
                                                    <span class="nav-link text-center text-primary">
                                                        User IP Address Details
                                                    </span>
                                                </li>
                                                <li class="nav-item text-center">
                                                    <?php
                                                    $ip = $logs->ip;

                                                    $ch = curl_init('http://ipwhois.app/json/' . $ip);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                    $json = curl_exec($ch);
                                                    curl_close($ch);

                                                    // Decode JSON response
                                                    $ipwhois_result = json_decode($json, true);

                                                    // Country code output, field "country_code"
                                                    echo "IP Address      : " .  $ipwhois_result['ip'] . "<br>";
                                                    echo "IP Address Type : " .  $ipwhois_result['type'] . "<br>";
                                                    echo "Continent Code  : " .  $ipwhois_result['continent_code'] . "<br>";
                                                    echo "Country         : " .  $ipwhois_result['country'] . "<br>";
                                                    echo "Country Code    : " .  $ipwhois_result['country_code'] . "<br>";
                                                    echo "Country Capital : " .  $ipwhois_result['country_capital'] . "<br>";
                                                    echo "Region          : " .  $ipwhois_result['region'] . "<br>";
                                                    echo "City            : " .  $ipwhois_result['city'] . "<br>";
                                                    echo "Latitude        : " .  $ipwhois_result['latitude'] . "<br>";
                                                    echo "Longitude       : " .  $ipwhois_result['longitude'] . "<br>";
                                                    echo "Organization    : " .  $ipwhois_result['org'] . "<br>";
                                                    echo "ISP             : " .  $ipwhois_result['isp'] . "<br>";
                                                    echo "Time Zone       : " .  $ipwhois_result['timezone'] . "<br>";
                                                    echo "Time Zone Name  : " .  $ipwhois_result['timezone_name'] . "<br>";
                                                    ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>
                    <!-- Main Footer -->
                    <?php require_once('public/partials/_footer.php'); ?>
                </div>
            </div>
            <!-- ./wrapper -->
        <?php require_once('public/partials/_scripts.php');
        } ?>

</body>

</html>