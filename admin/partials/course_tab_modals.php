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
                                    <label for="">Number Of Lectures Per Week</label>
                                    <input type="text" required name="lectures_number" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Module CAT Weight Percentage</label>
                                    <input type="text" required name="cat_weight_percentage" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Module End Exam Weight Percentage</label>
                                    <input type="text" required name="exam_weight_percentage" class="form-control">
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
<?php
} ?>