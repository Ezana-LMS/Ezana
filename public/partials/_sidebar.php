<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="edu_admn_dashboard.php" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="edu_admn_faculties.php?view=<?php echo $admin->school_id; ?>" class="nav-link">
                    <i class="nav-icon fas fa-university"></i>
                    <p>
                        Faculties
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="edu_admn_departments.php?view=<?php echo $admin->school_id; ?>" class="nav-link">
                    <i class="nav-icon fas fa-building"></i>
                    <p>
                        Departments
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="edu_admn_courses.php?view=<?php echo $admin->school_id; ?>" class="nav-link">
                    <i class="nav-icon fas fa-chalkboard-teacher"></i>
                    <p>
                        Courses
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="edu_admn_modules.php?view=<?php echo $admin->school_id; ?>" class="nav-link">
                    <i class="nav-icon fas fa-chalkboard"></i>
                    <p>
                        Modules
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="edu_admn_lecturers.php?view=<?php echo $admin->school_id; ?>" class="nav-link">
                    <i class="nav-icon fas fa-user-tie"></i>
                    <p>
                        Lecturers
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="edu_admn_students.php?view=<?php echo $admin->school_id; ?>" class="nav-link">
                    <i class="nav-icon fas fa-user-graduate"></i>
                    <p>
                        Students
                    </p>
                </a>
            </li>
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-cogs"></i>
                    <p>
                        System Settings
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="edu_admn_reports.php" class="nav-link">
                            <i class="fas fa-angle-right nav-icon"></i>
                            <p>Reports</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="edu_admn_system_settings.php" class="nav-link">
                            <i class="fas fa-angle-right nav-icon"></i>
                            <p>System Settings</p>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>