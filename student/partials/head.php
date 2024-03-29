<?php
/* Persist System Settings */
$ret = "SELECT * FROM `ezanaLMS_Settings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($sys = $res->fetch_object()) {
?>
    <!DOCTYPE html>
    <html>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $sys->sysname; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- SEO META TAGS -->
        <meta name="title" content="<?php echo $sys->sysname; ?>">
        <meta name="description" content="Powered By Ezana Learning Management System">

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
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
        <!-- Icons CSS -->
        <link rel="stylesheet" href="../assets/css/icons.css">
        <!-- Data Tables -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" />
        <!-- Scroll Bars -->
        <link rel="stylesheet" href="../assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
        <!-- Summernote -->
        <link rel="stylesheet" href="../assets/plugins/summernote/summernote-bs4.css">
        <!-- FlatPickr PlugIn CSS -->
        <link href="../assets/lugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
        <link href="../assets/plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="../assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Select2 -->
        <link rel="stylesheet" href="../assets/plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="../assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="../assets/css/adminlte.min.css">
        <!-- Bootstrap Toggle CDN -->
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <!-- Date Picker -->
        <link rel="stylesheet" href="../assets/plugins/datepicker/css/bootstrap-datetimepicker.min.css" />
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        <!-- IziAlerts -->
        <link rel="stylesheet" type="text/css" href="../assets/plugins/iziToast/iziToast.min.css">
        <style>
            thead input {
                width: 100%;
            }
        </style>
    </head>
<?php
} ?>