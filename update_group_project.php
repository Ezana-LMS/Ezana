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
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "EzanaLMSData/Group_Projects/" . $_FILES["attachments"]["name"]);
    $updated_at = date('d M Y g:i');
    $submitted_on = $_POST['submitted_on'];
    $faculty = $_GET['faculty'];

    $query = "UPDATE ezanaLMS_GroupsAssignments SET attachments =?, details =?, updated_at =?, submitted_on =? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssss', $attachments, $details, $updated_at, $submitted_on, $update);
    $stmt->execute();
    if ($stmt) {
        $success = "Group Assignment / Project Updated" && header("refresh:1; url=student_group_assignments.php?faculty=$faculty");
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
            $update = $_GET['update'];
            $ret = "SELECT * FROM `ezanaLMS_GroupsAssignments` WHERE id = '$update'  ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($gcode = $res->fetch_object()) {
                require_once('partials/_faculty_sidebar.php');
        ?>
                <!-- /.navbar -->

                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark"><?php echo $gcode->group_name; ?></h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></li>
                                        <li class="breadcrumb-item"><a href="student_groups.php?faculty=<?php echo $row->id; ?>">Student Groups</a></li>
                                        <li class="breadcrumb-item"><a href="student_group_assignments.php?faculty=<?php echo $row->id; ?>">Assignments</a></li>
                                        <li class="breadcrumb-item active"> Update Assignments </li>
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
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputPassword1">Submission Date </label>
                                                            <input type="date" value="<?php echo $gcode->submitted_on; ?>" required name="submitted_on" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="">Upload Group Project / Assignment (PDF Or Docx)</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input data-default-file="dist/Group_Projects/<?php echo $gcode->attachments; ?>" required data-max-file-size="5M" name="attachments" type="file" class="custom-file-input">
                                                                    <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h2 class="text-center">Or</h2>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputPassword1">Type Group Project / Assignment Or Project Description </label>
                                                            <textarea name="details" id="textarea" rows="10" required class="form-control"><?php echo $gcode->details; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-right">
                                                    <button type="submit" name="update_group_project" class="btn btn-primary">Submit</button>
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