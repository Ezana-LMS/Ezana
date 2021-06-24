<!-- jQuery -->
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI -->
<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Ezana App Js -->
<script src="../assets/js/adminlte.min.js"></script>
<script src="../assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- OPTIONAL SCRIPTS -->
<script src="../assets/js/demo.js"></script>
<!-- Popper Min Js -->
<script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
<!-- Moment Min jS -->
<script src="../assets/plugins/datepicker/js/moment.js"></script>
<!-- Dropify Plug in -->
<script src="../assets/plugins/dropify/dropify.min.js"></script>
<!-- Summernote -->
<script src="../assets/plugins/summernote/summernote-bs4.min.js"></script>
<!-- Canvas Chart Js -->
<script src="../assets/plugins/canvas/canvasjs.min.js"></script>
<!-- Date Time Picker -->
<script src="../assets/plugins/datepicker/js/bootstrap-datetimepicker.min.js"></script>

<!-- iZi Toast Js -->
<script src="../assets/plugins/iziToast/iziToast.min.js" type="text/javascript"></script>
<?php if (isset($success)) { ?>
    <!--This code for injecting success alert-->
    <script>
        iziToast.success({
            title: 'Success',
            position: 'topRight',
            transitionIn: 'flipInX',
            transitionOut: 'flipOutX',
            transitionInMobile: 'fadeInUp',
            transitionOutMobile: 'fadeOutDown',
            message: '<?php echo $success; ?>',
        });
    </script>

<?php } ?>

<?php if (isset($err)) { ?>
    <!--This code for injecting error alert-->
    <script>
        iziToast.error({
            title: 'Error',
            timeout: 10000,
            resetOnHover: true,
            position: 'topRight',
            transitionIn: 'flipInX',
            transitionOut: 'flipOutX',
            transitionInMobile: 'fadeInUp',
            transitionOutMobile: 'fadeOutDown',
            message: '<?php echo $err; ?>',
        });
    </script>

<?php } ?>

<?php if (isset($info)) { ?>
    <!--This code for injecting info alert-->
    <script>
        iziToast.warning({
            title: 'Warning',
            position: 'topLeft',
            transitionIn: 'flipInX',
            transitionOut: 'flipOutX',
            transitionIn: 'fadeInUp',
            transitionInMobile: 'fadeInUp',
            transitionOutMobile: 'fadeOutDown',
            message: '<?php echo $info; ?>',
        });
    </script>

<?php }
?>
<!-- Data Table Js -->
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.js"></script>
<script>
    $(document).ready(function() {
        $('table').DataTable();
    });
    /* Initialize Date Picker */
    $(function() {
        $('.availability').datetimepicker();
    });
    /* Overidde Default Font Awesome 4 Icons */
    $.extend(true, $.fn.datetimepicker.defaults, {
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'fas fa-calendar-check',
            clear: 'far fa-trash-alt',
            close: 'far fa-times-circle'
        }
    });

    /* Advanced Filter */
    $(document).ready(function() {
        $('#advanced-filter').DataTable({
            "initComplete": function() {
                var api = this.api();
                api.$('td').click(function() {
                    api.search(this.innerHTML).draw();
                });
            }
        });
    });
</script>



<!-- File Uploads  -->
<script src="../assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>
    $(document).ready(function() {
        bsCustomFileInput.init();
    });

    /* Admin Bulk Clear Notifications */
    function setDeleteAction() {
        if (confirm("Are you sure want to clear these notifications?")) {
            document.notificationsForm.action = "clear_notifications";
            document.notificationsForm.submit();
        }
    }
    /* Load Summernotes */
    $(document).ready(function() {
        $('.Summernote').summernote({
            height: 300,
            focus: true,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['height', ['height']],
                ['view', ['fullscreen', 'codeview', 'help']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],

            ]
        });
    });
</script>

<!-- Select2 -->
<script src="../assets/plugins/select2/select2.min.js"></script>
<script src="../assets/plugins/select2/custom-select2.js"></script>
<script>
    var ss = $(".basic").select2({
        tags: true,
    });
</script>

<!-- Load Ajax Scripts -->
<?php require_once('ajax_scripts.php'); ?>

