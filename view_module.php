<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update_module'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Module Code Cannot Be Empty";
    }
    if (isset($_POST['c_name']) && !empty($_POST['c_name'])) {
        $course_name = mysqli_real_escape_string($mysqli, trim($_POST['c_name']));
    } else {
        $error = 1;
        $err = "Course Name Cannot Be Empty";
    }
    if (isset($_POST['course_id']) && !empty($_POST['course_id'])) {
        $course_id = mysqli_real_escape_string($mysqli, trim($_POST['course_id']));
    } else {
        $error = 1;
        $err = "Course ID Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (!$error) {
        $view = $_GET['view'];
        $name = $_POST['name'];
        $code = $_POST['code'];
        $details = $_POST['details'];
        $course_name = $_POST['c_name'];
        $course_id = $_POST['course_id'];
        $course_duration = $_POST['course_duration'];
        $exam_weight_percentage = $_POST['exam_weight_percentage'];
        $cat_weight_percentage = $_POST['cat_weight_percentage'];
        $lectures_number = $_POST['lectures_number'];
        $updated_at = date('d M Y');
        $faculty = $_GET['faculty'];
        $query = "UPDATE ezanaLMS_Modules SET  name =?, code =?, details =?, course_name =?, course_id =?, course_duration =?, exam_weight_percentage =?, cat_weight_percentage=?,  lectures_number =?, updated_at =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssssssss', $name, $code, $details, $course_name, $course_id, $course_duration, $exam_weight_percentage, $cat_weight_percentage, $lectures_number, $updated_at, $view);
        $stmt->execute();
        if ($stmt) {
            $success = "Module Updated" && header("refresh:1; url=view_module.php?view=$view&faculty=$faculty");
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
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($mod = $res->fetch_object()) {

            $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$mod->faculty_id'  ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            $cnt = 1;
            while ($row = $res->fetch_object()) {
                require_once('partials/_faculty_sidebar.php');
        ?>
                <!-- /.navbar -->
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark"> <?php echo $mod->name; ?> </h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="faculties.php">Faculties</a></li>
                                        <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></li>
                                        <li class="breadcrumb-item"><a href="modules.php?faculty=<?php echo $mod->faculty_id; ?>"><?php echo $mod->name; ?></a></li>
                                        <li class="breadcrumb-item active">View</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="content">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- Profile Image -->
                                    <div class="card card-primary card-outline">
                                        <div class="card-body box-profile">
                                            <div class="text-center">
                                                <img class='profile-user-img img-fluid img-circle' src='dist/img/logo.jpeg' alt='User profile picture'>
                                            </div>
                                            <br>
                                            <ul class="list-group list-group-unbordered mb-3">
                                                <li class="list-group-item">
                                                    <b>Module Name: </b> <a class="float-right"><?php echo $mod->name; ?></a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Module Code: </b> <a class="float-right"><?php echo $mod->code; ?></a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Course Name: </b> <a class="float-right"><?php echo $mod->course_name; ?></a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Duration : </b> <a class="float-right"><?php echo $mod->course_duration; ?></a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>No Of Lecturers Per Week : </b> <a class="float-right"><?php echo $mod->lectures_number; ?></a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Cat Weight Percentage : </b> <a class="float-right"><?php echo $mod->cat_weight_percentage; ?></a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Exam Weight Percentage : </b> <a class="float-right"><?php echo $mod->exam_weight_percentage; ?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card card-primary card-outline">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Details</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"><?php echo $mod->name; ?> Settings</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="custom-content-below-tabContent">
                                                <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                    <br>
                                                    <?php echo $mod->details; ?>
                                                </div>
                                                <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                                    <br>
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-md-4">
                                                                    <label for="">Module Name</label>
                                                                    <input type="text" value="<?php echo $mod->name; ?>" required name="name" class="form-control" id="exampleInputEmail1">
                                                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="">Module Number / Code</label>
                                                                    <input type="text" required name="code" value="<?php echo $mod->code; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="">Course Name</label>
                                                                    <input type="text" name="c_name" readonly required value="<?php echo $mod->course_name; ?>" class="form-control">
                                                                    <input type="hidden" value="<?php echo $mod->course_id; ?>" required name="course_id" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <!-- <div class="form-group col-md-6">
                                                                    <label for="">Course Name</label>
                                                                    <select class='form-control basic' id="CourseName" onchange="getCourseDetails(this.value);" name="c_name">
                                                                        <option selected>Select Course Name</option>
                                                                        <?php
                                                                        $ret = "SELECT * FROM `ezanaLMS_Courses`  ";
                                                                        $stmt = $mysqli->prepare($ret);
                                                                        $stmt->execute(); //ok
                                                                        $res = $stmt->get_result();
                                                                        while ($course = $res->fetch_object()) {
                                                                        ?>
                                                                            <option><?php echo $course->name; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Course Code</label>
                                                                    <input type="text" id="CourseCode" readonly required name="" class="form-control">
                                                                    <input type="hidden" id="CourseID" required name="course_id" class="form-control">
                                                                </div> -->

                                                            </div>

                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Teaching Duration</label>
                                                                    <input type="text" value="<?php echo $mod->course_duration; ?>" required name="course_duration" class="form-control" id="exampleInputEmail1">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Number Of Lectures Per Week</label>
                                                                    <input type="text" value="<?php echo $mod->lectures_number; ?>" required name="lectures_number" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">CAT Exam Weight Percentage</label>
                                                                    <input type="text" value="<?php echo $mod->cat_weight_percentage; ?>" required name="cat_weight_percentage" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">End Exam Weight Percentage</label>
                                                                    <input type="text" value="<?php echo $mod->exam_weight_percentage; ?>" required name="exam_weight_percentage" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputPassword1">Module Details</label>
                                                                    <textarea required id="textarea" name="details" rows="10" class="form-control"><?php echo $mod->details; ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit" name="update_module" class="btn btn-primary">Update Module</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Course Materials -->
                                <div class="col-md-6">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header text-center">
                                            <h3 class="card-title">Course Materials</h3>
                                        </div>
                                        <div class="card-body box-profile">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h2 class="text-right">
                                                        <a class="btn btn-outline-success" href="add_module_reading_materials.php?faculty=<?php echo $row->id; ?>">
                                                            Upload Reading Materials
                                                        </a>
                                                    </h2>
                                                </div>
                                                <div class="card-body">
                                                    <table id="example1" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Reading Material Shared On</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_ModuleRecommended` WHERE faculty_id ='$row->id' AND module_code = '$mod->code' ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            $cnt = 1;
                                                            while ($rm = $res->fetch_object()) {
                                                            ?>
                                                                <tr class="table-row" data-href="view_module_reading_material.php?view=<?php echo $rm->id; ?>&faculty=<?php echo $row->id; ?>">
                                                                    <td><?php echo $cnt; ?></td>
                                                                    <td><?php echo $rm->created_at; ?></td>
                                                                </tr>
                                                            <?php $cnt = $cnt + 1;
                                                            } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Course Materials -->
                                <div class="col-md-6">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header text-center">
                                            <h3 class="card-title">Class Recordings</h3>
                                        </div>
                                        <div class="card-body box-profile">

                                        </div>
                                    </div>
                                </div>


                                <!-- Lecturer Assigned Module -->
                                <div class="col-md-6">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header text-center">
                                            <h3 class="card-title">Lecturer Assigned Module</h3>
                                        </div>
                                        <div class="card-body box-profile">

                                        </div>
                                    </div>
                                </div>

                                <!-- Students Enrolled -->
                                <div class="col-md-6">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header text-center">
                                            <h3 class="card-title">Students Enrolled On <?php echo $mod->name; ?></h3>
                                        </div>
                                        <div class="card-body box-profile">

                                        </div>
                                    </div>
                                </div>

                                <!-- Student Groups -->
                                <div class="col-md-6">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header text-center">
                                            <h3 class="card-title">Student Groups</h3>
                                        </div>
                                        <div class="card-body box-profile">

                                        </div>
                                    </div>
                                </div>

                            </div>
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