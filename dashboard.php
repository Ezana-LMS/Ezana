<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('dashboard/partials/_analytics.php');
require_once('dashboard/partials/_head.php');

?>

<body>

	<div id="wrapper">

		<?php
		require_once('dashboard/partials/_topbar.php');
		?>
		<!-- /#top-bar -->

		<!-- Static Sidebar -->
		<div id="sidebar-wrapper" class="collapse sidebar-collapse">

			<div id="search">
				<form action="search_results.php" method="GET">
					<input class="form-control input-sm" type="text" name="search" placeholder="Enter Faculty Name" />
					<button type="search" name="query" id="search-btn" class="btn"><i class="fa fa-search"></i></button>
				</form>
			</div>

			<nav id="sidebar">

				<ul id="main-nav" class="open-active">

					<li class="active">
						<a href="index-2.html">
							<i class="fa fa-dashboard"></i>
							Main Dashboard
						</a>
					</li>
					<li>
						<a href="index-2.html">
							<i class="fa fa-university"></i>
							Faculties
						</a>
					</li>
					<li>
						<a href="index-2.html">
							<i class="fa fa-building"></i>
							Departments
						</a>
					</li>
					<li>
						<a href="index-2.html">
							<i class="fa fa-chalkboard-teacher"></i>
							Courses
						</a>
					</li>
					<li>
						<a href="index-2.html">
							<i class="fas fa-chalkboard"></i>
							Modules
						</a>
					</li>
					<li>
						<a href="index-2.html">
							<i class="fas  fa-user-tie"></i>
							Lecturers
						</a>
					</li>
					<li>
						<a href="index-2.html">
							<i class="fas fa-user-graduate"></i>
							Students
						</a>
					</li>

				</ul>
			</nav> <!-- #sidebar -->

		</div>
		<!-- Static Sidebar -->

		<div id="content">

			<div id="content-header">
				<h1>Main Dashboard</h1>
			</div> <!-- #content-header -->

			<div id="content-container">
				<div class="row">
					<div class="col-md-3 col-sm-6">
						<a href="javascript:;" class="dashboard-stat primary">
							<div class="visual">
								<i class="fas fa-university"></i>
							</div>
							<div class="details">
								<span class="content">Faculties</span>
								<span class="value"><?php echo $faculties_count; ?></span>
							</div>
						</a>
					</div>

					<div class="col-md-3 col-sm-6">

						<a href="javascript:;" class="dashboard-stat secondary">
							<div class="visual">
								<i class="fas fa-building"></i>
							</div>

							<div class="details">
								<span class="content">Departments</span>
								<span class="value"><?php echo $departments; ?></span>
							</div>
							<i class="fa fa-play-circle more"></i>
						</a>
					</div>

					<div class="col-md-3 col-sm-6">

						<a href="javascript:;" class="dashboard-stat tertiary">
							<div class="visual">
								<i class="fa fa-chalkboard-teacher"></i>
							</div>

							<div class="details">
								<span class="content">Courses</span>
								<span class="value"><?php echo $courses; ?></span>
							</div>
						</a>
					</div>

					<div class="col-md-3 col-sm-6">

						<a href="javascript:;" class="dashboard-stat">
							<div class="visual">
								<i class="fas fas fa-chalkboard"></i>
							</div>

							<div class="details">
								<span class="content">Modules</span>
								<span class="value"><?php echo $modules; ?></span>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	require_once("dashboard/partials/_footer.php");
	require_once('dashboard/partials/_scripts.php');
	?>
</body>

</html>