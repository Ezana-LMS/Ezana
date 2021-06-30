<div class="col-md-3">
    <?php
    $ret = "SELECT * FROM `ezanaLMS_Courses`  ORDER BY RAND()  LIMIT 8";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute(); //ok
    $res = $stmt->get_result();
    $cnt = 1;
    while ($course = $res->fetch_object()) {
    ?>
        <div class="col-md-12">
            <div class="card collapsed-card">
                <div class="card-header">
                    <a href="course.php?view=<?php echo $course->id; ?>">
                        <h3 class="card-title"><?php echo $cnt; ?>. <?php echo $course->name; ?></h3>
                        <div class="card-tools text-right">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </a>
                </div>

                <div class="card-body">
                    <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="course_modules.php?view=<?php echo $course->id; ?>">
                                Modules
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="course_memos.php?view=<?php echo $course->id; ?>">
                                Memos & Notices
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="module_allocations.php?view=<?php echo $course->id; ?>">
                                Modules Allocations
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="timetables.php?view=<?php echo $course->id; ?>">
                                Time Table
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="enrollments.php?view=<?php echo $course->id; ?>">
                                Enrolled Students
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