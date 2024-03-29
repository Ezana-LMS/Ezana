<?php
/*
 * Created on Mon Jun 21 2021
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
require_once('../config/codeGen.php');
require_once('../config/DataSource.php');
admin_checklogin();

/* Bulk Import Lecturers Via .XLS  */
require_once('../vendor/autoload.php');

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
        $time = time();
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
                /* Some Realy Messsed Up Shit Here */
                $id = sha1(md5(rand(mysqli_real_escape_string($conn, $spreadSheetAry[$i][0]), date('Y'))));
            }

            $number = "";
            if (isset($spreadSheetAry[$i][1])) {
                $number = mysqli_real_escape_string($conn, $spreadSheetAry[$i][1]);
            }

            $name = "";
            if (isset($spreadSheetAry[$i][2])) {
                $name = mysqli_real_escape_string($conn, $spreadSheetAry[$i][2]);
            }

            $idno = "";
            if (isset($spreadSheetAry[$i][3])) {
                $idno = mysqli_real_escape_string($conn, $spreadSheetAry[$i][3]);
            }

            $phone = "";
            if (isset($spreadSheetAry[$i][4])) {
                $phone = mysqli_real_escape_string($conn, $spreadSheetAry[$i][4]);
            }

            $email = "";
            if (isset($spreadSheetAry[$i][5])) {
                $email = mysqli_real_escape_string($conn, $spreadSheetAry[$i][5]);
            }

            $adr = "";
            if (isset($spreadSheetAry[$i][6])) {
                $adr = mysqli_real_escape_string($conn, $spreadSheetAry[$i][6]);
            }


            $work_email = "";
            if (isset($spreadSheetAry[$i][7])) {
                $work_email = mysqli_real_escape_string($conn, $spreadSheetAry[$i][7]);
            }

            $gender = "";
            if (isset($spreadSheetAry[$i][8])) {
                $gender = mysqli_real_escape_string($conn, $spreadSheetAry[$i][8]);
            }

            $employee_id = "";
            if (isset($spreadSheetAry[$i][9])) {
                $employee_id = mysqli_real_escape_string($conn, $spreadSheetAry[$i][9]);
            }

            $date_employed = "";
            if (isset($spreadSheetAry[$i][10])) {
                $date_employed = mysqli_real_escape_string($conn, $spreadSheetAry[$i][10]);
            }

            $status = "";
            if (isset($spreadSheetAry[$i][11])) {
                $status = mysqli_real_escape_string($conn, $spreadSheetAry[$i][11]);
            }

            /* Constant Values */
            $view = $_POST['view'];
            $faculty_name = $_POST['faculty_name'];
            $created_at = date("d M Y");

            /* Default Student Account Password */
            $mailed_password = substr(str_shuffle("QWERTYUIOPwertyuioplkjLKJHGFDSAZXCVBNM1234567890qhgfdsazxcvbnm"), 1, 8);
            $password = sha1(md5($mailed_password));

            /* Load Lec Mailer */
            include('../config/bulk_lec_mailer.php');

            if (!empty($name) || !empty($employee_id) || !empty($idno) || !empty($email) || !empty($number)) {
                $query = "INSERT INTO ezanaLMS_Lecturers (id, faculty_id, gender, faculty_name, work_email, employee_id, date_employed, name, email, phone, idno, adr, created_at, password, number, status) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $paramType = "ssssssssssssssss";
                $paramArray = array(
                    $id,
                    $view,
                    $gender,
                    $faculty_name,
                    $work_email,
                    $employee_id,
                    $date_employed,
                    $name,
                    $email,
                    $phone,
                    $idno,
                    $adr,
                    $created_at,
                    $password,
                    $number,
                    $status
                );
                $insertId = $db->insert($query, $paramType, $paramArray);
                if (!empty($insertId)) {
                    $err = "Error Occured While Importing Data";
                } else if ($mail->send()) {
                    $success = "Lecturers Data Imported";
                } else {
                    $err = "$mail->ErrorInfo";
                }
            }
        }
    } else {
        $info = "Invalid File Type. Upload Excel File.";
    }
}

