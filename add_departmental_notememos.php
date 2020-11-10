<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_memo'])) {
    //Error Handling
    $error = 0;
    
    if (isset($_POST['department_id']) && !empty($_POST['department_id'])) {
        $department_id = mysqli_real_escape_string($mysqli, trim($_POST['department_id']));
    } else {
        $error = 1;
        $err = "Department Name / ID  Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $department_id = $_POST['department_id'];
        $department_name  = $_POST['department_name'];
        $departmental_memo = $_POST['departmental_memo'];
        $attachments = $_FILES['attachments']['name'];
        move_uploaded_file($_FILES["attachments"]["tmp_name"], "EzanaLMSData/memos/" . $_FILES["attachments"]["name"]);
        $created_at = date('d M Y g:i');
        $type = $_POST['type'];
        $faculty = $_GET['faculty'];

        $query = "INSERT INTO ezanaLMS_DepartmentalMemos (id, department_id, department_name, departmental_memo, attachments, created_at, type, faculty_id) VALUES(?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssss', $id, $department_id, $department_name, $departmental_memo, $attachments, $created_at, $type, $faculty);
        $stmt->execute();
        if ($stmt) {
            $success = "Departmental NoteMemo Added" && header("refresh:1; url=add_departmental_notememos.php");
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
                                <h1 class="m-0 text-dark">Add Departmental Notices & Memo</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Faculties</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $faculty; ?>"><?php echo $f->name; ?></a></li>
                                    <li class="breadcrumb-item"><a href="departmental_notememos.php?faculty=<?php echo $faculty; ?>">Memos & Notices</a></li>
                                    <li class="breadcrumb-item active">Add</li>
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
                                                    <div class="form-group col-md-4">
                                                        <label for="">Department Name</label>
                                                        <select class='form-control basic' id="DepartmentName" onchange="getDepartmentDetails(this.value);" name="department_name">
                                                            <option selected>Select Department Name</option>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_Departments`  WHERE faculty_id = '$faculty' ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($dep = $res->fetch_object()) {
                                                            ?>
                                                                <option><?php echo $dep->name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="">Department ID</label>
                                                        <input type="text" id="DepartmentID" readonly required name="department_id" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="">Type</label>
                                                        <select class='form-control basic1' id="DepartmentName" onchange="getDepartmentDetails(this.value);" name="department_name">
                                                            <option selected>Select If Notice Or Memo</option>
                                                            <option>Notice</option>
                                                            <option>Memo</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="">Upload Departmental Memo (PDF Or Docx)</label>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input name="attachments" type="file" class="custom-file-input">
                                                                <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h2 class="text-center">Or </h2>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="exampleInputPassword1">Type Departmental Memo</label>
                                                        <textarea name="departmental_memo" id="textarea" rows="10" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" name="add_memo" class="btn btn-primary">Add Departmental Memo</button>
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