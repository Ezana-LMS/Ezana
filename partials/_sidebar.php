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
                            <span class="right badge badge-danger">New</span>
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
                        <li class="nav-item">
                            <a href="add_lecturer.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Add Lecturer</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="manage_lectures.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Manage Lecturers</p>
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
                        <li class="nav-item">
                            <a href="add_student.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Add Students</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="manage_students.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Manage Students</p>
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
                        <li class="nav-item">
                            <a href="add_faculties.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Add Faculty</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="manage_faculties.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
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
                            <a href="add_department.php" class="nav-link">
                                <i class="fas fa-chevron-right  nav-icon"></i>
                                <p>Add Department</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="manage_departments.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Manage Departments</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="departmental_memos.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Dept Memos</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="departmental_notices.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
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
                            <a href="add_course.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Add Course</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="manage_courses.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Manage Courses</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="course_directory.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
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
                            <a href="add_module.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Add Module</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="manage_modules.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Manage Module</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="assign_lecturer_module.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Assign Module</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="module_notices.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Module Notices</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="reading_materials.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
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
                            <a href="add_class.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Add Class </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="generate_timetable.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Generate Table</p>
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
                            <a href="add_groups.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Add Groups </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="manage_groups.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Manage Groups</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="assign_students.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Assign Students</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="group_projects.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Group Projects</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="group_announcements.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Group Announcements</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="group_feedbacks.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Group Feedbacks</p>
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
                            <a href="add_past_exams.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Upload Papers</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="manage_past_exam_papers.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Manage Exam Papers</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="upload_solutions.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Upload Solutions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="manage_solutions.php" class="nav-link">
                                <i class="fas fa-chevron-right nav-icon"></i>
                                <p>Manage Solutions</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="school_calendar.php" class="nav-link">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>
                            School Calendar
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="class_recordings.php" class="nav-link">
                        <i class="nav-icon fas fa-file-video"></i>
                        <p>
                            Class Recordings
                        </p>
                    </a>
                </li>

                <li class="nav-header">Advaced Reporting</li>
                <li class="nav-item">
                    <a href="pages/calendar.html" class="nav-link">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>
                            School Calendar
                            <span class="badge badge-info right">2</span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/gallery.html" class="nav-link">
                        <i class="nav-icon far fa-image"></i>
                        <p>
                            Gallery
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>
                            Mailbox
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/mailbox/mailbox.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inbox</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/mailbox/compose.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Compose</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/mailbox/read-mail.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Read</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Pages
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/examples/invoice.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Invoice</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/profile.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/e_commerce.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>E-commerce</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/projects.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Projects</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/project_add.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Project Add</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/project_edit.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Project Edit</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/project_detail.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Project Detail</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/contacts.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Contacts</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-plus-square"></i>
                        <p>
                            Extras
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/examples/login.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Login</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/register.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Register</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/forgot-password.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Forgot Password</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/recover-password.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Recover Password</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/lockscreen.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Lockscreen</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/legacy-user-menu.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Legacy User Menu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/language-menu.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Language Menu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/404.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Error 404</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/500.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Error 500</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/pace.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pace</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/blank.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Blank Page</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="starter.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Starter Page</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">MISCELLANEOUS</li>
                <li class="nav-item">
                    <a href="https://adminlte.io/docs/3.0" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Documentation</p>
                    </a>
                </li>
                <li class="nav-header">MULTI LEVEL EXAMPLE</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-circle nav-icon"></i>
                        <p>Level 1</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-circle"></i>
                        <p>
                            Level 1
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Level 2</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Level 2
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Level 2</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-circle nav-icon"></i>
                        <p>Level 1</p>
                    </a>
                </li>
                <li class="nav-header">LABELS</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle text-danger"></i>
                        <p class="text">Important</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle text-warning"></i>
                        <p>Warning</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle text-info"></i>
                        <p>Informational</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>