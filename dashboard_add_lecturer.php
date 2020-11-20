<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_lec'])) {
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
    if (isset($_POST['faculty']) && !empty($_POST['faculty'])) {
        $faculty = mysqli_real_escape_string($mysqli, trim($_POST['faculty']));
    } else {
        $error = 1;
        $err = "Faculty Cannot Be Empty";
    }

    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Lecturers WHERE  email='$email' || phone ='$phone' || idno = '$idno' || number ='$number' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($email == $row['email']) {
                $err =  "Account With This Email Already Exists";
            } elseif ($phone == $row['phone']) {
                $err = "Account With That Phone Number Exists";
            } elseif ($idno == $row['idno']) {
                $err = "National ID Number  / Passport Number Already Exists";
            } else {
                $err = "Lecturer Number Already Exists";
            }
        } else {
            $faculty = $_POST['faculty'];
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $number = $_POST['number'];
            $idno  = $_POST['idno'];
            $adr = $_POST['adr'];
            $created_at = date('d M Y');
            $password = sha1(md5($_POST['password']));
            $profile_pic = $_FILES['profile_pic']['name'];
            move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "dist/img/lecturers/" . $_FILES["profile_pic"]["name"]);

            $query = "INSERT INTO ezanaLMS_Lecturers (id, faculty_id, name, email, phone, idno, adr, profile_pic, created_at, password, number) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('sssssssssss', $id, $faculty, $name, $email, $phone, $idno, $adr, $profile_pic, $created_at, $password, $number);
            $stmt->execute();
            if ($stmt) {
                $success = "Lecturer Added" && header("refresh:1; url=dashboard_add_lecturer.php");
            } else {
                //inject alert that profile update task failed
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}
require_once('partials/_head.php');
?>

<body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">
        <?php
        require_once('partials/_nav.php');
        require_once('partials/_sidebar.php');
        ?>
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Add Lecturers</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Add Lecturers </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container">
                    <section class="content">
                        <div class="container-fluid">
                            <div class="col-md-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Fill All Required Fields</h3>
                                    </div>
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
                                                    <label for="">Number</label>
                                                    <input type="text" required name="number" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="">ID / Passport Number</label>
                                                    <input type="text" required name="idno" class="form-control">
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
                                                    <textarea required name="adr" rows="5" id="textarea" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="submit" name="add_lec" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <?php require_once('partials/_footer.php');
        ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>