<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update_class'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['classdate']) && !empty($_POST['classdate'])) {
        $classdate = mysqli_real_escape_string($mysqli, trim($_POST['classdate']));
    } else {
        $error = 1;
        $err = "Date Cannot Be Empty";
    }
    if (isset($_POST['classtime']) && !empty($_POST['classtime'])) {
        $classtime = mysqli_real_escape_string($mysqli, trim($_POST['classtime']));
    } else {
        $error = 1;
        $err = "Time Cannot Be Empty";
    }
    if (isset($_POST['classlocation']) && !empty($_POST['classlocation'])) {
        $classlocation = mysqli_real_escape_string($mysqli, trim($_POST['classlocation']));
    } else {
        $error = 1;
        $err = "Lecture Hall Be Empty";
    }
    if (isset($_POST['classlecturer']) && !empty($_POST['classlecturer'])) {
        $classlecturer = mysqli_real_escape_string($mysqli, trim($_POST['classlecturer']));
    } else {
        $error = 1;
        $err = "Lecturer Name Be Empty";
    }



    if (!$error) {
        $update = $_GET['update'];
        $classdate = $_POST['classdate'];
        $classtime  = $_POST['classtime'];
        $classlocation = $_POST['classlocation'];
        $classlecturer = $_POST['classlecturer'];
        $classname  = $_POST['classname'];
        $classlink = $_POST['classlink'];
        $query = "UPDATE ezanaLMS_TimeTable SET classdate =?, classtime =?, classlocation =?, classlecturer =?, classname =?, classlink =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssss', $classdate, $classtime, $classlocation, $classlecturer, $classname, $classlink, $update);
        $stmt->execute();
        if ($stmt) {
            $success = "Class Updated" && header("refresh:1; url=generate_timetable.php");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

require_once('partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('partials/_nav.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php
        require_once('partials/_sidebar.php');
        $update = $_GET['update'];
        $ret = "SELECT * FROM `ezanaLMS_TimeTable` WHERE id = '$update'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        while ($tt = $res->fetch_object()) {
        ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Update <?php echo $tt->classname; ?> Class</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="generate_timetable.php">TimeTables</a></li>
                                    <li class="breadcrumb-item active">Update Class</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Fill All Required Fields</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" enctype="multipart/form-data" role="form">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label for="">Class Name</label>
                                                <input type="text" value="<?php echo $tt->classname; ?>" required name="classname" class="form-control" id="exampleInputEmail1">
                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="">Lecturer Name</label>
                                                <input type="text" value="<?php echo $tt->classlecturer; ?>" required name="classlecturer" class="form-control">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="">Lecture Hall / Room / Location</label>
                                                <input type="text" required value='<?php echo $tt->classlocation; ?>' name="classlocation" class="form-control" id="exampleInputEmail1">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="">Time</label>
                                                <input type="text" value="<?php echo $tt->classtime; ?>" required name="classtime" class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Date</label>
                                                <input type="text" required value="<?php echo $tt->classdate; ?>" name="classdate" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="exampleInputPassword1">Class Link <small class="text-danger">If Its Virtual Class </small></label>
                                                <input type="text" name="classlink" value="<?php echo $tt->classlink; ?>" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="update_class" class="btn btn-primary">Update Class</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        <?php require_once('partials/_footer.php');
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>