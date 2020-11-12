<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

//Delete
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $faculty = $_GET['faculty'];
    $code = $_GET['code'];
    $status = 0;
    $adn = "DELETE FROM ezanaLMS_ModuleAssigns WHERE id=?";
    $up = "UPDATE ezanaLMS_Modules SET ass_status =? WHERE code =? ";
    $stmt = $mysqli->prepare($adn);
    $upst = $mysqli->prepare($up);
    $stmt->bind_param('s', $delete);
    $upst->bind_param('is', $status, $code);
    $stmt->execute();
    $upst->execute();
    $upst->close();
    $stmt->close();
    if ($stmt && $upst) {
        $success = "Deleted" && header("refresh:1; url=assign_lecturer_module.php?faculty=$faculty");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
require_once('partials/_head.php');
