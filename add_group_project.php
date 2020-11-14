<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_group_project'])) {

    $id = $_POST['id'];
    $group_code = $_GET['group_code'];
    $group_name  = $_GET['group_name'];
    $type = $_GET['type'];
    $details = $_POST['details'];
    $view = $_GET['view'];
    $faculty = $_GET['faculty'];
    $code = $_GET['code'];
    $name = $_GET['name'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "EzanaLMSData/Group_Projects/" . $_FILES["attachments"]["name"]);
    $created_at = date('d M Y g:i');
    $submitted_on = $_POST['submitted_on'];
    $query = "INSERT INTO ezanaLMS_GroupsAssignments (id, faculty_id, group_code, group_name, attachments, type, details, created_at, submitted_on) VALUES(?,?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssssss', $id, $faculty, $group_code, $group_name, $attachments,  $type, $details, $created_at, $submitted_on);
    $stmt->execute();
    if ($stmt) {
        $success = "Group Assignment / Project Submitted" && header("refresh:1; url=add_group_project.php?&name=$name&code=$code&view=$view&faculty=$view&group_code=$code&group_name=$name&type=Project");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

require_once('partials/_head.php');
?>