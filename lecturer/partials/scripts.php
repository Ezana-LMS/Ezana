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
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>


<script>
    $(document).ready(function() {
        $('.table').DataTable();
    });

    $(document).ready(function() {
        $('#export-data-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ]
        });
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
                ['insert', ['link', 'video']],
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
