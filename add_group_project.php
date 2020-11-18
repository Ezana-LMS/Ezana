<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_group_project'])) {

    $id = $_POST['id'];
    $group_code = $_POST['group_code'];
    $group_name  = $_POST['group_name'];
    $type = $_GET['type'];
    $details = $_POST['details'];
    $faculty = $_GET['faculty'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "EzanaLMSData/Group_Projects/" . $_FILES["attachments"]["name"]);
    $created_at = date('d M Y g:i');
    $submitted_on = $_POST['submitted_on'];

    $query = "INSERT INTO ezanaLMS_GroupsAssignments (id, faculty_id, group_code, group_name, attachments, type, details, created_at, submitted_on) VALUES(?,?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssssss', $id, $faculty, $group_code, $group_name, $attachments,  $type, $details, $created_at, $submitted_on);
    $stmt->execute();
    if ($stmt) {
        $success = "Group Assignment Added" && header("refresh:1; url=add_group_project.php?&faculty=$faculty&type=Project");
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

        ?>
            <!-- /.navbar -->

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">Add Group Assignment</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $f->id; ?>"><?php echo $f->name; ?></a></li>
                                    <li class="breadcrumb-item"><a href="student_groups.php?faculty=<?php echo $f->id; ?>">Student Groups</a></li>
                                    <li class="breadcrumb-item"><a href="student_group_assignments.php?faculty=<?php echo $f->id; ?>">Group Assignments</a></li>
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
                                            <h3 class="card-title">Fill Required Fields</h3>
                                        </div>
                                        <form method="post" enctype="multipart/form-data" role="form">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputPassword1">Group Name</label>
                                                        <select class='form-control basic' id="GroupName" onchange="getGroupDetails(this.value);" name="group_name">
                                                            <option selected>Select Group Name</option>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_Groups` WHERE faculty_id = '$f->id'";
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
                                                        <label for="exampleInputPassword1">Submission Date </label>
                                                        <input type="date" required name="submitted_on" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="">Upload Group Project / Assignment (PDF Or Docx)</label>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input name="attachments" type="file" class="custom-file-input">
                                                                <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h2 class="text-center">Or</h2>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="exampleInputPassword1">Type Group Project / Assignment Or Project Description </label>
                                                        <textarea name="details" id="textarea" rows="10" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                <button type="submit" name="add_group_project" class="btn btn-primary">Submit</button>
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
        ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>