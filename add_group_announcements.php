<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_notice'])) {
    $id = $_POST['id'];
    $group_code  = $_POST['group_code'];
    $group_name = $_POST['group_name'];
    $announcement = $_POST['announcement'];
    $created_by = $_POST['created_by'];
    $created_at = date('d M Y');
    $faculty = $_GET['faculty'];

    $query = "INSERT INTO ezanaLMS_GroupsAnnouncements (id, faculty_id, group_name, group_code, announcement, created_by, created_at) VALUES(?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssss', $id, $faculty, $group_name, $group_code, $announcement, $created_by, $created_at);
    $stmt->execute();
    if ($stmt) {
        $success = "Posted" && header("refresh:1; url=add_group_announcements.php?faculty=$faculty");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

require_once('partials/_head.php');
?>