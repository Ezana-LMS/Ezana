<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?php
    /* Persisit System Settings On Brand */
    $ret = "SELECT * FROM `ezanaLMS_Settings` ";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute(); //ok
    $res = $stmt->get_result();
    while ($sys = $res->fetch_object()) {
    ?>
        <a href="" class="brand-link">
            <img src="../Data/SystemLogo/<?php echo $sys->logo; ?>" alt="<?php echo $sys->sysname; ?> Logo" class="brand-image img-circle elevation-3">
            <span class="brand-text font-weight-light"><?php echo $sys->sysname; ?></span>
        </a>
    <?php
    }
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
                        <a href="dashboard" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="faculty?view=<?php echo $admin->school_id; ?>" class="nav-link">
                            <i class="nav-icon fas fa-university"></i>
                            <p>
                                Faculty
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="departments?view=<?php echo $admin->school_id; ?>" class="nav-link">
                            <i class="nav-icon fas fa-building"></i>
                            <p>
                                Departments
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="courses?view=<?php echo $admin->school_id; ?>" class="nav-link">
                            <i class="nav-icon fas fas fa-chalkboard"></i>
                            <p>
                                Courses
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="modules?view=<?php echo $admin->school_id; ?>" class="nav-link">
                            <i class="nav-icon fas fa-cubes"></i>
                            <p>
                                Modules
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="lecturers?view=<?php echo $admin->school_id; ?>" class="nav-link">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>
                                Lecturers
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="students?view=<?php echo $admin->school_id; ?>" class="nav-link">
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
                                <a href="reports?view=<?php echo $admin->school_id; ?>" class="nav-link">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Reports</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="system_batch_addition?view=<?php echo $admin->school_id; ?>" class="nav-link">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Batch Addition</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="system_bulk_delete?view=<?php echo $admin->school_id; ?>" class="nav-link">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Delete Operations</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="system_settings?view=<?php echo $admin->school_id; ?>" class="nav-link">
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
    }
    ?>
</aside>