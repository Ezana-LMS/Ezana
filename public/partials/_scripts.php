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
        $("#")
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

    /* $(document).ready(function() {
        // Setup - add a text input to each footer cell
        $('#filter-table thead tr').clone(true).appendTo('#filter-table thead');
        $('#filter-table thead tr:eq(1) th').each(function(i) {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');

            $('input', this).on('keyup change', function() {
                if (table.column(i).search() !== this.value) {
                    table
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });

        var table = $('#filter-table').DataTable({
            orderCellsTop: true,
            fixedHeader: true
        });
    }); */
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
</script>

<!-- fullCalendar 2.2.5 -->
<script src="public/plugins/moment/moment.min.js"></script>
<script src="public/plugins/fullcalendar/main.min.js"></script>
<script src="public/plugins/fullcalendar-daygrid/main.min.js"></script>
<script src="public/plugins/fullcalendar-timegrid/main.min.js"></script>
<script src="public/plugins/fullcalendar-interaction/main.min.js"></script>
<script src="public/plugins/fullcalendar-bootstrap/main.min.js"></script>

<!-- Select2 -->
<script src="public/plugins/select2/select2.min.js"></script>
<script src="public/plugins/select2/custom-select2.js"></script>
<script>
    var ss = $(".basic").select2({
        tags: true,
    });
</script>

<script>
    /* CK Editor */

    /*  CKEDITOR.replace('textarea');
     CKEDITOR.replace('department_details');
     CKEDITOR.replace('dep_details');
     CKEDITOR.replace('dep_memo'); */
</script>
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
<!-- Page specific script -->
<script>
    $(function() {

        /* initialize the external events
         -----------------------------------------------------------------*/
        function ini_events(ele) {
            ele.each(function() {

                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim($(this).text()) // use the element's text as the event title
                }

                // store the Event Object in the DOM element so we can get to it later
                $(this).data('eventObject', eventObject)

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 1070,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0 //  original position after the drag
                })

            })
        }

        ini_events($('#external-events div.external-event'))

        /* initialize the calendar
         -----------------------------------------------------------------*/
        //Date for the calendar events (dummy data)
        var date = new Date()
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear()

        var Calendar = FullCalendar.Calendar;
        var Draggable = FullCalendarInteraction.Draggable;

        var containerEl = document.getElementById('external-events');
        var checkbox = document.getElementById('drop-remove');
        var calendarEl = document.getElementById('calendar');

        // initialize the external events
        // -----------------------------------------------------------------

        new Draggable(containerEl, {
            itemSelector: '.external-event',
            eventData: function(eventEl) {
                console.log(eventEl);
                return {
                    title: eventEl.innerText,
                    backgroundColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                    borderColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                    textColor: window.getComputedStyle(eventEl, null).getPropertyValue('color'),
                };
            }
        });

        var calendar = new Calendar(calendarEl, {
            plugins: ['bootstrap', 'interaction', 'dayGrid', 'timeGrid'],
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            //Random default events
            events: [{
                    title: 'All Day Event',
                    start: new Date(y, m, 1),
                    backgroundColor: '#f56954', //red
                    borderColor: '#f56954', //red
                    allDay: true
                },
                {
                    title: 'Long Event',
                    start: new Date(y, m, d - 5),
                    end: new Date(y, m, d - 2),
                    backgroundColor: '#f39c12', //yellow
                    borderColor: '#f39c12' //yellow
                },
                {
                    title: 'Meeting',
                    start: new Date(y, m, d, 10, 30),
                    allDay: false,
                    backgroundColor: '#0073b7', //Blue
                    borderColor: '#0073b7' //Blue
                },
                {
                    title: 'Lunch',
                    start: new Date(y, m, d, 12, 0),
                    end: new Date(y, m, d, 14, 0),
                    allDay: false,
                    backgroundColor: '#00c0ef', //Info (aqua)
                    borderColor: '#00c0ef' //Info (aqua)
                },
                {
                    title: 'Birthday Party',
                    start: new Date(y, m, d + 1, 19, 0),
                    end: new Date(y, m, d + 1, 22, 30),
                    allDay: false,
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor: '#00a65a' //Success (green)
                },
                {
                    title: 'Click for Google',
                    start: new Date(y, m, 28),
                    end: new Date(y, m, 29),
                    url: 'http://google.com/',
                    backgroundColor: '#3c8dbc', //Primary (light-blue)
                    borderColor: '#3c8dbc' //Primary (light-blue)
                }
            ],
            editable: true,
            droppable: true, // this allows things to be dropped onto the calendar !!!
            drop: function(info) {
                // is the "remove after drop" checkbox checked?
                if (checkbox.checked) {
                    // if so, remove the element from the "Draggable Events" list
                    info.draggedEl.parentNode.removeChild(info.draggedEl);
                }
            }
        });

        calendar.render();
        // $('#calendar').fullCalendar()

        /* ADDING EVENTS */
        var currColor = '#3c8dbc' //Red by default
        //Color chooser button
        var colorChooser = $('#color-chooser-btn')
        $('#color-chooser > li > a').click(function(e) {
            e.preventDefault()
            //Save color
            currColor = $(this).css('color')
            //Add color effect to button
            $('#add-new-event').css({
                'background-color': currColor,
                'border-color': currColor
            })
        })
        $('#add-new-event').click(function(e) {
            e.preventDefault()
            //Get value and make sure it is not null
            var val = $('#new-event').val()
            if (val.length == 0) {
                return
            }

            //Create events
            var event = $('<div />')
            event.css({
                'background-color': currColor,
                'border-color': currColor,
                'color': '#fff'
            }).addClass('external-event')
            event.html(val)
            $('#external-events').prepend(event)

            //Add draggable funtionality
            ini_events(event)

            //Remove event from text input
            $('#new-event').val('')
        })
    })
</script>