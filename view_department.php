<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update_dept'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Department Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Department Name Cannot Be Empty";
    }
    if (!$error) {
        $department = $_GET['department'];
        $name = $_POST['name'];
        $code = $_POST['code'];
        $details = $_POST['details'];
        $hod = $_POST['hod'];

        $query = "UPDATE ezanaLMS_Departments SET code =?, name =?,  details =?, hod =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssss', $code, $name, $details, $hod, $department);
        $stmt->execute();
        if ($stmt) {
            $success = "Faculty Department Updated" && header("refresh:1; url=view_department.php?department=$department");
        } else {
            //inject alert that profile update task failed
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
        require_once('partials/_dep_nav.php');
        $department = $_GET['department'];
        $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE id = '$department' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {
            require_once('partials/_departmental_sidebar.php');
        ?>
            <!-- /.navbar -->
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"> <?php echo $row->name; ?> Dashboard </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculties.php">Faculties</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->faculty_id; ?>"><?php echo $row->name; ?></a></li>
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
                                                <b>Name: </b> <a class="float-right"><?php echo $row->name; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Code / Number : </b> <a class="float-right"><?php echo $row->code; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>HOD</b> <a class="float-right"><?php echo $row->hod; ?></a>
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
                                                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"><?php echo $row->name; ?> Settings</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="custom-content-below-tabContent">
                                            <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                <br>
                                                <?php echo $row->details; ?>
                                            </div>
                                            <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                                <br>
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group col-md-4">
                                                                <label for="">Department Name</label>
                                                                <input type="text" required name="name" value="<?php echo $row->name; ?>" class="form-control" id="exampleInputEmail1">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Department Number / Code</label>
                                                                <input type="text" required name="code" value="<?php echo $row->code; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Department HOD</label>
                                                                <input type="text" required value="<?php echo $row->hod; ?>" name="hod" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputPassword1">Department Details</label>
                                                                <textarea name="details" id="textarea" rows="10" class="form-control"><?php echo $row->details; ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class=" text-right">
                                                        <button type="submit" name="update_dept" class="btn btn-primary">Update Department</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Courses -->
                            <div class="col-md-6">
                                <div class="card card-primary card-outline">
                                    <div class="card-header text-center">
                                        <h3 class="card-title">Courses Under <?php echo $row->name; ?> Department</h3>
                                    </div>
                                    <div class="card-body box-profile">
                                        <div class="">
                                            <div class="card-header">
                                                <h2 class="text-right">
                                                    <a class="btn btn-outline-primary" href="add_course.php?faculty=<?php echo $row->faculty_id; ?>">
                                                        Register New Course
                                                    </a>
                                                </h2>
                                            </div>
                                            <div class="card-body">
                                                <table id="example1" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Course Code</th>
                                                            <th>Course Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE department_id = '$row->id'  ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        $cnt = 1;
                                                        while ($course = $res->fetch_object()) {
                                                        ?>

                                                            <tr class="table-row" data-href="view_course.php?department=<?php echo $course->department_id; ?>&view=<?php echo $course->id; ?>&faculty=<?php echo $course->faculty_id; ?>">
                                                                <td><?php echo $cnt; ?></td>
                                                                <td><?php echo $course->code; ?></td>
                                                                <td><?php echo $course->name; ?></td>
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

                            <div class="col-md-6">
                                <!-- Departmental Notices -->
                                <div class="card card-primary card-outline">
                                    <div class="card-header text-center">
                                        <h3 class="card-title">Notices / Memos Under <?php echo $row->name; ?> Department</h3>
                                    </div>
                                    <div class="card-body box-profile">
                                        <div class="">
                                            <div class="card-header">
                                                <h2 class="text-right">
                                                    <a class="btn btn-outline-primary" href="add_departmental_notememos.php?faculty=<?php echo $row->faculty_id; ?>">
                                                        Add New Departmental Memos & Notices
                                                    </a>
                                                </h2>
                                            </div>
                                            <div class="card-body">
                                                <table id="enrollment" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Date Posted</th>
                                                            <th>Type</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $ret = "SELECT * FROM `ezanaLMS_DepartmentalMemos` WHERE department_id = '$row->id'  ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        $cnt = 1;
                                                        while ($memo = $res->fetch_object()) {
                                                        ?>
                                                            <tr class="table-row" data-href="view_departmental_notememo.php?view=<?php echo $memo->id; ?>&faculty=<?php echo $memo->faculty_id; ?>">
                                                                <td><?php echo $cnt; ?></td>
                                                                <td><?php echo $memo->created_at; ?></td>
                                                                <td>Departmental <?php echo $memo->type; ?></td>
                                                                <!-- <td>
                                                                    <a class="badge badge-primary" href="update_departmental_memo.php?update=<?php echo $memo->id; ?>&faculty=<?php echo $f->id; ?>">
                                                                        <i class="fas fa-edit"></i>
                                                                        Update
                                                                    </a>
                                                                    <a class="badge badge-danger" href="departmental_memos.php?delete=<?php echo $memo->id; ?>&faculty=<?php echo $f->id; ?>">
                                                                        <i class="fas fa-trash"></i>
                                                                        Delete
                                                                    </a>
                                                                </td> -->
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
                </div>
            </div>
        <?php require_once('partials/_footer.php');
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>