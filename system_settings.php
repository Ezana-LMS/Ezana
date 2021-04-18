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
check_login();

/* System Settings */
if (isset($_POST['systemSettings'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['sysname']) && !empty($_POST['sysname'])) {
        $sysname = mysqli_real_escape_string($mysqli, trim($_POST['sysname']));
    } else {
        $error = 1;
        $err = "System Name Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $version = $_POST['version'];
        $sysname = $_POST['sysname'];
        $logo = $_FILES['logo']['name'];
        move_uploaded_file($_FILES["logo"]["tmp_name"], "public/dist/img/" . $_FILES["logo"]["name"]);

        $query = "UPDATE ezanaLMS_Settings SET sysname =?, logo =?, version=? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssss',  $sysname,  $logo, $version, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Settings Updated" && header("refresh:1; url=system_settings.php");
        } else {
            //inject alert that profile update task failed
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* System Calendar */
if (isset($_POST['Calendar_Iframe'])) {

    $id = $_POST['id'];
    $calendar_iframe = $_POST['calendar_iframe'];

    $query = "UPDATE ezanaLMS_Settings SET calendar_iframe =? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ss',  $calendar_iframe, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Settings Updated" && header("refresh:1; url=system_settings.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* System Mail Settings */
if (isset($_POST['mailSettings'])) {

    $id = $_POST['id'];
    $stmp_host = $_POST['stmp_host'];
    $stmp_port = $_POST['stmp_port'];
    $stmp_sent_from = $_POST['stmp_sent_from'];
    $stmp_username = $_POST['stmp_username'];
    $stmp_password = $_POST['stmp_password'];

    $query = "UPDATE ezanaLMS_Settings SET  stmp_host =?, stmp_port =?, stmp_sent_from =?, stmp_username =?, stmp_password =? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssss',  $stmp_host, $stmp_port, $stmp_sent_from, $stmp_username, $stmp_password, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Settings Updated" && header("refresh:1; url=system_settings.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}


/* Current Academic Year And Academic Semester */
if (isset($_POST['CurrentAcademicTerm'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['current_academic_year']) && !empty($_POST['current_academic_year'])) {
        $current_academic_year = mysqli_real_escape_string($mysqli, trim($_POST['current_academic_year']));
    } else {
        $error = 1;
        $err = "Current Academic Year Cannot Be Empty";
    }
    if (isset($_POST['current_semester']) && !empty($_POST['current_semester'])) {
        $current_semester = mysqli_real_escape_string($mysqli, trim($_POST['current_semester']));
    } else {
        $error = 1;
        $err = "Current Academic Year Cannot Be Empty";
    }
    if (isset($_POST['end_date']) && !empty($_POST['end_date'])) {
        $end_date = mysqli_real_escape_string($mysqli, trim($_POST['end_date']));
    } else {
        $error = 1;
        $err = "Semester Closing Date   Cannot Be Empty";
    }
    if (isset($_POST['start_date']) && !empty($_POST['start_date'])) {
        $start_date = mysqli_real_escape_string($mysqli, trim($_POST['start_date']));
    } else {
        $error = 1;
        $err = "Semester Start Date  Cannot Be Empty";
    }

    if (!$error) {
        $id = $_POST['id'];
        $current_academic_year = $_POST['current_academic_year'];
        $current_semester = $_POST['current_semester'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        $query = "UPDATE ezanaLMS_AcademicSettings SET current_academic_year =?, current_semester =?, start_date =?, end_date = ? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssss',  $current_academic_year,  $current_semester, $start_date, $end_date, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Settings Updated" && header("refresh:1; url=system_settings.php");
        } else {
            //inject alert that profile update task failed
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Delete Faculty */
if (isset($_GET['delete_faculty'])) {
    $delete = $_GET['delete_faculty'];
    $adn = "DELETE FROM ezanaLMS_Faculties WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_settings.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Departments */
if (isset($_GET['delete_department'])) {
    $delete = $_GET['delete_department'];
    $adn = "DELETE FROM ezanaLMS_Departments WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Department Details Deleted" && header("refresh:1; url=system_settings.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Courses */
if (isset($_GET['delete_course'])) {
    $delete = $_GET['delete_course'];
    $adn = "DELETE FROM ezanaLMS_Courses WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_settings.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Modules */
if (isset($_GET['delete_module'])) {
    $delete = $_GET['delete_module'];
    $adn = "DELETE FROM ezanaLMS_Modules WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_settings.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Student */
if (isset($_GET['delete_student'])) {
    $delete = $_GET['delete_student'];
    $adn = "DELETE FROM ezanaLMS_Students WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_settings.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Lecturers */
if (isset($_GET['delete_lecturer'])) {
    $delete = $_GET['delete_lecturer'];
    $adn = "DELETE FROM ezanaLMS_Lecturers WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_settings.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete On Non Teaching Staff */
if (isset($_GET['delete_staff'])) {
    $delete = $_GET['delete_staff'];
    $adn = "DELETE FROM ezanaLMS_Admins WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_settings.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Add Faculty */
if (isset($_POST['add_faculty'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Faculty Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Faculty Name Cannot Be Empty";
    }
    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Faculties WHERE  code='$code' || name ='$name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Faculty With This Code Already Exists";
            } else {
                $err = "Faculty Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $details = $_POST['details'];
            $head = $_POST['head'];
            $email = $_POST['email'];

            $query = "INSERT INTO ezanaLMS_Faculties (id, code, name, details, head, email) VALUES(?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssss', $id, $code, $name, $details, $head, $email);
            $stmt->execute();
            if ($stmt) {
                $success = "Added" && header("refresh:1; url=system_settings.php");
            } else {
                //inject alert that profile update task failed
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/* Add Departments */
if (isset($_POST['add_dept'])) {
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
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Departments WHERE  code='$code' || name ='$name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Department With This Code Already Exists";
            } else {
                $err = "Department Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $faculty = $_POST['faculty'];
            $details = $_POST['details'];
            $hod = $_POST['hod'];
            $faculty_name = $_POST['faculty_name'];

            $query = "INSERT INTO ezanaLMS_Departments (id, code, name, faculty_id, faculty_name, details, hod) VALUES(?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('sssssss', $id, $code, $name, $faculty, $faculty_name, $details, $hod);
            $stmt->execute();
            if ($stmt) {
                $success = "$name Department Added" && header("refresh:1; url=system_settings.php");;
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

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
                $success = "Course Added" && header("refresh:1; url=system_settings.php");
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/* Add Module */
if (isset($_POST['add_module'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Module Code Cannot Be Empty";
    }
    if (isset($_POST['course_name']) && !empty($_POST['course_name'])) {
        $course_name = mysqli_real_escape_string($mysqli, trim($_POST['course_name']));
    } else {
        $error = 1;
        $err = "Course Name Cannot Be Empty";
    }
    if (isset($_POST['course_id']) && !empty($_POST['course_id'])) {
        $course_id = mysqli_real_escape_string($mysqli, trim($_POST['course_id']));
    } else {
        $error = 1;
        $err = "Course ID Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Modules WHERE  code='$code' || name ='$name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Module With This Code Already Exists";
            } else {
                $err = "Module  Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $details = $_POST['details'];
            $course_name = $_POST['course_name'];
            $course_id = $_POST['course_id'];
            $course_duration = $_POST['course_duration'];
            $exam_weight_percentage = $_POST['exam_weight_percentage'];
            $cat_weight_percentage = $_POST['cat_weight_percentage'];
            $lectures_number = $_POST['lectures_number'];
            $created_at = date('d M Y');
            $faculty_id = $_POST['faculty_id'];

            $query = "INSERT INTO ezanaLMS_Modules (id, name, code, details, course_name, course_id, course_duration, exam_weight_percentage, cat_weight_percentage, lectures_number, created_at, faculty_id) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssssssss', $id, $name, $code, $details, $course_name, $course_id, $course_duration, $exam_weight_percentage, $cat_weight_percentage, $lectures_number, $created_at, $faculty_id);
            $stmt->execute();
            if ($stmt) {
                $success = "Module Added" && header("refresh:1; url=system_settings.php");
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

require_once('configs/codeGen.php');
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
                            <a href="#" class="active nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    System Settings
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="reports.php" class=" nav-link">
                                        <i class="fas fa-angle-right nav-icon"></i>
                                        <p>Reports</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="system_settings.php" class="active nav-link">
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
                            <h1 class="m-0">System Settings</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="reports.php">System</a></li>
                                <li class="breadcrumb-item active">System Settings</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-primary card-outline">
                                    <div class="card-body">
                                        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="pill" href="#customization" role="tab">Customization</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="pill" href="#academic_settings" role="tab">Academic Settings</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="pill" href="#google_calendar" role="tab">Google Calendar Settings</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="pill" href="#add_functionality" role="tab">Add Functionality</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="pill" href="#utilities" role="tab">Ezana LMS Utilities </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="pill" href="#email" role="tab">Email Settings</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link text-danger" data-toggle="pill" href="#delete_functionalities" role="tab">Delete Functionalities</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="custom-content-below-tabContent">
                                            <div class="tab-pane fade show active" id="customization" role="tabpanel">
                                                <br>
                                                <?php
                                                /* Persisit System Settings On Brand */
                                                $ret = "SELECT * FROM `ezanaLMS_Settings` ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($sys = $res->fetch_object()) {
                                                ?>
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-md-4">
                                                                    <label for="">System Name</label>
                                                                    <input type="text" required name="sysname" value="<?php echo $sys->sysname; ?>" class="form-control">
                                                                    <input type="hidden" required name="id" value="<?php echo $sys->id ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="">System Version</label>
                                                                    <input type="text" required name="version" value="<?php echo $sys->version; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="">System Logo</label>
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input required name="logo" type="file" class="custom-file-input">
                                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit" name="systemSettings" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                <?php
                                                } ?>
                                            </div>

                                            <div class="tab-pane fade show " id="academic_settings" role="tabpanel">
                                                <br>
                                                <?php
                                                /* Persisit Academic Settings */
                                                $ret = "SELECT * FROM `ezanaLMS_AcademicSettings` ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($academic_settings = $res->fetch_object()) {
                                                ?>
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Current Academic Year</label>
                                                                    <input type="text" required value="<?php echo $academic_settings->current_academic_year; ?> " name="current_academic_year" class="form-control">
                                                                    <input type="hidden" required name="id" value="<?php echo $academic_settings->id ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Current Semester</label>
                                                                    <input type="text" required value="<?php echo $academic_settings->current_semester; ?>" name="current_semester" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Current Semester Opening Date</label>
                                                                    <input type="date" required value="<?php echo $academic_settings->start_date; ?>" name="start_date" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Current Semester Closing Dates</label>
                                                                    <input type="date" required value="<?php echo $academic_settings->end_date; ?>" name="end_date" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit" name="CurrentAcademicTerm" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                <?php
                                                } ?>
                                            </div>

                                            <div class="tab-pane fade show " id="delete_functionalities" role="tabpanel">
                                                <br>
                                                <p class="text-danger">Select Any Module To Access Delete Functionalities</p>
                                                <div class="col-md-12">
                                                    <div class="card collapsed-card">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Faculties</h3>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                                            </div>
                                                            <!-- /.card-tools -->
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <table id="faculties" class=" table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Code Number</th>
                                                                        <th>Name</th>
                                                                        <th>Head</th>
                                                                        <th>Email</th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Faculties`  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($faculty = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $faculty->code; ?></td>
                                                                            <td><?php echo $faculty->name; ?></td>
                                                                            <td><?php echo $faculty->head; ?></td>
                                                                            <td><?php echo $faculty->email; ?></td>
                                                                            <td>
                                                                                <!-- End Update Modal -->
                                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $faculty->id; ?>"> <i class="fas fa-trash"></i> Delete</a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $faculty->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $faculty->name; ?> ?</h4>
                                                                                                <br>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_settings.php?delete_faculty=<?php echo $faculty->id; ?>" class="text-center btn btn-danger"> Delete </a>
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
                                                        <!-- /.card-body -->
                                                    </div>

                                                    <!-- Departments -->
                                                    <div class="card collapsed-card ">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Departments</h3>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                                            </div>
                                                            <!-- /.card-tools -->
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <table id="departments" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Code</th>
                                                                        <th>Name</th>
                                                                        <th>HOD</th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Departments` ORDER BY `name` ASC   ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($dep = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $dep->code; ?></td>
                                                                            <td><?php echo $dep->name; ?></td>
                                                                            <td><?php echo $dep->hod; ?></td>
                                                                            <td>

                                                                                <a class="badge badge-danger" href="#delete-<?php echo $dep->id; ?>" data-toggle="modal">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $dep->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $dep->name; ?> ?</h4>
                                                                                                <br>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_settings.php?delete_department=<?php echo $dep->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- End Delete Confirmation Modal -->
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                    } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>

                                                    <!-- Courses -->
                                                    <div class="card collapsed-card ">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Courses</h3>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                                            </div>
                                                            <!-- /.card-tools -->
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <table id="courses" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Code</th>
                                                                        <th>Name</th>
                                                                        <th>Department</th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Courses`";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    $cnt = 1;
                                                                    while ($courses = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $courses->code; ?></td>
                                                                            <td><?php echo $courses->name; ?></td>
                                                                            <td><?php echo $courses->department_name; ?></td>
                                                                            <td>
                                                                                <a class="badge badge-danger" href="#delete-<?php echo $courses->id; ?>" data-toggle="modal">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $courses->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $courses->name; ?> ?</h4>
                                                                                                <br>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_settings.php?delete_course=<?php echo $courses->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- End Delete Confirmation Modal -->
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                    } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>

                                                    <!-- Modules -->
                                                    <div class="card collapsed-card">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Modules</h3>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                                            </div>
                                                            <!-- /.card-tools -->
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <table id="modules" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Name</th>
                                                                        <th>Code</th>
                                                                        <th>Course</th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Modules`  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    $cnt = 1;
                                                                    while ($mod = $res->fetch_object()) {
                                                                    ?>

                                                                        <tr>
                                                                            <td><?php echo $mod->name; ?></td>
                                                                            <td><?php echo $mod->code; ?></td>
                                                                            <td><?php echo $mod->course_name; ?></td>
                                                                            <td>
                                                                                <!-- End Modal -->
                                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $mod->id; ?>">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $mod->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $mod->name; ?> ?</h4>
                                                                                                <br>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_settings.php?delete_module=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- End Delete Confirmation Modal -->
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                    } ?>
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>

                                                    <!-- Non Teaching Staff -->
                                                    <div class="card collapsed-card ">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Non Teaching Staffs</h3>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                                            </div>
                                                            <!-- /.card-tools -->
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <table id="example1" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Name</th>
                                                                        <th>Email</th>
                                                                        <th>Phone</th>
                                                                        <th>Rank</th>
                                                                        <th>Manage</th>
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
                                                                            <td><?php echo $admin->name; ?></td>
                                                                            <td><?php echo $admin->email; ?></td>
                                                                            <td><?php echo $admin->phone; ?></td>
                                                                            <td><?php echo $admin->rank; ?></td>
                                                                            <td>
                                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $admin->id; ?>">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->

                                                                                <div class="modal fade" id="delete-<?php echo $admin->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $admin->name; ?> Details ?</h4>
                                                                                                <br>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_settings.php?delete_staff=<?php echo $admin->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- End Delete Confirmation Modal -->
                                                                            </td>
                                                                        </tr>
                                                                    <?php $cnt = $cnt + 1;
                                                                    } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>

                                                    <!-- Lecturers -->
                                                    <div class="card collapsed-card ">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Lecturers</h3>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                                            </div>
                                                            <!-- /.card-tools -->
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <table id="lecturers" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Number</th>
                                                                        <th>Name</th>
                                                                        <th>Email</th>
                                                                        <th>Phone</th>
                                                                        <th>ID/Passport </th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Lecturers`  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($lec = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $lec->number; ?></td>
                                                                            <td><?php echo $lec->name; ?></td>
                                                                            <td><?php echo $lec->email; ?></td>
                                                                            <td><?php echo $lec->phone; ?></td>
                                                                            <td><?php echo $lec->idno; ?></td>
                                                                            <td>

                                                                                <!-- End Lec Modal -->
                                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $lec->id; ?>">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $lec->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $lec->name; ?> Details ?</h4>
                                                                                                <br>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_settings.php?delete_lecturer=<?php echo $lec->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- End Delete Confirmation Modal -->
                                                                            </td>
                                                                        </tr>
                                                                    <?php $cnt = $cnt + 1;
                                                                    } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>

                                                    <!-- Students -->
                                                    <div class="card collapsed-card ">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Students</h3>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                                            </div>
                                                            <!-- /.card-tools -->
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <table id="students" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Adm No</th>
                                                                        <th>Name</th>
                                                                        <th>Email</th>
                                                                        <th>Phone</th>
                                                                        <th>ID/Passport</th>
                                                                        <th>Gender</th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Students` ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    $cnt = 1;
                                                                    while ($std = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $std->admno; ?></td>
                                                                            <td><?php echo $std->name; ?></td>
                                                                            <td><?php echo $std->email; ?></td>
                                                                            <td><?php echo $std->phone; ?></td>
                                                                            <td><?php echo $std->idno; ?></td>
                                                                            <td><?php echo $std->gender; ?></td>
                                                                            <td>
                                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $std->id; ?>">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $std->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $std->name; ?> Details ?</h4>
                                                                                                <br>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_settings.php?delete_student=<?php echo $std->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- End Delete Confirmation Modal -->
                                                                            </td>
                                                                        </tr>
                                                                    <?php $cnt = $cnt + 1;
                                                                    } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="tab-pane fade show " id="utilities" role="tabpanel">
                                                <br>
                                                <div class="text-center">
                                                    <a href="system_database_dump.php" target="_blank" class="btn btn-primary">Backup System Database</a>
                                                    <a href="FileManager/" target="_blank" class="btn btn-primary">Ezana LMS Files Explorer</a>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade show " id="email" role="tabpanel">
                                                <br>
                                                <?php
                                                /* Persisit System Settings On Brand */
                                                $ret = "SELECT * FROM `ezanaLMS_Settings` ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($sys = $res->fetch_object()) {
                                                ?>
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-md-4">
                                                                    <label for="">STMP Host</label>
                                                                    <input type="text" required name="stmp_host" value="<?php echo $sys->stmp_host; ?>" class="form-control">
                                                                    <input type="hidden" required name="id" value="<?php echo $sys->id ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="">STMP Port</label>
                                                                    <input type="text" required name="stmp_port" value="<?php echo $sys->stmp_port; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="">Send Mails From</label>
                                                                    <input type="text" required name="stmp_sent_from" value="<?php echo $sys->stmp_sent_from; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">STMP Username</label>
                                                                    <input type="text" required name="stmp_username" value="<?php echo $sys->stmp_username; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">STMP Password</label>
                                                                    <input type="password" required name="stmp_password" value="<?php echo $sys->stmp_password; ?>" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit" name="mailSettings" class="btn btn-primary">Save</button>
                                                        </div>
                                                    </form>
                                                <?php
                                                } ?>
                                                
                                            </div>

                                            <div class="tab-pane fade show " id="google_calendar" role="tabpanel">
                                                <br>
                                                <?php
                                                /* Persisit System Settings On Brand */
                                                $ret = "SELECT * FROM `ezanaLMS_Settings` ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($sys = $res->fetch_object()) {
                                                ?>
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="">Google Calendar Iframe Embed Code</label>
                                                                    <textarea type="text" rows='5' required name="calendar_iframe" class="form-control"><?php echo $sys->calendar_iframe; ?></textarea>
                                                                    <input type="hidden" required name="id" value="<?php echo $sys->id ?>" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit" name="Calendar_Iframe" class="btn btn-primary">Update Calendar</button>
                                                        </div>
                                                    </form>
                                                <?php
                                                } ?>

                                            </div>

                                            <div class="tab-pane fade show " id="add_functionality" role="tabpanel">
                                                <br>
                                                <div class="text-center">
                                                    <h5>Add Faculty</h5>
                                                </div>
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Faculty Name</label>
                                                                <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Faculty Number / Code</label>
                                                                <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Faculty Head</label>
                                                                <input type="text" required name="head" class="form-control" id="exampleInputEmail1">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Faculty Head Email</label>
                                                                <input type="email" required name="email" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputPassword1">Faculty Description</label>
                                                                <textarea name="details" rows="10" class="form-control Summernote"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit" name="add_faculty" class=" btn btn-primary">Add Faculty</button>
                                                    </div>
                                                </form>
                                                <hr>
                                                <div class="text-center">
                                                    <h5>Add Department</h5>
                                                </div>
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Faculty Code</label>
                                                                <select class='form-control basic' id="FacultyCode" onchange="OptimizedFacultyDetails(this.value);">
                                                                    <option selected>Select Faculty Code</option>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Faculties` ORDER BY `code` ASC  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($fac = $res->fetch_object()) {
                                                                    ?>
                                                                        <option><?php echo $fac->code; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <input type="hidden" required name="faculty" id="FacultyID" class="form-control">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Faculty Name</label>
                                                                <input type="text" required name="faculty_name" class="form-control" id="FacultyName">
                                                            </div>

                                                            <div class="form-group col-md-4">
                                                                <label for="">Department Name</label>
                                                                <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Department Number / Code</label>
                                                                <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">HOD</label>
                                                                <input type="text" required name="hod" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputPassword1">Department Details</label>
                                                                <textarea name="details" rows="10" class="form-control Summernote"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer text-right">
                                                        <button type="submit" name="add_dept" class="btn btn-primary">Add Department</button>
                                                    </div>
                                                </form>
                                                <hr>
                                                <div class="text-center">
                                                    <h5>Add Course</h5>
                                                </div>
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Department Code</label>
                                                                <select class='form-control basic' id="DepCode" onchange="getDepartmentDetailsOnDocuments(this.value);">
                                                                    <option selected>Select Department Code</option>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Departments`  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($dep = $res->fetch_object()) {
                                                                    ?>
                                                                        <option><?php echo $dep->code; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Department Name</label>
                                                                <input type="text" id="DepName" required name="department_name" class="form-control">
                                                                <input type="hidden" id="DepID" readonly required name="department_id" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="">Faculty Name</label>
                                                                <input type="text" id="DepFacName" required name="faculty_name" class="form-control">
                                                                <input type="hidden" id="DepFacID" readonly required name="faculty_id" class="form-control">
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
                                                                <label for="">Course HOD Name</label>
                                                                <input type="text" required name="hod" class="form-control" id="exampleInputEmail1">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Course HOD Email</label>
                                                                <input type="text" required name="email" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputPassword1">Course Description</label>
                                                                <textarea required name="details" rows="10" class="form-control Summernote"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer text-right">
                                                        <button type="submit" name="add_course" class="btn btn-primary">Add Course</button>
                                                    </div>
                                                </form>
                                                <hr>
                                                <div class="text-center">
                                                    <h5>Add Module</h5>
                                                </div>
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Module Name</label>
                                                                <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Module Number / Code</label>
                                                                <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Course Code</label>
                                                                <select class='form-control basic' id="ModuleCourseCode" onchange="optimizedCourseDetails(this.value);">
                                                                    <option selected>Select Course Code</option>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Courses` ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($course = $res->fetch_object()) {
                                                                    ?>
                                                                        <option><?php echo $course->code; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Course Name</label>
                                                                <input type="text" id="ModuleCourseName" required name="course_name" class="form-control">
                                                                <input type="hidden" readonly id="ModuleCourseID" required name="course_id" class="form-control">
                                                                <input type="hidden" readonly id="ModuleCourseFacultyID" required name="faculty_id" class="form-control">

                                                            </div>
                                                        </div>


                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Teaching Duration</label>
                                                                <input type="text" required name="course_duration" class="form-control" id="exampleInputEmail1">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Number Of Lectures Per Week</label>
                                                                <input type="text" required name="lectures_number" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Module CAT Weight Percentage</label>
                                                                <input type="text" required name="cat_weight_percentage" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Module End Exam Weight Percentage</label>
                                                                <input type="text" required name="exam_weight_percentage" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputPassword1">Module Details</label>
                                                                <textarea required name="details" rows="10" class="form-control Summernote"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer text-right">
                                                        <button type="submit" name="add_module" class="btn btn-primary">Add Module</button>
                                                    </div>
                                                </form>

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
        <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>