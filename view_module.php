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
                                                    <a class="nav-link" id="custom-content-below-profile-notices" data-toggle="pill" href="#custom-content-below-notices" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"> Notices</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-content-below-profile-pastpapers" data-toggle="pill" href="#custom-content-below-pastpapers" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"> Past Papers</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"> Settings</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="custom-content-below-tabContent">
                                                <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                    <br>
                                                    <?php echo $mod->details; ?>
                                                </div>
                                                <div class="tab-pane fade show " id="custom-content-below-notices" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                    <br>
                                                    <table id="example1" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Created By</th>
                                                                <th>Date Posted</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_ModulesAnnouncements` WHERE faculty_id  = '$row->id' AND module_name = '$mod->name'  ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            $cnt = 1;
                                                            while ($not = $res->fetch_object()) {
                                                            ?>
                                                                <tr class="table-row" data-href="view_module_notices.php?view=<?php echo $not->id; ?>&faculty=<?php echo $not->faculty_id; ?>">
                                                                    <td><?php echo $cnt; ?></td>
                                                                    <td><?php echo $not->created_by; ?></td>
                                                                    <td><?php echo $not->created_at; ?></td>
                                                                    <td>
                                                                        <a class="badge badge-primary" href="update_module_notice.php?update=<?php echo $not->id; ?>&faculty=<?php echo $not->faculty_id; ?>">
                                                                            <i class="fas fa-edit"></i>
                                                                            Update Announcement
                                                                        </a>
                                                                        <a class="badge badge-danger" href="module_notices.php?delete=<?php echo $not->id; ?>&faculty=<?php echo $not->faculty_id; ?>">
                                                                            <i class="fas fa-trash"></i>
                                                                            Delete Announcement
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            <?php $cnt = $cnt + 1;
                                                            } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="tab-pane fade show " id="custom-content-below-pastpapers" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                    <br>
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
                                            <div class="">
                                                <div class="">
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

                                <!-- Class recordingings -->
                                <div class="col-md-6">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header text-center">
                                            <h3 class="card-title">Class Recordings</h3>
                                        </div>
                                        <div class="card-body box-profile">
                                            <div class="">
                                                <div class="">
                                                    <h2 class="text-right">
                                                        <a class="btn btn-outline-success" href="add_class_recording.php?faculty=<?php echo $row->id; ?>">
                                                            <i class="fas fa-plus"></i>
                                                            <i class="fas fa-file-video"></i>
                                                            Upload Class Recoding
                                                        </a>
                                                    </h2>
                                                </div>
                                                <div class="card-body">
                                                    <table id="admins" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Lecturer </th>
                                                                <th>Date Uploaded</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_ClassRecordings` WHERE faculty_id = '$row->id'  ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            $cnt = 1;
                                                            while ($cr = $res->fetch_object()) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $cnt; ?></td>
                                                                    <td><?php echo $cr->class_name; ?></td>
                                                                    <td><?php echo $cr->lecturer_name; ?></td>
                                                                    <td><?php echo date('d M Y', strtotime($cr->created_at)); ?></td>
                                                                    <td>
                                                                        <a class="badge badge-success" href="view_class_recording.php?watch=<?php echo $cr->id; ?>&faculty=<?php echo $row->id; ?>">
                                                                            <i class="fas fa-play"></i>
                                                                            Watch Recording
                                                                        </a>
                                                                    </td>
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


                                <!-- Lecturer Assigned Module -->
                                <div class="col-md-6">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header text-center">
                                            <h3 class="card-title">Lecturer Assigned Module</h3>
                                        </div>
                                        <div class="card-body box-profile">
                                            <?php
                                            $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns` WHERE module_code = '$mod->code'  ";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while ($ass = $res->fetch_object()) {
                                                /* 
                                                    Lec dETAILS
                                                */
                                                $lec = $ass->lec_id;
                                                $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE id = '$lec'  ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                $cnt = 1;
                                                while ($lecturer = $res->fetch_object()) {
                                                    //Get Default Profile Picture
                                                    if ($lecturer->profile_pic == '') {
                                                        $dpic = "<img class='profile-user-img img-fluid img-circle' src='dist/img/logo.jpeg' alt='User profile picture'>";
                                                    } else {
                                                        $dpic = "<img class='profile-user-img img-fluid img-circle' src='dist/img/lecturers/$lecturer->profile_pic' alt='User profile picture'>";
                                                    }
                                            ?>
                                                    <div class="">
                                                        <div class="">
                                                            <div class="text-center">
                                                                <?php echo $dpic; ?>
                                                            </div>

                                                            <h3 class="profile-username text-center"><?php echo $lecturer->name; ?></h3>

                                                            <p class="text-muted text-center"><?php echo $lecturer->number; ?></p>

                                                            <ul class="list-group list-group-unbordered mb-3">
                                                                <li class="list-group-item">
                                                                    <b>Email: </b> <a class="float-right"><?php echo $lecturer->email; ?></a>
                                                                </li>
                                                                <li class="list-group-item">
                                                                    <b>ID / Passport: </b> <a class="float-right"><?php echo $lecturer->idno; ?></a>
                                                                </li>
                                                                <li class="list-group-item">
                                                                    <b>Phone: </b> <a class="float-right"><?php echo $lecturer->phone; ?></a>
                                                                </li>
                                                                <li class="list-group-item">
                                                                    <b>Address</b> <a class="float-right"><?php echo $lecturer->adr; ?></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                            <?php
                                                }
                                            } ?>
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
                                            <div class="card-body">
                                                <h2 class="text-right">
                                                    <a class="btn btn-outline-success" href="add_student_groups.php?faculty=<?php echo $row->id; ?>&module_id=<?php echo $mod->id; ?>">
                                                        Create New Group
                                                    </a>
                                                    <a class="btn btn-outline-primary" href="student_group_assignments.php?faculty=<?php echo $row->id; ?>">
                                                        Group Assignments
                                                    </a>
                                                    <a class="btn btn-outline-secondary" href="student_group_notices.php?faculty=<?php echo $row->id; ?>">
                                                        Group Notices
                                                    </a>
                                                </h2>
                                            </div>
                                            <div class="card-body">
                                                <table id="studentGroups" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Code</th>
                                                            <th>Name</th>
                                                            <th>Created At</th>
                                                            <th>Manage</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $ret = "SELECT * FROM `ezanaLMS_Groups` WHERE faculty_id = '$row->id' AND module_id = '$mod->id'  ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        $cnt = 1;
                                                        while ($g = $res->fetch_object()) {
                                                        ?>

                                                            <tr class="table-row" data-href="view_student_group.php?&name=<?php echo $g->name; ?>&code=<?php echo $g->code; ?>&view=<?php echo $g->id; ?>&faculty=<?php echo $f->id; ?>">
                                                                <td><?php echo $cnt; ?></td>
                                                                <td><?php echo $g->code; ?></td>
                                                                <td><?php echo $g->name; ?></td>
                                                                <td><?php echo $g->created_at; ?></td>
                                                                <td>
                                                                    <a class="badge badge-primary" href="update_group.php?update=<?php echo $g->id; ?>&faculty=<?php echo $row->id; ?>">
                                                                        <i class="fas fa-edit"></i>
                                                                        Update
                                                                    </a>
                                                                    <a class="badge badge-danger" href="student_groups.php?delete=<?php echo $g->id; ?>&faculty=<?php echo $row->id; ?>">
                                                                        <i class="fas fa-trash"></i>
                                                                        Delete
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php $cnt = $cnt + 1;
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Students Enrolled -->
                                <div class="col-md-12">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header text-center">
                                            <h3 class="card-title">Students Enrolled On <?php echo $mod->name; ?></h3>
                                        </div>
                                        <div class="card-body box-profile">
                                            <h2 class="text-right">
                                                <a class="btn btn-outline-success" href="add_student_enrollment.php?faculty=<?php echo $row->id; ?>">
                                                    <i class="fas fa-user-plus"></i>
                                                    Add Enrollment
                                                </a>
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <table id="export-dt" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Admission</th>
                                                        <th>Name</th>
                                                        <th>Course</th>
                                                        <th>Academic Yr</th>
                                                        <th>Sem Enrolled</th>
                                                        <th>Sem Start</th>
                                                        <th>Sem End </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_Enrollments`  WHERE faculty_id = '$row->id' AND module_code ='$mod->code' ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    $cnt = 1;
                                                    while ($en = $res->fetch_object()) {
                                                    ?>

                                                        <tr>
                                                            <td><?php echo $cnt; ?></td>
                                                            <td><?php echo $en->student_adm; ?></td>
                                                            <td><?php echo $en->student_name; ?></td>
                                                            <td><?php echo $en->course_name; ?></td>
                                                            <td><?php echo $en->academic_year_enrolled; ?></td>
                                                            <td><?php echo $en->semester_enrolled; ?></td>
                                                            <td><?php echo date('d M Y', strtotime($en->semester_start)); ?></td>
                                                            <td><?php echo date('d M Y', strtotime($en->semester_end)); ?></td>
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
                    </div>
                </div>
        <?php require_once('partials/_footer.php');
            }
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>