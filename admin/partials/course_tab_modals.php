<?php
/*
 * Created on Wed Jun 23 2021
 *
 * The MIT License (MIT)
 * Copyright (c) 2021 MartDevelopers Inc
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 * TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */


/* Load Logged In User Session */
$id = $_SESSION['id'];
$ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id = '$id' ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($admin = $res->fetch_object()) {
?>
    <!-- Add Memo Modal -->
    <div class="modal fade" id="add_memo">
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
                                    <label for="">Upload Memo (PDF Or Docx)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input name="attachments" accept=".pdf, .docx, .doc" type="file" class="custom-file-input">
                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                            <input type="hidden" required name="course_id" value="<?php echo $course->id; ?>" class="form-control">
                                            <input type="hidden" required name="course_name" value="<?php echo $course->name; ?>" class="form-control">
                                            <input type="hidden" required name="faculty" value="<?php echo $course->faculty_id; ?>" class="form-control">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Created By</label>
                                    <input type="text" readonly value="<?php echo $admin->name; ?>" required name="created_by" class="form-control">
                                </div>
                                <div style="display:none" class="form-group col-md-6">
                                    <label for="">Type</label>
                                    <select class='form-control basic' name="type">
                                        <option selected>Memo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputPassword1">Type Memo</label>
                                    <textarea name="course_memo" rows="10" class="form-control Summernote"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" name="add_memo" class="btn btn-primary">Add Memo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add Memo -->

    <!-- Add Module -->
    <div class="modal fade" id="add_module">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Fill All Required Values </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add Module Form -->
                    <form method="post" enctype="multipart/form-data" role="form">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="">Module Name</label>
                                    <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                    <input type="hidden" readonly value="<?php echo $course->id; ?>" required name="course_id" class="form-control">
                                    <input type="hidden" readonly value="<?php echo $course->faculty_id; ?>" required name="faculty_id" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Module Number / Code</label>
                                    <input type="text" readonly required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Course Name</label>
                                    <input type="text" readonly value="<?php echo $course->name; ?>" required name="course_name" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="">Teaching Duration (Hours & Minutes)</label>
                                    <input type="text" required name="course_duration" class="form-control" id="exampleInputEmail1">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Number Of Lectures Per Week </label>
                                    <input type="number" required name="lectures_number" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Module CAT Weight Percentage (%)</label>
                                    <input type="number" min="1" max="100" required name="cat_weight_percentage" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Module End Exam Weight Percentage (%)</label>
                                    <input type="number" min="1" max="100" required name="exam_weight_percentage" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputPassword1">Module Details</label>
                                    <textarea required name="details" rows="10" class="form-control Summernote"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" name="add_module" class="btn btn-primary">Add Module</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add Module -->

    <!-- Lecturer Modal Allocation -->
    <div class="modal fade" id="add_module_allocation">
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
                                    <input type="hidden" id="lecID" readonly required name="lec_id" class="form-control">
                                    <input type="text" id="lecName" readonly required name="lec_name" class="form-control">
                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                    <input type="hidden" required name="faculty" value="<?php echo $course->faculty_id; ?>" class="form-control">
                                </div>
                                <hr>
                                <div class="form-group col-md-6">
                                    <label for="">Module Name</label>
                                    <select class='form-control basic' id="ModuleCode" onchange="OptimizedModuleDetails(this.value);" name="module_code">
                                        <option selected>Select Module Code </option>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_Modules`  WHERE ass_status = '0' AND course_id = '$course->id'  ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        while ($mod = $res->fetch_object()) {
                                        ?>
                                            <option><?php echo $mod->code; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="">Module Name</label>
                                    <input type="text" readonly id="ModuleName" required name="module_name" class="form-control">
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
    <div class="modal fade" id="add_guest_lec_module_allocation">
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
                                    <input type="hidden" required name="faculty" value="<?php echo $course->faculty_id; ?>" class="form-control">
                                </div>
                                <hr>
                                <div class="form-group col-md-6">
                                    <label for="">Module Name</label>
                                    <select class='form-control basic' id="moduleCode" onchange="guestLecModule(this.value);" name="module_code">
                                        <option selected>Select Module Code </option>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_Modules`  WHERE  course_id = '$course->id'  ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        while ($mod = $res->fetch_object()) {
                                        ?>
                                            <option><?php echo $mod->code; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="">Module Name</label>
                                    <input type="text" readonly id="moduleName" required name="module_name" class="form-control">
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
                            <button type="submit" name="assign_guest_lec" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Guest Lecturer Module Allocation -->

    <!-- Add Class To Time Table -->
    <div class="modal fade" id="add_class">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Fill All Required Values </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add Time Table Form -->
                    <form method="post" enctype="multipart/form-data" role="form">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <!-- Hidden values -->
                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                    <input type="hidden" required name="faculty" value="<?php echo $course->faculty_id; ?>" class="form-control">
                                    <input type="hidden" required name="course_id" value="<?php echo $course->id; ?>" class="form-control">
                                    <input type="hidden" required name="course_code" value="<?php echo $course->code; ?>" class="form-control">
                                    <input type="hidden" required name="course_name" value="<?php echo $course->name; ?>" class="form-control">
                                    <!-- Ajax This Shit Real Quick-->
                                    <label for="">Module Code</label>
                                    <select class='form-control basic' id="AllocatedModuleCode" onchange="getAllocatedModuleDetails(this.value);" name="module_code">
                                        <option selected>Select Module Code</option>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns` WHERE course_id = '$course->id'  ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        while ($module_allocations = $res->fetch_object()) {
                                        ?>
                                            <option><?php echo $module_allocations->module_code; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Module Name</label>
                                    <input type="text" readonly id="AllocatedModuleName" required name="module_name" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Lecturer Name</label>
                                    <input type="text" readonly id="AllocatedLecturerName" required name="lecturer" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="">Class Day</label>
                                    <select class='form-control basic' name="day">
                                        <option selected>Select Day</option>
                                        <option>Sunday</option>
                                        <option>Monday</option>
                                        <option>Tuesday</option>
                                        <option>Wednesday</option>
                                        <option>Thursday</option>
                                        <option>Friday</option>
                                        <option>Saturday</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Time</label>
                                    <input type="text" required name="time" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Room</label>
                                    <input type="text" required name="room" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputPassword1">Class Link <small class="text-danger">If Its Virtual Class </small></label>
                                    <input type="text" name="link" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" name="add_class" class="btn btn-primary">Create Class</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add Class To Time Table -->

    <!-- Add Enrollments  Modal -->
    <div class="modal fade" id="add_enrollment">
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
                            <div class="row" style="display:none">
                                <div class="form-group col-md-6">
                                    <label for=""> Name</label>
                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                    <input type="hidden" required name="course_id" value="<?php echo $course->id; ?>" class="form-control">
                                    <input type="hidden" required name="faculty" value="<?php echo $course->faculty_id; ?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Enroll Code</label>
                                    <input type="text" readonly required name="code" value="<?php echo $a . $b; ?>" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="">Student Admission Number</label>
                                    <select class='form-control basic' id="StudentAdmn" onchange="getStudentDetails(this.value);" name="student_adm">
                                        <option selected>Select Student Admission Number</option>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_Students` WHERE faculty_id = '$course->faculty_id'  ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        while ($std = $res->fetch_object()) {
                                        ?>
                                            <option><?php echo $std->admno; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Student Name</label>
                                    <input type="text" readonly id="StudentName" readonly required name="student_name" class="form-control">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="">Course Code</label>
                                    <input type="text" readonly required name="course_code" value="<?php echo $course->code; ?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Course Name</label>
                                    <input type="text" readonly required value="<?php echo $course->name; ?>" name="course_name" class="form-control">
                                </div>
                                <hr>
                                <div class="form-group col-md-6">
                                    <label for="">Module Code</label>
                                    <select class='form-control basic' id="EnrollmentModuleCode" onchange="EnrollmentModuleDetails(this.value);" name="module_code">
                                        <option selected>Select Module Code </option>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE course_id = '$course->id'   ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        while ($mod = $res->fetch_object()) {
                                        ?>
                                            <option><?php echo $mod->code; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Module Name</label>
                                    <input type="text" readonly id="EnrollmentModuleName" required name="module_name" class="form-control">
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
                                        <label for="">Academic Year Enrolled</label>
                                        <input type="text" readonly value="<?php echo $academic_settings->current_academic_year; ?>" required name="academic_year_enrolled" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">Semester Enrolled</label>
                                        <input type="text" readonly value="<?php echo $academic_settings->current_semester; ?>" required name="semester_enrolled" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">Semester Start Date</label>
                                        <input type="text" readonly value="<?php echo $academic_settings->start_date; ?>" required name="semester_start" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">Semester End Date</label>
                                        <input type="text" readonly value="<?php echo $academic_settings->end_date; ?>" required name="semester_end" class="form-control">
                                    </div>
                                <?php
                                } ?>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" name="add_enroll" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Enrollments Modal -->
<?php
} ?>