<!DOCTYPE html>
<html class="no-js">

<head>
    <title>Ezana LMS </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="">
    <meta name="title" content="Ezana LMS">
    <meta name="description" content="Ezana Learning Management System">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://ezana.org">
    <meta property="og:title" content="Ezana LMS">
    <meta property="og:description" content="Ezana Learning Management System">
    <meta property="og:image" content="auth/images/logo.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://ezana.org">
    <meta property="twitter:title" content="Ezana LMS">
    <meta property="twitter:description" content="Ezana Learning Management System">
    <meta property="twitter:image" content="auth/images/logo.png">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="dashboard/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="dashboard/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="dashboard/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="dashboard/img/favicons/site.webmanifest">


    <!-- StyleSheets -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,800italic,400,600,800" type="text/css">
    <link rel="stylesheet" href="dashboard/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="dashboard/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="dashboard/js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.css" type="text/css" />
    <link rel="stylesheet" href="dashboard/js/plugins/icheck/skins/minimal/blue.css" type="text/css" />
    <link rel="stylesheet" href="dashboard/js/plugins/select2/select2.css" type="text/css" />
    <link rel="stylesheet" href="dashboard/js/plugins/fullcalendar/fullcalendar.css" type="text/css" />
    <link rel="stylesheet" href="dashboard/css/App.css" type="text/css" />
    <link rel="stylesheet" href="dashboard/css/custom.css" type="text/css" />
    <!-- Clickable Table Css -->
    <link rel="stylesheet" type="text/css" href="dist/css/clickable_table.css">

    <!-- CDNS -->

    <!-- CK Editor CDN -->
    <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
    <!-- Swal -->
    !-- SWAL ALERTS INJECTION-->
    <script src="dashboard/js/swal.js"></script>
    <!--Inject SWAL-->
    <?php if (isset($success)) { ?>
        <!--This code for injecting success alert-->
        <script>
            setTimeout(function() {
                    swal("Success", "<?php echo $success; ?>", "success");
                },
                100);
        </script>

    <?php } ?>

    <?php if (isset($err)) { ?>
        <!--This code for injecting error alert-->
        <script>
            setTimeout(function() {
                    swal("Failed", "<?php echo $err; ?>", "error");
                },
                100);
        </script>

    <?php } ?>
    <?php if (isset($info)) { ?>
        <!--This code for injecting info alert-->
        <script>
            setTimeout(function() {
                    swal("Success", "<?php echo $info; ?>", "warning");
                },
                100);
        </script>

    <?php } ?>



</head>