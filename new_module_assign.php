<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['assign_module'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['module_code']) && !empty($_POST['module_code'])) {
        $module_code = mysqli_real_escape_string($mysqli, trim($_POST['module_code']));
    } else {
        $error = 1;
        $err = "Module Code Cannot Be Empty";
    }
    if (isset($_POST['module_name']) && !empty($_POST['module_name'])) {
        $module_name = mysqli_real_escape_string($mysqli, trim($_POST['module_name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (isset($_POST['lec_id']) && !empty($_POST['lec_id'])) {
        $lec_id = mysqli_real_escape_string($mysqli, trim($_POST['lec_id']));
    } else {
        $error = 1;
        $err = "Lec ID Cannot Be Empty";
    }
    if (isset($_POST['lec_name']) && !empty($_POST['lec_name'])) {
        $lec_name = mysqli_real_escape_string($mysqli, trim($_POST['lec_name']));
    } else {
        $error = 1;
        $err = "Lec Name Cannot Be Empty";
    }
    if (!$error) {

        $id = $_POST['id'];
        $module_code = $_POST['module_code'];
        $module_name = $_POST['module_name'];
        $lec_id = $_POST['lec_id'];
        $lec_name = $_POST['lec_name'];
        $created_at = date('d M Y');
        $faculty = $_GET['faculty'];

        //On Assign, Update Module Status to Assigned
        $ass_status = 1;
        
        $query = "INSERT INTO ezanaLMS_ModuleAssigns (id, faculty_id, module_code , module_name, lec_id, lec_name, created_at) VALUES(?,?,?,?,?,?,?)";
        $modUpdate = "UPDATE ezanaLMS_Modules SET ass_status =?  WHERE code = ?";
        $stmt = $mysqli->prepare($query);
        $modstmt = $mysqli->prepare($modUpdate);
        $rc = $stmt->bind_param('sssssss', $id, $faculty, $module_code, $module_name, $lec_id, $lec_name, $created_at);
        $rc = $modstmt->bind_param('is', $ass_status, $module_code);
        $stmt->execute();
        $modstmt->execute();
        if ($stmt && $modstmt) {
            $success = "Module Assignment Added" && header("refresh:1; url=new_module_assign.php?faculty=$faculty");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

require_once('partials/_head.php');
?>