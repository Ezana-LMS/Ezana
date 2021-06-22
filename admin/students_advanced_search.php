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

/* Update Student */
if (isset($_POST['update_student'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $admno = $_POST['admno'];
    $idno = $_POST['idno'];
    $adr = $_POST['adr'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $updated_at = date('d M Y');

    $query = "UPDATE ezanaLMS_Students SET name =?, email =?, phone =?, admno =?, idno =?, adr =?, dob =?, gender =?, updated_at =? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssssssss',  $name, $email, $phone, $admno, $idno, $adr, $dob, $gender, $updated_at, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Student Account Updated";
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Suspend Account */
if (isset($_GET['suspend'])) {
    $suspend = $_GET['suspend'];
    $adn = "UPDATE  ezanaLMS_Students SET acc_status = 'Suspended' WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $suspend);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Suspended" && header("refresh:1; url=students_advanced_search");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* UnSuspend Account */
if (isset($_GET['unsuspend'])) {
    $unsuspend = $_GET['unsuspend'];
    $adn = "UPDATE  ezanaLMS_Students SET acc_status = 'Active' WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $unsuspend);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Un Suspended" && header("refresh:1; url=students_advanced_search");
    } else {
        $info = "Please Try Again Or Try Later";
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
                            <h1 class="m-0 text-dark">Students - Advanced Searching / Filtering</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="students">Students</a></li>
                                <li class="breadcrumb-item active">Advanced Search</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <hr>
                <section class="content">
                    <div class="container-fluid">
                        <div class="">
                            <form method="POST">
                                <div class="d-flex justify-content-center">
                                    <select name="School" class='col-md-6 form-control basic mr-sm-2'>
                                        <option selected>Select Faculty / School Name</option>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_Faculties` ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        while ($faculty = $res->fetch_object()) {
                                        ?>
                                            <option><?php echo $faculty->name; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="text-right">
                                        <button name="SearchStudents" class="btn btn-outline-success my-2 my-sm-0" type="submit">Search By School / Faculty</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Adm No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>School</th>
                                            <th>Department</th>
                                            <th>Course</th>
                                            <th>Year</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_POST['SearchStudents'])) {

                                            $School = $_POST['School'];
                                            $Department = $_POST['Department'];
                                            $Course = $_POST['Course'];
                                            $CurrentYear = $_POST['CurrentYear'];

                                            $ret = "SELECT * FROM `ezanaLMS_Students`  WHERE school = '$School' || department = '$Department' || course = '$Course' || current_year = '$CurrentYear' ";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
                                            while ($std = $res->fetch_object()) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $std->admno; ?></td>
                                                    <td><?php echo $std->name; ?></td>
                                                    <td><?php echo $std->email; ?></td>
                                                    <td><?php echo $std->school; ?></td>
                                                    <td><?php echo $std->department; ?></td>
                                                    <td><?php echo $std->course; ?></td>
                                                    <td><?php echo $std->current_year; ?></td>
                                                    <td>
                                                        <a class="badge badge-success" href="student?view=<?php echo $std->id; ?>">
                                                            <i class="fas fa-user-graduate"></i>
                                                            View
                                                        </a>

                                                        <a class="badge badge-primary" data-toggle="modal" href="#update-student-<?php echo $std->id; ?>">
                                                            <i class="fas fa-edit"></i>
                                                            Update
                                                        </a>
                                                        <?php
                                                        /* Suspend  */
                                                        if ($std->acc_status != 'Suspended') {
                                                            echo
                                                            "
                                                        <a class='badge badge-danger' data-toggle='modal' href='#suspend-$std->id'>
                                                            <i class='fas fa-user-clock'></i>
                                                            Suspend Account
                                                        </a>
                                                        ";
                                                        } else {
                                                            echo
                                                            "
                                                        <a class='badge badge-success' data-toggle='modal' href='#unsuspend-$std->id'>
                                                            <i class='fas fa-user-check'></i>
                                                            UnSuspend Account
                                                        </a>
                                                        ";
                                                        } ?>
                                                        <!-- Update Student Modal -->
                                                        <div class="modal fade" id="update-student-<?php echo $std->id; ?>">
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
                                                                                        <label for="">Name</label>
                                                                                        <input type="text" required name="name" class="form-control" value="<?php echo $std->name; ?>">
                                                                                        <input type="hidden" required name="id" value="<?php echo $std->id; ?>" class="form-control">
                                                                                    </div>
                                                                                    <div class="form-group col-md-6">
                                                                                        <label for="">Admission Number</label>
                                                                                        <input type="text" required name="admno" value="<?php echo $std->admno; ?>" class="form-control">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="form-group col-md-4">
                                                                                        <label for="">ID / Passport Number</label>
                                                                                        <input type="text" required name="idno" value="<?php echo $std->idno; ?>" class="form-control">
                                                                                    </div>
                                                                                    <div class="form-group col-md-4">
                                                                                        <label for="">Date Of Birth</label>
                                                                                        <input type="text" required name="dob" value="<?php echo $std->dob; ?>" class="form-control">
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
                                                                                        <input type="email" required value="<?php echo $std->email; ?>" name="email" class="form-control">
                                                                                    </div>
                                                                                    <div class="form-group col-md-6">
                                                                                        <label for="">Phone Number</label>
                                                                                        <input type="text" required name="phone" value="<?php echo $std->phone; ?>" class="form-control">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                    <div class="form-group col-md-12">
                                                                                        <label for="exampleInputPassword1">Current Address</label>
                                                                                        <textarea required name="adr" rows="3" class="form-control"><?php echo $std->adr; ?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="text-right">
                                                                                <button type="submit" name="update_student" class="btn btn-primary">Submit</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Suspend Modal -->
                                                        <div class="modal fade" id="suspend-<?php echo $std->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">CONFIRM ACCOUNT SUSPEND</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-center text-danger">
                                                                        <h4>Suspend <?php echo  $std->admno . " - " . $std->name; ?> Account ?</h4>
                                                                        <br>
                                                                        <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                        <a href="students_advanced_search?suspend=<?php echo $std->id; ?>" class="text-center btn btn-danger">Yes Suspend Account </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- End Suspend -->

                                                        <div class="modal fade" id="unsuspend-<?php echo $std->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title " id="exampleModalLabel">CONFIRM ACCOUNT RESTORATION</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-center text-danger">
                                                                        <h4>UnSuspend <?php echo  $std->admno . " " . $std->name; ?> Account ?</h4>
                                                                        <br>
                                                                        <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                        <a href="students_advanced_search?unsuspend=<?php echo $std->id; ?>" class="text-center btn btn-danger">Yes Unsuspend Account </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
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
        <?php require_once('partials/scripts.php'); ?>
</body>

</html>