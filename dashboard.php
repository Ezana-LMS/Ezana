<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('partials/_analytics.php');
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
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">System Administrator Dashboard </h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Info boxes -->
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-anchor"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Faculties</span>
                                    <span class="info-box-number"><?php echo $faculties; ?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-bezier-curve"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Departments</span>
                                    <span class="info-box-number">
                                        <?php echo $departments; ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>

                        <!-- fix for small devices only -->
                        <div class="clearfix hidden-md-up"></div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-copy"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Courses</span>
                                    <span class="info-box-number"><?php echo $courses; ?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-paste"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Modules</span>
                                    <span class="info-box-number"><?php echo $modules; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-secret"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Administrators </span>
                                    <span class="info-box-number"><?php echo $admins; ?></span>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-tie"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Lecturers</span>
                                    <span class="info-box-number">
                                        <?php echo $lecs; ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>

                        <!-- fix for small devices only -->
                        <div class="clearfix hidden-md-up"></div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Students</span>
                                    <span class="info-box-number"><?php echo $students; ?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users-cog"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Student Groups</span>
                                    <span class="info-box-number"><?php echo $groups; ?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Administrators</h5>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="admins" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Names</th>
                                                <th>Email</th>
                                                <th>Rank</th>
                                                <th>Phone No. </th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ret = "SELECT * FROM `ezanaLMS_Admins`  ";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while ($admin = $res->fetch_object()) {
                                            ?>

                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $admin->name; ?></td>
                                                    <td><?php echo $admin->email; ?></td>
                                                    <td><?php echo $admin->rank; ?></td>
                                                    <td><?php echo $admin->phone; ?></td>
                                                    <td>
                                                        <a class="badge badge-success" href="view_admin.php?view=<?php echo $admin->id; ?>">
                                                            <i class="fas fa-eye"></i>
                                                            View
                                                        </a>

                                                    </td>
                                                </tr>

                                            <?php $cnt = $cnt + 1;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Lecturers</h5>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Lec Number</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>ID / Passport No</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ret = "SELECT * FROM `ezanaLMS_Lecturers`  ";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while ($lec = $res->fetch_object()) {
                                            ?>

                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $lec->number; ?></td>
                                                    <td><?php echo $lec->name; ?></td>
                                                    <td><?php echo $lec->email; ?></td>
                                                    <td><?php echo $lec->phone; ?></td>
                                                    <td><?php echo $lec->idno; ?></td>
                                                    <td>
                                                        <a class="badge badge-success" href="view_lec.php?view=<?php echo $lec->id; ?>">
                                                            <i class="fas fa-eye"></i>
                                                            View Account
                                                        </a>

                                                    </td>
                                                </tr>
                                            <?php $cnt = $cnt + 1;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Students</h5>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="enrollment" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Adm No</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>ID / Passport No</th>
                                                <th>Gender</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ret = "SELECT * FROM `ezanaLMS_Students`  ";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while ($std = $res->fetch_object()) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $std->admno; ?></td>
                                                    <td><?php echo $std->name; ?></td>
                                                    <td><?php echo $std->email; ?></td>
                                                    <td><?php echo $std->phone; ?></td>
                                                    <td><?php echo $std->idno; ?></td>
                                                    <td><?php echo $std->gender; ?></td>
                                                    <td>
                                                        <a class="badge badge-success" href="view_student.php?view=<?php echo $std->id; ?>">
                                                            <i class="fas fa-eye"></i>
                                                            View Account
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php $cnt = $cnt + 1;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                </div>
                <!--/. container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <!-- ./wrapper -->]
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>