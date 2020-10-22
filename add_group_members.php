<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_member'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['student_admn']) && !empty($_POST['student_admn'])) {
        $student_admn = mysqli_real_escape_string($mysqli, trim($_POST['student_admn']));
    } else {
        $error = 1;
        $err = "Student Admission Number Cannot Be Empty";
    }
    if (isset($_POST['student_name']) && !empty($_POST['student_name'])) {
        $student_name = mysqli_real_escape_string($mysqli, trim($_POST['student_name']));
    } else {
        $error = 1;
        $err = "Student Name Cannot Be Empty";
    }
    if (isset($_GET['code']) && !empty($_GET['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_GET['code']));
    } else {
        $error = 1;
        $err = "Group Code Cannot Be Empty";
    }

    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_StudentsGroups  WHERE  (code = '$code' AND student_admn ='$student_admn')   ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if (($code  && $student_admn) == ($row['code'] && $row['student_admn'])) {
                $err = "Student Already Added To Group";
            }
        } else {
            $id = $_POST['id'];
            $name = $_GET['name'];
            $code = $_GET['code'];
            $student_admn = $_POST['student_admn'];
            $student_name = $_POST['student_name'];

            $query = "INSERT INTO ezanaLMS_StudentsGroups (id, name, code, student_admn, student_name) VALUES(?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('sssss', $id, $name, $code, $student_admn, $student_name);
            $stmt->execute();
            if ($stmt) {
                $success = "Student Added To group" && header("refresh:1; url=add_group_members.php?name=$name&code=$code");
            } else {
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
                            <h1>Add Students' </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="manage_groups.php">Groups</a></li>
                                <li class="breadcrumb-item active">Add Members</li>
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
                                            <label for="">Student Admission Number</label>
                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                            <select class='form-control basic' id="StudentAdmn" onchange="getStudentDetails(this.value);" name="student_admn">
                                                <option selected>Select Admission Number</option>
                                                <?php
                                                $ret = "SELECT * FROM `ezanaLMS_Students`  ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($std = $res->fetch_object()) {
                                                ?>
                                                    <option><?php echo $std->admno; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="">Student Name</label>
                                            <input type="text" id="StudentName" readonly required name="student_name" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" name="add_member" class="btn btn-primary">Add Member</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>