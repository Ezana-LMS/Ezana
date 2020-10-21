<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_class'])) {
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
        $id = $_POST['id'];
        $classdate = $_POST['classdate'];
        $classtime  = $_POST['classtime'];
        $classlocation = $_POST['classlocation'];
        $classlecturer = $_POST['classlecturer'];
        $classname  = $_POST['classname'];
        $classlink = $_POST['classlink'];
        $query = "INSERT INTO ezanaLMS_TimeTable (id, classdate, classtime, classlocation, classlecturer, classname, classlink) VALUES(?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssss', $id, $classdate, $classtime, $classlocation, $classlecturer, $classname, $classlink);
        $stmt->execute();
        if ($stmt) {
            $success = "Class Added" && header("refresh:1; url=add_class.php");
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
        <?php require_once('partials/_sidebar.php'); ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Add New Faculty</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="manage_faculties.php">Faculties</a></li>
                                <li class="breadcrumb-item active">Add</li>
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
                                        <div class="form-group col-md-6">
                                            <label for="">Faculty Name</label>
                                            <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="">Faculty Number / Code</label>
                                            <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="exampleInputPassword1">Faculty Description</label>
                                            <textarea required id="textarea" name="details" rows="10" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" name="add_faculty" class="btn btn-primary">Add Faculty</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>