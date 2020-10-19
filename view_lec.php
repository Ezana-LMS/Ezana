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

        <?php
        require_once('partials/_sidebar.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE id ='$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($lec = $res->fetch_object()) {
            //Get Default Profile Picture
            if ($lec->profile_pic == '') {
                $dpic = "<img class='profile-user-img img-fluid img-circle' src='dist/img/logo.jpeg' alt='User profile picture'>";
            } else {
                $dpic = "<img class='profile-user-img img-fluid img-circle' src='dist/img/lecturers/$lec->profile_pic' alt='User profile picture'>";
            }
        ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1><?php echo $lec->name; ?> Profile</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="manage_lectures.php">Lecturers</a></li>
                                    <li class="breadcrumb-item active"><?php echo $lec->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-3">
                                <!-- Profile Image -->
                                <div class="card card-primary card-outline">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            <?php echo $dpic; ?>
                                        </div>

                                        <h3 class="profile-username text-center"><?php echo $lec->name; ?></h3>

                                        <p class="text-muted text-center"><?php echo $lec->number; ?></p>

                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item">
                                                <b>Email: </b> <a class="float-right"><?php echo $lec->email; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>ID / Passport: </b> <a class="float-right"><?php echo $lec->idno; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Phone: </b> <a class="float-right"><?php echo $lec->phone; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Address</b> <a class="float-right"><?php echo $lec->adr; ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-9">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#modules" data-toggle="tab">Modules Assigned</a></li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="active tab-pane" id="modules">
                                                <table id="example1" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Module Code</th>
                                                            <th>Module Name</th>
                                                            <th>Date Allocated</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns`  ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        while ($mod = $res->fetch_object()) {
                                                            $cnt = 1;
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $cnt; ?></td>
                                                                <td><?php echo $mod->module_code; ?></td>
                                                                <td><?php echo $lec->module_name; ?></td>
                                                                <td><?php echo $lec->created_at; ?></td>
                                                            </tr>
                                                        <?php $cnt = $cnt + 1;
                                                        } ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div><!-- /.card-body -->
                                </div>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>
            </div>
        <?php require_once('partials/_footer.php');
        } ?>

    </div>

</body>
<?php require_once('partials/_scripts.php'); ?>

</html>