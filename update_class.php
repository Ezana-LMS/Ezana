<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update_class'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['classdate']) && !empty($_POST['classdate'])) {
        $classdate = mysqli_real_escape_string($mysqli, trim($_POST['classdate']));
    } else {
        $error = 1;
        $err = "Date Cannot Be Empty";
    }
    if (isset($_POST['classtime']) && !empty($_POST['classtime'])) {
        $classtime = mysqli_real_escape_string($mysqli, trim($_POST['classtime']));
    } else {
        $error = 1;
        $err = "Time Cannot Be Empty";
    }
    if (isset($_POST['classlocation']) && !empty($_POST['classlocation'])) {
        $classlocation = mysqli_real_escape_string($mysqli, trim($_POST['classlocation']));
    } else {
        $error = 1;
        $err = "Lecture Hall Be Empty";
    }
    if (isset($_POST['classlecturer']) && !empty($_POST['classlecturer'])) {
        $classlecturer = mysqli_real_escape_string($mysqli, trim($_POST['classlecturer']));
    } else {
        $error = 1;
        $err = "Lecturer Name Be Empty";
    }



    if (!$error) {
        $update = $_GET['update'];
        $classdate = $_POST['classdate'];
        $classtime  = $_POST['classtime'];
        $classlocation = $_POST['classlocation'];
        $classlecturer = $_POST['classlecturer'];
        $classname  = $_POST['classname'];
        $classlink = $_POST['classlink'];
        $faculty = $_GET['faculty'];
        $query = "UPDATE ezanaLMS_TimeTable SET classdate =?, classtime =?, classlocation =?, classlecturer =?, classname =?, classlink =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssss', $classdate, $classtime, $classlocation, $classlecturer, $classname, $classlink, $update);
        $stmt->execute();
        if ($stmt) {
            $success = "Class Updated" && header("refresh:1; url=timetables.php?faculty=$faculty");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

require_once('partials/_head.php');
?>