/* Add Lects */
if (isset($_POST['add_lec'])) {
    $error = 0;
    if (isset($_POST['number']) && !empty($_POST['number'])) {
        $number = mysqli_real_escape_string($mysqli, trim($_POST['number']));
    } else {
        $error = 1;
        $err = "Lecturer Number Cannot Be Empty";
    }
    if (isset($_POST['idno']) && !empty($_POST['idno'])) {
        $idno = mysqli_real_escape_string($mysqli, trim($_POST['idno']));
    } else {
        $error = 1;
        $err = "National ID / Passport Number Cannot Be Empty";
    }
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = mysqli_real_escape_string($mysqli, trim($_POST['email']));
    } else {
        $error = 1;
        $err = "Email Cannot Be Empty";
    }
    if (isset($_POST['phone']) && !empty($_POST['phone'])) {
        $phone = mysqli_real_escape_string($mysqli, trim($_POST['phone']));
    } else {
        $error = 1;
        $err = "Phone Number Cannot Be Empty";
    }
    if (isset($_POST['faculty_id']) && !empty($_POST['faculty_id'])) {
        $faculty_id = mysqli_real_escape_string($mysqli, trim($_POST['faculty_id']));
    } else {
        $error = 1;
        $err = "Faculty Cannot Be Empty";
    }

    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Lecturers WHERE  email='$email' || phone ='$phone' || idno = '$idno' || number ='$number' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($email == $row['email']) {
                $err =  "Account With This Email Already Exists";
            } elseif ($phone == $row['phone']) {
                $err = "Account With That Phone Number Exists";
            } elseif ($idno == $row['idno']) {
                $err = "National ID Number  / Passport Number Already Exists";
            } else {
                $err = "Lecturer Number Already Exists";
            }
        } else {
            $faculty_name = $_POST['faculty_name'];
            $gender = $_POST['gender'];
            $work_email = $_POST['work_email'];
            $employee_id = $_POST['employee_id'];
            $date_employed = $_POST['date_employed'];
            $id = $_POST['id'];
            $name = $_POST['name'];
            $adr = $_POST['adr'];
            $created_at = date('d M Y');
            /* Mail clean password */
            $mailed_password = $_POST['password'];
            /* Persist Encrypted Password */
            $password = sha1(md5($mailed_password));
            $profile_pic = time() .  $_FILES['profile_pic']['name'];
            move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "../Data/User_Profiles/lecturers/" . time()  . $_FILES["profile_pic"]["name"]);

            $query = "INSERT INTO ezanaLMS_Lecturers (id, faculty_id, gender, faculty_name, work_email, employee_id, date_employed, name, email, phone, idno, adr, profile_pic, created_at, password, number) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssssssssssss', $id, $faculty_id, $gender, $faculty_name, $work_email, $employee_id, $date_employed, $name, $email, $phone, $idno, $adr, $profile_pic, $created_at, $password, $number);
            $stmt->execute();
            /* Load Lec Mailer */
            require_once('../config/lec_mailer.php');
            if ($stmt && $mail->send()) {
                $success = "Lecturer Added";
            } else {
                $info = "Please Try Again Or Try Later $mail->ErrorInfo";
            }
        }
    }
}

/* Update Lec */
if (isset($_POST['update_lec'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['number']) && !empty($_POST['number'])) {
        $number = mysqli_real_escape_string($mysqli, trim($_POST['number']));
    } else {
        $error = 1;
        $err = "Lecturer Number Cannot Be Empty";
    }
    if (isset($_POST['idno']) && !empty($_POST['idno'])) {
        $idno = mysqli_real_escape_string($mysqli, trim($_POST['idno']));
    } else {
        $error = 1;
        $err = "National ID / Passport Number Cannot Be Empty";
    }
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = mysqli_real_escape_string($mysqli, trim($_POST['email']));
    } else {
        $error = 1;
        $err = "Email Cannot Be Empty";
    }
    if (isset($_POST['phone']) && !empty($_POST['phone'])) {
        $phone = mysqli_real_escape_string($mysqli, trim($_POST['phone']));
    } else {
        $error = 1;
        $err = "Phone Number Cannot Be Empty";
    }

    if (isset($_POST['faculty_id']) && !empty($_POST['faculty_id'])) {
        $faculty_id = mysqli_real_escape_string($mysqli, trim($_POST['faculty_id']));
    } else {
        $error = 1;
        $err = "Faculty Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $adr = $_POST['adr'];
        $gender = $_POST['gender'];
        $work_email = $_POST['work_email'];
        $employee_id = $_POST['employee_id'];
        $date_employed = $_POST['date_employed'];

        $query = "UPDATE ezanaLMS_Lecturers SET  name =?,  gender = ?, work_email =?, employee_id = ?, date_employed = ?,email =?, phone =?, idno =?, adr =?, number =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssssssss', $name,  $gender, $work_email, $employee_id, $date_employed, $email, $phone, $idno, $adr, $number, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Lecturer Account Updated";
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}


