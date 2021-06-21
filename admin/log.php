<?php
/*
 * Created on Mon Jun 21 2021
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
require_once('../config/checklogin.php');
admin_checklogin();
require_once('partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('partials/header.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_UserLog` WHERE id= '$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($logs = $res->fetch_object()) {
        ?>
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            <?php require_once('partials/aside.php');?>
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">User Authentication Activity Log Details</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
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
                                                <div class="widget-user-header text-left bg-primary">
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
                                                                        Date Logged In : <span class="float-right "><?php echo date('d-M-Y g:ia', strtotime($logs->exact_login_time)); ?></span>
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
                                                <div class="widget-user-header text-left bg-primary">
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
                                                    ?>
                                                    <table class='table table-hover'>
                                                        <thead>
                                                            <tr>
                                                                <th>IP Address</th>
                                                                <th>IP Address Type</th>
                                                                <th>Continent Code</th>
                                                                <th>Country</th>
                                                                <th>Country Code</th>
                                                                <th>Country Capital</th>
                                                                <th>Region</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><?php echo $ipwhois_result["ip"]; ?></td>
                                                                <td><?php echo $ipwhois_result["type"]; ?></td>
                                                                <td><?php echo $ipwhois_result["continent_code"]; ?></td>
                                                                <td><?php echo $ipwhois_result["country"]; ?></td>
                                                                <td><?php echo $ipwhois_result["country_code"]; ?></td>
                                                                <td><?php echo $ipwhois_result["country_capital"]; ?></td>
                                                                <td><?php echo $ipwhois_result["region"]; ?></td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                    <table class='table table-hover'>
                                                        <thead>
                                                            <tr>
                                                                <th>City</th>
                                                                <th>Time Zone </th>
                                                                <th>Time Zone Name </th>
                                                                <th>Latitude</th>
                                                                <th>Longitude</th>
                                                                <th>Organization</th>
                                                                <th>ISP</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><?php echo $ipwhois_result["city"]; ?></td>
                                                                <td><?php echo $ipwhois_result["timezone"]; ?></td>
                                                                <td><?php echo $ipwhois_result["timezone_name"]; ?></td>
                                                                <td><?php echo $ipwhois_result["latitude"]; ?></td>
                                                                <td><?php echo $ipwhois_result["longitude"]; ?></td>
                                                                <td><?php echo $ipwhois_result["org"]; ?></td>
                                                                <td><?php echo $ipwhois_result["isp"]; ?></td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>
                    <!-- Main Footer -->
                    <?php require_once('partials/footer.php'); ?>
                </div>
            </div>
            <!-- ./wrapper -->
        <?php require_once('partials/scripts.php');
        } ?>

</body>

</html>