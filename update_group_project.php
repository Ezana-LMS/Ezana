<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update_group_project'])) {

    $update = $_GET['update'];
    $details = $_POST['details'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "dist/Group_Projects/" . $_FILES["attachments"]["name"]);
    $updated_at = date('d M Y g:i');
    $submitted_on = $_POST['submitted_on'];
    $faculty = $_GET['faculty'];
    $code = $_GET['code'];
    $view = $_GET['view'];
    $name = $_GET['name'];

    $query = "UPDATE ezanaLMS_GroupsAssignments SET attachments =?, details =?, updated_at =?, submitted_on =? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssss', $attachments, $details, $updated_at, $submitted_on, $update);
    $stmt->execute();
    if ($stmt) {
        $success = "Group Assignment / Project Updated" && header("refresh:1; url=view_student_group.php?view=$view&faculty=$faculty&code=$code&name=$name");
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
        while ($f = $res->fetch_object()) {
            $group_code = $_GET['group_code'];
            $ret = "SELECT * FROM `ezanaLMS_Groups` WHERE code = '$group_code'  ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($g = $res->fetch_object()) {

        ?>
                <!-- /.navbar -->

                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark"><?php echo $g->name; ?></h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $f->id; ?>"><?php echo $f->name; ?></a></li>
                                        <li class="breadcrumb-item"><a href="student_groups.php?faculty=<?php echo $f->id; ?>">Student Groups</a></li>
                                        <li class="breadcrumb-item"><a href="view_student_group.php?&name=<?php echo $g->name; ?>&code=<?php echo $g->code; ?>&view=<?php echo $g->id; ?>&faculty=<?php echo $f->id; ?>"><?php echo $g->code; ?></a></li>
                                        <li class="breadcrumb-item active"> Update Assignments </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content">
                        <div class="container">
                            
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