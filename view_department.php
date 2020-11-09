<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
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
        $department = $_GET['department'];
        $name = $_POST['name'];
        $code = $_POST['code'];
        $details = $_POST['details'];
        $hod = $_POST['hod'];

        $query = "UPDATE ezanaLMS_Departments SET code =?, name =?,  details =?, hod =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssss', $code, $name, $details, $hod, $department);
        $stmt->execute();
        if ($stmt) {
            $success = "Faculty Department Updated" && header("refresh:1; url=view_department.php?department=$department");
        } else {
            //inject alert that profile update task failed
            $info = "Please Try Again Or Try Later";
        }
    }
}
require_once('partials/_head.php');
?>

<body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <?php
        require_once('partials/_dep_nav.php');
        $department = $_GET['department'];
        $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE id = '$department' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {
        ?>
            <!-- /.navbar -->
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"> <?php echo $row->name; ?> Details </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculties.php">Faculties</a></li>
                                    <li class="breadcrumb-item"><a href=""><?php echo $row->name; ?></a></li>
                                    <li class="breadcrumb-item active">View</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="content">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">

                                <!-- Profile Image -->
                                <div class="card card-primary card-outline">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            <img class='profile-user-img img-fluid img-circle' src='dist/img/logo.jpeg' alt='User profile picture'>
                                        </div>

                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item">
                                                <b>Name: </b> <a class="float-right"><?php echo $row->name; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Code / Number : </b> <a class="float-right"><?php echo $row->code; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>HOD</b> <a class="float-right"><?php echo $row->hod; ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title">Department Details</h3>
                                    </div>
                                    <div class="card-body box-profile">
                                        <?php echo $row->details; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Department Settings</a></li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="active tab-pane" id="settings">
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group col-md-4">
                                                                <label for="">Department Name</label>
                                                                <input type="text" required name="name" value="<?php echo $dep->name; ?>" class="form-control" id="exampleInputEmail1">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Department Number / Code</label>
                                                                <input type="text" required name="code" value="<?php echo $dep->code; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Department HOD</label>
                                                                <input type="text" required value="<?php echo $dep->hod; ?>" name="hod" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputPassword1">Department Details</label>
                                                                <textarea name="details" id="textarea" rows="10" class="form-control"><?php echo $dep->details; ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <button type="submit" name="update_dept" class="btn btn-primary">Update Department</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php require_once('partials/_footer.php');
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>