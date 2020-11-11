<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_reading_materials'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['module_name']) && !empty($_POST['module_name'])) {
        $module_name = mysqli_real_escape_string($mysqli, trim($_POST['module_name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (isset($_POST['module_code']) && !empty($_POST['module_code'])) {
        $module_code = mysqli_real_escape_string($mysqli, trim($_POST['module_code']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $module_name  = $_POST['module_name'];
        $module_code = $_POST['module_code'];
        $readingMaterials = $_FILES['readingMaterials']['name'];
        move_uploaded_file($_FILES["readingMaterials"]["tmp_name"], "EzanaLMSData/Reading_Materials/" . $_FILES["readingMaterials"]["name"]);
        $external_link = $_POST['external_link'];
        $created_at = date('d M Y');
        $faculty = $_GET['faculty'];

        $query = "INSERT INTO ezanaLMS_ModuleRecommended (id, faculty_id, module_name, module_code, readingMaterials, created_at, external_link) VALUES(?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssss', $id, $facult, $module_name, $module_code, $readingMaterials, $created_at, $external_link);
        $stmt->execute();
        if ($stmt) {
            $success = "Reading Materials Shared" && header("refresh:1; url=add_module_reading_materials.php?faculty=$faculty");
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
        $faculty = $_GET['faculty'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$faculty' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {
            require_once('partials/_faculty_nav.php'); ?>
            <!-- /.navbar -->
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">Add Module Reading Materials</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculties.php">Faculties</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->id; ?>"> <?php echo $row->name; ?></a></li>
                                    <li class="breadcrumb-item"><a href="modules.php?faculty=<?php echo $row->id; ?>">Modules</a></li>
                                    <li class="breadcrumb-item"><a href="module_reading_materials.php?faculty=<?php echo $row->id; ?>">Reading Materials</a></li>
                                    <li class="breadcrumb-item active ">Add</li>
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
                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                        <select class='form-control basic' id="ModuleName" onchange="getModuleDetails(this.value);" name="module_name">
                                                            <option selected>Select Module Name </option>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE faculty_id = '$row->id' ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($mod = $res->fetch_object()) {
                                                            ?>
                                                                <option><?php echo $mod->name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="">Module Code</label>
                                                        <input type="text" id="ModuleCode" readonly required name="module_code" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputPassword1">Hyperlink</label>
                                                        <input type="text" name="external_link" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputFile">Reading Materials (PDF, DOCX, PPTX)</label>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input required name="readingMaterials" type="file" class="custom-file-input" id="exampleInputFile">
                                                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" name="add_reading_materials" class="btn btn-primary">Add Reading Materials</button>
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