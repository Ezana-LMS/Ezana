<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <title>Ezana LMS Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="public/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="public/dist/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="public/dist/css/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="public/plugins/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="public/plugins/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="public/dist/css/util.css">
    <link rel="stylesheet" type="text/css" href="public/dist/css/main.css">
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
</head>