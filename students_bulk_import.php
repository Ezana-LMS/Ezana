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
require_once('configs/codeGen.php');

/* Add STD */
if (isset($_POST['add_student'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (!$error) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $admno = $_POST['admno'];
        $idno = $_POST['idno'];
        $adr = $_POST['adr'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $acc_status = 'Active';
        $created_at = date('d M Y');
        $password = sha1(md5($_POST['password']));
        $faculty_id = $_POST['faculty_id'];
        $day_enrolled = $_POST['day_enrolled'];
        $school = $_POST['school'];
        $course = $_POST['course'];
        $department = $_POST['department'];
        $current_year = $_POST['current_year'];
        $no_of_modules = $_POST['no_of_modules'];

        $profile_pic = $_FILES['profile_pic']['name'];
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "public/uploads/UserImages/students/" . $_FILES["profile_pic"]["name"]);

        $sql = "SELECT * FROM ezanaLMS_Students WHERE email='$email' || phone ='$phone' || idno = '$idno' || admno ='$admno' ";

        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($email == $row['email']) {
                $err = "Account With This Email Already Exists";
            } elseif ($admno == $row['admno']) {
                $err = "Student Admission Number Already Exists";
            } elseif ($idno == $row['idno']) {
                $err = "National ID Number / Passport Number Already Exists";
            } else {
                $err = "Account With That Phone Number Exists";
            }
        } else {

            $query = "INSERT INTO ezanaLMS_Students (id, faculty_id, day_enrolled, school, course, department, current_year, no_of_modules, name, email, phone, admno, idno, adr, dob, gender, acc_status, created_at, password, profile_pic) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssssssssssssssss', $id, $faculty_id, $day_enrolled, $school, $course, $department, $current_year, $no_of_modules, $name, $email, $phone, $admno, $idno, $adr, $dob, $gender, $acc_status, $created_at, $password, $profile_pic);
            $stmt->execute();
            if ($stmt) {
                $success = "Student Add " && header("refresh:1; url=students.php");
            } else {
                //inject alert that profile update task failed
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/* Bulk Import On Students */

use EzanaLmsAPI\DataSource;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

require_once('configs/DataSource.php');
$db = new DataSource();
$conn = $db->getConnection();
require_once('vendor/autoload.php');

if (isset($_POST["upload"])) {

    $allowedFileType = [
        'application/vnd.ms-excel',
        'text/xls',
        'text/xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    /* Where Magic Happens */

    if (in_array($_FILES["file"]["type"], $allowedFileType)) {

        $targetPath = 'public/uploads/EzanaLMSData/XLSFiles/' . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

        $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadSheet = $Reader->load($targetPath);
        $excelSheet = $spreadSheet->getActiveSheet();
        $spreadSheetAry = $excelSheet->toArray();
        $sheetCount = count($spreadSheetAry);

        for ($i = 1; $i <= $sheetCount; $i++) {

            $id = "";
            if (isset($spreadSheetAry[$i][0])) {
                $id = mysqli_real_escape_string($conn, $spreadSheetAry[$i][0]);
            }

            $admno = "";
            if (isset($spreadSheetAry[$i][1])) {
                $admno = mysqli_real_escape_string($conn, $spreadSheetAry[$i][1]);
            }

            $name = "";
            if (isset($spreadSheetAry[$i][2])) {
                $name = mysqli_real_escape_string($conn, $spreadSheetAry[$i][2]);
            }

            $email = "";
            if (isset($spreadSheetAry[$i][3])) {
                $email = mysqli_real_escape_string($conn, $spreadSheetAry[$i][3]);
            }

            $phone = "";
            if (isset($spreadSheetAry[$i][4])) {
                $phone = mysqli_real_escape_string($conn, $spreadSheetAry[$i][4]);
            }

            $adr = "";
            if (isset($spreadSheetAry[$i][5])) {
                $adr = mysqli_real_escape_string($conn, $spreadSheetAry[$i][5]);
            }



            $dob = "";
            if (isset($spreadSheetAry[$i][6])) {
                $dob = mysqli_real_escape_string($conn, $spreadSheetAry[$i][6]);
            }

            $idno = "";
            if (isset($spreadSheetAry[$i][7])) {
                $idno = mysqli_real_escape_string($conn, $spreadSheetAry[$i][7]);
            }

            $gender = "";
            if (isset($spreadSheetAry[$i][8])) {
                $gender = mysqli_real_escape_string($conn, $spreadSheetAry[$i][8]);
            }

            $acc_status = "";
            if (isset($spreadSheetAry[$i][9])) {
                $acc_status = mysqli_real_escape_string($conn, $spreadSheetAry[$i][9]);
            }

            $day_enrolled = "";
            if (isset($spreadSheetAry[$i][10])) {
                $day_enrolled = mysqli_real_escape_string($conn, $spreadSheetAry[$i][10]);
            }

            $current_year = "";
            if (isset($spreadSheetAry[$i][11])) {
                $current_year = mysqli_real_escape_string($conn, $spreadSheetAry[$i][11]);
            }

            $no_of_modules = "";
            if (isset($spreadSheetAry[$i][12])) {
                $no_of_modules = mysqli_real_escape_string($conn, $spreadSheetAry[$i][12]);
            }

            /* Constant Values */
            $faculty_id = $_POST['faculty_id'];
            $school = $_POST['school'];
            $course = $_POST['course'];
            $department = $_POST['department'];
            $created_at = date("d M Y");

            /* Default Student Account Password */
            $password = sha1(md5("Student"));


            if (!empty($name) || !empty($admno) || !empty($idno) || !empty($email) || !empty($gender)) {
                $query = "INSERT INTO ezanaLMS_Students (id, faculty_id, day_enrolled, school, course, department, current_year, no_of_modules, name, email, phone, admno, idno, adr, dob, gender, acc_status, created_at, password) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $paramType = "sssssssssssssssssss";
                $paramArray = array(
                    $id,
                    $faculty_id,
                    $day_enrolled,
                    $school,
                    $course,
                    $department,
                    $current_year,
                    $no_of_modules,
                    $name,
                    $email,
                    $phone,
                    $admno,
                    $idno,
                    $adr,
                    $dob,
                    $gender,
                    $acc_status,
                    $created_at,
                    $password

                );
                $insertId = $db->insert($query, $paramType, $paramArray);
                if (!empty($insertId)) {
                    $err = "Error Occured While Importing Data";
                } else {
                    $success = "Data Imported" && header("refresh:1; url=students_bulk_import.php");
                }
            }
        }
    } else {
        $info = "Invalid File Type. Upload Excel File.";
    }
}

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
                            <a href="students.php" class="active nav-link">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Students
                                </p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class=" nav-link">
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
                            <h1 class="m-0 text-dark">Bulk Import Students As Per Course</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Students</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="">
                            <nav class="navbar navbar-light bg-light col-md-12">
                                <form class="form-inline">
                                </form>
                                <div class="text-right">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Student</button>
                                </div>
                                <!-- Add Student Modal -->
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
                                                            <div class="form-group col-md-6">
                                                                <label for="">Course Enrolled</label>
                                                                <select class='form-control basic' id="CourseCode" onchange="getStudentCourseDetails(this.value);">
                                                                    <option selected>Select Course Code </option>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Courses` ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($courses = $res->fetch_object()) {
                                                                    ?>
                                                                        <option><?php echo $courses->code; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Course Name</label>
                                                                <input type="text" required name="course" class="form-control" id="CourseName">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Department Name</label>
                                                                <input type="text" required name="department" class="form-control" id="DepartmentName">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Faculty / School Name</label>
                                                                <input type="text" required name="school" class="form-control" id="FacultyName">
                                                                <input type="hidden" required name="faculty_id" class="form-control" id="FacultyID">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Current Year</label>
                                                                <input type="text" required name="current_year" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Date Enrolled</label>
                                                                <input type="text" placeholder="DD - MM - YYYY" required name="day_enrolled" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">No Of Modules</label>
                                                                <input type="text" required name="no_of_modules" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Name</label>
                                                                <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Admission Number</label>
                                                                <input type="text" required name="admno" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-4">
                                                                <label for="">ID / Passport Number</label>
                                                                <input type="text" required name="idno" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Date Of Birth</label>
                                                                <input type="text" placeholder="DD - MM - YYYY" required name="dob" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Gender</label>
                                                                <select type="text" required name="gender" class="form-control basic">
                                                                    <option>Male</option>
                                                                    <option>Female</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Email</label>
                                                                <input type="email" required name="email" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Phone Number</label>
                                                                <input type="text" required name="phone" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Password</label>
                                                                <input type="text" value="<?php echo $defaultPass; ?>" required name="password" class="form-control">
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
                                                                <label for="exampleInputPassword1">Current Address</label>
                                                                <textarea required name="adr" rows="3" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer text-right">
                                                        <button type="submit" name="add_student" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Student Modal -->
                            </nav>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Course Code</th>
                                            <th>Course Name</th>
                                            <th>Course Department</th>
                                            <th>Faculty / School</th>
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
                                                <td><?php echo $courses->faculty_name; ?></td>
                                                <td>
                                                    <a class="badge badge-primary" data-toggle="modal" href="#bulk-import-<?php echo $courses->id; ?>">
                                                        <i class="fas fa-file-upload"></i>
                                                        Bulk Import Students
                                                    </a>
                                                    <!-- Update Faculty Modal -->
                                                    <div class="modal fade" id="bulk-import-<?php echo $courses->id; ?>">
                                                        <div class="modal-dialog  modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Bulk Import Students To <?php echo $courses->name; ?></h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="form-group text-center col-md-12">
                                                                                    <label for="exampleInputFile">Allowed File Types: XLS, XLSX. Please, <a href="public/templates/ezanaLMS_Students_XLS_Template.xlsx">Download</a> A Sample File. </label>
                                                                                </div>
                                                                                <div class="form-group col-md-12">
                                                                                    <label for="exampleInputFile">Select File</label>
                                                                                    <div class="input-group">
                                                                                        <div class="custom-file">
                                                                                            <input required name="file" accept=".xls,.xlsx" type="file" class="custom-file-input" id="exampleInputFile">
                                                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                                            <!-- Hidden Values -->
                                                                                            <input type="hidden" required name="school" class="form-control" value="<?php echo $courses->faculty_name; ?>">
                                                                                            <input type="hidden" required name="faculty_id" class="form-control" value="<?php echo $courses->faculty_id; ?>">
                                                                                            <input type="hidden" required name="course" class="form-control" value="<?php echo $courses->name; ?>">
                                                                                            <input type="hidden" required name="department" class="form-control" value="<?php echo $courses->department_name; ?>">

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-right">
                                                                            <button type="submit" name="upload" class="btn btn-primary">Upload File</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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