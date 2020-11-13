<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_group'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Group Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Group Name Cannot Be Empty";
    }
    /* 
    if (isset($_POST['student_admn']) && !empty($_POST['student_admn'])) {
        $student_admn = mysqli_real_escape_string($mysqli, trim($_POST['student_admn']));
    } else {
        $error = 1;
        $err = "Student Admission Number Cannot Be Empty";
    }
    if (isset($_POST['student_name']) && !empty($_POST['student_name'])) {
        $student_admn = mysqli_real_escape_string($mysqli, trim($_POST['student_name']));
    } else {
        $error = 1;
        $err = "Student Name Number Cannot Be Empty";
    } */

    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Groups WHERE  code='$code'   ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Group With This Code Already Exists";
            } /* elseif (($code  && $student_admn) == ($row['code'] && $row['student_admn'])) {
                $err = "Student Already Added To Group";
            } */
        } else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $created_at = date('d M Y');
            $details = $_POST['details'];
            $faculty = $_GET['faculty'];


            $query = "INSERT INTO ezanaLMS_Groups (id, faculty, name, code, created_at, details) VALUES(?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssss', $id, $faculty, $name, $code, $created_at, $details);
            $stmt->execute();
            if ($stmt) {
                $success = "Student Group  Added" && header("refresh:1; url=add_student_groups.php?faculty=$faculty");
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}
require_once('partials/_head.php');
