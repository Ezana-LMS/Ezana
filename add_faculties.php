<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_faculty'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Faculty Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Faculty Name Cannot Be Empty";
    }
    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Faculties WHERE  code='$code' || name ='$name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Faculty With This Code Already Exists";
            } else {
                $err = "Faculty Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $details = $_POST['details'];

            $query = "INSERT INTO ezanaLMS_Faculties (id, code, name) VALUES(?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('sss', $id, $code, $name);
            $stmt->execute();
            if ($stmt) {
                $success = "Faculty Added" && header("refresh:1; url=add_faculties.php");
            } else {
                //inject alert that profile update task failed
                $info = "Please Try Again Or Try Later";
            }
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
                            <h1>Add New Lecturer</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="manage_lectures.php">Lecturers</a></li>
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
                                        <div class="form-group col-md-4">
                                            <label for="">Name</label>
                                            <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Number</label>
                                            <input type="text" required name="number" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                        </div>
                                        <div class="form-group col-md-4">
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
                                            <input type="password" required name="password" class="form-control">
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
                                            <textarea required name="adr" rows="5" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" name="add_lec" class="btn btn-primary">Submit</button>
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