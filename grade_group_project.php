<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_score'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['group_name']) && !empty($_POST['group_name'])) {
        $group_name = mysqli_real_escape_string($mysqli, trim($_POST['group_name']));
    } else {
        $error = 1;
        $err = "Group Name Cannot Be Empty";
    }
    if (isset($_POST['group_score']) && !empty($_POST['group_score'])) {
        $group_score = mysqli_real_escape_string($mysqli, trim($_POST['group_score']));
    } else {
        $error = 1;
        $err = "Group Score Cannot Be Empty";
    }
    if (isset($_GET['project_id']) && !empty($_GET['project_id'])) {
        $project_id = mysqli_real_escape_string($mysqli, trim($_GET['project_id']));
    } else {
        $error = 1;
        $err = "Project ID Cannot Be Empty";
    }
    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_GroupsAssignmentsGrades WHERE   project_id='$project_id' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($project_id == $row['project_id']) {
                $err =  "Group Work Already Graded";
            }
        } else {
            $id = $_POST['id'];
            $group_name = $_GET['group_name'];
            $group_code = $_GET['group_code'];
            $project_id = $_GET['project_id'];
            $group_score = $_POST['group_score'];
            $created_at = date('d M Y');
            $faculty = $_GET['faculty'];

            $query = "INSERT INTO ezanaLMS_GroupsAssignmentsGrades (id,faculty_id, group_name, group_code, project_id, group_score, created_at) VALUES(?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('sssssss', $id, $faculty, $group_name, $group_code, $project_id, $group_score, $created_at);
            $stmt->execute();
            if ($stmt) {
                $success = "Group Score Added";
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}
require_once('partials/_head.php');
?>