<script>
    /* User Login Activity Chart */
    window.onload = function() {

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title: {
                text: ""
            },
            axisX: {
                valueFormatString: "DD MMM,YY"
            },
            axisY: {
                title: "No Of Users",
                includeZero: false,
                suffix: ""
            },
            legend: {
                cursor: "pointer",
                fontSize: 16,
                itemclick: toggleDataSeries
            },
            toolTip: {
                shared: true
            },
            data: [{
                    name: "Administrators",
                    type: "spline",
                    yValueFormatString: "#0.## ",
                    showInLegend: true,
                    dataPoints: [{
                            /* Yesterday */
                            x: new Date(Date.now() - 864e5),
                            y: <?php
                                $today = date('Y-m-d', time() - 60 * 60 * 24);
                                $query = "SELECT COUNT(DISTINCT user_id)  FROM `ezanaLMS_UserLog` WHERE loginTime = '$today' AND User_Rank = 'Administrator' ";
                                $stmt = $mysqli->prepare($query);
                                $stmt->execute();
                                $stmt->bind_result($admins);
                                $stmt->fetch();
                                $stmt->close();
                                echo $admins;
                                ?>
                        },
                        {
                            /* Today */
                            x: new Date(),
                            y: <?php
                                $today = date('Y-m-d');
                                $query = "SELECT COUNT(DISTINCT user_id)  FROM `ezanaLMS_UserLog` WHERE loginTime = '$today' AND User_Rank = 'Administrator' ";
                                $stmt = $mysqli->prepare($query);
                                $stmt->execute();
                                $stmt->bind_result($admins);
                                $stmt->fetch();
                                $stmt->close();
                                echo $admins;
                                ?>
                        }
                    ]
                },
                {
                    name: "Lecturers",
                    type: "spline",
                    yValueFormatString: "#0.## ",
                    showInLegend: true,
                    dataPoints: [{
                            /* Yesterday */
                            x: new Date(Date.now() - 864e5),
                            y: <?php
                                $today = date('Y-m-d', time() - 60 * 60 * 24);
                                $query = "SELECT COUNT(DISTINCT user_id)  FROM `ezanaLMS_UserLog` WHERE loginTime = '$today' AND User_Rank = 'Lecturer' ";
                                $stmt = $mysqli->prepare($query);
                                $stmt->execute();
                                $stmt->bind_result($admins);
                                $stmt->fetch();
                                $stmt->close();
                                echo $admins;
                                ?>
                        },
                        {
                            /* Today */
                            x: new Date(),
                            y: <?php
                                $today = date('Y-m-d');
                                $query = "SELECT COUNT(DISTINCT user_id)  FROM `ezanaLMS_UserLog` WHERE loginTime = '$today' AND User_Rank = 'Lecturer' ";
                                $stmt = $mysqli->prepare($query);
                                $stmt->execute();
                                $stmt->bind_result($admins);
                                $stmt->fetch();
                                $stmt->close();
                                echo $admins;
                                ?>
                        }
                    ]
                },
                {
                    name: "Students",
                    type: "spline",
                    yValueFormatString: "#0.## ",
                    showInLegend: true,
                    dataPoints: [{
                            /* Yesterday */
                            x: new Date(Date.now() - 864e5),
                            y: <?php
                                $today = date('Y-m-d', time() - 60 * 60 * 24);
                                $query = "SELECT COUNT(DISTINCT user_id)  FROM `ezanaLMS_UserLog` WHERE loginTime = '$today' AND User_Rank = 'Student' ";
                                $stmt = $mysqli->prepare($query);
                                $stmt->execute();
                                $stmt->bind_result($admins);
                                $stmt->fetch();
                                $stmt->close();
                                echo $admins;
                                ?>
                        },
                        {
                            /* Today */
                            x: new Date(),
                            y: <?php
                                $today = date('Y-m-d');
                                $query = "SELECT COUNT(DISTINCT user_id)  FROM `ezanaLMS_UserLog` WHERE loginTime = '$today' AND User_Rank = 'Student' ";
                                $stmt = $mysqli->prepare($query);
                                $stmt->execute();
                                $stmt->bind_result($admins);
                                $stmt->fetch();
                                $stmt->close();
                                echo $admins;
                                ?>
                        }
                    ]
                }
            ]
        });
        chart.render();

        function toggleDataSeries(e) {
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            chart.render();
        }

    }
</script>