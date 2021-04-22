<div class="col-md-3">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <a href="edu_admn_course.php?view=<?php echo $course->id; ?>">
                    <h3 class="card-title"><?php echo $course->name; ?> </h3>
                    <div class="card-tools text-right">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"></i>
                        </button>
                    </div>
                </a>
            </div>

            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_course_modules.php?view=<?php echo $course->id; ?>">
                            Modules
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_course_memos.php?view=<?php echo $course->id; ?>">
                            Memos & Notices
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_module_allocations.php?view=<?php echo $course->id; ?>">
                            Modules Allocations
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_timetables.php?view=<?php echo $course->id; ?>">
                            Time Table
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_enrollments.php?view=<?php echo $course->id; ?>">
                            Enrolled Students
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>