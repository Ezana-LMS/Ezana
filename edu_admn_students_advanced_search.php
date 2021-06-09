<?php
/*
 * Created on Wed Apr 21 2021
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
require_once('configs/codeGen.php');
check_login();

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

        /* Faculty ID  */
        $view = $_GET['view'];

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

            $query = "INSERT INTO ezanaLMS_Students (id, faculty_id, day_enrolled, school, course, department, current_year,  name, email, phone, admno, idno, adr, dob, gender, acc_status, created_at, password, profile_pic) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('sssssssssssssssssss', $id, $faculty_id, $day_enrolled, $school, $course, $department, $current_year, $name, $email, $phone, $admno, $idno, $adr, $dob, $gender, $acc_status, $created_at, $password, $profile_pic);
            $stmt->execute();
            if ($stmt) {
                $success = "Student Added " && header("refresh:1; url=edu_admn_students.php?view=$view");
            } else {
                //inject alert that profile update task failed
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/* Update Student */
if (isset($_POST['update_student'])) {
    $error = 0;
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
    $day_enrolled = $_POST['day_enrolled'];
    $school = $_POST['school'];
    $course = $_POST['course'];
    $department = $_POST['department'];
    $current_year = $_POST['current_year'];

    /* FacultyID */
    $view = $_POST['view'];

    if (!$error) {
        $query = "UPDATE ezanaLMS_Students SET day_enrolled =?, school =?, course =?, department =?, current_year =?,  name =?, email =?, phone =?, admno =?, idno =?, adr =?, dob =?, gender =?, updated_at =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssssssssssss', $day_enrolled, $school, $course, $department, $current_year, $name, $email, $phone, $admno, $idno, $adr, $dob, $gender, $updated_at, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Student Add " && header("refresh:1; url=edu_admn_students.php?view=$view");
        } else {
            //inject alert that profile update task failed
            $info = "Please Try Again Or Try Later";
        }
    }
}



/* Suspend Account */
if (isset($_GET['suspend'])) {
    $suspend = $_GET['suspend'];
    /* FacultyID */
    $view = $_GET['view'];

    $adn = "UPDATE  ezanaLMS_Students SET acc_status = 'Suspended' WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $suspend);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Suspended" && header("refresh:1; url=edu_admn_students.php?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* UnSuspend Account */
if (isset($_GET['unsuspend'])) {
    $unsuspend = $_GET['unsuspend'];
    /* FacultyID */
    $view = $_GET['view'];
    $adn = "UPDATE  ezanaLMS_Students SET acc_status = 'Active' WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $unsuspend);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Un Suspended" && header("refresh:1; url=edu_admn_students.php?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}


require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('public/partials/_edu_admn_nav.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id= '$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($faculty = $res->fetch_object()) {
        ?>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <?php require_once('public/partials/_brand.php'); ?>
                <!-- Sidebar -->
                <?php require_once('public/partials/_sidebar.php'); ?>
            </aside>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"><?php echo $faculty->name; ?> Students Advanced Search</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="edu_admn_dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item active"><?php echo $faculty->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <form method="POST">
                                <div class="d-flex justify-content-center">
                                    <select name="Course" class='col-md-6 form-control basic mr-sm-2'>
                                        <option selected>Select Courses</option>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE faculty_id = '$view' ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        while ($courses = $res->fetch_object()) {
                                        ?>
                                            <option><?php echo $courses->name; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="text-right">
                                        <button name="SearchStudents" class="btn btn-outline-success my-2 my-sm-0" type="submit">Search By Course</button>
                                    </div>
                                </div>
                            </form>

                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Adm No</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Department</th>
                                                        <th>Course</th>
                                                        <th>Year</th>
                                                        <th>Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (isset($_POST['SearchStudents'])) {
                                                        $Department = $_POST['Department'];
                                                        $Course = $_POST['Course'];
                                                        $CurrentYear = $_POST['CurrentYear'];

                                                        $ret = "SELECT * FROM `ezanaLMS_Students` WHERE faculty_id = '$view' AND (department = '$Department' || course = '$Course' || current_year = '$CurrentYear')";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        while ($std = $res->fetch_object()) {
                                                    ?>
                                                            <tr>
                                                                <td><?php echo $std->admno; ?></td>
                                                                <td><?php echo $std->name; ?></td>
                                                                <td><?php echo $std->email; ?></td>
                                                                <td><?php echo $std->department; ?></td>
                                                                <td><?php echo $std->course; ?></td>
                                                                <td><?php echo $std->current_year; ?></td>
                                                                <td>
                                                                    <a class="badge badge-success" href="edu_admn_student_profile.php?view=<?php echo $std->id; ?>">
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
                                                                                                    <label for="">Course Name</label>
                                                                                                    <input type="text" value="<?php echo $std->course; ?>" required name="course" class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-6">
                                                                                                    <label for="">Department Name</label>
                                                                                                    <input type="text" required name="department" class="form-control" value="<?php echo $std->department; ?>">
                                                                                                </div>
                                                                                                <div class="form-group col-md-12">
                                                                                                    <label for="">Faculty / School Name</label>
                                                                                                    <input type="text" required name="school" class="form-control" value="<?php echo $std->school; ?>">
                                                                                                </div>
                                                                                                <div class="form-group col-md-6">
                                                                                                    <label for="">Current Year</label>
                                                                                                    <select name="current_year" class='form-control basic'>
                                                                                                        <option><?php echo $std->current_year; ?></option>
                                                                                                        <option>1st Year </option>
                                                                                                        <option>2nd Year </option>
                                                                                                        <option>3rd Year </option>
                                                                                                        <option>4th Year </option>
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="form-group col-md-6">
                                                                                                    <label for="">Date Enrolled</label>
                                                                                                    <input type="text" required name="day_enrolled" value="<?php echo $std->day_enrolled; ?>" class="form-control">
                                                                                                </div>

                                                                                                <div class="form-group col-md-6">
                                                                                                    <label for="">Name</label>
                                                                                                    <input type="text" required name="name" class="form-control" value="<?php echo $std->name; ?>">
                                                                                                    <input type="hidden" required name="id" value="<?php echo $std->id; ?>" class="form-control">
                                                                                                    <input type="hidden" required name="view" value="<?php echo $faculty->id; ?>" class="form-control">

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
                                                                                                    <input type="date" required name="dob" value="<?php echo $std->dob; ?>" class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-4">
                                                                                                    <label for="">Gender</label>
                                                                                                    <select type="text" required name="gender" class="form-control basic">
                                                                                                        <option><?php echo $std->gender; ?></option>
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
                                                                                        <div class="card-footer text-right">
                                                                                            <button type="submit" name="update_student" class="btn btn-primary">Submit</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                                <div class="modal-footer justify-content-between">
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                                                                                    <a href="edu_admn_students.php?view=<?php echo $faculty->id; ?>&suspend=<?php echo $std->id; ?>" class="text-center btn btn-danger">Yes Suspend Account </a>
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
                                                                                    <a href="edu_admn_students.php?view=<?php echo $faculty->id; ?>&unsuspend=<?php echo $std->id; ?>" class="text-center btn btn-danger">Yes Unsuspend Account </a>
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
                            </div>
                        </div>
                    </section>
                    <!-- Main Footer -->
                    <?php require_once('public/partials/_footer.php'); ?>
                </div>
            </div>
            <!-- ./wrapper -->
        <?php require_once('public/partials/_scripts.php');
        } ?>
</body>

</html>