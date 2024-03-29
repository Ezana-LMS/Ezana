<script>
    /*
     * Created on Mon Jun 21 2021
     *
     * The MIT License (MIT)
     * Copyright (c) 2021 MartDevelopers Inc
     *
     * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
     * and associated documentation files (the "Software"), to deal in the Software without restriction,
     * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
     * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
     * subject to the following conditions:
     *
     * The above copyright notice and this permission notice shall be included in all copies or substantial
     * portions of the Software.
     *
     * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
     * TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
     * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
     * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
     */

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
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'DepFacID=' + val,
            success: function(data) {
                //alert(data);
                $('#DepFacName').val(data);
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
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'ModuleName=' + val,
            success: function(data) {
                //alert(data);
                $('#ModuleID').val(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'ModuleID=' + val,
            success: function(data) {
                //alert(data);
                $('#ModuleCourseId').val(data);
            }
        });
    }

    /* Enrollment Module Details */
    function EnrollmentModuleDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'EnrollmentModuleCode=' + val,
            success: function(data) {
                //alert(data);
                $('#EnrollmentModuleName').val(data);
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
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'moduleName=' + val,
            success: function(data) {
                //alert(data);
                $('#moduleId').val(data);
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
        /* Student Stage */
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'StudentName=' + val,
            success: function(data) {
                //alert(data);
                $('#StudentYear').val(data);
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

    /*  Optimized Faculty Function*/
    function OptimizedFacultyDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'FacultyCode=' + val,
            success: function(data) {
                //alert(data);
                $('#FacultyID').val(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'FacultyID=' + val,
            success: function(data) {
                //alert(data);
                $('#FacultyName').val(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'FacultyName=' + val,
            success: function(data) {
                //alert(data);
                $('#AdminFacultyID').val(data);
            }
        });
    }

    /*Student Educational Details  */
    function getStudentCourseDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'CourseCode=' + val,
            success: function(data) {
                //alert(data);
                $('#CourseName').val(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'CourseName=' + val,
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
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'FacultyID=' + val,
            success: function(data) {
                //alert(data);
                $('#FacultyName').val(data);
            }
        });
    }

    /*Optimized Course Details  */
    function optimizedCourseDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'ModuleCourseCode=' + val,
            success: function(data) {
                //alert(data);
                $('#ModuleCourseID').val(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'ModuleCourseID=' + val,
            success: function(data) {
                //alert(data);
                $('#ModuleCourseName').val(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'ModuleCourseName=' + val,
            success: function(data) {
                //alert(data);
                $('#ModuleCourseFacultyID').val(data);
            }
        });
    }

    /* Get Faculty Head Details */
    function getFacultyHeadDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'FacultyHead=' + val,
            success: function(data) {
                //alert(data);
                $('#FacultyHeadEmail').val(data);
            }
        });
    }
    /* Get Department Head Email */
    function getDepartmentHeadDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'DepartmentHead=' + val,
            success: function(data) {
                //alert(data);
                $('#DepartmentHeadEmail').val(data);
            }
        });
    }
    /* Get Course Head Email */
    function getCourseHeadDetails(val) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: 'CourseHead=' + val,
            success: function(data) {
                //alert(data);
                $('#CourseHeadEmail').val(data);
            }
        });
    }
</script>