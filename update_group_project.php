<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update_group_project'])) {

    $update = $_GET['update'];
    $details = $_POST['details'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "dist/Group_Projects/" . $_FILES["attachments"]["name"]);
    $updated_at = date('d M Y g:i');
    $submitted_on = $_POST['submitted_on'];
    $faculty = $_GET['faculty'];
    $code = $_GET['code'];
    $view = $_GET['view'];
    $name = $_GET['name'];

    $query = "UPDATE ezanaLMS_GroupsAssignments SET attachments =?, details =?, updated_at =?, submitted_on =? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssss', $attachments, $details, $updated_at, $submitted_on, $update);
    $stmt->execute();
    if ($stmt) {
        $success = "Group Assignment / Project Updated" && header("refresh:1; url=view_student_group.php?view=$view&faculty=$faculty&code=$code&name=$name");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

require_once('partials/_head.php');
?>
