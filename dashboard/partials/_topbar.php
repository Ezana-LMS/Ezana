<header id="header">

    <h1 id="site-logo">
        <a href="dashboard.php">
            <img src="auth/images/logo.png" class="img-fluid" height="60" width="60" alt="Ezana LMS Logo" />
        </a>
    </h1>

    <a href="javascript:;" data-toggle="collapse" data-target=".top-bar-collapse" id="top-bar-toggle" class="navbar-toggle collapsed">
        <i class="fa fa-cog"></i>
    </a>

    <a href="javascript:;" data-toggle="collapse" data-target=".sidebar-collapse" id="sidebar-toggle" class="navbar-toggle collapsed">
        <i class="fa fa-reorder"></i>
    </a>

</header>
<nav id="top-bar" class="collapse top-bar-collapse">

    <ul class="nav navbar-nav pull-left">
        <li class="">
            <a href="dashboard.php">
                <i class="fa fa-home"></i>
                Home
            </a>
        </li>

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
                Faculties <span class="caret"></span>
            </a>

            <ul class="dropdown-menu" role="menu">
                <?php
                /* Get A List Of The Faculties */
                $ret = "SELECT * FROM `ezanaLMS_Faculties` ORDER BY `ezanaLMS_Faculties`.`name` ASC   ";
                $stmt = $mysqli->prepare($ret);
                $stmt->execute(); //ok
                $res = $stmt->get_result();
                $cnt = 1;
                while ($faculties = $res->fetch_object()) {
                ?>
                    <li><a href="faculty_dashboard.php?faculty=<?php echo $faculties->id;?>"><i class="fa fa-university"></i>&nbsp;&nbsp; <?php echo $faculties->name;?></a></li>
                <?php } ?>
                <li class="divider"></li>
                <li><a href="add_faculty.php"><i class="fa fa-plus"></i>&nbsp;&nbsp; Add Faculty</a></li>
            </ul>
        </li>

    </ul>

    <ul class="nav navbar-nav pull-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
                <i class="fa fa-user"></i>
                <?php echo $_SESSION["name"]; ?>
                <span class="caret"></span>
            </a>

            <ul class="dropdown-menu" role="menu">
                <li>
                    <a href="profile.php">
                        <i class="fa fa-user"></i>
                        &nbsp;&nbsp;My Profile
                    </a>
                </li>
                <li>
                    <a href="page-calendar.html">
                        <i class="fa fa-calendar"></i>
                        &nbsp;&nbsp;My Calendar
                    </a>
                </li>
                <li>
                    <a href="page-settings.html">
                        <i class="fa fa-cogs"></i>
                        &nbsp;&nbsp;Settings
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="logout.php">
                        <i class="fa fa-sign-out"></i>
                        &nbsp;&nbsp;Logout
                    </a>
                </li>
            </ul>
        </li>
    </ul>

</nav>