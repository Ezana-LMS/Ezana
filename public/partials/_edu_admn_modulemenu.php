<div class="col-md-3">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?php echo $mod->name; ?></h3>
                <div class="card-tools text-right">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_module.php?view=<?php echo $mod->id; ?>">
                            Module Home
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_module_notices.php?view=<?php echo $mod->id; ?>">
                            Notices & Memos
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_pastpapers.php?view=<?php echo $mod->id; ?>">
                            Past Papers
                        </a>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_course_materials.php?view=<?php echo $mod->id; ?>">
                            Reading Materials
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_class_recordings.php?view=<?php echo $mod->id; ?>">
                            Class Recordings
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_module_assignments.php?view=<?php echo $mod->id; ?>">
                            Assignments
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_student_groups.php?view=<?php echo $mod->id; ?>">
                            Student Groups
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_grades.php?view=<?php echo $mod->id; ?>">
                            Grades
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="edu_admn_module_enrollments.php?view=<?php echo $mod->id; ?>">
                            Module Enrollments
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>