<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Ezana App Js -->
<script src="dist/js/adminlte.min.js"></script>
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- OPTIONAL SCRIPTS -->
<script src="dist/js/demo.js"></script>
<!-- Dropify Plug in -->
<script src="plugins/dropify/dropify.min.js"></script>
<!-- jQuery Mapael -->
<script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="plugins/raphael/raphael.min.js"></script>
<script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<script src="dist/js/pages/dashboard2.js"></script>
<!-- Data Tables -->
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script>
    $(function() {
        $("#example1").DataTable();
        $("#courses_enrolled").DataTable();
        $("#enrollment").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
        });
    });
</script>

<!-- File Uploads  -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>
    $(document).ready(function() {
        bsCustomFileInput.init();
    });
</script>

<!-- Select2 -->
<script src="plugins/select2/select2.min.js"></script>
<script src="plugins/select2/custom-select2.js"></script>
<script>
    var ss = $(".basic").select2({
        tags: true,
    });
</script>

<!-- CK Editor -->
<script src="plugins/ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace('textarea');
</script>


<!-- Get Department Details Script -->
<script>
    function getDepartmentDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'DepartmentName=' + val,
            success: function(data) {
                //alert(data);
                $('#DepartmentID').val(data);
            }
        });
    }
</script>
<!-- Get Course Details -->
<script>
    function getCourseDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'CourseName=' + val,
            success: function(data) {
                //alert(data);
                $('#CourseCode').val(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'CourseCode=' + val,
            success: function(data) {
                //alert(data);
                $('#CourseID').val(data);
            }
        });
        
        /* Get course name*/
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'CourseCode=' + val,
            success: function(data) {
                //alert(data);
                $('#CourseName').val(data);
            }
        });

    }
</script>
<!--Get Module Details -->
<script>
    function getModuleDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'ModuleName=' + val,
            success: function(data) {
                //alert(data);
                $('#ModuleCode').val(data);
            }
        });
    }
</script>
<!-- Get Lec Details -->
<script>
    function getLecDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'LecName=' + val,
            success: function(data) {
                //alert(data);
                $('#lecID').val(data);
            }
        });
    }
</script>
<!-- Get Student Details -->
<script>
    function getStudentDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'StudentAdmn=' + val,
            success: function(data) {
                //alert(data);
                $('#StudentName').val(data);
            }
        });
    }
</script>