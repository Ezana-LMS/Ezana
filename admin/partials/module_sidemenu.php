<div class="col-md-3">
    <?php
    $ret = "SELECT * FROM `ezanaLMS_Modules`  ORDER BY RAND()  LIMIT 8";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute(); //ok
    $res = $stmt->get_result();
    $cnt = 1;
    while ($module = $res->fetch_object()) {
    ?>
        <div class="col-md-12">
            <div class="card collapsed-card">
                <div class="card-header">
                    <a href="module?view=<?php echo $module->id; ?>">
                        <h3 class="card-title"><?php echo $cnt; ?>. <?php echo $module->name; ?></h3>
                        <div class="card-tools text-right">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </a>
                </div>

                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="module_notices?view=<?php echo $module->id; ?>">
                                Module Home
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="module_notices?view=<?php echo $module->id; ?>">
                                Notices & Memo
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="module_lecture_materials?view=<?php echo $module->id; ?>">
                                Lecture Materials
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="module_class_recordings?view=<?php echo $module->id; ?>">
                                Class Recordings
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="module_assignments?view=<?php echo $module->id; ?>">
                                Assignments
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="module_grades?view=<?php echo $module->id; ?>">
                                Grades
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="module_pastpapers?view=<?php echo $module->id; ?>">
                                Past Papers
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="module_student_groups?view=<?php echo $module->id; ?>">
                                Student Groups
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="module_enrollments?view=<?php echo $module->id; ?>">
                                Module Enrolments
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    <?php
        $cnt = $cnt + 1;
    } ?>
</div>