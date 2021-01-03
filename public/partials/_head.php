<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="public/dist/css/icons.css">
    <!-- Data Tables -->
    <link rel="stylesheet" href="public/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="public/dist/css/data-table.css">
    <!-- <link rel="stylesheet" type="text/css" href="plugins/datatable/datatables.css"> -->
    <link rel="stylesheet" type="text/css" href="public/plugins/datatable/custom_dt_html5.css">
    <!-- <link rel="stylesheet" type="text/css" href="plugins/datatable/dt-global_style.css">  -->
    <!-- CK Editor CDN -->
    <!-- <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script> -->
    <!-- Scroll Bars -->
    <link rel="stylesheet" href="public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Full Calendar Plug In -->
    <link rel="stylesheet" href="public/plugins/fullcalendar/main.min.css">
    <link rel="stylesheet" href=public/plugins/fullcalendar-daygrid/main.min.css"> <link rel="stylesheet" href="public/plugins/fullcalendar-timegrid/main.min.css">
    <link rel="stylesheet" href="public/plugins/fullcalendar-bootstrap/main.min.css">
    <!-- FlatPickr PlugIn CSS -->
    <link href="public/plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link href="public/plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="public/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="public/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!--load swal js -->
    <script src="public/dist/js/swal.js"></script>
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
    <!-- Table CSS -->
    <style>
        thead input {
            width: 100%;
        }
    </style>
</head>