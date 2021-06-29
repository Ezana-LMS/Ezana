<?php
/*
 * Created on Tue Jun 29 2021
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
require_once('../config/config.php');
require_once('../config/edu_admn_checklogin.php');
edu_admn_checklogin();
require_once('../config/codeGen.php');
require_once('../config/DataSource.php');
require_once('../vendor/autoload.php');
$time = time();

/* Bulk Import On Students */

use EzanaLmsAPI\DataSource;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$db = new DataSource();
$conn = $db->getConnection();

if (isset($_POST["upload"])) {

    $allowedFileType = [
        'application/vnd.ms-excel',
        'text/xls',
        'text/xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    /* Where Magic Happens */
    if (in_array($_FILES["file"]["type"], $allowedFileType)) {
        $targetPath = '../Data/XLSFiles/' . $time . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

        /* Initaite XLS Class */
        $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadSheet = $Reader->load($targetPath);
        $excelSheet = $spreadSheet->getActiveSheet();
        $spreadSheetAry = $excelSheet->toArray();
        $sheetCount = count($spreadSheetAry);

        /* Decode XLS File */
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
            /* Load Bulk Students Mailer */
            include('../config/bulk_student_mailer.php');
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
                } elseif ($mail->send()) {
                    $success = "Bulk Student Data Imported";
                } else {
                    $err = "$mail->ErrorInfo";
                }
            }
        }
    } else {
        $info = "Invalid File Type. Upload Excel File.";
    }
}

require_once('partials/head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('partials/header.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once('partials/aside.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id= '$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($faculty = $res->fetch_object()) {
        ?>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-bold">Bulk Import Students As Per Course</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right small">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                    <li class="breadcrumb-item"><a href="students?view=<?php echo $view; ?>">Students</a></li>
                                    <li class="breadcrumb-item active">Bulk Import</li>
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
                                            $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE faculty_id = '$view'";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
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
                                                                                        <label for="exampleInputFile">Allowed File Types: XLS, XLSX. Please, <a href="../Data/XLS_Templates/ezanaLMS_Students_XLS_Template.xlsx">Download</a> A Sample File. </label>
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
                    <?php require_once('partials/footer.php'); ?>
                </div>
            </div>
            <!-- ./wrapper -->
        <?php
        }
        require_once('partials/scripts.php'); ?>
</body>

</html>