/* On Leave */
if (isset($_GET['leave'])) {
    $leave = $_GET['leave'];
    $view = $_GET['view'];
    $adn = "UPDATE  ezanaLMS_Lecturers SET status = 'On Leave' WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $leave);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Lecturer On Leave" && header("refresh:1; url=faculty_lecturers?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* On Work */
if (isset($_GET['onwork'])) {
    $onwork = $_GET['onwork'];
    $view = $_GET['view'];
    $adn = "UPDATE  ezanaLMS_Lecturers SET status = 'On Work' WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $onwork);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Lecturer On Work" && header("refresh:1; url=faculty_lecturers?view=$view");;
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

require_once('partials/head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('partials/header.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id= '$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($faculty = $res->fetch_object()) {
        ?>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <?php require_once('partials/aside.php'); ?>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right small">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculties">Faculties</a></li>
                                    <li class="breadcrumb-item active">Lecturers</li>
                                </ol>
                            </div>
                        </div>
                    </div>


                    <section class="content">
                        <div class="container-fluid">
                            <div class="col-md-12">
                                <div class="text-center">
                                    <h1 class="m-0 text-bold"><?php echo $faculty->name; ?> Lecturers</h1>
                                    <br>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-import-lecs">Bulk Import Lecturers</button>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Lecturer</button>
                                </div>
                            </div>
                            <div class="">
                                <!-- Add Lecturer Modal -->
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
                                                            <input type="hidden" required name="faculty_name" class="form-control" value="<?php echo $faculty->name; ?>">
                                                            <input type="hidden" required name="faculty_id" value="<?php echo $faculty->id; ?>" class="form-control">
                                                            <input type="hidden" required name="password" value="<?php echo $defaultPass; ?>" class="form-control">
                                                            <div class="form-group col-md-12">
                                                                <label for="">Name</label>
                                                                <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Gender</label>
                                                                <select class='form-control basic' name="gender">
                                                                    <option selected>Male</option>
                                                                    <option>Female</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Number</label>
                                                                <input type="text" required name="number" value="<?php echo $a . $b; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
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
                                                            <div class="form-group col-md-4">
                                                                <label for="">Employee ID</label>
                                                                <input type="text" required name="employee_id" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Date Employed</label>
                                                                <input type="date" required name="date_employed" placeholder="DD - MM - YYYY" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Profile Picture</label>
                                                                <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input name="profile_pic" type="file" class="custom-file-input">
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
                                                    <div class="text-right">
                                                        <button type="submit" name="add_lec" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->

                                <!-- Bulk Import Lecs Modal -->
                                <div class="modal fade" id="modal-import-lecs">
                                    <div class="modal-dialog  modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title text-danger">Bulk Import Lecturers</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group text-center col-md-12">
                                                                <label for="exampleInputFile">Allowed File Types: XLS, XLSX. Please, <a href="../Data/XLS_Templates/ezanaLMS_Lecturers_XLS_Template.xlsx">Download</a> A Template File. </label>
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputFile">Select File</label>
                                                                <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input required name="file" accept=".xls,.xlsx" type="file" class="custom-file-input" id="exampleInputFile">
                                                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                        <!-- Hidden Values -->
                                                                        <input type="hidden" required name="faculty_name" class="form-control" value="<?php echo $faculty->name; ?>">
                                                                        <input type="hidden" required name="view" class="form-control" value="<?php echo $faculty->id; ?>">
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
                                <!-- End Import Lecs Modal -->
                            </div>
                            <hr>
                            <div class="row">
                                <!-- Faculty Side Menu -->
                                <?php require_once('partials/faculty_menu.php'); ?>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Number</th>
                                                        <th>Name</th>
                                                        <th>Gender</th>
                                                        <th>Work Email</th>
                                                        <th>Phone</th>
                                                        <th>ID/Passport </th>
                                                        <th>Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE faculty_id = '$view' ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    while ($lec = $res->fetch_object()) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $lec->number; ?></td>
                                                            <td><?php echo $lec->name; ?></td>
                                                            <td><?php echo $lec->gender; ?></td>
                                                            <td><?php echo $lec->work_email; ?></td>
                                                            <td><?php echo $lec->phone; ?></td>
                                                            <td><?php echo $lec->idno; ?></td>
                                                            <td>
                                                                <a class="badge badge-success" href="lecturer?view=<?php echo $lec->id; ?>">
                                                                    <i class="fas fa-eye"></i>
                                                                    View
                                                                </a>
                                                                <a class="badge badge-primary" data-toggle="modal" href="#update-lecturer-<?php echo $lec->id; ?>">
                                                                    <i class="fas fa-edit"></i>
                                                                    Update
                                                                </a>

                                                                <?php
                                                                if ($lec->status == 'On Leave') {
                                                                    echo
                                                                    "
                                                                        <a class='badge badge-danger' data-toggle='modal' href='#onwork-$lec->id'>
                                                                            <i class='fas fa-user-lock'></i>
                                                                            On Leave
                                                                        </a>

                                                                        ";
                                                                } else {
                                                                    echo
                                                                    "
                                                                        <a class='badge badge-primary' data-toggle='modal' href='#leave-$lec->id'>
                                                                            <i class='fas fa-user-check'></i>
                                                                            On Work
                                                                        </a>
                                                                        ";
                                                                }
                                                                ?>
                                                                <!-- Update Lec Modal -->
                                                                <div class="modal fade" id="update-lecturer-<?php echo $lec->id; ?>">
                                                                    <div class="modal-dialog  modal-xl">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Update <?php echo $lec->name; ?> Profile Details </h4>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <form method="post" enctype="multipart/form-data" role="form">
                                                                                    <div class="card-body">
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-3">
                                                                                                <label for="">Name</label>
                                                                                                <input type="text" required name="name" value="<?php echo $lec->name; ?>" class="form-control" id="exampleInputEmail1">
                                                                                                <input type="hidden" required name="id" value="<?php echo $lec->id; ?>" class="form-control">
                                                                                                <input type="hidden" required name="faculty_id" value="<?php echo $faculty->id; ?>" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-3">
                                                                                                <label for="">Gender</label>
                                                                                                <select class='form-control basic' name="gender">
                                                                                                    <option>Female</option>
                                                                                                    <option>Male</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="form-group col-md-3">
                                                                                                <label for="">Number</label>
                                                                                                <input type="text" required name="number" value="<?php echo $lec->number; ?>" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-3">
                                                                                                <label for="">ID / Passport Number</label>
                                                                                                <input type="text" required name="idno" value="<?php echo $lec->idno; ?>" class="form-control">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-4">
                                                                                                <label for="">Personal Email</label>
                                                                                                <input type="email" required name="email" value=<?php echo $lec->email; ?> class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-4">
                                                                                                <label for="">Work Email</label>
                                                                                                <input type="email" required name="work_email" value="<?php echo $lec->work_email; ?>" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-4">
                                                                                                <label for="">Phone Number</label>
                                                                                                <input type="text" required name="phone" value=<?php echo $lec->phone; ?> class="form-control">
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Employee ID</label>
                                                                                                <input type="text" required name="employee_id" value="<?php echo $lec->employee_id; ?>" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Date Employed</label>
                                                                                                <input type="date" required name="date_employed" value="<?php echo $lec->date_employed; ?>" placeholder="DD - MM - YYYY" class="form-control">
                                                                                            </div>

                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="exampleInputPassword1">Address</label>
                                                                                                <textarea required name="adr" rows="2" class="form-control"><?php echo $lec->adr; ?></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="text-right">
                                                                                        <button type="submit" name="update_lec" class="btn btn-primary">Submit</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Onleave Or WOrk -->
                                                                <div class="modal fade" id="leave-<?php echo $lec->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title " id="exampleModalLabel">CONFIRM LEAVE STATUS</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center text-danger">
                                                                                <h4>Give <?php echo $lec->name; ?> Leave ?</h4>
                                                                                <br>
                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                <a href="faculty_lecturers?leave=<?php echo $lec->id; ?>&view=<?php echo $view; ?>" class="text-center btn btn-danger"> Yes </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- End -->

                                                                <!-- Onleave Or WOrk -->
                                                                <div class="modal fade" id="onwork-<?php echo $lec->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title " id="exampleModalLabel">CONFIRM LEAVE STATUS</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center text-danger">
                                                                                <h4>Set <?php echo $lec->name; ?> To Be On Work ?</h4>
                                                                                <br>
                                                                                <a href="faculty_lecturers?onwork=<?php echo $lec->id; ?>&view=<?php echo $view; ?>" class="text-center btn btn-success"> Confirm </a>
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
                            </div>
                        </div>
                    </section>
                    <!-- Main Footer -->
                    <?php require_once('partials/footer.php'); ?>
                </div>
            </div>
            <!-- ./wrapper -->
        <?php require_once('partials/scripts.php');
        } ?>
</body>

</html>