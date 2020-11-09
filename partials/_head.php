<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Ezana LMS</title>
    <meta name="title" content="Ezana LMS">
    <meta name="description" content="Ezana Learning Management System">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://ezana.org">
    <meta property="og:title" content="Ezana LMS">
    <meta property="og:description" content="Ezana Learning Management System">
    <meta property="og:image" content="dist/img/logo.jpeg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://ezana.org">
    <meta property="twitter:title" content="Ezana LMS">
    <meta property="twitter:description" content="Ezana Learning Management System">
    <meta property="twitter:image" content="dist/img/logo.jpeg">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="dist/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="dist/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="dist/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="dist/img/favicons/site.webmanifest">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Data Tables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- CK Editor CDN -->
    <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- Swal Js -->
    <script src="dist/js/swal.js"></script>
    <!--Inject SWAL-->
    <?php if (isset($success)) { ?>
        <script>
            setTimeout(function() {
                    /* Success */
                    swal("Success", "<?php echo $success; ?>", "success");
                },
                100);
        </script>

    <?php } ?>

    <?php if (isset($err)) { ?>
        <script>
            setTimeout(function() {
                    /* Error */
                    swal("Failed", "<?php echo $err; ?>", "error");
                },
                100);
        </script>

    <?php } ?>
    <?php if (isset($info)) { ?>
        <script>
            setTimeout(function() {
                    /* Info */

                    swal("Success", "<?php echo $info; ?>", "warning");
                },
                100);
        </script>
    <?php } ?>
</head>