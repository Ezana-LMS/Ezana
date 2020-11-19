<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

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
        $faculty = $_GET['faculty'];
        $view = $_GET['view'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $number = $_POST['number'];
        $idno  = $_POST['idno'];
        $adr = $_POST['adr'];
        $profile_pic = $_FILES['profile_pic']['name'];
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "dist/img/lecturers/" . $_FILES["profile_pic"]["name"]);

        $query = "UPDATE ezanaLMS_Lecturers SET  name =?, email =?, phone =?, idno =?, adr =?, profile_pic =?, number =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssss', $name, $email, $phone, $idno, $adr, $profile_pic, $number, $update);
        $stmt->execute();
        if ($stmt) {
            $success = "Lecturer Updated" && header("refresh:1; url=view_lecturer.php?view=$view&faculty=$faculty");
        } else {
            //inject alert that profile update task failed
            $info = "Please Try Again Or Try Later";
        }
    }
}
//Change Password
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
            $query = "UPDATE ezanaLMS_Lecturers SET  password =? WHERE id =?";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ss', $new_password, $update);
            $stmt->execute();
            if ($stmt) {
                $success = "Password Changed" && header("refresh:1; url=view_lecturer.php?view=$view&faculty=$faculty");
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
            require_once('partials/_faculty_sidebar.php');
            $view = $_GET['view'];
            $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE id ='$view' ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($lec = $res->fetch_object()) {
                //Get Default Profile Picture
                if ($lec->profile_pic == '') {
                    $dpic = "<img class='profile-user-img img-fluid img-circle' src='dist/img/logo.jpeg' alt='User profile picture'>";
                } else {
                    $dpic = "<img class='profile-user-img img-fluid img-circle' src='dist/img/lecturers/$lec->profile_pic' alt='User profile picture'>";
                } ?>
                <!-- /.navbar -->
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark"> <?php echo $lec->name; ?> Profile </h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></li>
                                        <li class="breadcrumb-item"><a href="lecturers.php?faculty=<?php echo $row->id; ?>">Lecturers</a></li>
                                        <li class="breadcrumb-item active">View</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
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

                                                    <h3 class="profile-username text-center"><?php echo $lec->name; ?></h3>

                                                    <p class="text-muted text-center"><?php echo $lec->number; ?></p>

                                                    <ul class="list-group list-group-unbordered mb-3">
                                                        <li class="list-group-item">
                                                            <b>Email: </b> <a class="float-right"><?php echo $lec->email; ?></a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>ID / Passport: </b> <a class="float-right"><?php echo $lec->idno; ?></a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Phone: </b> <a class="float-right"><?php echo $lec->phone; ?></a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Address</b> <a class="float-right"><?php echo $lec->adr; ?></a>
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
                                                            <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Modules Assigned</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="custom-content-below-profile-settings" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Profile Settings</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="custom-content-below-changepwd" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Change Password</a>
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
                                                                        <th>Date Allocated</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns` WHERE lec_id ='$view'   ";
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
                                                                            <input type="text" required name="name" value="<?php echo $lec->name; ?>" class="form-control" id="exampleInputEmail1">
                                                                        </div>
                                                                        <div class="form-group col-md-4">
                                                                            <label for="">Number</label>
                                                                            <input type="text" required name="number" value="<?php echo $lec->name; ?>" class="form-control">
                                                                        </div>
                                                                        <div class="form-group col-md-4">
                                                                            <label for="">ID / Passport Number</label>
                                                                            <input type="text" value="<?php echo $lec->idno; ?>" required name="idno" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group col-md-6">
                                                                            <label for="">Email</label>
                                                                            <input type="email" value="<?php echo $lec->email; ?>" required name="email" class="form-control">
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label for="">Phone Number</label>
                                                                            <input type="text" required value="<?php echo $lec->phone; ?>" name="phone" class="form-control">
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
                                                                            <textarea required name="adr" id="textarea" rows="5" class="form-control"><?php echo $lec->adr; ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card-footer">
                                                                    <button type="submit" name="update_lec" class="btn btn-primary">Submit</button>
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