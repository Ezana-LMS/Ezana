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
    if (isset($_POST['faculty']) && !empty($_POST['faculty'])) {
        $faculty = mysqli_real_escape_string($mysqli, trim($_POST['faculty']));
    } else {
        $error = 1;
        $err = "Faculty Cannot Be Empty";
    }

    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Students WHERE  email='$email' || phone ='$phone' || idno = '$idno' || admno ='$admno' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($email == $row['email']) {
                $err =  "Account With This Email Already Exists";
            } elseif ($admno == $row['admno']) {
                $err = "Student Admission Number Already Exists";
            } elseif ($idno == $row['idno']) {
                $err = "National ID Number  / Passport Number Already Exists";
            } else {
                $err = "Account With That Phone Number Exists";
            }
        } else {
            $faculty = $_POST['faculty'];
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $admno = $_POST['admno'];
            $idno  = $_POST['idno'];
            $adr = $_POST['adr'];
            $dob = $_POST['dob'];
            $gender = $_POST['gender'];
            $acc_status = 'Active';
            $created_at = date('d M Y');
            $password = sha1(md5($_POST['password']));
            $profile_pic = $_FILES['profile_pic']['name'];
            move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "public/uploads/UserImages/students/" . $_FILES["profile_pic"]["name"]);

            $query = "INSERT INTO ezanaLMS_Students (id, faculty_id,  name, email, phone, admno, idno, adr, dob, gender, acc_status, created_at, password, profile_pic) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssssssssss', $id, $faculty,  $name, $email, $phone, $admno,  $idno, $adr, $dob, $gender, $acc_status, $created_at, $password,  $profile_pic);
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
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $admno = $_POST['admno'];
        $idno  = $_POST['idno'];
        $adr = $_POST['adr'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $acc_status = $_POST['acc_status'];
        $updated_at = date('d M Y');
        $profile_pic = $_FILES['profile_pic']['name'];
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "public/uploads/UserImages/students/" . $_FILES["profile_pic"]["name"]);

        $query = "UPDATE ezanaLMS_Students SET name =?, email =?, phone =?, admno =?, idno =?, adr =?, dob =?, gender =?, acc_status =?, updated_at =?, profile_pic =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssssssss', $name, $email, $phone, $admno,  $idno, $adr, $dob, $gender, $acc_status, $updated_at, $profile_pic, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Updated Profile" && header("refresh:1; url=students.php");
        } else {
            //inject alert that profile update task failed
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Delete Student */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $adn = "DELETE FROM ezanaLMS_Students WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=students.php");
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
            <a href="dashboard.php" class="brand-link">
                <img src="public/dist/img/logo.png" alt="Ezana LMS Logo" class="brand-image img-circle elevation-3">
                <span class="brand-text font-weight-light">Ezana LMS</span>
            </a>
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
                        <li class="nav-item">
                            <a href="settings.php" class="nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    System Settings
                                </p>
                            </a>
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
                        <div class="text-left">
                            <nav class="navbar navbar-light bg-light col-md-12">
                                <form class="form-inline">
                                </form>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Student</button>
                                <div class="modal fade" id="modal-default">
                                    <div class="modal-dialog  modal-lg">
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
                                                                <label for="">Faculty Name</label>
                                                                <select class='form-control basic' id="FacultyName" onchange="getFacutyDetails(this.value);">
                                                                    <option selected>Select Faculty Name </option>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Faculties`  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($row = $res->fetch_object()) {
                                                                    ?>
                                                                        <option><?php echo $row->name; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <input type="hidden" required name="faculty" id="FacultyId" class="form-control">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Name</label>
                                                                <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Admission Number</label>
                                                                <input type="text" required name="admno" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">ID / Passport Number</label>
                                                                <input type="text" required name="idno" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Date Of Birth</label>
                                                                <input type="text" required name="dob" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
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
                                                                <label for="exampleInputPassword1">Address</label>
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
                            </nav>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
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
                                                <td><?php echo $cnt; ?></td>
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
                                                    <a class="badge badge-primary" data-toggle="modal" href="#update-student-<?php echo $std->id; ?>">
                                                        <i class="fas fa-edit"></i>
                                                        Update
                                                    </a>
                                                    <!-- Update Lec Modal -->
                                                    <div class="modal fade" id="update-student-<?php echo $std->id; ?>">
                                                        <div class="modal-dialog  modal-lg">
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
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Modal -->
                                                    <a class="badge badge-danger" href="students.php?delete=<?php echo $std->id; ?>">
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </a>
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