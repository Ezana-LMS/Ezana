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
