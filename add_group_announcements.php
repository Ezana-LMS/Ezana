<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_notice'])) {
    $id = $_POST['id'];
    $group_code  = $_POST['group_code'];
    $group_name = $_POST['group_name'];
    $announcement = $_POST['announcement'];
    $created_by = $_POST['created_by'];
    $created_at = date('d M Y');
    $faculty = $_GET['faculty'];

    $query = "INSERT INTO ezanaLMS_GroupsAnnouncements (id, faculty_id, group_name, group_code, announcement, created_by, created_at) VALUES(?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssss', $id, $faculty, $group_name, $group_code, $announcement, $created_by, $created_at);
    $stmt->execute();
    if ($stmt) {
        $success = "Posted" && header("refresh:1; url=add_group_announcements.php?faculty=$faculty");
    } else {
        $info = "Please Try Again Or Try Later";
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
            require_once('partials/_faculty_sidebar.php')
        ?>
            <!-- /.navbar -->

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">Student Groups Announcements</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></li>
                                    <li class="breadcrumb-item"><a href="student_groups.php?faculty=<?php echo $row->id; ?>">Student Groups</a></li>
                                    <li class="breadcrumb-item"><a href="student_group_notices.php?faculty=<?php echo $row->id; ?>">Notices</a></li>
                                    <li class="breadcrumb-item active"> Add </li>
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
                                                        <label for="exampleInputPassword1">Group Name</label>
                                                        <select class='form-control basic' id="GroupName" onchange="getGroupDetails(this.value);" name="group_name">
                                                            <option selected>Select Group Name</option>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_Groups` WHERE faculty_id = '$row->id'";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($group = $res->fetch_object()) {
                                                            ?>
                                                                <option><?php echo $group->name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputPassword1"> Group Code</label>
                                                        <input type="text" required name="group_code" id="groupCode" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="">Notice Posted By</label>
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
                                                        <label for="exampleInputPassword1">Group Notice</label>
                                                        <textarea required id="textarea" name="announcement" rows="20" class="form-control"></textarea>
                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                <button type="submit" name="add_notice" class="btn btn-primary">Add Notice</button>
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