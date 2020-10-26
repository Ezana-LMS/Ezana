<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

require_once('partials/_head.php');

?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('partials/_nav.php'); ?>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        <?php require_once('partials/_sidebar.php'); ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Faculties</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="faculties_reports.php">Advanced Reporting</a></li>
                                <li class="breadcrumb-item active">Faculties</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Faculty Code Number</th>
                                            <th>Faculty Name</th>
                                            <th>Number Of Departments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_Faculties`  ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($faculty = $res->fetch_object()) {
                                        ?>

                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $faculty->code; ?></td>
                                                <td><?php echo $faculty->name; ?></td>
                                                <td>
                                                    <?php
                                                    $query = "SELECT COUNT(*)  FROM `ezanaLMS_Departments` WHERE faculty_name ='$faculty->name' ";
                                                    $stmt = $mysqli->prepare($query);
                                                    $stmt->execute();
                                                    $stmt->bind_result($departments);
                                                    $stmt->fetch();
                                                    $stmt->close();
                                                    echo $departments;
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php $cnt = $cnt + 1;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->
        </div>
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>