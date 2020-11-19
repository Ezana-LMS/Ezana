<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
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
        $view = $_GET['view'];
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
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "dist/img/students/" . $_FILES["profile_pic"]["name"]);

        $query = "UPDATE ezanaLMS_Students SET name =?, email =?, phone =?, admno =?, idno =?, adr =?, dob =?, gender =?, acc_status =?, updated_at =?, profile_pic =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssssssss', $name, $email, $phone, $admno,  $idno, $adr, $dob, $gender, $acc_status, $updated_at, $profile_pic, $view);
        $stmt->execute();
        if ($stmt) {
            $success = "Updated Profile" && header("refresh:1; url=view_student.php?view=$view&faculty=$faculty");
        } else {
            //inject alert that profile update task failed
            $info = "Please Try Again Or Try Later";
        }
    }
}

//Change Password
if (isset($_POST['change_password'])) {

    //Change Password
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
            $view = $_GET['view'];
            $new_password  = sha1(md5($_POST['new_password']));
            $query = "UPDATE ezanaLMS_Students SET  password =? WHERE id =?";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ss', $new_password, $view);
            $stmt->execute();
            if ($stmt) {
                $success = "Password Changed" && header("refresh:1; url=view_student.php?view=$view&faculty=$faculty");
            } else {
                $err = "Please Try Again Or Try Later";
            }
        }
    }
}

require_once('partials/_head.php');
?>

<body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <?php
        require_once('partials/_faculty_nav.php');
        $faculty = $_GET['faculty'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$faculty' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {

            $view = $_GET['view'];
            $ret = "SELECT * FROM `ezanaLMS_Students` WHERE id ='$view' ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($std = $res->fetch_object()) {
                //Get Default Profile Picture
                if ($std->profile_pic == '') {
                    $dpic = "<img class='profile-user-img img-fluid img-circle' src='dist/img/logo.jpeg' alt='User profile picture'>";
                } else {
                    $dpic = "<img class='profile-user-img img-fluid img-circle' src='dist/img/students/$std->profile_pic' alt='User profile picture'>";
                }
                require_once('partials/_faculty_sidebar.php');
        ?>
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark"><?php echo $std->name; ?> Profile</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></li>
                                        <li class="breadcrumb-item"><a href="students.php?faculty=<?php echo $row->id; ?>">Students</a></li>
                                        <li class="breadcrumb-item active"> Profile </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content">
                        <div class="container">
                            <section class="content">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="card card-primary card-outline">
                                                <div class="card-body box-profile">
                                                    <div class="text-center">
                                                        <?php echo $dpic; ?>
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
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="card card-primary card-outline">
                                                <div class="card-body">
                                                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Modules Enrolled</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="custom-content-below-profile-settings" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Profile Settings</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="custom-content-below-settings" data-toggle="pill" href="#custom-content-below-changepwd" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Change Password</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content" id="custom-content-below-tabContent">
                                                        <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                            <br>
                                                            <table id="example1" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Module Code</th>
                                                                        <th>Module Name</th>
                                                                        <th>Date Enrolled</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $admission_number = $std->admno;
                                                                    $ret = "SELECT * FROM `ezanaLMS_Enrollments` WHERE student_adm = '$admission_number'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    $cnt = 1;
                                                                    while ($mod = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $cnt; ?></td>
                                                                            <td><?php echo $mod->module_code; ?></td>
                                                                            <td><?php echo $mod->module_name; ?></td>
                                                                            <td><?php echo $mod->created_at; ?></td>
                                                                        </tr>
                                                                    <?php $cnt = $cnt + 1;
                                                                    } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                                            <br>
                                                            <form method="post" enctype="multipart/form-data" role="form">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="form-group col-md-4">
                                                                            <label for="">Name</label>
                                                                            <input type="text" required name="name" value="<?php echo $std->name; ?>" class="form-control" id="exampleInputEmail1">
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
                                                                            <textarea required id='textarea' name="adr" rows="5" class="form-control"><?php echo $std->adr; ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right">
                                                                    <button type="submit" name="update_student" class="btn btn-primary">Update Students Profile</button>
                                                                </div>
                                                            </form>
                                                        </div>

                                                        <div class="tab-pane fade" id="custom-content-below-changepwd" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                                            <br>
                                                            <form method="post" enctype="multipart/form-data" role="form">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="form-group col-md-12">
                                                                            <label for="">New Password</label>
                                                                            <input type="password" required name="new_password" class="form-control">
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
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
        <?php require_once('partials/_footer.php');
            }
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>