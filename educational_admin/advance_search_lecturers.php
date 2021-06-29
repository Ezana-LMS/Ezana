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
            $success = "$name Lecturer Account Updated";
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}


/* On Leave */
if (isset($_GET['leave'])) {
    $leave = $_GET['leave'];
    $adn = "UPDATE  ezanaLMS_Lecturers SET status = 'On Leave' WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $leave);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "On Leave" && header("refresh:1; url=advance_search_lecturers");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* On Work */
if (isset($_GET['onwork'])) {
    $onwork = $_GET['onwork'];
    $adn = "UPDATE  ezanaLMS_Lecturers SET status = 'On Work' WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $onwork);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Lecturer Is On Work" && header("refresh:1; url=advance_search_lecturers");
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
                            <h1 class="m-0 text-dark">Lecturers - Advance Search</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="lecturers">Lecturers</a></li>
                                <li class="breadcrumb-item active">Advanced Search</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <hr>
                <section class="content">
                    <div class="container-fluid">
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
                                    <button name="SearchLecturers" class="btn btn-outline-success my-2 my-sm-0" type="submit">Search By School / Faculty</button>
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
                                        <th>Number</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Work Email</th>
                                        <th>Phone</th>
                                        <th>ID/Passport </th>
                                        <th>Faculty/School</th>
                                        <th>Manage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_POST['SearchLecturers'])) {
                                        $School = $_POST['School'];
                                        $ret = "SELECT * FROM `ezanaLMS_Lecturers`  WHERE faculty_name = '$School' ";
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
                                                <td><?php echo $lec->faculty_name; ?></td>
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
                                                    } ?>
                                                    <!-- Update Lec Modal -->
                                                    <div class="modal fade" id="update-lecturer-<?php echo $lec->id; ?>">
                                                        <div class="modal-dialog  modal-xl">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Update <?php echo $lec->name; ?> </h4>
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
                                                                                </div>
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="">Gender</label>
                                                                                    <select class='form-control basic' name="gender">
                                                                                        <option selected><?php echo $lec->gender; ?></option>
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
                                                                    <a href="advance_search_lecturers?leave=<?php echo $lec->id; ?>" class="text-center btn btn-danger"> Yes </a>
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
                                                                    <a href="advance_search_lecturers?onwork=<?php echo $lec->id; ?>" class="text-center btn btn-success"> Confirm </a>
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
                </section>
                <!-- Main Footer -->
                <?php require_once('partials/footer.php'); ?>
            </div>
        </div>
        <!-- ./wrapper -->
        <?php require_once('partials/scripts.php'); ?>
</body>

</html>