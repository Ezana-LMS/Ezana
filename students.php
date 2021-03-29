<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('configs/codeGen.php');

/* Add STD */
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
        $no_of_modules = $_POST['no_of_modules'];

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

            $query = "INSERT INTO ezanaLMS_Students (id, faculty_id, day_enrolled, school, course, department, current_year, no_of_modules, name, email, phone, admno, idno, adr, dob, gender, acc_status, created_at, password, profile_pic) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssssssssssssssss', $id, $faculty_id, $day_enrolled, $school, $course, $department, $current_year, $no_of_modules, $name, $email, $phone, $admno, $idno, $adr, $dob, $gender, $acc_status, $created_at, $password, $profile_pic);
            $stmt->execute();
            if ($stmt) {
                $success = "Student Add " && header("refresh:1; url=students.php");
            } else {
                //inject alert that profile update task failed
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/* Update Student */
if (isset($_POST['update_student'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['admno']) && !empty($_POST['admno'])) {
        $admno = mysqli_real_escape_string($mysqli, trim($_POST['admno']));
    } else {
        $error = 1;
        $err = "Admission  Number Cannot Be Empty";
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
        $day_enrolled = $_POST['date_enrolled'];
        $school = $_POST['school'];
        $course = $_POST['course'];
        $department = $_POST['department'];
        $current_year = $_POST['current_year'];
        $no_of_modules = $_POST['no_of_modules'];
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $admno = $_POST['admno'];
        $idno  = $_POST['idno'];
        $adr = $_POST['adr'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $updated_at = date('d M Y');
        $profile_pic = $_FILES['profile_pic']['name'];
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "public/uploads/UserImages/students/" . $_FILES["profile_pic"]["name"]);

        $query = "UPDATE ezanaLMS_Students SET day_enrolled =?, school =?, course =?, department =?, current_year =?, no_of_modules =?, name =?, email =?, phone =?, admno =?, idno =?, adr =?, dob =?, gender =?, updated_at =?, profile_pic =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param(
            'sssssssssssssssss',
            $day_enrolled,
            $school,
            $course,
            $department,
            $current_year,
            $no_of_modules,
            $name,
            $email,
            $phone,
            $admno,
            $idno,
            $adr,
            $dob,
            $gender,
            $updated_at,
            $profile_pic,
            $id
        );
        $stmt->execute();
        if ($stmt) {
            $success = "Updated Profile" && header("refresh:1; url=students.php");
        } else {
            //inject alert that profile update task failed
            $info = "Please Try Again Or Try Later";
        }
    }
}


