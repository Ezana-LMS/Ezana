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
<!-- Dropify Plug in -->
<script src="../assets/plugins/dropify/dropify.min.js"></script>
<!-- Summernote -->
<script src="../assets/plugins/summernote/summernote-bs4.min.js"></script>
<!-- Canvas Chart Js -->
<script src="../assets/plugins/canvas/canvasjs.min.js"></script>
<!-- Load IziAlerts -->
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
<!-- Data Tables -->
<script src="../assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script src="../assets/dist/js/data-table.js"></script>
<script src="../assets/dist/js/data-table-init.js"></script>
<script>
    $(function() {
        $("#example1").DataTable();
        $("#courses_enrolled").DataTable();
        $("#enrollment").DataTable();
        $("#admins").DataTable();
        $("#past-papers").DataTable();
        $("#course-materials").DataTable();
        $("#studentGroups").DataTable();
        $("#group_ass").DataTable();
        /* Delete Functionalities Data Tables Init */
        $("#faculties").DataTable();
        $("#departments").DataTable();
        $("#courses").DataTable();
        $("#modules").DataTable();
        $("#lecturers").DataTable();
        $("#students").DataTable();

        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
        });
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

<!-- Data Tables V2.01 -->
<!-- NOTE TO Use Copy CSV Excel PDF Print Options You Must Include These Files  -->
<script src="../assets/plugins/datatable/button-ext/dataTables.buttons.min.js"></script>
<script src="../assets/plugins/datatable/button-ext/jszip.min.js"></script>
<script src="../assets/plugins/datatable/button-ext/buttons.html5.min.js"></script>
<script src="../assets/plugins/datatable/button-ext/buttons.print.min.js"></script>
<script>
    $('#export-dt').DataTable({
        dom: '<"row"<"col-md-12"<"row"<"col-md-6"B><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
        buttons: {
            buttons: [{
                    extend: 'copy',
                    className: 'btn'
                },
                {
                    extend: 'csv',
                    className: 'btn'
                },
                {
                    extend: 'excel',
                    className: 'btn'
                },
                {
                    extend: 'print',
                    className: 'btn'
                }
            ]
        },
        "oLanguage": {
            "oPaginate": {
                "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
            },
            "sInfo": "Showing page _PAGE_ of _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Search...",
            "sLengthMenu": "Results :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [7, 10, 20, 50],
        "pageLength": 7
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
<?php
//require_once('_ajax.php');
?>
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