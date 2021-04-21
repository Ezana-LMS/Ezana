<div class="col-md-3">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Menu</h3>
                <div class="card-tools text-right">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_faculty_departments.php?view=<?php echo $faculty->id; ?>">
                            Departments
                        </a>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_faculty_courses.php?view=<?php echo $faculty->id; ?>">
                            Courses
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_faculty_modules.php?view=<?php echo $faculty->id; ?>">
                            Modules
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_school_calendar.php?view=<?php echo $faculty->id; ?>">
                            Important Dates
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_faculty_lects.php?view=<?php echo $faculty->id; ?>">
                            Lecturers
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_faculty_students.php?view=<?php echo $faculty->id; ?>">
                            Students
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>