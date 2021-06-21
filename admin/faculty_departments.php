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
admin_checklogin();
require_once('../config/codeGen.php');

/* Add Departments */
if (isset($_POST['add_dept'])) {
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
            $view = $_GET['view'];
            $details = $_POST['details'];
            $hod = $_POST['hod'];
            $faculty_name = $_POST['faculty_name'];
            $created_at = date('d M Y');
            $email = $_POST['email'];

            $query = "INSERT INTO ezanaLMS_Departments (id, code, name, faculty_id, faculty_name, details, hod, email,  created_at) VALUES(?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('sssssssss', $id, $code, $name, $view, $faculty_name, $details, $hod, $email, $created_at);
            $stmt->execute();
            if ($stmt) {
                $success = "$name Department Added";
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/*  Update Department*/
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
        $id = $_POST['id'];
        $details = $_POST['details'];
        $hod = $_POST['hod'];
        $email = $_POST['email'];
        $view = $_GET['view'];

        $query = "UPDATE ezanaLMS_Departments SET code =?, name =?, hod=?, email=?,  details =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssss', $code, $name, $hod, $email, $details, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "$name Department Updated";
        } else {
            $info = "Please Try Again Or Try Later";
        }
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
                                <h1 class="m-0 text-dark"><?php echo $faculty->name; ?> Departments</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculties">Faculties</a></li>
                                    <li class="breadcrumb-item active">Departments</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="text-left">
                                <nav class="navbar col-md-12">
                                    <form class="form-inline" method="GET">
                                    </form>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add New Department</button>
                                    <!-- Add Department Modal -->
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
                                                                    <label for="">Department Name</label>
                                                                    <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                    <input type="hidden" required name="faculty_name" value="<?php echo $faculty->name; ?>" class="form-control">

                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Department Number / Code</label>
                                                                    <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                                </div>
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
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputPassword1">Department Description</label>
                                                                    <textarea name="details" rows="10" class="form-control Summernote"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit" name="add_dept" class="btn btn-primary">Add Department</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </nav>
                            </div>
                            <hr>
                            <div class="row">
                                <!-- Faculty Sidemenu -->
                                <?php require_once('partials/faculty_menu.php'); ?>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-12">
                                            <table id="example1" class="table table-bordered table-striped">
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
                                                    $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE faculty_id = '$view'  ORDER BY `name` ASC   ";
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
                                                                <a class="badge badge-success" href="department?view=<?php echo $dep->id; ?>">
                                                                    <i class="fas fa-eye"></i>
                                                                    View
                                                                </a>
                                                                <a class="badge badge-primary" href="#update-<?php echo $dep->id; ?>" data-toggle="modal">
                                                                    <i class="fas fa-edit"></i>
                                                                    Update
                                                                </a>
                                                                <!-- Update Department Modal -->
                                                                <div class="modal fade" id="update-<?php echo $dep->id; ?>">
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
                                                                                                <label for="">Department Name</label>
                                                                                                <input type="text" required name="name" value="<?php echo $dep->name; ?>" class="form-control" id="exampleInputEmail1">
                                                                                            </div>
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Department Number / Code</label>
                                                                                                <input type="text" required name="code" value="<?php echo $dep->code; ?>" class="form-control">
                                                                                                <input type="hidden" required name="id" value="<?php echo $dep->id; ?>" class="form-control">
                                                                                                <input type="hidden" required name="view" value="<?php echo $dep->faculty_id; ?>" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Department HOD</label>
                                                                                                <input type="text" required value="<?php echo $dep->hod; ?>" name="hod" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Department HOD Email</label>
                                                                                                <input type="text" required value="<?php echo $dep->email; ?>" name="email" class="form-control">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="exampleInputPassword1">Department Details</label>
                                                                                                <textarea name="details" class="form-control Summernote"><?php echo $dep->details; ?></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class=" text-right">
                                                                                        <button type="submit" name="update_dept" class="btn btn-primary">Update Department</button>
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