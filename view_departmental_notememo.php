<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
require_once('partials/_head.php');
?>

<body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('partials/_faculty_nav.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_DepartmentalMemos` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($memo = $res->fetch_object()) {

            $faculty = $_GET['faculty'];
            $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id ='$faculty' ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($row = $res->fetch_object()) {
        ?>
                <!-- /.navbar -->
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark"><?php echo $memo->department_name; ?></h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="faculties.php">Faculties</a></li>
                                        <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></li>
                                        <li class="breadcrumb-item"><a href="departmental_notememos.php?faculty=<?php echo $row->id; ?>">Notices & Memos</a></li>
                                        <li class="breadcrumb-item active">View</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="content">
                        <div class="container">
                        <div class="col-md-12">
    <div class="card">
        <div class="card-header p-2">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#notices" data-toggle="tab">Notices</a></li>
            </ul>
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link " href="#memos" data-toggle="tab">Memos</a></li>
            </ul>
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link " href="#settings" data-toggle="tab">Department Settings</a></li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="active tab-pane" id="settings">
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
                        <div class="card-footer text-right">
                            <button type="submit" name="update_dept" class="btn btn-primary">Update Department</button>
                        </div>
                    </form>
                </div>
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