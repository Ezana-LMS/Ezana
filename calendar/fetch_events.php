<?php
    require_once "configs/config.php";

    $json = array();
    $sqlQuery = "SELECT * FROM ezanaLMS_Events ORDER BY id";

    $result = mysqli_query($mysqli, $sqlQuery);
    $eventArray = array();
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($eventArray, $row);
    }
    mysqli_free_result($result);

    mysqli_close($mysqli);
    echo json_encode($eventArray);
