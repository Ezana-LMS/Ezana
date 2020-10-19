<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['profile_update'])) {

    $view = $_GET['view'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $adr = $_POST['adr'];
    $rank = $_POST['rank'];
    $profile_pic = $_FILES['profile_pic']['name'];
    move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "dist/img/system_admin/" . $_FILES["profile_pic"]["name"]);
    $query = "UPDATE ezanaLMS_Admins  SET name =?, email =?, phone =?, adr =?, profile_pic =?, rank =? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssss', $name, $email, $phone, $adr,  $profile_pic, $rank,  $view);
    $stmt->execute();
    if ($stmt) {
        $success = "Profile Updated" && header("refresh:1; url=system_admins.php");
    } else {
        //inject alert that profile update task failed
        $info = "Please Try Again Or Try Later";
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
                            <h1>Add New Administrator</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="system_admins.php">System Administrators</a></li>
                                <li class="breadcrumb-item active">Register Administrator</li>
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
                            <form role="form">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputEmail1">Name</label>
                                            <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control" id="exampleInputEmail1">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputEmail1">Email</label>
                                            <input type="email" required name="email" class="form-control" id="exampleInputEmail1">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputEmail1">Phone Number</label>
                                            <input type="text" required name="phone" class="form-control" id="exampleInputEmail1">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Ranks</label>
                                            <select name="rank" class="form-control select2">
                                                <option selected="selected">System Administrator</option>
                                                <option>Education Administrator</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputPassword1">Password</label>
                                            <input type="password" required name="password" class="form-control" id="exampleInputPassword1">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputFile">Profile Picture</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input required name="profile_pic" type="file" class="custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
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