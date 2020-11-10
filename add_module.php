<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_module'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Module Code Cannot Be Empty";
    }
    if (isset($_POST['course_name']) && !empty($_POST['course_name'])) {
        $course_name = mysqli_real_escape_string($mysqli, trim($_POST['course_name']));
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
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Modules WHERE  code='$code' || name ='$name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Module With This Code Already Exists";
            } else {
                $err = "Module  Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $details = $_POST['details'];
            $course_name = $_POST['course_name'];
            $course_id = $_POST['course_id'];
            $course_duration = $_POST['course_duration'];
            $weight_percentage = $_POST['weight_percentage'];
            $lectures_number = $_POST['lectures_number'];
            $created_at = date('d M Y');
            $faculty = $_GET['faculty'];
            $query = "INSERT INTO ezanaLMS_Modules (id, name, code, details, course_name, course_id, faculty_id, course_duration, weight_percentage, lectures_number, created_at) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('sssssssssss', $id, $name, $code, $details, $course_name, $course_id, $faculty_id, $course_duration, $weight_percentage, $lectures_number, $created_at);
            $stmt->execute();
            if ($stmt) {
                $success = "Module Created" && header("refresh:1; url=add_module.php?faculty=$faculty");
            } else {
                $info = "Please Try Again Or Try Later";
            }
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
                                <h1 class="m-0 text-dark">Add Module</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Faculties</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $faculty; ?>"><?php echo $f->name; ?></a></li>
                                    <li class="breadcrumb-item active">Add Module</li>
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
                                                    <div class="form-group col-md-6">
                                                        <label for="">Module Name</label>
                                                        <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="">Module Number / Code</label>
                                                        <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label for="">Course Name</label>
                                                        <select class='form-control basic' id="Cname" onchange="getCourseDetails(this.value);" name="course_name">
                                                            <option selected>Select Course Name</option>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE faculty_id = '$f->id'  ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($course = $res->fetch_object()) {
                                                            ?>
                                                                <option><?php echo $course->name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="">Course Code</label>
                                                        <input type="text" id="CourseCode" readonly required name="" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="">Course ID</label>
                                                        <input type="text" readonly id="CourseID" required name="course_id" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label for="">Teaching Duration</label>
                                                        <input type="text" required name="course_duration" class="form-control" id="exampleInputEmail1">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="">Number Of Lectures Per Week</label>
                                                        <input type="text" required name="lectures_number" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="">Module CAT / End Exam Weight Percentage</label>
                                                        <input type="text" required name="weight_percentage" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="exampleInputPassword1">Module Details</label>
                                                        <textarea required id="textarea" name="details" rows="10" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" name="add_module" class="btn btn-primary">Add Module</button>
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