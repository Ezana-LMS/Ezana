<div class="col-md-3">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Menu</h3>
            </div>

            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="module?view=<?php echo $mod->code; ?>">
                            Module Home
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="module_notices?view=<?php echo $mod->id; ?>">
                            Notices & Memos
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="module_lecture_materials?view=<?php echo $mod->id; ?>">
                            Lecture Materials
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="module_class_recordings?view=<?php echo $mod->id; ?>">
                            Class Recordings
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="module_pastpapers?view=<?php echo $mod->id; ?>">
                            Past Papers
                        </a>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="module_assignments?view=<?php echo $mod->id; ?>">
                            Assignments
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="module_grades?view=<?php echo $mod->id; ?>">
                            Grades
                        </a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="module_student_groups?view=<?php echo $mod->id; ?>">
                            Student Groups
                        </a>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="module_enrollments?view=<?php echo $mod->id; ?>">
                            Module Enrollments
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>