<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();

/* Add Course */
if (isset($_POST['add_course'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Couse  Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Course Name Cannot Be Empty";
    }
    if (isset($_POST['department_id']) && !empty($_POST['department_id'])) {
        $department_id = mysqli_real_escape_string($mysqli, trim($_POST['department_id']));
    } else {
        $error = 1;
        $err = "Department Name / ID  Cannot Be Empty";
    }
    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Courses WHERE  code='$code' || name ='$name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Course With This Code Already Exists";
            } else {
                $err = "Course Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $details = $_POST['details'];
            $department_id = $_POST['department_id'];
            $department_name = $_POST['department_name'];

            $query = "INSERT INTO ezanaLMS_Courses (id, code, name, details, department_id, department_name) VALUES(?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssss', $id, $code, $name, $details, $department_id, $department_name);
            $stmt->execute();
            if ($stmt) {
                $success = "$name Course Added";
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/* Add Departmental Notice / Memo */
if (isset($_POST['add_memo'])) {
    $id = $_POST['id'];
    $department_id = $_POST['department_id'];
    $department_name = $_POST['department_name'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/memos/" . $_FILES["attachments"]["name"]);
    $departmental_memo = $_POST['departmental_memo'];
    $created_at = date('d M Y g:i');
    $type = $_POST['type'];
    $faculty = $_POST['faculty'];
    $created_by = $_POST['created_by'];

    $query = "INSERT INTO ezanaLMS_DepartmentalMemos (id, created_by, department_id, department_name, type, departmental_memo, attachments, created_at, faculty_id) VALUES(?,?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssssss', $id, $created_by, $department_id, $department_name, $type, $departmental_memo, $attachments, $created_at, $faculty);
    $stmt->execute();
    if ($stmt) {
        $success = "Departmental Memo Added"; // && header("refresh:1; url=create_departmental_memo.php?department_name=$department_name&department_id=$department_id");
    } else {
        //inject alert that profile update task failed
        $info = "Please Try Again Or Try Later";
    }
}

/* Add Notice */
if (isset($_POST['add_notice'])) {

    $id = $_POST['id'];
    $department_id = $_POST['department_id'];
    $department_name = $_POST['department_name'];
    $departmental_memo = $_POST['departmental_memo'];
    $created_at = date('d M Y g:i');
    $type = $_POST['type'];
    $faculty = $_POST['faculty'];
    $created_by = $_POST['created_by'];

    $query = "INSERT INTO ezanaLMS_DepartmentalMemos (id, created_by, department_id, department_name, type, departmental_memo, created_at, faculty_id) VALUES(?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssssss', $id, $created_by, $department_id, $department_name, $type, $departmental_memo, $created_at, $faculty);
    $stmt->execute();
    if ($stmt) {
        $success = "Notice Posted"; // && header("refresh:1; url=create_departmental_memo.php?department_name=$department_name&department_id=$department_id");
    } else {
        //inject alert that profile update task failed
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Departmental Memo */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_DepartmentalMemos WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=departmental_memos.php?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
/* Update Departmental Notices */

if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $departmental_memo = $_POST['departmental_memo'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/memos/" . $_FILES["attachments"]["name"]);
    $created_at = date('d M Y g:i');
    $type = $_POST['type'];
    $faculty = $_POST['faculty'];
    $created_by = $_POST['created_by'];

    $query = "UPDATE ezanaLMS_DepartmentalMemos SET  created_by=?, departmental_memo =?, attachments =?, created_at =?, type =?, faculty_id =? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssss',  $created_by, $departmental_memo, $attachments, $created_at, $type, $faculty, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Updated";
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('public/partials/_nav.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE id= '$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($department = $res->fetch_object()) {
            /* Faculty Analytics */
            require_once('public/partials/_facultyanalyitics.php');
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
                                <a href="departments.php" class="active nav-link">
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
                                <h1 class="m-0 text-dark"><?php echo $department->name; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculties.php">Departmentents</a></li>
                                    <li class="breadcrumb-item active"><?php echo $department->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="text-left">
                                <nav class="navbar navbar-light bg-light col-md-12">
                                    <form class="form-inline" action="department_search_result.php" method="GET">
                                        <input class="form-control mr-sm-2" type="search" name="query" placeholder="Dep Name Or Code">
                                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                    </form>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add New Course</button>

                                    <div class="modal fade" id="modal-default">
                                        <div class="modal-dialog  modal-lg">
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
                                                                    <label for="">Course Name</label>
                                                                    <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                    <input type="hidden" required name="department_id" value="<?php echo $department->id; ?>" class="form-control">
                                                                    <input type="hidden" required name="faculty_id" value="<?php echo $department->faculty_id; ?>" class="form-control">

                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="">Course Number / Code</label>
                                                                    <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="">Department Name</label>
                                                                    <input type="text" required name="department_name" value="<?php echo $department->name; ?>" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputPassword1">Course Description</label>
                                                                    <textarea required name="details" id="textarea" rows="10" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer">
                                                            <button type="submit" name="add_course" class="btn btn-primary">Add Course</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </nav>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">

                                    <div class="col-md-12">
                                        <div class="card card-primary">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    <a href="department_details.php?view=<?php echo $department->id; ?>">
                                                        <?php echo $department->name; ?>
                                                    </a>
                                                </h3>
                                                <div class="card-tools text-right">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="departmental_memos.php?view=<?php echo $department->id; ?>">
                                                            Memos & Notices
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="courses.php?view=<?php echo $department->id; ?>">
                                                            Courses
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="modules.php?view=<?php echo $department->faculty_id; ?>">
                                                            Modules
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="text-left">
                                                <a href="departments.php" class="btn btn-outline-success">
                                                    <i class="fas fa-arrow-left"></i>
                                                    Back
                                                </a>
                                                <a href="#add-memo" data-toggle="modal" class=" pull-left btn btn-outline-success">
                                                    <i class="fas fa-file"></i>
                                                    Add Memo
                                                </a>
                                                <div class="modal fade" id="add-memo">
                                                    <div class="modal-dialog  modal-lg">
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
                                                                            <div class="form-group col-md-12">
                                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                                <input type="hidden" required name="department_id" value="<?php echo $department->id; ?>" class="form-control">
                                                                                <input type="hidden" required name="department_name" value="<?php echo $department->name; ?>" class="form-control">
                                                                                <input type="hidden" required name="faculty" value="<?php echo $department->faculty_id; ?>" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Upload Departmental Memo (PDF Or Docx)</label>
                                                                                <div class="input-group">
                                                                                    <div class="custom-file">
                                                                                        <input name="attachments" type="file" class="custom-file-input">
                                                                                        <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Created By</label>
                                                                                <input type="text" required name="created_by" class="form-control">
                                                                            </div>
                                                                            <div style="display:none" class="form-group col-md-6">
                                                                                <label for="">Type</label>
                                                                                <select class='form-control basic' name="type">
                                                                                    <option selected>Memo</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <h2 class="text-center">Or </h2>
                                                                        <div class="row">
                                                                            <div class="form-group col-md-12">
                                                                                <label for="exampleInputPassword1">Type Departmental Memo</label>
                                                                                <textarea name="departmental_memo" id="dep_memo" rows="10" class="form-control"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-footer text-right">
                                                                        <button type="submit" name="add_memo" class="btn btn-primary">Add Departmental Memo</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-header text-center">
                                                            <h2>Recently Posted Departmental Memos</h2>
                                                        </div>
                                                        <div class="card-body">
                                                            <table id="example1" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Posted By</th>
                                                                        <th>Date Posted</th>
                                                                        <th>Type</th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_DepartmentalMemos` WHERE department_id = '$department->id'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    $cnt = 1;
                                                                    while ($memo = $res->fetch_object()) {
                                                                    ?>

                                                                        <tr>
                                                                            <td><?php echo $memo->created_by; ?></td>
                                                                            <td><?php echo $memo->created_at; ?></td>
                                                                            <td><?php echo $memo->type; ?></td>
                                                                            <td>
                                                                                <a class="badge badge-success" data-toggle="modal" href="#view-<?php echo $memo->id; ?>">
                                                                                    <i class="fas fa-eye"></i>
                                                                                    View
                                                                                </a>
                                                                                <!-- View Deptmental Memo Modal -->
                                                                                <div class="modal fade" id="view-<?php echo $memo->id; ?>">
                                                                                    <div class="modal-dialog  modal-lg">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h4 class="modal-title"><?php echo $department->name; ?> <?php echo $memo->type; ?> Created On <span class='text-success'><?php echo $memo->created_at; ?></span></h4>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <?php echo $memo->departmental_memo; ?>
                                                                                                <hr>
                                                                                                <?php

                                                                                                if ($memo->attachments != '') {
                                                                                                    echo
                                                                                                    "<a href='public/uploads/EzanaLMSData/memos/$memo->attachments' target='_blank' class='btn btn-outline-success'><i class='fas fa-download'></i> Download $memo->type </a>";
                                                                                                } else {
                                                                                                    echo
                                                                                                    "<a  class='btn btn-outline-danger'><i class='fas fa-times'></i> $memo->type Attachment Not Available </a>";
                                                                                                }
                                                                                                ?>

                                                                                            </div>
                                                                                            <div class="modal-footer justify-content-between">
                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                                <a class="badge badge-primary" data-toggle="modal" href="#update-<?php echo $memo->id; ?>">
                                                                                    <i class="fas fa-edit"></i>
                                                                                    Update
                                                                                </a>
                                                                                <!-- Update Departmental Memo Modal -->
                                                                                <div class="modal fade" id="update-<?php echo $memo->id; ?>">
                                                                                    <div class="modal-dialog  modal-lg">
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
                                                                                                            <div class="form-group col-md-6">
                                                                                                                <label for="">Type</label>
                                                                                                                <select class='form-control basic' name="type">
                                                                                                                    <option selected><?php echo $memo->type; ?></option>
                                                                                                                    <option>Notice</option>
                                                                                                                    <option>Memo</option>
                                                                                                                </select>
                                                                                                            </div>
                                                                                                            <div class="form-group col-md-6">
                                                                                                                <label for="">Upload Memo | Notice (PDF r Docx)</label>
                                                                                                                <div class="input-group">
                                                                                                                    <div class="custom-file">
                                                                                                                        <input name="attachments" type="file" class="custom-file-input">
                                                                                                                        <input type="hidden" required name="faculty" value="<?php echo $department->faculty_id; ?>" class="form-control">
                                                                                                                        <input type="hidden" required name="id" value="<?php echo $memo->id; ?>" class="form-control">
                                                                                                                        <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="form-group col-md-12">
                                                                                                                <label for="">Created By</label>
                                                                                                                <input type="text" name="created_by" class="form-control" value="<?php echo $memo->created_by; ?>">
                                                                                                            </div>

                                                                                                        </div>
                                                                                                        <h2 class="text-center">Or </h2>
                                                                                                        <div class="row">
                                                                                                            <div class="form-group col-md-12">
                                                                                                                <label for="exampleInputPassword1">Type Departmental Memo | Notice</label>
                                                                                                                <textarea name="departmental_memo" id="editor-<?php echo $memo->id; ?>" rows="10" class="form-control"><?php echo $memo->departmental_memo; ?></textarea>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="card-footer text-right">
                                                                                                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                                                                                                    </div>
                                                                                                </form>
                                                                                            </div>
                                                                                            <div class="modal-footer justify-content-between">
                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- End Update Departmental Memo Modal -->
                                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $memo->id; ?>">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation -->
                                                                                <div class="modal fade" id="delete-<?php echo $memo->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete This <?php echo $memo->type; ?> ?</h4>
                                                                                                <br>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="departmental_memos.php?delete=<?php echo $memo->id; ?>&view=<?php echo $memo->department_id; ?>" class="text-center btn btn-danger"> Delete </a>
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
                                                </div>
                                            </div>
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