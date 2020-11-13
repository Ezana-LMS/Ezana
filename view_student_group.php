<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
//Remove Member
if (isset($_GET['remove'])) {
    $view = $_GET['view'];
    $faculty = $_GET['faculty'];
    $remove = $_GET['remove'];
    $name = $_GET['name'];
    $code = $_GET['code'];
    $adn = "DELETE FROM ezanaLMS_StudentsGroups WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $remove);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=view_student_group.php?view=$view&faculty=$faculty&code=$code&name=$name");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

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
            $view = $_GET['view'];
            $faculty = $_GET['faculty'];


            $query = "INSERT INTO ezanaLMS_StudentsGroups (id, faculty_id, name, code, student_admn, student_name) VALUES(?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssss', $id, $faculty, $name, $code, $student_admn, $student_name);
            $stmt->execute();
            if ($stmt) {
                $success = "Student Added To group" && header("refresh:1; url=view_student_group.php?view=$view&faculty=$faculty&code=$code&name=$name");
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
            $view = $_GET['view'];
            $ret = "SELECT * FROM `ezanaLMS_Groups` WHERE id ='$view'  ";
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
                                    <h1 class="m-0 text-dark">Student Groups</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $f->id; ?>"><?php echo $f->name; ?></a></li>
                                        <li class="breadcrumb-item"><a href="student_groups.php?faculty=<?php echo $f->id; ?>">Student Groups</a></li>
                                        <li class="breadcrumb-item active"> View Members </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content">
                        <div class="container">
                            <section class="content">
                                <div class="container-fluid">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <?php echo $g->code; ?> - <?php echo $g->name; ?> Created On <span class="text-success"> <?php echo $g->created_at; ?></span> And Updated On <span class="text-warning"><?php echo $g->updated_at; ?></span>
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Group Details</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-content-below-enrollment-tab" data-toggle="pill" href="#custom-content-below-members" role="tab" aria-controls="custom-content-below-members" aria-selected="false">Group Members</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-content-below-enrollment-tab" data-toggle="pill" href="#custom-content-below-add_member" role="tab" aria-controls="custom-content-below-notices" aria-selected="false">Add Members</a>
                                                </li>
                                                <!-- <li class="nav-item">
                                        <a class="nav-link" id="custom-content-below-enrollment-tab" data-toggle="pill" href="#custom-content-below-projects" role="tab" aria-controls="custom-content-below-projects" aria-selected="false">Group Projects</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-content-below-enrollment-tab" data-toggle="pill" href="#custom-content-below-assignments" role="tab" aria-controls="custom-content-below-assignments" aria-selected="false">Group Assignments</a>
                                    </li> -->
                                            </ul>
                                            <div class="tab-content" id="custom-content-below-tabContent">
                                                <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                    <br>
                                                    <?php echo $g->details; ?>
                                                </div>
                                                <div class="tab-pane fade" id="custom-content-below-members" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                                    <br>
                                                    <table id="example1" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Student Admission No</th>
                                                                <th>Student Name</th>
                                                                <th>Date Added</th>
                                                                <th>Manage Members</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $GroupCode = $g->code;
                                                            $ret = "SELECT * FROM `ezanaLMS_StudentsGroups` WHERE code = '$GroupCode' AND faculty_id = '$faculty'  ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            $cnt = 1;
                                                            while ($stdGroup = $res->fetch_object()) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $cnt; ?></td>
                                                                    <td><?php echo $stdGroup->student_admn; ?></td>
                                                                    <td><?php echo $stdGroup->student_name; ?></td>
                                                                    <td><?php echo date('d M Y g:i', strtotime($stdGroup->created_at)); ?></td>
                                                                    <td>
                                                                        <a class="badge badge-danger" href="view_student_group.php?remove=<?php echo $stdGroup->id; ?>&view=<?php echo $g->id; ?>&faculty=<?php echo $f->id; ?>">
                                                                            <i class="fas fa-user-times"></i>
                                                                            Remove Member
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            <?php $cnt = $cnt + 1;
                                                            } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="tab-pane fade" id="custom-content-below-add_member" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                                    <br>
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
                                                                        <?php
                                                                        } ?>
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