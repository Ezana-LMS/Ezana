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
require_once('../config/codeGen.php');
edu_admn_checklogin();

/* Add Student */
if (isset($_POST['add_student'])) {
    $time = time();
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
    $mailed_password = (($_POST['password']));
    $hashed_password = sha1(md5($mailed_password));
    $faculty_id = $_POST['faculty_id'];
    $day_enrolled = $_POST['day_enrolled'];
    $school = $_POST['school'];
    $course = $_POST['course'];
    $department = $_POST['department'];
    $current_year = $_POST['current_year'];

    $profile_pic = $time . $_FILES['profile_pic']['name'];
    move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "../Data/User_Profiles/students/" . $time . $_FILES["profile_pic"]["name"]);

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

        $query = "INSERT INTO ezanaLMS_Students (id, faculty_id, day_enrolled, school, course, department, current_year, name, email, phone, admno, idno, adr, dob, gender, acc_status, created_at, password, profile_pic) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssssssssssssssss', $id, $faculty_id, $day_enrolled, $school, $course, $department, $current_year, $name, $email, $phone, $admno, $idno, $adr, $dob, $gender, $acc_status, $created_at, $hashed_password, $profile_pic);
        $stmt->execute();
        /* Load Mailer */
        require_once('../config/student_mailer.php');

        if ($stmt && $mail->send()) {

            $success = "Student Added";
        } else {
            $info = "Please Try Again Or Try Later $mail->ErrorInfo ";
        }
    }
}



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
        $success = "$name Account Updated";
    } else {
        $info = "Please Try Again Or Try Later";
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
        $success = "Suspended" && header("refresh:1; url=faculty_students?view=$view");
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
        $success = "Un Suspended" && header("refresh:1; url=faculty_students?view=$view");
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
                                    <li class="breadcrumb-item"><a href="faculty?view=<?php echo $view; ?>">Faculties</a></li>
                                    <li class="breadcrumb-item active">Students</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="col-md-12">
                                <div class="text-center">
                                    <h1 class="m-0 text-bold"><?php echo $faculty->name; ?> Students</h1>
                                    <br>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Student</button>

                                </div>
                            </div>
                            <div class="">
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
                                                                <label for="">Name</label>
                                                                <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                <input type="hidden" required name="password" value="<?php echo $defaultPass ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Admission Number</label>
                                                                <input type="text" required name="admno" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">ID / Passport Number</label>
                                                                <input type="text" required name="idno" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Date Of Birth</label>
                                                                <input type="date" placeholder="DD - MM - YYYY" required name="dob" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Gender</label>
                                                                <select type="text" required name="gender" class="form-control basic">
                                                                    <option>Male</option>
                                                                    <option>Female</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Course Enrolled</label>
                                                                <select class='form-control basic' id="CourseCode" onchange="getStudentCourseDetails(this.value);">
                                                                    <option selected>Select Course Code </option>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE faculty_id = '$view' ";
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
                                                                <input type="text" required readonly name="course" class="form-control" id="CourseName">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Department Name</label>
                                                                <input type="text" required readonly name="department" class="form-control" id="DepartmentName">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Faculty / School Name</label>
                                                                <input type="text" required readonly name="school" class="form-control" id="FacultyName">
                                                                <input type="hidden" required name="faculty_id" class="form-control" id="FacultyID">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Current Year</label>
                                                                <select name="current_year" class='form-control basic'>
                                                                    <option>1st Year </option>
                                                                    <option>2nd Year </option>
                                                                    <option>3rd Year </option>
                                                                    <option>4th Year </option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Date Enrolled</label>
                                                                <input type="date" placeholder="DD - MM - YYYY" required name="day_enrolled" class="form-control">
                                                            </div>

                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group col-md-4">
                                                                <label for="">Email</label>
                                                                <input type="email" required name="email" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Phone Number</label>
                                                                <input type="text" required name="phone" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
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
                                                    <div class="text-right">
                                                        <button type="submit" name="add_student" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Add Student Modal -->
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
                                                    $ret = "SELECT * FROM `ezanaLMS_Students` WHERE faculty_id = '$view' ";
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
                                                                }
                                                                ?>
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
                                                                                <a href="faculty_students?view=<?php echo $faculty->id; ?>&suspend=<?php echo $std->id; ?>" class="text-center btn btn-danger">Yes Suspend Account </a>
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
                                                                                <a href="faculty_students?view=<?php echo $faculty->id; ?>&unsuspend=<?php echo $std->id; ?>" class="text-center btn btn-danger">Yes Unsuspend Account </a>
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