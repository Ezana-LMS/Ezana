<?php
/*
 * Created on Tue Jun 22 2021
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
require_once('../config/checklogin.php');
admin_checklogin();
require_once('../config/codeGen.php');
require_once('../config/DataSource.php');
require_once('../vendor/autoload.php');
$time = time();



/* Bulk Import Non Teaching Staff Via .XLS  */

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

        $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadSheet = $Reader->load($targetPath);
        $excelSheet = $spreadSheet->getActiveSheet();
        $spreadSheetAry = $excelSheet->toArray();
        $sheetCount = count($spreadSheetAry);

        for ($i = 1; $i <= $sheetCount; $i++) {

            $id = "";
            if (isset($spreadSheetAry[$i][0])) {
                /* Load Mumble Jumble Here */
                $id = sha1(md5(rand(mysqli_real_escape_string($conn, $spreadSheetAry[$i][0]), date('Y'))));
            }

            $name = "";
            if (isset($spreadSheetAry[$i][1])) {
                $name = mysqli_real_escape_string($conn, $spreadSheetAry[$i][1]);
            }

            $email = "";
            if (isset($spreadSheetAry[$i][2])) {
                $email = mysqli_real_escape_string($conn, $spreadSheetAry[$i][2]);
            }

            $personal_email = "";
            if (isset($spreadSheetAry[$i][3])) {
                $personal_email = mysqli_real_escape_string($conn, $spreadSheetAry[$i][3]);
            }

            $rank = "";
            if (isset($spreadSheetAry[$i][4])) {
                $rank = mysqli_real_escape_string($conn, $spreadSheetAry[$i][4]);
            }

            $phone = "";
            if (isset($spreadSheetAry[$i][5])) {
                $phone = mysqli_real_escape_string($conn, $spreadSheetAry[$i][5]);
            }

            $gender = "";
            if (isset($spreadSheetAry[$i][6])) {
                $gender = mysqli_real_escape_string($conn, $spreadSheetAry[$i][6]);
            }

            $employee_id = "";
            if (isset($spreadSheetAry[$i][7])) {
                $employee_id = mysqli_real_escape_string($conn, $spreadSheetAry[$i][7]);
            }

            $gender = "";
            if (isset($spreadSheetAry[$i][8])) {
                $gender = mysqli_real_escape_string($conn, $spreadSheetAry[$i][8]);
            }

            $employee_id = "";
            if (isset($spreadSheetAry[$i][9])) {
                $employee_id = mysqli_real_escape_string($conn, $spreadSheetAry[$i][9]);
            }

            $national_id = "";
            if (isset($spreadSheetAry[$i][10])) {
                $national_id = mysqli_real_escape_string($conn, $spreadSheetAry[$i][10]);
            }

            $date_employed = "";
            if (isset($spreadSheetAry[$i][11])) {
                $date_employed = mysqli_real_escape_string($conn, $spreadSheetAry[$i][11]);
            }

            $adr = "";
            if (isset($spreadSheetAry[$i][12])) {
                $adr = mysqli_real_escape_string($conn, $spreadSheetAry[$i][12]);
            }

            $status = "";
            if (isset($spreadSheetAry[$i][13])) {
                $status = mysqli_real_escape_string($conn, $spreadSheetAry[$i][13]);
            }

            /* Constant Values */
            $school_id = $_POST['school_id'];
            $school = $_POST['school'];

            /* Default Student Account Password */
            $mailed_password = substr(str_shuffle("QWERTYUIOPwertyuioplkjLKJHGFDSAZXCVBNM1234567890qhgfdsazxcvbnm"), 1, 8);
            $password = sha1(md5($mailed_password));

            /* Load Lec Mailer */
            include('../config/non_teaching_mailer.php');

            if (!empty($name) || !empty($employee_id) || !empty($national_id) || !empty($email) || !empty($personal_email)) {
                $query = "INSERT INTO ezanaLMS_Admins (id, name, email, personal_email, password, rank, phone, gender, employee_id, national_id, 
                date_employed, school, school_id, adr, status) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $paramType = "sssssssssssssss";
                $paramArray = array(
                    $id,
                    $name,
                    $email,
                    $personal_email,
                    $password,
                    $rank,
                    $phone,
                    $gender,
                    $employee_id,
                    $national_id,
                    $date_employed,
                    $school,
                    $school_id,
                    $adr,
                    $status
                );
                $insertId = $db->insert($query, $paramType, $paramArray);
                if (!empty($insertId)) {
                    $err = "Error Occured While Importing Data";
                } else if ($mail->send()) {
                    $success = "Non Teaching Staff Bulk Data Imported";
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
        <?php require_once('partials/aside.php'); ?>
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Bulk Import Non Teaching Staffs</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="non_teaching_staffs">Non Teaching Staffs</a></li>
                                <li class="breadcrumb-item active">Bulk Import</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-center">Select On Any Faculty To Import Non Teaching Staffs </h4>
                            <table id="example1" class=" table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Faculty Code Number</th>
                                        <th>Faculty Name</th>
                                        <th>Actions</th>
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
                                            <td>
                                                <a class="badge badge-primary" data-toggle="modal" href="#edit-faculty-<?php echo $faculty->id; ?>">
                                                    <i class="fas fa-file-upload"></i>
                                                    Import Non Teaching Staffs
                                                </a>
                                                <!-- Import Modal -->
                                                <div class="modal fade" id="edit-faculty-<?php echo $faculty->id; ?>">
                                                    <div class="modal-dialog  modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Bulk Import Non Teaching Staffs To <?php echo $faculty->name; ?></h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form method="post" enctype="multipart/form-data" role="form">
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="form-group text-center col-md-12">
                                                                                <label for="exampleInputFile">Allowed File Types: XLS, XLSX. Please, <a href="../Data/XLS_Templates/ezanaLMS_Non_Teaching_Staffs_XLS_Template.xlsx">Download</a> A Template File. </label>
                                                                            </div>
                                                                            <div class="form-group col-md-12">
                                                                                <label for="exampleInputFile">Select File</label>
                                                                                <div class="input-group">
                                                                                    <div class="custom-file">
                                                                                        <input required name="file" accept=".xls,.xlsx" type="file" class="custom-file-input" id="exampleInputFile">
                                                                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                                        <!-- Hidden Values -->
                                                                                        <input type="hidden" required name="school" class="form-control" value="<?php echo $faculty->name; ?>">
                                                                                        <input type="hidden" required name="school_id" class="form-control" value="<?php echo $faculty->id; ?>">
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
                </section>
                <!-- Main Footer -->
                <?php require_once('partials/footer.php'); ?>
            </div>
        </div>
        <!-- ./wrapper -->
        <?php require_once('partials/scripts.php'); ?>
</body>

</html>