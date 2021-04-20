<?php
/*
 * Created on Wed Apr 21 2021
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
$id  = $_SESSION['id'];
$ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id ='$id' ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($admin = $res->fetch_object()) {
?>
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
<?php
} ?>