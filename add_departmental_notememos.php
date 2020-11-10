<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_memo'])) {

    $id = $_POST['id'];
    $department_id = $_POST['department_id'];
    $department_name  = $_POST['department_name'];
    $departmental_memo = $_POST['departmental_memo'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "dist/memos/" . $_FILES["attachments"]["name"]);
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
                                                        <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="">Department Number / Code</label>
                                                        <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="">Department HOD</label>
                                                        <input type="text" required name="hod" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="exampleInputPassword1">Department Details</label>
                                                        <textarea name="details" id="textarea" rows="10" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" name="add_dept" class="btn btn-primary">Add Department</button>
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