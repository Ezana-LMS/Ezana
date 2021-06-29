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

/* Update Non Teaching Staff */
if (isset($_POST['update_non_teaching_staff'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = mysqli_real_escape_string($mysqli, trim($_POST['id']));
    } else {
        $error = 1;
        $err = "ID Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Name Cannot Be Empty";
    }
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = mysqli_real_escape_string($mysqli, trim($_POST['email']));
    } else {
        $error = 1;
        $err = "Email Cannot Be Empty";
    }

    if (isset($_POST['rank']) && !empty($_POST['rank'])) {
        $rank = mysqli_real_escape_string($mysqli, trim($_POST['rank']));
    } else {
        $error = 1;
        $err = "Rank Cannot Be Empty";
    }
    if (isset($_POST['phone']) && !empty($_POST['phone'])) {
        $phone = mysqli_real_escape_string($mysqli, trim($_POST['phone']));
    } else {
        $error = 1;
        $err = "Phone Cannot Be Empty";
    }

    if (isset($_POST['adr']) && !empty($_POST['adr'])) {
        $adr = mysqli_real_escape_string($mysqli, trim($_POST['adr']));
    } else {
        $error = 1;
        $err = "Address Cannot Be Empty";
    }

    if (!$error) {

        $gender = $_POST['gender'];
        $employee_id = $_POST['employee_id'];
        $date_employed = $_POST['date_employed'];
        $personal_email = $_POST['personal_email'];
        $national_id = $_POST['personal_email'];

        $query = "UPDATE ezanaLMS_Admins SET name =?, email =?,  rank =?, phone =?, personal_email = ?, national_id = ?, adr =?, gender = ?, employee_id =?, date_employed =? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssssssss', $name, $email, $rank, $phone, $personal_email, $national_id, $adr, $gender, $employee_id, $date_employed,  $id);
        $stmt->execute();
        if ($stmt) {
            $success = "$name  Updated";
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}


/* Suspend Account */
if (isset($_GET['suspend'])) {
    $suspend = $_GET['suspend'];
    $adn = "UPDATE  ezanaLMS_Admins SET status = 'Suspended' WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $suspend);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Suspended" && header("refresh:1; url=advanced_search_non_teaching_staff");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Unsuspend Account  */
if (isset($_GET['unsuspend'])) {
    $unsuspend = $_GET['unsuspend'];
    $adn = "UPDATE  ezanaLMS_Admins SET status = '' WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $unsuspend);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "UnSuspended" && header("refresh:1; url=advanced_search_non_teaching_staff");
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
                            <h1 class="m-0 text-bold">Advanced Search Non Teaching Staff</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right small">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="non_teaching_staff">Non Teaching Staff</a></li>
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
                                        <button name="SearchAdmins" class="btn btn-outline-success my-2 my-sm-0" type="submit">Search By School / Faculty</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Faculty / School</th>
                                        <th>Rank</th>
                                        <th>Manage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_POST['SearchAdmins'])) {
                                        $School = $_POST['School'];
                                        $ret = "SELECT * FROM `ezanaLMS_Admins`  WHERE school = '$School'  ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        while ($admin = $res->fetch_object()) {
                                    ?>
                                            <tr>
                                                <td><?php echo $admin->name; ?></td>
                                                <td><?php echo $admin->email; ?></td>
                                                <td><?php echo $admin->school; ?></td>
                                                <td><?php echo $admin->rank; ?></td>
                                                <td>
                                                    <a class="badge badge-success" href="administrator?view=<?php echo $admin->id; ?>">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>

                                                    <a class="badge badge-warning" data-toggle="modal" href="#edit-<?php echo $admin->id; ?>">
                                                        <i class="fas fa-user-edit"></i>
                                                        Update
                                                    </a>
                                                    <?php
                                                    /* Suspend  */
                                                    if ($admin->status == '') {
                                                        echo
                                                        "
                                                    <a class='badge badge-danger' data-toggle='modal' href='#suspend-$admin->id'>
                                                        <i class='fas fa-user-clock'></i>
                                                        Suspend
                                                    </a>
                                                    ";
                                                    } else {
                                                        echo
                                                        "
                                                    <a class='badge badge-success' data-toggle='modal' href='#unsuspend-$admin->id'>
                                                        <i class='fas fa-user-check'></i>
                                                        UnSuspend
                                                    </a>
                                                    ";
                                                    } ?>


                                                    <!-- Suspend Modal -->
                                                    <div class="modal fade" id="suspend-<?php echo $admin->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">CONFIRM ACCOUNT SUSPEND</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body text-center text-danger">
                                                                    <h4>Suspend <?php echo $admin->name; ?> Account ?</h4>
                                                                    <br>
                                                                    <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                    <a href="advanced_search_non_teaching_staff?suspend=<?php echo $admin->id; ?>" class="text-center btn btn-danger"> Suspend Account </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Suspend -->

                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="edit-<?php echo $admin->id; ?>">
                                                        <div class="modal-dialog  modal-xl">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Update <?php echo $admin->name; ?> Details </h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="form-group col-md-12">
                                                                                    <label for="">Name</label>
                                                                                    <input type="text" required value="<?php echo $admin->name; ?>" name="name" class="form-control" id="exampleInputEmail1">
                                                                                    <input type="hidden" required name="id" value="<?php echo $admin->id; ?>" class="form-control">
                                                                                </div>

                                                                                <div class="form-group col-md-6">
                                                                                    <label for="">Work Email</label>
                                                                                    <input type="text" value="<?php echo $admin->email; ?>" required name="email" class="form-control">
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="">Personal Email</label>
                                                                                    <input type="text" value="<?php echo $admin->personal_email; ?>" required name="personal_email" class="form-control">
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="">National ID</label>
                                                                                    <input type="text" value="<?php echo $admin->national_id; ?>" required name="national_id" class="form-control">
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="">Phone Number</label>
                                                                                    <input type="text" value="<?php echo $admin->phone; ?>" required name="phone" class="form-control">
                                                                                </div>

                                                                                <div class="form-group col-md-3">
                                                                                    <label for="">Rank</label>
                                                                                    <select class="form-control basic" name="rank">
                                                                                        <option>System Administrator</option>
                                                                                        <option>Education Administrator</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="">Gender</label>
                                                                                    <select class="form-control basic" name="gender">
                                                                                        <option>Male</option>
                                                                                        <option>Female</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="">Employee ID</label>
                                                                                    <input type="text" value="<?php echo $admin->employee_id; ?>" required name="employee_id" class="form-control">
                                                                                </div>
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="">Date Employed</label>
                                                                                    <input type="date" placeholder="DD-MM-YYYY" value="<?php echo $admin->date_employed; ?>" required name="date_employed" class="form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="form-group col-md-12">
                                                                                    <label for="exampleInputPassword1">Address</label>
                                                                                    <textarea required name="adr" rows="2" class="form-control Summernote"><?php echo $admin->adr; ?></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-right">
                                                                            <button type="submit" name="update_non_teaching_staff" class="btn btn-primary">Submit</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Modal -->

                                                    <!-- Unsuspend -->
                                                    <div class="modal fade" id="unsuspend-<?php echo $admin->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title " id="exampleModalLabel">CONFIRM ACCOUNT RESTORATION</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body text-center text-danger">
                                                                    <h4>UnSuspend <?php echo $admin->name; ?> Account ?</h4>
                                                                    <br>
                                                                    <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                    <a href="advanced_search_non_teaching_staff?unsuspend=<?php echo $admin->id; ?>" class="text-center btn btn-danger"> Unsuspend Account </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Modal -->

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