<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_lec'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['number']) && !empty($_POST['number'])) {
        $number = mysqli_real_escape_string($mysqli, trim($_POST['number']));
    } else {
        $error = 1;
        $err = "Lecturer Number Cannot Be Empty";
    }
    if (isset($_POST['idno']) && !empty($_POST['idno'])) {
        $idno = mysqli_real_escape_string($mysqli, trim($_POST['idno']));
    } else {
        $error = 1;
        $err = "National ID / Passport Number Cannot Be Empty";
    }
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = mysqli_real_escape_string($mysqli, trim($_POST['email']));
    } else {
        $error = 1;
        $err = "Email Cannot Be Empty";
    }
    if (isset($_POST['phone']) && !empty($_POST['phone'])) {
        $phone = mysqli_real_escape_string($mysqli, trim($_POST['phone']));
    } else {
        $error = 1;
        $err = "Phone Number Cannot Be Empty";
    }

    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Lecturers WHERE  email='$email' || phone ='$phone' || idno = '$idno' || number ='$number' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($email == $row['email']) {
                $err =  "Account With This Email Already Exists";
            } elseif ($phone == $row['phone']) {
                $err = "Account With That Phone Number Exists";
            } elseif ($idno == $row['idno']) {
                $err = "National ID Number  / Passport Number Already Exists";
            } else {
                $err = "Lecturer Number Already Exists";
            }
        } else {
            $faculty = $_GET['faculty'];
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $number = $_POST['number'];
            $idno  = $_POST['idno'];
            $adr = $_POST['adr'];
            $created_at = date('d M Y');
            $password = sha1(md5($_POST['password']));
            $profile_pic = $_FILES['profile_pic']['name'];
            move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "dist/img/lecturers/" . $_FILES["profile_pic"]["name"]);

            $query = "INSERT INTO ezanaLMS_Lecturers (id, faculty_id, name, email, phone, idno, adr, profile_pic, created_at, password, number) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('sssssssssss', $id, $faculty, $name, $email, $phone, $idno, $adr, $profile_pic, $created_at, $password, $number);
            $stmt->execute();
            if ($stmt) {
                $success = "Lecturer Added" && header("refresh:1; url=add_lecturer.php?faculty=$faculty");
            } else {
                //inject alert that profile update task failed
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}
require_once('partials/_head.php');
?>