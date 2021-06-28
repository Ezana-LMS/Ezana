<!-- Lecturer Modal Allocation -->
<div class="modal fade" id="assign_lect">
    <div class="modal-dialog  modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Fill All Required Values </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" role="form">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="">Lecturer Number</label>
                                <select class='form-control basic' id="LecNumber" onchange="getLecturerDetails(this.value);" name="">
                                    <option selected>Select Lecturer Number</option>
                                    <?php
                                    $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE faculty_id = '$mod->faculty_id'  ";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute(); //ok
                                    $res = $stmt->get_result();
                                    while ($lec = $res->fetch_object()) {
                                    ?>
                                        <option><?php echo $lec->number; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Lecturer Name</label>
                                <input type="hidden" id="lecID" readonly required name="lec_id" class="form-control">
                                <input type="text" id="lecName" readonly required name="lec_name" class="form-control">
                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                <input type="hidden" readonly readonly value="<?php echo $mod->name; ?>" required name="module_name" class="form-control">
                                <input class='form-control' value="<?php echo $mod->code; ?>" type="hidden" name="module_code">
                                <input type="hidden" required name="faculty" value="<?php echo $mod->faculty_id; ?>" class="form-control">
                            </div>
                            <?php
                            /* Persisit Academic Settings */
                            $ret = "SELECT * FROM `ezanaLMS_AcademicSettings` WHERE status = 'Current' ";
                            $stmt = $mysqli->prepare($ret);
                            $stmt->execute(); //ok
                            $res = $stmt->get_result();
                            while ($academic_settings = $res->fetch_object()) {
                            ?>
                                <div class="form-group col-md-6">
                                    <label for="">Academic Year </label>
                                    <input type="text" readonly value="<?php echo $academic_settings->current_academic_year; ?>" required name="academic_year" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Semester</label>
                                    <input type="text" readonly value="<?php echo $academic_settings->current_semester; ?>" required name="semester" class="form-control">
                                </div>

                            <?php
                            } ?>

                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" name="assign_module" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- End Lecturer Modal Allocation -->

<!-- Add Guest Lecturer Module Allocation -->
<div class="modal fade" id="assign_guest_lect">
    <div class="modal-dialog  modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Fill All Required Values </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" role="form">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="">Lecturer Number</label>
                                <select class='form-control basic' id="lecNumber" onchange="getGuestLec(this.value);" name="">
                                    <option selected>Select Lecturer Number</option>
                                    <?php
                                    $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE faculty_id = '$course->faculty_id'  ";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute(); //ok
                                    $res = $stmt->get_result();
                                    while ($lec = $res->fetch_object()) {
                                    ?>
                                        <option><?php echo $lec->number; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Lecturer Name</label>
                                <input type="hidden" id="LecID" readonly required name="lec_id" class="form-control">
                                <input type="text" id="LecName" readonly required name="lec_name" class="form-control">
                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                <input type="hidden" readonly readonly value="<?php echo $mod->name; ?>" required name="module_name" class="form-control">
                                <input class='form-control' value="<?php echo $mod->code; ?>" type="hidden" name="module_code">
                                <input type="hidden" required name="faculty" value="<?php echo $course->faculty_id; ?>" class="form-control">
                            </div>
                            <hr>
                            <?php
                            /* Persisit Academic Settings */
                            $ret = "SELECT * FROM `ezanaLMS_AcademicSettings` WHERE status = 'Current' ";
                            $stmt = $mysqli->prepare($ret);
                            $stmt->execute(); //ok
                            $res = $stmt->get_result();
                            while ($academic_settings = $res->fetch_object()) {
                            ?>
                                <div class="form-group col-md-6">
                                    <label for="">Academic Year </label>
                                    <input type="text" readonly value="<?php echo $academic_settings->current_academic_year; ?>" required name="academic_year" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Semester</label>
                                    <input type="text" readonly value="<?php echo $academic_settings->current_semester; ?>" required name="semester" class="form-control">
                                </div>

                            <?php
                            } ?>

                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" name="assign_guest_lec" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Guest Lecturer Module Allocation -->