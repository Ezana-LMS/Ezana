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

/* Bulk Import On Students */

use EzanaLmsAPI\DataSource;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

/* Load Mailer */

require_once('vendor/PHPMailer/src/SMTP.php');
require_once('vendor/PHPMailer/src/PHPMailer.php');
require_once('vendor/PHPMailer/src/Exception.php');

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
        $time = date("d-M-Y") . "-" . time();
        $targetPath = 'public/uploads/EzanaLMSData/XLSFiles/' . $time . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

        $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadSheet = $Reader->load($targetPath);
        $excelSheet = $spreadSheet->getActiveSheet();
        $spreadSheetAry = $excelSheet->toArray();
        $sheetCount = count($spreadSheetAry);

        for ($i = 1; $i <= $sheetCount; $i++) {

            $id = "";
            if (isset($spreadSheetAry[$i][0])) {
                $id = sha1(md5(rand(mysqli_real_escape_string($conn, $spreadSheetAry[$i][0]), date('Y'))));
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


            /* Constant Values K */
            $faculty_id = $_POST['faculty_id'];
            $school = $_POST['school'];
            $course = $_POST['course'];
            $department = $_POST['department'];
            $created_at = date("d M Y");

            /* Default Student Account Password */
            $mailed_password = substr(str_shuffle("QWERTYUIOPwertyuioplkjLKJHGFDSAZXCVBNM1234567890qhgfdsazxcvbnm"), 1, 8);
            $password = sha1(md5($mailed_password));

            /* Load Mailer */
            function sendEmail($email, $name)
            {
                require_once('configs/config.php');
                /* Load System Settings */
                $ret = "SELECT * FROM `ezanaLMS_Settings` ";
                $stmt = $mysqli->prepare($ret);
                $stmt->execute(); //ok
                $res = $stmt->get_result();
                while ($sys = $res->fetch_object()) {
                    $mail = new PHPMailer\PHPMailer\PHPMailer();
                    $mail->IsSMTP();
                    $mail->Host = $sys->stmp_host;
                    $mail->Port = 465;
                    $mail->SMTPAuth = true;
                    $mail->SMTPSecure = 'ssl';
                    $mail->Username = $sys->stmp_username;
                    $mail->Password = $sys->stmp_password;
                    $mail->From = $username;
                    $mail->FromName = $sys->sysname;
                    $mail->clearAddresses();
                    $mail->Subject = 'Welcome';
                    $mail->IsHTML(true);
                    $mail->addAddress($email, $name);     // Add a recipient
                    $mail->Body = '
                    <body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
                        <!--100% body table-->
                        <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
                            style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: "Open Sans", sans-serif;">
                            <tr>
                                <td>
                                    <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0"
                                        align="center" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="height:80px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;">
                                            <a href="https://ezana.org" title="logo" target="_blank">
                                                <img width="80" src="https://ezana.org/logo.png" title="logo" alt=" Ezana LMS">
                                            </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:20px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                                    style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                                    <tr>
                                                        <td style="height:40px;">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding:0 35px;">
                                                            <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:"Rubik",sans-serif;">Welcome To  ' . $sys->sysname . '</h1>
                                                            <span
                                                                style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                                            <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                                                Hi ' . $name . ', <br>
                                                                We’re thrilled to have you as a student  at ' . $sys->sysname . '.
                                                            </p>
                                                            <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                                            This is your default student account authentication credentials: <br>
                                                            <b>Email: ' . $email . '</b><br>
                                                            <b>Password: ' . $mailed_password . '</b><br>
                                                            <br>
                                                            Kindly change them after login.
                                                            <br>
                                                            <br>
                                                            Kind Regards<br>
                                                            Ezana LMS Team
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="height:40px;">&nbsp;</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        <tr>
                                            <td style="height:20px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;">
                                                <p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">&copy; <strong>www.ezana.org</strong></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:80px;">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </body>
                    ';

                    if (!$mail->send()) {
                        error_log($mail->ErrorInfo);
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            foreach ($contacts as $contact) {
                //create message here
                sendEmail($contact->email, $contact->name);
            }


            if (!empty($name) || !empty($admno) || !empty($idno) || !empty($email) || !empty($gender)) {
                $query = "INSERT INTO ezanaLMS_Students (id, faculty_id, day_enrolled, school, course, department, current_year, name, email, phone, admno, idno, adr, dob, gender, acc_status, created_at, password) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $paramType = "ssssssssssssssssss";
                $paramArray = array(
                    $id,
                    $faculty_id,
                    $day_enrolled,
                    $school,
                    $course,
                    $department,
                    $current_year,
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
                } else if ($mail->send()) {
                    $success = "Data Imported" && header("refresh:1; url=students_bulk_import.php");
                } else {
                    $err = "$mail->ErrorInfo";
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