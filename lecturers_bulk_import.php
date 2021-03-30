<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('configs/codeGen.php');
/* Bulk Import Lecturers Via .XLS  */

require_once('public/partials/_analytics.php');
require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('public/partials/_nav.php'); ?>
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
                            <a href="dashboard.php" class=" nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="faculties.php" class=" nav-link">
                                <i class="nav-icon fas fa-university"></i>
                                <p>
                                    Faculties
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="departments.php" class=" nav-link">
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
                            <a href="lecturers.php" class="active nav-link">
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
                            <h1 class="m-0 text-dark">Bulk Import Lecturers</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Lecturers</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <nav class="navbar navbar-light bg-light col-md-12">
                            <form class="form-inline">
                            </form>
                            <div class="text-left">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">Add Lecturer</button>
                            </div>
                            <!-- Add Lec Modal -->
                            <div class="modal fade" id="modal-default">
                                <div class="modal-dialog  modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Fill All Values </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" enctype="multipart/form-data" role="form">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-4">
                                                            <label for="">Faculty Code</label>
                                                            <select class='form-control basic' id="FacultyCode" onchange="OptimizedFacultyDetails(this.value);" id='F'>
                                                                <option selected>Select Faculty Code Name </option>
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_Faculties`  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($row = $res->fetch_object()) {
                                                                ?>
                                                                    <option><?php echo $row->code; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-8">
                                                            <label for="">Faculty Name</label>
                                                            <input type="text" required name="faculty_name" class="form-control" id="FacultyName">
                                                            <input type="hidden" required name="faculty_id" id="FacultyID" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="">Name</label>
                                                            <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="">Gender</label>
                                                            <select class='form-control basic' name="gender">
                                                                <option selected>Male</option>
                                                                <option>Female</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="">Number</label>
                                                            <input type="text" required name="number" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="">ID / Passport Number</label>
                                                            <input type="text" required name="idno" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-4">
                                                            <label for="">Personal Email</label>
                                                            <input type="email" required name="email" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="">Work Email</label>
                                                            <input type="email" required name="work_email" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="">Phone Number</label>
                                                            <input type="text" required name="phone" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="row">

                                                        <div class="form-group col-md-6">
                                                            <label for="">Default Password</label>
                                                            <input type="text" value="Lecturer" required name="password" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Employee ID</label>
                                                            <input type="text" required name="employee_id" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Date Employed</label>
                                                            <input type="text" required name="date_employed" placeholder="DD - MM - YYYY" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Profile Picture</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input required name="profile_pic" type="file" class="custom-file-input">
                                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputPassword1">Address</label>
                                                            <textarea required name="adr" rows="2" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-right">
                                                    <button type="submit" name="add_lec" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Add Lec Modal -->
                        </nav>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-center">Select On Any Faculty To Import Lecturers To</h4>
                            <table id="example1" class=" table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Faculty Code Number</th>
                                        <th>Faculty Name</th>
                                        <th>Manage</th>
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
                                            <td><?php echo $faculty->code; ?></td>
                                            <td><?php echo $faculty->name; ?></td>
                                            <td>
                                                <a class="badge badge-primary" data-toggle="modal" href="#edit-faculty-<?php echo $faculty->id; ?>">
                                                    <i class="fas fa-edit"></i>
                                                    Import Lecturers
                                                </a>
                                                <!-- Update Faculty Modal -->
                                                <div class="modal fade" id="edit-faculty-<?php echo $faculty->id; ?>">
                                                    <div class="modal-dialog  modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Edit <?php echo $faculty->name; ?> Details</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Update Faculty Modal -->
                                                                <form method="post" enctype="multipart/form-data" role="form">
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Faculty Name</label>
                                                                                <input type="text" required name="name" value="<?php echo $faculty->name; ?>" class="form-control" id="exampleInputEmail1">
                                                                                <input type="hidden" required name="id" value="<?php echo $faculty->id; ?>" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Faculty Number / Code</label>
                                                                                <input type="text" required name="code" value="<?php echo $faculty->code; ?>" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Faculty Head</label>
                                                                                <input type="text" required name="head" value="<?php echo $faculty->head; ?>" class="form-control" id="exampleInputEmail1">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Faculty Email</label>
                                                                                <input type="text" required name="email" value="<?php echo $faculty->email; ?>" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group col-md-12">
                                                                                <label for="exampleInputPassword1">Faculty Description</label>
                                                                                <textarea id="textarea" name="details" rows="5" class="form-control"><?php echo $faculty->details; ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <button type="submit" name="update_faculty" class=" btn btn-primary">Update Faculty</button>
                                                                    </div>
                                                                </form>
                                                                <!-- End Update Faculty Modal -->
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php $cnt = $cnt + 1;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
                <!-- Main Footer -->
                <?php require_once('public/partials/_footer.php'); ?>
            </div>
        </div>
        <!-- ./wrapper -->
        <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>