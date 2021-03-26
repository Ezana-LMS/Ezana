<!-- Get Department Details Script -->
<script>
    /* Ajaxing Aint Easy */
    /*Department Details  */
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
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'DepartmentID=' + val,
            success: function(data) {
                //alert(data);
                $('#DepartmentFacultyId').val(data);
            }
        });
    }

    /* Optimized Department Ajax */
    function OptimizedGetDepartmentDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'DepartmentCode=' + val,
            success: function(data) {
                //alert(data);
                $('#DepartmentID').val(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'DepartmentID=' + val,
            success: function(data) {
                //alert(data);
                $('#DepartmentName').val(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'DepartmentName=' + val,
            success: function(data) {
                //alert(data);
                $('#FacultyID').val(data);
            }
        });
    }

    /* Optimized Department Ajax */
    function getDepartmentDetailsOnDocuments(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'DepCode=' + val,
            success: function(data) {
                //alert(data);
                $('#DepID').val(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'DepID=' + val,
            success: function(data) {
                //alert(data);
                $('#DepName').val(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'DepName=' + val,
            success: function(data) {
                //alert(data);
                $('#DepFacID').val(data);
            }
        });
    }

    /* getAllocatedModuleDetails */
    function getAllocatedModuleDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'AllocatedModuleCode=' + val,
            success: function(data) {
                //alert(data);
                $('#AllocatedModuleName').val(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'AllocatedModuleName=' + val,
            success: function(data) {
                //alert(data);
                $('#AllocatedLecturerName').val(data);
            }
        });
    }

    /* Course Details */
    function getCourseDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'Cname=' + val,
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
            data: 'Coursecode=' + val,
            success: function(data) {
                //alert(data);
                $('#CourseName').val(data);
            }
        });
        /* Get Faculty Course Details */
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'CourseName=' + val,
            success: function(data) {
                //alert(data);
                $('#CourseFacultyID').val(data);
            }
        });


    }
    /* Module Details */
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

    /* Optimized Module Details */
    function OptimizedModuleDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'ModuleCode=' + val,
            success: function(data) {
                //alert(data);
                $('#ModuleName').val(data);
            }
        });
    }


    /* Optimized Guest Module Details */
    function guestLecModule(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'moduleCode=' + val,
            success: function(data) {
                //alert(data);
                $('#moduleName').val(data);
            }
        });
    }

    /* Lecturer Details */
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

    /* Optimized Lecturer Details */
    function getLecturerDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'LecNumber=' + val,
            success: function(data) {
                //alert(data);
                $('#lecID').val(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'lecID=' + val,
            success: function(data) {
                //alert(data);
                $('#lecName').val(data);
            }
        });
    }

    /* Guest Lecturer Details */
    function getGuestLec(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'lecNumber=' + val,
            success: function(data) {
                //alert(data);
                $('#LecID').val(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'LecID=' + val,
            success: function(data) {
                //alert(data);
                $('#LecName').val(data);
            }
        });
    }


    /* Student Details */
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

    /* Semester Details */
    function getSemesterDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'SemesterEnrolled=' + val,
            success: function(data) {
                //alert(data);
                $('#SemesterStart').val(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'SemesterStart=' + val,
            success: function(data) {
                //alert(data);
                $('#SemesterEnd').val(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'SemesterEnd=' + val,
            success: function(data) {
                //alert(data);
                $('#AcademicYear').val(data);
            }
        });

    }

    /* Group Details */
    function getGroupDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'GroupName=' + val,
            success: function(data) {
                //alert(data);
                $('#groupCode').val(data);
            }
        });
    }

    /* Faculty Details */
    function getFacutyDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'FacultyName=' + val,
            success: function(data) {
                //alert(data);
                $('#FacultyId').val(data);
            }
        });
    }
</script>