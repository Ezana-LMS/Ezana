<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_dept'])) {
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
            $name = $_POST['name'];
            $code = $_POST['code'];
            $faculty = $_GET['faculty'];
            $details = $_POST['details'];
            $hod = $_POST['hod'];
            $created_at = date('d M Y');

            $query = "INSERT INTO ezanaLMS_Departments (id, code, name, faculty_id, details, hod, created_at) VALUES(?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('sssssss', $id, $code, $name, $faculty, $details, $hod, $created_at);
            $stmt->execute();
            if ($stmt) {
                $success = "Faculty Department Added" && header("refresh:1; url=add_department.php?faculty=$faculty");
            } else {
                //inject alert that profile update task failed
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

$faculty = $_GET['faculty'];
$ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$faculty' ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($f = $res->fetch_object()) {
    require_once('partials/_head.php'); ?>

    <body class="hold-transition sidebar-collapse layout-top-nav">
        <div class="wrapper">

            <!-- Navbar -->
            <?php require_once('partials/_faculty_sidebar.php'); ?>
            <!-- /.navbar -->

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">Add Department</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Faculties</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $faculty; ?>"><?php echo $f->name; ?></a></li>
                                    <li class="breadcrumb-item active">Add Department</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content">
                    <div class="container">
                        <section class="content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Fill All Required Fields</h3>
                                        </div>
                                        <form method="post" enctype="multipart/form-data" role="form">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label for="">Department Name</label>
                                                        <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="">Department Number / Code</label>
                                                        <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="">Department HOD</label>
                                                        <input type="text" required name="hod" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="exampleInputPassword1">Department Details</label>
                                                        <textarea name="details" id="textarea" rows="10" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" name="add_dept" class="btn btn-primary">Add Department</button>
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
    } ?>
        </div>
        <?php require_once('partials/_scripts.php'); ?>
    </body>

    </html>