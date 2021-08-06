<?php
/*
 * Created on Thu Jul 01 2021
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
while ($std = $res->fetch_object()) {
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
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="text-primary fas fa-user"></i>
                    Hello <i><?php echo $std->name; ?></i>
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
                    <a href="requests" class="dropdown-item">
                        <i class="text-primary fas fa-user-clock mr-2"></i>
                        User Requests
                    </a>
                    <a href="report_bug" class="dropdown-item">
                        <i class="text-primary fas fa-bug mr-2"></i>
                        Bug Reports
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