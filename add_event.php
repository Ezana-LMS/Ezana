<?php
    require_once "configs/config.php";

$title = isset($_POST['title']) ? $_POST['title'] : "";
$start = isset($_POST['start']) ? $_POST['start'] : "";
$end = isset($_POST['end']) ? $_POST['end'] : "";

$sqlInsert = "INSERT INTO ezanaLMS_Events (title, start, end) VALUES ('".$title."','".$start."','".$end ."')";

$result = mysqli_query($mysqli, $sqlInsert);

if (! $result) {
    $result = mysqli_error($mysqli);
}
