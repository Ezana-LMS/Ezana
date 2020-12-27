<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('configs/codeGen.php');

/* Add Non Teaching Staff */
if (isset($_POST['add_non_teaching_staff'])) {
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
    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['password']))));
    } else {
        $error = 1;
        $err = "Password Cannot Be Empty";
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
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Admins WHERE  email='$email' || phone ='$phone' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($email == $row['email']) {
                $err =  "Account With This Email Already Exists";
            } elseif ($phone == $row['phone']) {
                $err = "Account With That Phone Number Exists";
            } else {
                //Silence
            }
        } else {
            $profile_pic = $_FILES['profile_pic']['name'];
            move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "public/uploads/UserImages/admins/" . $_FILES["profile_pic"]["name"]);

            $query = "INSERT INTO ezanaLMS_Admins (id, name, email, password, rank, phone, adr, profile_pic) VALUES(?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssss', $id, $name, $email, $password, $rank, $phone, $adr, $profile_pic);
            $stmt->execute();
            if ($stmt) {
                $success = "Non Teaching Staff Added" && header("refresh:1; url=non_teaching_staff.php");
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/* Update Lec */
if (isset($_POST['update_non_teaching_staff'])) {
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
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $number = $_POST['number'];
        $idno  = $_POST['idno'];
        $adr = $_POST['adr'];
        $profile_pic = $_FILES['profile_pic']['name'];
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "public/uploads/UserImages/lecturers/" . $_FILES["profile_pic"]["name"]);

        $query = "UPDATE ezanaLMS_Lecturers SET  name =?, email =?, phone =?, idno =?, adr =?, profile_pic =?, number =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssss', $name, $email, $phone, $idno, $adr, $profile_pic, $number, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Lecturer Updated" && header("refresh:1; url=lecturers.php");
        } else {
            //inject alert that profile update task failed
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Delete Lec */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $adn = "DELETE FROM ezanaLMS_Admins WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=non_teaching_staff.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Change Lec Password */
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
            $update = $_GET['update'];
            $new_password  = sha1(md5($_POST['new_password']));
            $query = "UPDATE ezanaLMS_Admins SET  password =? WHERE id =?";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ss', $new_password, $update);
            $stmt->execute();
            if ($stmt) {
                $success = "Password Changed" && header("refresh:1; url=non_teaching_staff.php");
            } else {
                $err = "Please Try Again Or Try Later";
            }
        }
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
                            <a href="non_teaching_staff.php" class="active nav-link">
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
                            <a href="students.php" class="nav-link">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Students
                                </p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    System Settings
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="reports.php" class="nav-link">
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
                            <h1 class="m-0 text-dark">Non Teaching Staff</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Non Teaching Staff</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <nav class="navbar navbar-light bg-light col-md-12">
                            <form class="form-inline">
                            </form>
                            <div class="text-left">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">Add Non Teaching Staff</button>
                            </div>
                            <!-- Add Lec Modal -->
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
                                                            <label for="">Name</label>
                                                            <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Email</label>
                                                            <input type="text" required name="email" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Phone Number</label>
                                                            <input type="text" required name="phone" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Rank</label>
                                                            <select class="form-control basic" name="rank">
                                                                <option>System Administrator</option>
                                                                <option>Education Administrator</option>
                                                            </select>
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
                                                            <textarea required name="adr" rows="2" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-right">
                                                    <button type="submit" name="add_non_teaching_staff" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Add Non Teaching Staff Modal -->
                        </nav>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Rank</th>
                                        <th>Manage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM `ezanaLMS_Admins`  ";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute(); //ok
                                    $res = $stmt->get_result();
                                    $cnt = 1;
                                    while ($admin = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $admin->name; ?></td>
                                            <td><?php echo $admin->email; ?></td>
                                            <td><?php echo $admin->phone; ?></td>
                                            <td><?php echo $admin->rank; ?></td>
                                            <td>
                                                <a class="badge badge-success" data-toggle="modal" href="#view-<?php echo $admin->id; ?>">
                                                    <i class="fas fa-user-tie"></i>
                                                    View
                                                </a>
                                                <!-- Non Teaching Staff -->
                                                <div class="modal fade" id="view-<?php echo $admin->id; ?>">
                                                    <div class="modal-dialog  modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title"><?php echo $admin->name; ?> Profile</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="card-body box-profile">
                                                                    <div class="text-center">
                                                                        <?php
                                                                        if ($lec->profile_pic == '') {
                                                                            echo  "<img class='profile-user-img img-fluid img-circle' src='public/dist/img/no-profile.png' alt='User profile picture'>";
                                                                        } else {
                                                                            echo  "<img class='profile-user-img img-fluid img-circle' src='public/uploads/UserImages/admins/$admin->profile_pic' alt='User profile picture'>";
                                                                        }
                                                                        ?>
                                                                    </div>

                                                                    <h3 class="profile-username text-center"><?php echo $admin->name; ?></h3>

                                                                    <ul class="list-group list-group-unbordered mb-3">
                                                                        <li class="list-group-item">
                                                                            <b>Email: </b> <a class="float-right"><?php echo $admin->email; ?></a>
                                                                        </li>
                                                                        <li class="list-group-item">
                                                                            <b>Rank: </b> <a class="float-right"><?php echo $admin->rank; ?></a>
                                                                        </li>
                                                                        <li class="list-group-item">
                                                                            <b>Phone: </b> <a class="float-right"><?php echo $admin->phone; ?></a>
                                                                        </li>
                                                                        <li class="list-group-item">
                                                                            <b>Address: </b> <a class="float-right"><?php echo $admin->adr; ?></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a class="badge badge-primary" data-toggle="modal" href="#update-<?php echo $admin->id; ?>">
                                                    <i class="fas fa-edit"></i>
                                                    Update
                                                </a>

                                                <!-- Update Lec Modal -->
                                                <div class="modal fade" id="update-<?php echo $admin->id; ?>">
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
                                                                                <input type="text" required value="<?php echo $admin->name;?>" name="name" class="form-control" id="exampleInputEmail1">
                                                                                <input type="hidden" required name="id" value="<?php echo $admin->id;?>" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Email</label>
                                                                                <input type="text" value="<?php echo $admin->email;?>" required name="email" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Phone Number</label>
                                                                                <input type="text" value="<?php echo $admin->phone;?>" required name="phone" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Rank</label>
                                                                                <select class="form-control basic" name="rank">
                                                                                    <option><?php echo $admin->rank;?></option>
                                                                                    <option>System Administrator</option>
                                                                                    <option>Education Administrator</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group col-md-12">
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
                                                                                <textarea required name="adr" rows="2" class="form-control"><?php echo $admin->adr;?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-footer text-right">
                                                                        <button type="submit" name="update_non_teaching_staff" class="btn btn-primary">Submit</button>
                                                                    </div>
                                                                </form>

                                                                <!-- Change Password -->
                                                                <h4 class="text-center">Change <?php echo $admin->name; ?> Password</h4>
                                                                <form method="post" enctype="multipart/form-data" role="form">
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">New Password</label>
                                                                                <input type="password" required name="new_password" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Confirm Password</label>
                                                                                <input type="password" required name="confirm_password" class="form-control">
                                                                                <input type="hidden" required name="id" value="<?php echo $admin->id; ?>" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-right">
                                                                            <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                                <hr>
                                                                <!-- Email Password Reset Link -->
                                                                <h4 class="text-center">Email <?php echo $admin->name; ?> Password Reset Instructions</h4>
                                                                <div class="card-body">
                                                                    <div class="text-center">
                                                                        <a onClick="javascript:window.open('mailto:<?php echo $admin->email; ?>?subject=Password Reset Link!&body=Hello <?php echo $admin->name; ?>, Kindly Click On Forgot Password Link Then Follow The Prompts', 'mail');event.preventDefault()" class="btn btn-primary" href="mailto:<?php echo $admin->email; ?>">
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

                                                <!-- End Lec Modal -->
                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $admin->id; ?>">
                                                    <i class="fas fa-trash"></i>
                                                    Delete
                                                </a>
                                                <!-- Delete Confirmation Modal -->

                                                <div class="modal fade" id="delete-<?php echo $admin->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-center text-danger">
                                                                <h4>Delete <?php echo $admin->name; ?> Details ?</h4>
                                                                <br>
                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                <a href="non_teaching_staff.php?delete=<?php echo $admin->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- End Delete Confirmation Modal -->
                                            </td>
                                        </tr>
                                    <?php $cnt = $cnt + 1;
                                    } ?>
                                </tbody>
                            </table>
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