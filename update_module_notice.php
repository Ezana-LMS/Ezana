<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_notice'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['announcements']) && !empty($_POST['announcements'])) {
        $announcements = mysqli_real_escape_string($mysqli, trim($_POST['announcements']));
    } else {
        $error = 1;
        $err = "Noctices Cannot Be Empty";
    }
    if (!$error) {
        $announcements = $_POST['announcements'];
        $created_by = $_POST['created_by'];
        $created_at = date('d M Y g:i');
        $faculty = $_GET['faculty'];
        $update = $_GET['update'];
        $query = "UPDATE  ezanaLMS_ModulesAnnouncements SET announcements =?, created_by =?, created_at =? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssss', $announcements, $created_by, $created_at, $update);
        $stmt->execute();
        if ($stmt) {
            $success = "Posted" && header("refresh:1; url=view_module_notices.php?view=$update&faculty=$faculty");
        } else {
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
        require_once('partials/_faculty_nav.php');
        $faculty = $_GET['faculty'];
        $update = $_GET['update'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$faculty' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {
            $ret = "SELECT * FROM `ezanaLMS_ModulesAnnouncements` WHERE id = '$update' ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            $cnt = 1;
            while ($not = $res->fetch_object()) {
                require_once('partials/_faculty_sidebar.php');
        ?>
                <!-- /.navbar -->

                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Update Module Announcement</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></li>
                                        <li class="breadcrumb-item"><a href="modules.php?faculty=<?php echo $row->id; ?>">Modules</a></li>
                                        <li class="breadcrumb-item"><a href="module_notices.php?faculty=<?php echo $row->id; ?>">Announcements</a></li>
                                        <li class="breadcrumb-item active">Update</li>
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
                                                            <label for="">Module Name</label>
                                                            <input readonly type="text" value="<?php echo $not->module_name; ?>" id="ModuleCode" required name="module_code" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="">Module Code</label>
                                                            <input  readonly type="text" id="ModuleCode" value="<?php echo $not->module_code; ?>" required name="module_code" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="">Announcement Posted By</label>
                                                            <?php
                                                            $id = $_SESSION['id'];
                                                            $ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id = '$id'  ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($user = $res->fetch_object()) {
                                                            ?>
                                                                <input type="text" required name="created_by" value="<?php echo $user->name; ?>" class="form-control" id="exampleInputEmail1">
                                                            <?php
                                                            } ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputPassword1">Module Announcements</label>
                                                            <textarea required id="textarea" name="announcements" rows="20" class="form-control"><?php echo $not->announcements; ?></textarea>
                                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-right">
                                                    <button type="submit" name="add_notice" class="btn btn-primary">Update Notice</button>
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
            }
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>