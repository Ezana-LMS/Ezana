<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ezana Learning Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- SEO META TAGS -->
    <meta name="title" content="Ezana LMS">
    <meta name="description" content="Ezana Learning Management System">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://ezana.org">
    <meta property="og:title" content="Ezana LMS">
    <meta property="og:description" content="Ezana Learning Management System">
    <meta property="og:image" content="public/dist/img/logo.jpeg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://ezana.org">
    <meta property="twitter:title" content="Ezana LMS">
    <meta property="twitter:description" content="Ezana Learning Management System">
    <meta property="twitter:image" content="public/dist/img/logo.jpeg">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="public/dist/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="public/dist/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="public/dist/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="public/dist/img/favicons/site.webmanifest">
    <link rel="stylesheet" type="text/css" href="public/dist/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="public/dist/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="public/dist/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="public/dist/vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="public/dist/vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="public/dist/vendor/animsition/css/animsition.min.css">
    <link rel="stylesheet" type="text/css" href="public/dist/vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="public/dist/vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="public/dist/css/util.css">
    <link rel="stylesheet" type="text/css" href="public/dist/css/main.css">
    <script src="public/dist/js/swal.js"></script>
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="public/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
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