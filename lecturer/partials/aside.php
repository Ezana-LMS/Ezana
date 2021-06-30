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
    } ?>
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
                    <a href="modules" class="nav-link">
                        <i class="nav-icon fas fa-chalkboard"></i>
                        <p>
                            Allocated Modules
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="important_dates" class="nav-link">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            Important Dates
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="time_table" class="nav-link">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Time Table
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>