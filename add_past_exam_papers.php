<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_paper'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['course_name']) && !empty($_POST['course_name'])) {
        $course_name = mysqli_real_escape_string($mysqli, trim($_POST['course_name']));
    } else {
        $error = 1;
        $err = "Course Name Cannot Be Empty";
    }
    if (!$error) {
        $faculty = $_GET['faculty'];
        $module_name = $_POST['module_name'];
        $id = $_POST['id'];
        $course_name = $_POST['course_name'];
        $pastpaper_type = 'Past Paper';
        $created_at = date('d M Y h:m:s');
        $pastpaper = $_FILES['pastpaper']['name'];
        move_uploaded_file($_FILES["pastpaper"]["tmp_name"], "EzanaLMSData/PastPapers/" . $_FILES["pastpaper"]["name"]);

        $query = "INSERT INTO ezanaLMS_PastPapers (id, faculty_id, course_name, module_name,  pastpaper_type, created_at, pastpaper) VALUES(?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssss', $id, $faculty, $course_name, $module_name, $pastpaper_type, $created_at, $pastpaper);
        $stmt->execute();
        if ($stmt) {
            $success = "Past Paper Uploaded" && header("refresh:1; url=add_past_exam_papers.php?faculty=$faculty");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

require_once('partials/_head.php');