<?php
require_once "../configs/config.php";

$id = $_POST['id'];
$sqlDelete = "DELETE from ezanaLMS_Events WHERE id=" . $id;

mysqli_query($mysqli, $sqlDelete);
echo mysqli_affected_rows($mysqli);

mysqli_close($mysqli);
