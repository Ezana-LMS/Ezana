<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <?php
    /* Persisit System Settings On Auth */
    $ret = "SELECT * FROM `ezanaLMS_Settings` ";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute(); //ok
    $res = $stmt->get_result();
    while ($sys = $res->fetch_object()) {
    ?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $sys->sysname; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- SEO META TAGS -->
        <meta name="title" content="Ezana LMS">
        <meta name="description" content="Ezana Learning Management System">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://ezana.org">
        <meta property="og:title" content="<?php echo $sys->sysname; ?>">
        <meta property="og:description" content="Powered By Ezana Learning Management System">
        <meta property="og:image" content="../Data/SystemLogo/<?php echo $sys->logo; ?>">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="https://ezana.org">
        <meta property="twitter:title" content="<?php echo $sys->sysname; ?>">
        <meta property="twitter:description" content="Powered By Ezana Learning Management System">
        <meta property="twitter:image" content="../Data/SystemLogo/<?php echo $sys->logo; ?>">

        <!-- Favicons -->
        <link rel="icon" type="image/png" sizes="16x16" href="../Data/SystemLogo/<?php echo $sys->logo; ?>">

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="../assets/plugins/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../assets/plugins/animate/animate.css">
        <link rel="stylesheet" type="text/css" href="../assets/plugins/css-hamburgers/hamburgers.min.css">
        <link rel="stylesheet" type="text/css" href="../assets/plugins/animsition/css/animsition.min.css">
        <link rel="stylesheet" type="text/css" href="../assets/css/util.css">
        <link rel="stylesheet" type="text/css" href="../assets/css/main.css">
        <!-- IziAlerts Css -->
        <link rel="stylesheet" type="text/css" href="../assets/plugins/iziToast/iziToast.min.css">
    <?php
    } ?>
</head>