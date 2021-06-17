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
            $faculty_id = $_POST['faculty_id'];
            $faculty_name = $_POST['faculty_name'];
            $hod = $_POST['hod'];
            $email = $_POST['email'];
            $query = "INSERT INTO ezanaLMS_Courses (id, hod, email, code, name, details, department_id, faculty_id, faculty_name, department_name) VALUES(?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssssss', $id, $hod, $email, $code, $name, $details, $department_id, $faculty_id, $faculty_name,  $department_name);
            $stmt->execute();
            if ($stmt) {
                $success = "Course Added" && header("refresh:1; url=department_details.php?view=$department_id");
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/*  Update Course*/
if (isset($_POST['update_course'])) {
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
    if (!$error) {

        $id = $_POST['id'];
        $name = $_POST['name'];
        $code = $_POST['code'];
        $details = $_POST['details'];
        $hod = $_POST['hod'];
        $email = $_POST['email'];
        /* Department ID */
        $view = $_POST['view'];

        $query = "UPDATE ezanaLMS_Courses SET  code =?, hod =?, email =?,  name =?, details =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssss', $code, $hod, $email, $name, $details, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Course Updated" && header("refresh:1; url=department_details.php?view=$view");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Update Department */
if (isset($_POST['update_dept'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Department Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Department Name Cannot Be Empty";
    }
    if (!$error) {
        $view = $_GET['view'];
        $name = $_POST['name'];
        $code = $_POST['code'];
        $details = $_POST['details'];

        $query = "UPDATE ezanaLMS_Departments SET code =?, name =?,  details =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssss', $code, $name, $details, $view);
        $stmt->execute();
        if ($stmt) {
            $success = "Updated" && header("refresh:1; url=department_details.php?view=$view");
        } else {
            //inject alert that profile update task failed
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Update Department HOD */
if (isset($_POST['update_dept_hod'])) {

    $view = $_GET['view'];
    $hod = $_POST['hod'];
    $email = $_POST['email'];

    $query = "UPDATE ezanaLMS_Departments SET email =?, hod =? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sss', $email, $hod, $view);
    $stmt->execute();
    if ($stmt) {
        $success = "Updated" && header("refresh:1; url=department_details.php?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
/* Add Announcement */
if (isset($_POST['add_announcement'])) {

    $id = $_POST['id'];
    $department_id = $_POST['department_id'];
    $department_name = $_POST['department_name'];
    $departmental_memo = $_POST['departmental_memo'];
    $type = $_POST['type'];
    $faculty = $_POST['faculty'];

    /* Notify Me After Posting Memo / Notice */
    $notif_type = 'Posted Notice';
    $status = 'Unread';
    $notification_detail = "$type For $department_name";

    $query = "INSERT INTO ezanaLMS_DepartmentalMemos (id, department_id, department_name, type, departmental_memo, faculty_id) VALUES(?,?,?,?,?,?)";
    $notif_querry = "INSERT INTO ezanaLMS_Notifications(type, status, notification_detail) VALUES(?,?,?)";

    $stmt = $mysqli->prepare($query);
    $notif_stmt = $mysqli->prepare($notif_querry);

    $rc = $stmt->bind_param('ssssss', $id, $department_id, $department_name, $type, $departmental_memo, $faculty);
    $rc = $notif_stmt->bind_param('sss', $notif_type, $status, $notification_detail);

    $stmt->execute();
    $notif_stmt->execute();

    if ($stmt && $notif_stmt) {
        $success = "Added" && header("refresh:1; url=department_details.php?view=$department_id");
    } else {
        //inject alert that profile update task failed
        $info = "Please Try Again Or Try Later";
    }
}

/* Update Announcement  */
if (isset($_POST['update_announcement'])) {

    $id = $_POST['id'];
    $department_id = $_POST['department_id'];
    $departmental_memo = $_POST['departmental_memo'];

    $query = "UPDATE ezanaLMS_DepartmentalMemos SET departmental_memo =? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ss', $departmental_memo, $id);
    $stmt->execute();

    if ($stmt) {
        $success = "Updated" && header("refresh:1; url=department_details.php?view=$department_id");
    } else {
        //inject alert that profile update task failed
        $info = "Please Try Again Or Try Later";
    }
}

/* Add Memo / Notice */
if (isset($_POST['add_memo'])) {
    $id = $_POST['id'];
    $time = date("d-M-Y") . "-" . time();
    $department_id = $_POST['department_id'];
    $department_name = $_POST['department_name'];
    $attachments = $time . $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/memos/" . $time . $_FILES["attachments"]["name"]);
    $departmental_memo = $_POST['departmental_memo'];
    $type = $_POST['type'];
    $faculty = $_POST['faculty'];
    $created_by = $_POST['created_by'];

    /* Notify Me After Posting Memo / Notice */
    $notif_type = 'Department Memo';
    $status = 'Unread';
    $notification_detail = "$type For $department_name";

    $query = "INSERT INTO ezanaLMS_DepartmentalMemos (id, created_by, department_id, department_name, type, departmental_memo, attachments, faculty_id) VALUES(?,?,?,?,?,?,?,?)";
    $notif_querry = "INSERT INTO ezanaLMS_Notifications(type, status, notification_detail) VALUES(?,?,?)";
    $stmt = $mysqli->prepare($query);
    $notif_stmt = $mysqli->prepare($notif_querry);
    $rc = $stmt->bind_param('ssssssss', $id, $created_by, $department_id, $department_name, $type, $departmental_memo, $attachments, $faculty);
    $rc = $notif_stmt->bind_param('sss', $notif_type, $status, $notification_detail);
    $stmt->execute();
    $notif_stmt->execute();
    if ($stmt && $notif_stmt) {
        $success = "Departmental Memo Added" && header("refresh:1; url=department_details.php?view=$department_id");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Update Memo / Notice */
if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $time = date("d-M-Y") . "-" . time();
    $departmental_memo = $_POST['departmental_memo'];
    $attachments = $time . $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/memos/" . $time . $_FILES["attachments"]["name"]);
    $type = $_POST['type'];
    $faculty = $_POST['faculty'];
    $created_by = $_POST['created_by'];

    /* Department_ID */
    $department_id = $_POST['department_id'];

    $query = "UPDATE ezanaLMS_DepartmentalMemos SET  created_by=?, departmental_memo =?, attachments =?, type =?, faculty_id =? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssss',  $created_by, $departmental_memo, $attachments, $type, $faculty, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Updated" && header("refresh:1; url=department_details.php?view=$department_id");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Add Departmental Documents */
if (isset($_POST['add_departmental_doc'])) {
    $id = $_POST['id'];
    $time = date("d-M-Y") . "-" . time();
    $department_id = $_POST['department_id'];
    $department_name = $_POST['department_name'];
    $attachments = $time . $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/memos/" . $time . $_FILES["attachments"]["name"]);
    $type = $_POST['type'];
    $faculty = $_POST['faculty'];
    $created_by = $_POST['created_by'];

    $query = "INSERT INTO ezanaLMS_DepartmentalMemos (id, created_by,  department_id, department_name, type, attachments, faculty_id) VALUES(?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssss', $id, $created_by, $department_id, $department_name, $type,  $attachments, $faculty);
    $stmt->execute();
    if ($stmt) {
        $success = "Departmental Memo Added" && header("refresh:1; url=department_details.php?view=$department_id");
    } else {
        //inject alert that profile update task failed
        $info = "Please Try Again Or Try Later";
    }
}

/* Update Departmental Documents */
if (isset($_POST['update_departmental_doc'])) {

    $id = $_POST['id'];
    $attachments = $time . $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/memos/" . $time . $_FILES["attachments"]["name"]);
    $type = $_POST['type'];
    $faculty = $_POST['faculty'];
    $created_by = $_POST['created_by'];
    $department_id = $_POST['department_id'];

    $query = "UPDATE ezanaLMS_DepartmentalMemos SET  created_by = ?, attachments =?,  type =?, faculty_id =? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssss',  $created_by, $attachments, $type, $faculty, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Updated" && header("refresh:1; url=department_details.php?view=$department_id");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Departmental Memo/ Notice/ Announcemet / Departmental Documents */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_DepartmentalMemos WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=department_details.php?view=$view");
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
            /* Load Logged In User Session */
            $id  = $_SESSION['id'];
            $ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id ='$id' ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($admin = $res->fetch_object()) {

                /* Faculty Analytics */
                require_once('public/partials/_facultyanalyitics.php'); ?>
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
                                    <h1 class="m-0 text-dark"><?php echo $department->name; ?> Dashboard</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="departments.php">Departmentents</a></li>
                                        <li class="breadcrumb-item active"><?php echo $department->name; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <section class="content">
                            <div class="container-fluid">
                                <div class="text-left">
                                    <nav class="navbar navbar-light bg-light col-md-12">
                                        <div class="form-inline">
                                            <span class="btn btn-primary"><i class="fas fa-arrow-left"></i><a href="departments.php" class="text-white"> Back</a></span>
                                        </div>
                                        <div class="text-right">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add New Course</button>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#update-department-hod">Edit Department HOD</button>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#update-department-<?php echo $department->id; ?>">Edit Department</button>
                                        </div>
                                    </nav>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- Update Department Modal -->
                                                <div class="modal fade" id="update-department-<?php echo $department->id; ?>">
                                                    <div class="modal-dialog  modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Fill All Values</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form method="post" enctype="multipart/form-data" role="form">
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="form-group col-md-4">
                                                                                <label for="">Department Name</label>
                                                                                <input type="text" required name="name" value="<?php echo $department->name; ?>" class="form-control" id="exampleInputEmail1">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label for="">Department Number / Code</label>
                                                                                <input type="text" required name="code" value="<?php echo $department->code; ?>" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label for="">Department HOD</label>
                                                                                <input type="text" required value="<?php echo $department->hod; ?>" name="hod" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group col-md-12">
                                                                                <label for="exampleInputPassword1">Department Details</label>
                                                                                <textarea name="details" rows="10" class="form-control Summernote"><?php echo $department->details; ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class=" text-right">
                                                                        <button type="submit" name="update_dept" class="btn btn-primary">Update Department</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--End Update Department Modal -->

                                                <!-- Update Department HOD Modal -->
                                                <div class="modal fade" id="update-department-hod">
                                                    <div class="modal-dialog  modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Update Department HOD</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form method="post" enctype="multipart/form-data" role="form">
                                                                    <div class="row">
                                                                        <div class="form-group col-md-6">
                                                                            <label for="">HOD</label>
                                                                            <select class='form-control basic' id="DepartmentHead" name="hod" onchange="getDepartmentHeadDetails(this.value);">
                                                                                <option selected>Select HOD</option>
                                                                                <?php
                                                                                $ret = "SELECT * FROM `ezanaLMS_Lecturers` ";
                                                                                $stmt = $mysqli->prepare($ret);
                                                                                $stmt->execute(); //ok
                                                                                $res = $stmt->get_result();
                                                                                while ($hods = $res->fetch_object()) {
                                                                                ?>
                                                                                    <option><?php echo $hods->name; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label for="">Email</label>
                                                                            <input type="email" required name="email" id="DepartmentHeadEmail" class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class=" text-right">
                                                                        <button type="submit" name="update_dept_hod" class="btn btn-primary">Update HOD</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--End Update Department Modal -->

                                                <!-- Add Memo / Notice Modal -->
                                                <div class="modal fade" id="add-memo">
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
                                                                                        <input name="attachments" accept='.pdf, .docx, .doc' type="file" class="custom-file-input">
                                                                                        <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Created By</label>
                                                                                <input type="text" value="<?php echo $admin->name; ?>" required name="created_by" class="form-control">
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
                                                                                <textarea name="departmental_memo" rows="10" class="form-control Summernote"></textarea>
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
                                                <!-- End Add Memo / Notice Modal -->

                                                <!-- Add Departmental Document -->
                                                <div class="modal fade" id="add-dep-doc">
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
                                                                            <div class="form-group col-md-12">
                                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                                <input type="hidden" required name="department_id" value="<?php echo $department->id; ?>" class="form-control">
                                                                                <input type="hidden" required name="department_name" value="<?php echo $department->name; ?>" class="form-control">
                                                                                <input type="hidden" required name="faculty" value="<?php echo $department->faculty_id; ?>" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Upload Departmental Document (PDF Or Docx)</label>
                                                                                <div class="input-group">
                                                                                    <div class="custom-file">
                                                                                        <input name="attachments" accept='.pdf, .docx, .doc' type="file" class="custom-file-input">
                                                                                        <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Created By</label>
                                                                                <input type="text" required value="<?php echo $admin->name; ?>" name="created_by" class="form-control">
                                                                            </div>
                                                                            <div style="display:none" class="form-group col-md-6">
                                                                                <label for="">Type</label>
                                                                                <select class='form-control basic' name="type">
                                                                                    <option selected>Departmental Document</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-footer text-right">
                                                                        <button type="submit" name="add_departmental_doc" class="btn btn-primary">Add Departmental Document</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Add Departmental Document -->

                                                <!-- Add Course Modal -->
                                                <div class="modal fade" id="modal-default">
                                                    <div class="modal-dialog  modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Fill All Required Values </h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Add Course Form -->
                                                                <form method="post" enctype="multipart/form-data" role="form">
                                                                    <div class="card-body">
                                                                        <div class="row" style="display:none">
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Department Code</label>
                                                                                <input type="text" value="<?php echo $department->code; ?>" required name="department_name" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Department Name</label>
                                                                                <input type="text" value="<?php echo $department->name; ?>" required name="department_name" class="form-control">
                                                                                <input type="hidden" value="<?php echo $department->id; ?>" readonly required name="department_id" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-12">
                                                                                <label for="">Faculty Name</label>
                                                                                <input type="text" value="<?php echo $department->faculty_name; ?>" required name="faculty_name" class="form-control">
                                                                                <input type="hidden" value="<?php echo $department->faculty_id; ?>" readonly required name="faculty_id" class="form-control">
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Course Name</label>
                                                                                <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Course Number / Code</label>
                                                                                <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Course Head</label>
                                                                                <input type="text" required name="hod" class="form-control" id="exampleInputEmail1">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Course Head Email</label>
                                                                                <input type="text" required name="email" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group col-md-12">
                                                                                <label for="exampleInputPassword1">Course Description</label>
                                                                                <textarea required name="details" id="textarea" rows="10" class="form-control Summernote"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-footer text-right">
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
                                                <!-- End Course Modal -->

                                                <br>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="card card-widget widget-user-2">
                                                                    <div class="widget-user-header text-center bg-primary">
                                                                        <h3 class=""><?php echo $department->name; ?></h3>
                                                                    </div>
                                                                    <div class="card-footer p-0">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <ul class="nav flex-column">
                                                                                    <li class="nav-item">
                                                                                        <span class="nav-link text-primary">
                                                                                            Department Code : <span class="float-right "><?php echo $department->code; ?></span>
                                                                                        </span>
                                                                                    </li>
                                                                                    <li class="nav-item">
                                                                                        <span class="nav-link text-primary">
                                                                                            Department HOD : <span class="float-right "><?php echo $department->hod; ?></span>
                                                                                        </span>
                                                                                    </li>
                                                                                    <li class="nav-item">
                                                                                        <span class="nav-link text-primary">
                                                                                            Department HOD Email  : <a href="mailto:<?php echo $department->email; ?>"><span class="float-right "><?php echo $department->email; ?></span></a>
                                                                                        </span>
                                                                                    </li>
                                                                                    <?php
                                                                                    /* Fetch Details Of Facuty That This Department Is Registered To */
                                                                                    $faculty_id = $department->faculty_id;
                                                                                    $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id= '$faculty_id' ";
                                                                                    $stmt = $mysqli->prepare($ret);
                                                                                    $stmt->execute(); //ok
                                                                                    $res = $stmt->get_result();
                                                                                    while ($faculty = $res->fetch_object()) {
                                                                                    ?>
                                                                                        <li class="nav-item">
                                                                                            <span class="nav-link text-primary">
                                                                                                Faculty Code : <span class="float-right"><?php echo $faculty->code; ?></span>
                                                                                            </span>
                                                                                        </li>

                                                                                        <li class="nav-item">
                                                                                            <span class="nav-link text-primary">
                                                                                                Faculty Name : <span class="float-right"><?php echo $faculty->name; ?></span>
                                                                                            </span>
                                                                                        </li>

                                                                                        <li class="nav-item">

                                                                                            <span class="nav-link text-primary">
                                                                                                Faculty Email : <a href="mailto:<?php echo $faculty->email; ?>"><span class="float-right"><?php echo $faculty->email; ?></a></span>
                                                                                            </span>
                                                                                        </li>

                                                                                    <?php
                                                                                    } ?>

                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <hr>
                                                                        <div class="col-md-12">
                                                                            <ul class="nav flex-column">
                                                                                <li class="nav-item">
                                                                                    <span class="nav-link text-center text-primary">
                                                                                        Department Details
                                                                                    </span>
                                                                                </li>
                                                                                <li class="nav-item">
                                                                                    <?php echo $department->details; ?>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Departmental Menu -->
                                                    <div class="col-md-8">
                                                        <div class="card">
                                                            <div class="card-header p-2">
                                                                <ul class="nav nav-pills">
                                                                    <li class="nav-item"><a class="nav-link active" href="#dept_anouncements" data-toggle="tab">Anouncements</a></li>
                                                                    <li class="nav-item"><a class="nav-link" href="#dept_memos" data-toggle="tab">Memos & Notices</a></li>
                                                                    <li class="nav-item"><a class="nav-link" href="#dept_docs" data-toggle="tab">Dept. Documents</a></li>
                                                                    <li class="nav-item"><a class="nav-link" href="#dept_courses" data-toggle="tab">Courses</a></li>
                                                                </ul>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="tab-content">
                                                                    <div class="active tab-pane" id="dept_anouncements">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="card-header">
                                                                                    Post Announcement
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-12">
                                                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                                                <input type="hidden" required name="department_id" value="<?php echo $department->id; ?>" class="form-control">
                                                                                                <input type="hidden" required name="department_name" value="<?php echo $department->name; ?>" class="form-control">
                                                                                                <input type="hidden" required name="faculty" value="<?php echo $department->faculty_id; ?>" class="form-control">
                                                                                                <input type="hidden" required name="type" value="Announcement" class="form-control">
                                                                                                <textarea name="departmental_memo" id="dep_memo" rows="3" class="form-control Summernote"></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="text-right">
                                                                                            <button type="submit" name="add_announcement" class="btn btn-primary">Post</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-12">
                                                                                <div class="card">
                                                                                    <div class="card-header">
                                                                                        Recent Posted Announcements
                                                                                    </div>
                                                                                    <div class="card-body">
                                                                                        <?php
                                                                                        $departmentId = $department->id;
                                                                                        $ret = "SELECT * FROM `ezanaLMS_DepartmentalMemos` WHERE department_id = '$departmentId' AND type = 'Announcement' ORDER BY `ezanaLMS_DepartmentalMemos`.`created_at` DESC LIMIT 10  ";
                                                                                        $stmt = $mysqli->prepare($ret);
                                                                                        $stmt->execute(); //ok
                                                                                        $res = $stmt->get_result();
                                                                                        $cnt = 1;
                                                                                        while ($announcement = $res->fetch_object()) {
                                                                                        ?>
                                                                                            <div class="list-group">
                                                                                                <div class="d-flex w-100 justify-content-between">
                                                                                                    <h5 class="mb-1"></h5>
                                                                                                    <small><?php echo date('d-M-Y', strtotime($announcement->created_at)); ?><br><?php echo date('g : ia', strtotime($announcement->created_at)); ?></small>
                                                                                                </div>
                                                                                                <p>
                                                                                                    <?php
                                                                                                    echo  $announcement->departmental_memo;
                                                                                                    ?>
                                                                                                </p>
                                                                                                <div class="row">
                                                                                                    <a class="badge badge-primary" data-toggle="modal" href="#announcement-<?php echo $announcement->id; ?>">
                                                                                                        <i class="fas fa-edit"></i>
                                                                                                        Update Announcement
                                                                                                    </a>
                                                                                                    <a class="badge badge-danger" href="department_details.php?delete=<?php echo $announcement->id; ?>&view=<?php echo $view; ?>">
                                                                                                        <i class="fas fa-trash"></i>
                                                                                                        Delete Announcement
                                                                                                    </a>
                                                                                                    <!-- Update Announcement Modal -->
                                                                                                    <div class="modal fade" id="announcement-<?php echo $announcement->id; ?>">
                                                                                                        <div class="modal-dialog  modal-xl">
                                                                                                            <div class="modal-content">
                                                                                                                <div class="modal-header">
                                                                                                                    <h4 class="modal-title">Update Announcement</h4>
                                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                                <div class="modal-body">
                                                                                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                                                                                        <div class="row">
                                                                                                                            <div class="form-group col-md-12">
                                                                                                                                <input type="hidden" required name="id" value="<?php echo $announcement->id; ?>" class="form-control">
                                                                                                                                <input type="hidden" required name="department_id" value="<?php echo $department->id; ?>" class="form-control">
                                                                                                                                <textarea name="departmental_memo" id="dep_memo" rows="3" class="form-control Summernote"><?php echo $announcement->departmental_memo; ?></textarea>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        <div class="text-right">
                                                                                                                            <button type="submit" name="update_announcement" class="btn btn-primary">Update Announcement</button>
                                                                                                                        </div>
                                                                                                                    </form>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <!-- End Modal -->
                                                                                                </div>
                                                                                            </div>
                                                                                            <hr>
                                                                                        <?php
                                                                                        } ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="tab-pane" id="dept_memos">
                                                                        <div class="col-md-12">
                                                                            <div class="card-header text-center">
                                                                                <div class="text-right">
                                                                                    <a href="#add-memo" data-toggle="modal" class=" btn btn-outline-success">
                                                                                        <i class="fas fa-file"></i>
                                                                                        Add Memo / Notice
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                            <br>
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
                                                                                    $ret = "SELECT * FROM `ezanaLMS_DepartmentalMemos` WHERE department_id = '$department->id' AND type = 'Memo' || type = 'Notice' ORDER BY created_at DESC  ";
                                                                                    $stmt = $mysqli->prepare($ret);
                                                                                    $stmt->execute(); //ok
                                                                                    $res = $stmt->get_result();
                                                                                    while ($memo = $res->fetch_object()) {
                                                                                    ?>

                                                                                        <tr>
                                                                                            <td><?php echo $memo->created_by; ?></td>
                                                                                            <td><?php echo date('d M Y', strtotime($memo->created_at)); ?></td>
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
                                                                                                                <h4 class="modal-title"><?php echo $department->name; ?> <?php echo $memo->type; ?> Created On <span class='text-success'><?php echo date('d M Y', strtotime($memo->created_at)); ?></span></h4>
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
                                                                                                                } ?>
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
                                                                                                                            <div class="form-group col-md-6">
                                                                                                                                <label for="">Type</label>
                                                                                                                                <select class='form-control basic' name="type">
                                                                                                                                    <option>Notice</option>
                                                                                                                                    <option>Memo</option>
                                                                                                                                </select>
                                                                                                                            </div>
                                                                                                                            <div class="form-group col-md-6">
                                                                                                                                <label for="">Upload Memo | Notice (PDF r Docx)</label>
                                                                                                                                <div class="input-group">
                                                                                                                                    <div class="custom-file">
                                                                                                                                        <input name="attachments" accept='.pdf, .docx, .doc' type="file" class="custom-file-input">
                                                                                                                                        <input type="hidden" required name="faculty" value="<?php echo $department->faculty_id; ?>" class="form-control">
                                                                                                                                        <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <div class="form-group col-md-12">
                                                                                                                                <label for="">Created By</label>
                                                                                                                                <input type="text" name="created_by" class="form-control" value="<?php echo $memo->created_by; ?>">
                                                                                                                                <input type="hidden" required name="id" value="<?php echo $memo->id; ?>" class="form-control">
                                                                                                                                <input type="hidden" required name="department_id" value="<?php echo $view; ?>" class="form-control">

                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        <h2 class="text-center">Or </h2>
                                                                                                                        <div class="row">
                                                                                                                            <div class="form-group col-md-12">
                                                                                                                                <label for="exampleInputPassword1">Type Departmental Memo | Notice</label>
                                                                                                                                <textarea name="departmental_memo" rows="10" class="form-control Summernote"><?php echo $memo->departmental_memo; ?></textarea>
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
                                                                                                                <a href="department_details.php?delete=<?php echo $memo->id; ?>&view=<?php echo $memo->department_id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php
                                                                                    } ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                    <div class="tab-pane" id="dept_docs">
                                                                        <div class="col-md-12">
                                                                            <div class="card-header ">
                                                                                <div class="text-right">
                                                                                    <a href="#add-dep-doc" data-toggle="modal" class=" btn btn-outline-success">
                                                                                        <i class="fas fa-file"></i>
                                                                                        Add Departmental Document
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                            <br>
                                                                            <table id="faculties" class="table table-bordered table-striped">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Posted By</th>
                                                                                        <th>Date Posted</th>
                                                                                        <th>Manage</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    $ret = "SELECT * FROM `ezanaLMS_DepartmentalMemos` WHERE department_id = '$department->id' AND type = 'Departmental Document'  ";
                                                                                    $stmt = $mysqli->prepare($ret);
                                                                                    $stmt->execute(); //ok
                                                                                    $res = $stmt->get_result();
                                                                                    while ($dep_doc = $res->fetch_object()) {
                                                                                    ?>

                                                                                        <tr>
                                                                                            <td><?php echo $dep_doc->created_by; ?></td>
                                                                                            <td><?php echo date('d M Y g:ia', strtotime($dep_doc->created_at)); ?></td>
                                                                                            <td>
                                                                                                <a class="badge badge-success" data-toggle="modal" href="#view-<?php echo $dep_doc->id; ?>">
                                                                                                    <i class="fas fa-eye"></i>
                                                                                                    View
                                                                                                </a>
                                                                                                <!-- View Deptmental Memo Modal -->
                                                                                                <div class="modal fade" id="view-<?php echo $dep_doc->id; ?>">
                                                                                                    <div class="modal-dialog  modal-lg">
                                                                                                        <div class="modal-content">
                                                                                                            <div class="modal-header text-center">
                                                                                                                <h4 class="modal-title"><?php echo $department->name . "  " . $dep_doc->type; ?> Created On <br> <span class='text-success'><?php echo date('d M Y g:ia', strtotime($dep_doc->created_at)); ?></span></h4>
                                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                                </button>
                                                                                                            </div>
                                                                                                            <div class="modal-body text-center">
                                                                                                                <?php
                                                                                                                if ($dep_doc->attachments != '') {
                                                                                                                    echo
                                                                                                                    "<a href='public/uploads/EzanaLMSData/memos/$dep_doc->attachments' target='_blank' class='btn btn-outline-success'><i class='fas fa-download'></i> Download $dep_doc->type </a>";
                                                                                                                } else {
                                                                                                                    echo
                                                                                                                    "<a  class='btn btn-outline-danger'><i class='fas fa-times'></i> $dep_doc->type Attachment Not Available </a>";
                                                                                                                } ?>

                                                                                                            </div>
                                                                                                            <div class="modal-footer justify-content-between">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                    </div>
                                                                                                </div>
                                                                                                <a class="badge badge-primary" data-toggle="modal" href="#update-<?php echo $dep_doc->id; ?>">
                                                                                                    <i class="fas fa-edit"></i>
                                                                                                    Update
                                                                                                </a>
                                                                                                <!-- Update Departmental Memo Modal -->
                                                                                                <div class="modal fade" id="update-<?php echo $dep_doc->id; ?>">
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
                                                                                                                            <div class="form-group col-md-12">
                                                                                                                                <input type="hidden" required name="id" value="<?php echo $dep_doc->id; ?>" class="form-control">
                                                                                                                                <input type="hidden" required name="department_id" value="<?php echo $department->id; ?>" class="form-control">
                                                                                                                                <input type="hidden" required name="department_name" value="<?php echo $department->name; ?>" class="form-control">
                                                                                                                                <input type="hidden" required name="faculty" value="<?php echo $department->faculty_id; ?>" class="form-control">
                                                                                                                            </div>
                                                                                                                            <div class="form-group col-md-6">
                                                                                                                                <label for="">Upload Departmental Document (PDF Or Docx)</label>
                                                                                                                                <div class="input-group">
                                                                                                                                    <div class="custom-file">
                                                                                                                                        <input name="attachments" accept='.pdf, .docx, .doc' type="file" class="custom-file-input">
                                                                                                                                        <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <div class="form-group col-md-6">
                                                                                                                                <label for="">Created By</label>
                                                                                                                                <input type="text" value="<?php echo $dep_doc->created_by; ?>" required name="created_by" class="form-control">
                                                                                                                            </div>
                                                                                                                            <div style="display:none" class="form-group col-md-6">
                                                                                                                                <label for="">Type</label>
                                                                                                                                <select class='form-control basic' name="type">
                                                                                                                                    <option selected>Departmental Document</option>
                                                                                                                                </select>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="card-footer text-right">
                                                                                                                        <button type="submit" name="update_departmental_doc" class="btn btn-primary">Update Departmental Document</button>
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
                                                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $dep_doc->id; ?>">
                                                                                                    <i class="fas fa-trash"></i>
                                                                                                    Delete
                                                                                                </a>
                                                                                                <!-- Delete Confirmation -->
                                                                                                <div class="modal fade" id="delete-<?php echo $dep_doc->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                                        <div class="modal-content">
                                                                                                            <div class="modal-header">
                                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                                </button>
                                                                                                            </div>
                                                                                                            <div class="modal-body text-center text-danger">
                                                                                                                <h4>Delete This <?php echo $dep_doc->type; ?> ?</h4>
                                                                                                                <br>
                                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                                <a href="department_details.php?delete=<?php echo $dep_doc->id; ?>&view=<?php echo $dep_doc->department_id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php
                                                                                    } ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                    <div class="tab-pane" id="dept_courses">
                                                                        <div class="col-md-12">

                                                                            <!-- Department Courses -->
                                                                            <table id="courses" class="table table-bordered table-striped">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Code</th>
                                                                                        <th>Name</th>
                                                                                        <th>Head</th>
                                                                                        <th>Email</th>
                                                                                        <th>Manage</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE department_id  = '$department->id'";
                                                                                    $stmt = $mysqli->prepare($ret);
                                                                                    $stmt->execute(); //ok
                                                                                    $res = $stmt->get_result();
                                                                                    while ($courses = $res->fetch_object()) {
                                                                                    ?>
                                                                                        <tr>
                                                                                            <td><?php echo $courses->code; ?></td>
                                                                                            <td><?php echo $courses->name; ?></td>
                                                                                            <td><?php echo $courses->hod; ?></td>
                                                                                            <td><?php echo $courses->email; ?></td>
                                                                                            <td>
                                                                                                <a class="badge badge-success" href="course.php?view=<?php echo $courses->id; ?>">
                                                                                                    <i class="fas fa-eye"></i>
                                                                                                    View
                                                                                                </a>
                                                                                                <a class="badge badge-primary" data-toggle="modal" href="#edit-course-<?php echo $courses->id; ?>">
                                                                                                    <i class="fas fa-edit"></i>
                                                                                                    Update
                                                                                                </a>
                                                                                                <!-- Update Course Modal -->
                                                                                                <div class="modal fade" id="edit-course-<?php echo $courses->id; ?>">
                                                                                                    <div class="modal-dialog  modal-xl">
                                                                                                        <div class="modal-content">
                                                                                                            <div class="modal-header">
                                                                                                                <h4 class="modal-title">Fill All Required Values </h4>
                                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                                </button>
                                                                                                            </div>
                                                                                                            <div class="modal-body">
                                                                                                                <!-- Update Course Form -->
                                                                                                                <form method="post" enctype="multipart/form-data" role="form">
                                                                                                                    <div class="card-body">
                                                                                                                        <div class="row">
                                                                                                                            <div class="form-group col-md-6">
                                                                                                                                <label for="">Course Name</label>
                                                                                                                                <input type="text" required name="name" value="<?php echo $courses->name; ?>" class="form-control">
                                                                                                                                <input type="hidden" required name="id" value="<?php echo $courses->id; ?>" class="form-control">
                                                                                                                                <input type="hidden" required name="view" value="<?php echo $department->id; ?>" class="form-control">
                                                                                                                            </div>
                                                                                                                            <div class="form-group col-md-6">
                                                                                                                                <label for="">Course Number / Code</label>
                                                                                                                                <input type="text" required name="code" value="<?php echo $courses->code; ?>"" class=" form-control">
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        <div class="row">
                                                                                                                            <div class="form-group col-md-6">
                                                                                                                                <label for="">Course Head</label>
                                                                                                                                <input type="text" required value="<?php echo $courses->hod; ?>" name="hod" class="form-control" id="exampleInputEmail1">
                                                                                                                            </div>
                                                                                                                            <div class="form-group col-md-6">
                                                                                                                                <label for="">Course Head</label>
                                                                                                                                <input type="text" required value="<?php echo $courses->email; ?>" name="email" class="form-control">
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        <div class="row">
                                                                                                                            <div class="form-group col-md-12">
                                                                                                                                <label for="exampleInputPassword1">Course Description</label>
                                                                                                                                <textarea required name="details" rows="10" class="form-control Summernote"><?php echo $courses->details; ?></textarea>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="card-footer text-right">
                                                                                                                        <button type="submit" name="update_course" class="btn btn-primary">Update</button>
                                                                                                                    </div>
                                                                                                                </form>

                                                                                                            </div>
                                                                                                            <div class="modal-footer justify-content-between">
                                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <!-- End Update Modal -->
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php
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
            }
        } ?>
</body>

</html>