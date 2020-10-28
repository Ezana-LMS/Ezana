<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="brand-link">
        <img src="dist/img/favcon.jpeg" alt="Ezana LMS Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">Ezana LMS</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="system_admins.php" class="nav-link">
                        <i class="nav-icon fas fa-user-secret"></i>
                        <p>
                            System Admins
                            <!-- <span class="right badge badge-danger">New</span> -->
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="school_calendar.php" class="nav-link">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>
                            School Calendar
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>
                            Lecturers
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- <li class="nav-item">
                            <a href="add_lecturer.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Add Lecturer</p>
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a href="manage_lectures.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Manage Lecturers</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="lec_password_resets.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Password Resets</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Students
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- <li class="nav-item">
                            <a href="add_student.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Add Students</p>
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a href="manage_students.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Manage Students</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="student_password_resets.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Password Resets</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-anchor"></i>
                        <p>
                            Faculties
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- <li class="nav-item">
                            <a href="add_faculties.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Add Faculty</p>
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a href="manage_faculties.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Manage Faculties</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-bezier-curve"></i>
                        <p>
                            Departments
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="manage_departments.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Manage Departments</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="departmental_memos.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Dept Memos</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="departmental_notices.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Dept Notices</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Courses
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="manage_courses.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Manage Courses</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="course_directory.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Course Directories</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-paste"></i>
                        <p>
                            Modules
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="manage_modules.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Manage Module</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="assign_lecturer_module.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Assign Module</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="module_notices.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Module Notices</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="reading_materials.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Reading Materials</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Time Tables
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="manage_classes.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Manage Classes</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="fas fas fa-angle-double-right nav-icon"></i>
                                <p>
                                    Generate Time Table
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="generate_virtual_timetable.php" class="nav-link">
                                        <i class="fas fa-angle-double-right nav-icon"></i>
                                        <p>Virtual Class TimeTable</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="generate_timetable.php" class="nav-link">
                                        <i class="fas fa-angle-double-right nav-icon"></i>
                                        <p>Class Timetable</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>
                            Enrollments
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="manage_student_enrollments.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Manage Enrollments</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            Student Groups
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="manage_groups.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Manage Groups</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="assign_students.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Assign Students</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="group_projects.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Group Projects</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="group_announcements.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Group Announcements</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Exams / Tests
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="questions_bank.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Question Papers</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="assignments.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Assignments</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="results.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Results</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-signature"></i>
                        <p>
                            Past Exam Papers
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="past_papers.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Past Papers</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pastpaper_solutions.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>PastPapers Solutions</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="class_recordings.php" class="nav-link">
                        <i class="nav-icon fas fa-file-video"></i>
                        <p>
                            Class Recordings
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Reports
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="administrator_reports.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Administrators</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="lecturer_reports.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Lecturers</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="students_reports.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Students</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="faculties_reports.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Faculties</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="departments_reports.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Departments</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="courses_reports.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Courses</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="modules_reports.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Modules</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="allocation_reports.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Allocations</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="school_calendar_reports.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>School Calendar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="enrollment_reports.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Enrollments</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>