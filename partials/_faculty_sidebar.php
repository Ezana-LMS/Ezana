
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
                            Main Dashboard
                        </p>
                    </a>
                </li>
                <!-- Faculties -->
                <li class="nav-item">
                    <a href="faculty_dashboard.php?faculty=<?php echo $row->id;?>" class="nav-link">
                        <i class="nav-icon fas fa fa-university"></i>
                        <p>
                            Faculty Dashboard
                        </p>
                    </a>
                </li>

                <!-- Departments -->
                <li class="nav-item">
                    <a href="departments.php?faculty=<?php echo $row->id;?>" class="nav-link">
                        <i class="nav-icon fas fa fa-building"></i>
                        <p>
                            Departments
                        </p>
                    </a>
                </li>

                <!-- Courses -->
                <li class="nav-item">
                    <a href="courses.php?faculty=<?php echo $row->id;?>" class="nav-link">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>
                            Courses
                        </p>
                    </a>
                </li>

                <!-- Modules -->
                <li class="nav-item">
                    <a href="modules.php?faculty=<?php echo $row->id;?>" class="nav-link">
                        <i class="nav-icon fas fas fa-chalkboard"></i>
                        <p>
                            Modules
                        </p>
                    </a>
                </li>

                
                <!-- Lecturers -->
                <li class="nav-item">
                    <a href="lecturers.php?faculty=<?php echo $row->id;?>" class="nav-link">
                        <i class="nav-icon fas fas fa-user-tie"></i>
                        <p>
                            Lecturers
                        </p>
                    </a>
                </li>

                <!-- Students -->
                <li class="nav-item">
                    <a href="students.php?faculty=<?php echo $row->id;?>" class="nav-link">
                        <i class="nav-icon  fas fa-user-graduate"></i>
                        <p>
                            Students
                        </p>
                    </a>
                </li>

                <!-- Students Enrollments -->
                <li class="nav-item">
                    <a href="total_enrollments.php" class="nav-link">
                        <i class="nav-icon  fas fa-user-tag"></i>
                        <p>
                            Enrollments
                        </p>
                    </a>
                </li>
                <!-- Advanced Reporting -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-paste"></i>
                        <p>
                            Faculty Reports
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="reports_departments.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Departments</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="reports_courses.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Courses</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="reports_modules.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Modules</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="reports_teaching.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Teaching Allocations</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="reading_materials.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>Module Enrollments</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="reading_materials.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>School Calendars</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="reading_materials.php" class="nav-link">
                                <i class="fas fa-angle-double-right nav-icon"></i>
                                <p>TimeTables</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>