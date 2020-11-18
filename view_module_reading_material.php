<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update_reading_materials'])) {
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
        $view = $_GET['view'];
        $module_name  = $_POST['module_name'];
        $module_code = $_POST['module_code'];
        $readingMaterials = $_FILES['readingMaterials']['name'];
        move_uploaded_file($_FILES["readingMaterials"]["tmp_name"], "EzanaLMSData/Reading_Materials/" . $_FILES["readingMaterials"]["name"]);
        $external_link = $_POST['external_link'];
        $created_at = date('d M Y');
        $faculty = $_GET['faculty'];

        $query = "UPDATE ezanaLMS_ModuleRecommended SET module_name =?, module_code =?, readingMaterials =?, created_at =?, external_link =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssss', $module_name, $module_code, $readingMaterials, $created_at, $external_link, $view);
        $stmt->execute();
        if ($stmt) {
            $success = "Reading Materials Updated" && header("refresh:1; url=view_module_reading_material.php?view=$view&faculty=$faculty");
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
        $ret = "SELECT * FROM `ezanaLMS_ModuleRecommended` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($rm = $res->fetch_object()) {


            $faculty = $_GET['faculty'];
            $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id ='$faculty' ";
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
                                    <h1 class="m-0 text-dark"><?php echo $rm->module_name; ?> Reading Materials</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="faculties.php">Faculties</a></li>
                                        <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $f->id; ?>"> <?php echo $f->name; ?></a></li>
                                        <li class="breadcrumb-item"><a href="modules.php?faculty=<?php echo $f->id; ?>">Modules</a></li>
                                        <li class="breadcrumb-item"><a href="module_reading_materials.php?faculty=<?php echo $f->id; ?>">Reading Materials</a></li>
                                        <li class="breadcrumb-item active ">View</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="content">
                        <div class="container-fluid">
                            <section class="content">
                                <div class="container-fluid">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <?php echo $rm->module_name; ?> Reading Materials Posted On <?php echo $rm->created_at; ?>
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Reading Materials</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link " id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-links" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Reading Materials Extenal Link</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link " id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-update" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Update Reading Materials</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="custom-content-below-tabContent">
                                                <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                    <br>
                                                    <?php
                                                    if ($rm->readingMaterials != '') {
                                                        echo
                                                            "
                                                        <a target='_blank' href='EzanaLMSData/Reading_Materials/$rm->readingMaterials' class='btn btn-outline-primary'>
                                                            <i class='fas fa-download'></i>
                                                                Download Reading Materials
                                                        </a>
                                                        ";
                                                    } else {
                                                        echo
                                                            "
                                                        <a  class='btn btn-outline-danger'>
                                                            <i class='fas fa-times'></i>
                                                                Reading Materials Not Available
                                                        </a>
                                                        ";
                                                    }
                                                    ?>
                                                </div>
                                                <div class="tab-pane fade show " id="custom-content-below-links" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                    <br>
                                                    <?php
                                                    if ($rm->external_link != '') {
                                                        echo
                                                            "
                                                            <a target='_blank' href='$rm->external_link' class='btn btn-outline-success'>
                                                                <i class='fas fa-external-link-alt'></i>
                                                                Open Link
                                                            </a>
                                                            ";
                                                    } else {
                                                        echo
                                                            "
                                                            <a  class='btn btn-outline-danger'>
                                                                <i class='fas fa-times'></i>
                                                                    External Link Not Available
                                                            </a>                                             
                                                            ";
                                                    }
                                                    ?>
                                                </div>
                                                <div class="tab-pane fade show " id="custom-content-below-update" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                    <br>
                                                    <div class="card card-primary">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Fill All Required Fields</h3>
                                                        </div>
                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                            <div class="card-body">
                                                                <div class="row">

                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Module Name</label>
                                                                        <input type="text" id="ModuleCode" value="<?php echo $rm->module_name; ?>" readonly required name="module_name" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Module Code</label>
                                                                        <input type="text" id="ModuleCode" value="<?php echo $rm->module_code; ?>" readonly required name="module_code" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="exampleInputPassword1">Hyperlink</label>
                                                                        <input type="text" name="external_link" value="<?php echo $rm->external_link; ?>" class="form-control">
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
                                                            <div class="card-footer text-right">
                                                                <button type="submit" name="update_reading_materials" class="btn btn-primary">Update Reading Materials</button>
                                                            </div>
                                                        </form>
                                                    </div>
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