/* Update Student Passwords */
if (isset($_POST['change_password'])) {
    $error = 0;
    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $new_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['new_password']))));
    } else {
        $error = 1;
        $err = "New Password Cannot Be Empty";
    }
    if (isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])) {
        $confirm_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['confirm_password']))));
    } else {
        $error = 1;
        $err = "Confirmation Password Cannot Be Empty";
    }

    if (!$error) {
        if ($new_password != $confirm_password) {
            $err = "Password Does Not Match";
        } else {
            $student = $_POST['student'];
            $new_password  = sha1(md5($_POST['new_password']));
            $query = "UPDATE ezanaLMS_Students SET  password =? WHERE id =?";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ss', $new_password, $student);
            $stmt->execute();
            if ($stmt) {
                $success = "Password Changed" && header("refresh:1; url=students.php");
            } else {
                $err = "Please Try Again Or Try Later";
            }
        }
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
        $success = "Suspended" && header("refresh:1; url=students.php");
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
        $success = "Un Suspended" && header("refresh:1; url=students.php");
    } else {
        $info = "Please Try Again Or Try Later";
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
                            <h1 class="m-0 text-dark">Students</h1>
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
                        <div class="">
                            <nav class="navbar navbar-light bg-light col-md-12">
                                <form class="form-inline">
                                </form>
                                <div class="text-right">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Student</button>
                                </div>
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
                                                                <label for="">Course Enrolled</label>
                                                                <select class='form-control basic' id="CourseCode" onchange="getStudentCourseDetails(this.value);">
                                                                    <option selected>Select Course Code </option>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Courses` ";
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
                                                                <input type="text" required name="course" class="form-control" id="CourseName">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Department Name</label>
                                                                <input type="text" required name="department" class="form-control" id="DepartmentName">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Faculty / School Name</label>
                                                                <input type="text" required name="school" class="form-control" id="FacultyName">
                                                                <input type="hidden" required name="faculty_id" class="form-control" id="FacultyID">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Current Year</label>
                                                                <input type="text" required name="current_year" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Date Enrolled</label>
                                                                <input type="text" required name="day_enrolled" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">No Of Modules</label>
                                                                <input type="text" required name="no_of_modules" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Name</label>
                                                                <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Admission Number</label>
                                                                <input type="text" required name="admno" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-4">
                                                                <label for="">ID / Passport Number</label>
                                                                <input type="text" required name="idno" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Date Of Birth</label>
                                                                <input type="text" required name="dob" class="form-control">
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
                                                                <input type="email" required name="email" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Phone Number</label>
                                                                <input type="text" required name="phone" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Password</label>
                                                                <input type="text" value="<?php echo $defaultPass; ?>" required name="password" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
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
                                                    <div class="card-footer text-right">
                                                        <button type="submit" name="add_student" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Student Modal -->
                            </nav>
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
                                            <th>Phone</th>
                                            <th>ID/Passport</th>
                                            <th>Gender</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_Students` ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($std = $res->fetch_object()) {
                                        ?>
                                            <tr>
                                                <td><?php echo $std->admno; ?></td>
                                                <td><?php echo $std->name; ?></td>
                                                <td><?php echo $std->email; ?></td>
                                                <td><?php echo $std->phone; ?></td>
                                                <td><?php echo $std->idno; ?></td>
                                                <td><?php echo $std->gender; ?></td>
                                                <td>
                                                    <a class="badge badge-success" data-toggle="modal" href="#view-student-<?php echo $std->id; ?>">
                                                        <i class="fas fa-user-graduate"></i>
                                                        View
                                                    </a>
                                                    <!-- View Student Modal -->
                                                    <div class="modal fade" id="view-student-<?php echo $std->id; ?>">
                                                        <div class="modal-dialog  modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title"><?php echo $std->name; ?> Profile</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12 card-body box-profile">
                                                                            <div class="text-center">
                                                                                <?php
                                                                                //Get Default Profile Picture
                                                                                if ($std->profile_pic == '') {
                                                                                    echo "<img class='profile-user-img img-fluid img-circle' src='public/dist/img/no-profile.png' alt='User profile picture'>";
                                                                                } else {
                                                                                    echo "<img class='profile-user-img img-fluid img-circle' src='public/uploads/UserImages/students/$std->profile_pic' alt='User profile picture'>";
                                                                                } ?>
                                                                            </div>

                                                                            <h3 class="profile-username text-center"><?php echo $std->name; ?></h3>

                                                                            <p class="text-muted text-center"><?php echo $std->admno; ?></p>

                                                                            <ul class="list-group list-group-unbordered mb-3">
                                                                                <li class="list-group-item">
                                                                                    <b>Email: </b> <a class="float-right"><?php echo $std->email; ?></a>
                                                                                </li>
                                                                                <li class="list-group-item">
                                                                                    <b>ID / Passport: </b> <a class="float-right"><?php echo $std->idno; ?></a>
                                                                                </li>
                                                                                <li class="list-group-item">
                                                                                    <b>Phone: </b> <a class="float-right"><?php echo $std->phone; ?></a>
                                                                                </li>
                                                                                <li class="list-group-item">
                                                                                    <b>Address</b> <a class="float-right"><?php echo $std->adr; ?></a>
                                                                                </li>
                                                                                <li class="list-group-item">
                                                                                    <b>DOB</b> <a class="float-right"><?php echo $std->dob; ?></a>
                                                                                </li>
                                                                                <li class="list-group-item">
                                                                                    <b>Gender</b> <a class="float-right"><?php echo $std->gender; ?></a>
                                                                                </li>
                                                                                <li class="list-group-item">
                                                                                    <b>Added At </b> <a class="float-right"><?php echo $std->created_at; ?></a>
                                                                                </li>
                                                                                <li class="list-group-item">
                                                                                    <b>Updated At </b> <a class="float-right"><?php echo $std->updated_at; ?></a>
                                                                                </li>
                                                                                <li class="list-group-item">
                                                                                    <b>Account Status</b>
                                                                                    <a class="float-right">
                                                                                        <?php
                                                                                        if ($std->acc_status == 'Active') {
                                                                                            echo "<span class='badge badge-success'>$std->acc_status</span>";
                                                                                        } else {
                                                                                            echo "<span class='badge badge-danger'>$std->acc_status</span>";
                                                                                        }
                                                                                        ?>
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>

                                                                        <div class="modal-footer justify-content-between">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Modal -->
                                                    <a class="badge badge-primary" data-toggle="modal" href="#update-student-<?php echo $std->id; ?>">
                                                        <i class="fas fa-edit"></i>
                                                        Update
                                                    </a>
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
                                                                                <div class="form-group col-md-4">
                                                                                    <label for="">Name</label>
                                                                                    <input type="text" required name="name" value="<?php echo $std->name; ?>" class="form-control">
                                                                                    <input type="hidden" required name="id" value="<?php echo $std->id ?>" class="form-control">
                                                                                </div>
                                                                                <div class="form-group col-md-4">
                                                                                    <label for="">Admission Number</label>
                                                                                    <input type="text" required name="admno" value="<?php echo $std->admno; ?>" class="form-control">
                                                                                </div>
                                                                                <div class="form-group col-md-4">
                                                                                    <label for="">ID / Passport Number</label>
                                                                                    <input type="text" value="<?php echo $std->idno; ?>" required name="idno" class="form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="form-group col-md-4">
                                                                                    <label for="">Date Of Birth</label>
                                                                                    <input type="text" value="<?php echo $std->dob; ?>" required name="dob" class="form-control">
                                                                                </div>
                                                                                <div class="form-group col-md-4">
                                                                                    <label for="">Gender</label>
                                                                                    <select type="text" required name="gender" class="basic form-control">
                                                                                        <option selected><?php echo $std->gender; ?></option>
                                                                                        <option>Male</option>
                                                                                        <option>Female</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group col-md-4">
                                                                                    <label for="">Student Account Status</label>
                                                                                    <select type="text" required name="acc_status" class="basic form-control">
                                                                                        <option selected><?php echo $std->acc_status; ?></option>
                                                                                        <option>Active</option>
                                                                                        <option>Disabled</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="form-group col-md-4">
                                                                                    <label for="">Email</label>
                                                                                    <input value="<?php echo $std->email; ?>" type="email" required name="email" class="form-control">
                                                                                </div>
                                                                                <div class="form-group col-md-4">
                                                                                    <label for="">Phone Number</label>
                                                                                    <input type="text" value="<?php echo $std->phone; ?>" required name="phone" class="form-control">
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
                                                                                    <label for="exampleInputPassword1">Address</label>
                                                                                    <textarea required id='' name="adr" rows="3" class="form-control"><?php echo $std->adr; ?></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="card-footer text-right">
                                                                            <button type="submit" name="update_student" class="btn btn-primary">Update Students Profile</button>
                                                                        </div>
                                                                    </form>
                                                                    <!-- Change Password -->
                                                                    <h3 class="text-center">Change <?php echo $std->name; ?> Password</h3>
                                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="form-group col-md-12">
                                                                                    <label for="">New Password</label>
                                                                                    <input type="password" required name="new_password" class="form-control">
                                                                                    <input type="hidden" required name="student" value="<?php echo $std->id ?>" class="form-control">

                                                                                </div>
                                                                                <div class="form-group col-md-12">
                                                                                    <label for="">Confirm Password</label>
                                                                                    <input type="password" required name="confirm_password" class="form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="text-right">
                                                                                <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                    <!-- Email Password Reset Link -->
                                                                    <h4 class="text-center">Email <?php echo $std->name; ?> Password Reset Instructions</h4>
                                                                    <div class="card-body">
                                                                        <div class="text-center">
                                                                            <a onClick="javascript:window.open('mailto:<?php echo $std->email; ?>?subject=Password Reset Link!&body=Hello <?php echo $std->name; ?> - <?php echo $std->admno; ?>, Kindly Click On Forgot Password Link Then Follow The Prompts', 'mail');event.preventDefault()" class="btn btn-primary" href="mailto:<?php echo $std->email; ?>">
                                                                                Mail Password Reset Link And Instructions
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php $cnt = $cnt + 1;
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