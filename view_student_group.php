<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
//Remove Member
if (isset($_GET['remove'])) {
    $view = $_GET['view'];
    $faculty = $_GET['faculty'];
    $remove = $_GET['remove'];
    $adn = "DELETE FROM ezanaLMS_StudentsGroups WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $remove);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=view_student_group.php?view=$view&faculty=$faculty");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
require_once('partials/_head.php');
?>