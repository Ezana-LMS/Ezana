<!-- jQuery -->
<script src="public/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI -->
<script src="public/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Ezana App Js -->
<script src="public/dist/js/adminlte.min.js"></script>
<script src="public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- OPTIONAL SCRIPTS -->
<script src="public/dist/js/demo.js"></script>
<!-- Dropify Plug in -->
<script src="public/plugins/dropify/dropify.min.js"></script>
<!-- Summernote -->
<script src="public/plugins/summernote/summernote-bs4.min.js"></script>
<!-- Canvas Chart Js -->
<script src="public/plugins/canvas/canvasjs.min.js"></script>
<!-- Chart Js -->
<script src="public/plugins/chart.js/Chart.min.js"></script>
<!-- Data Tables -->
<script src="public/plugins/datatables/jquery.dataTables.js"></script>
<script src="public/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script src="public/dist/js/data-table.js"></script>
<script src="public/dist/js/data-table-init.js"></script>
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
    /* Clickable Row  */
    $(document).ready(function($) {
        $(".table-row").click(function() {
            window.document.location = $(this).data("href");
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
<script src="public/plugins/datatable/button-ext/dataTables.buttons.min.js"></script>
<script src="public/plugins/datatable/button-ext/jszip.min.js"></script>
<script src="public/plugins/datatable/button-ext/buttons.html5.min.js"></script>
<script src="public/plugins/datatable/button-ext/buttons.print.min.js"></script>
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
<script src="public/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>
    $(document).ready(function() {
        bsCustomFileInput.init();
    });

    /* Admin Bulk Clear Notifications */
    function setDeleteAction() {
        if (confirm("Are you sure want to clear these notifications?")) {
            document.notificationsForm.action = "notifications_clear.php";
            document.notificationsForm.submit();
        }
    }
    /* Faculty Clear Notifications */
    function setDeleteAction() {
        if (confirm("Are you sure want to clear these notifications?")) {
            document.notificationsForm.action = "edu_admn_notifications_clear.php";
            document.notificationsForm.submit();
        }
    }
    /* Load Summernotes */
    $(document).ready(function() {
        $('.Summernote').summernote({
            height: 300,
            minHeight: null,
            maxHeight: null,
            focus: true
        });
    });
</script>

<!-- Select2 -->
<script src="public/plugins/select2/select2.min.js"></script>
<script src="public/plugins/select2/custom-select2.js"></script>
<script>
    var ss = $(".basic").select2({
        tags: true,
    });
</script>
<!-- Load Ajax Scripts -->
<?php
require_once('_ajax.php');
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