<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update_notice'])) {
    $update = $_GET['update'];
    $announcement = $_POST['announcement'];
    $created_by = $_POST['created_by'];
    $updated_at = date('d M Y');
    $faculty = $_GET['faculty'];

    $query = "UPDATE  ezanaLMS_GroupsAnnouncements SET announcement =?, created_by =?, updated_at=? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssss', $announcement, $created_by, $updated_at, $update);
    $stmt->execute();
    if ($stmt) {
        $success = "Updated" && header("refresh:1; url=view_group_announcement.php?view=$update&faculty=$faculty");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

require_once('partials/_head.php');
?>