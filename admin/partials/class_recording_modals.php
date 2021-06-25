<!-- Update Clip Link Modal -->
<div class="modal fade" id="update-clip-link-<?php echo $cr->id; ?>">
    <div class="modal-dialog  modal-lg">
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
                            <div class="form-group col-md-12">
                                <label for="">Class Name</label>
                                <input type="text" value="<?php echo $cr->class_name; ?>" required name="class_name" class="form-control" id="exampleInputEmail1">
                                <input type="hidden" required name="id" value="<?php echo $cr->id; ?>" class="form-control">
                                <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                            </div>
                            <div class="form-group col-md-6" style="display:none">
                                <label for="">Lecturer Name</label>
                                <input type="text" value="<?php echo $cr->lecturer_name; ?>" required name="lecturer_name" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="">Class External Link <small class="text-danger">If In YouTube, Vimeo, Google Drive, etc</small> *Recomended</label>
                                <input type="text" name="external_link" value="<?php echo $cr->external_link; ?>" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="exampleInputPassword1">Description | Transcription</label>
                                <textarea type="text" rows="10" name="details" class="form-control Summernote"><?php echo $cr->details; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" name="add_class_recording_link" class="btn btn-primary">Update Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End  Modal -->

<!-- Update Clip Recording Modal -->
<div class="modal fade" id="update-clip-recording-<?php echo $cr->id; ?>">
    <div class="modal-dialog  modal-lg">
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
                            <div class="form-group col-md-12">
                                <label for="">Class Name</label>
                                <input type="text" value="<?php echo $cr->class_name; ?>" required name="class_name" class="form-control" id="exampleInputEmail1">
                                <input type="hidden" required name="id" value="<?php echo $cr->id; ?>" class="form-control">
                                <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                            </div>
                            <div class="form-group col-md-6" style="display: none;">
                                <label for="">Lecturer Name</label>
                                <input type="text" value="<?php echo $cr->lecturer_name; ?>" required name="lecturer_name" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="exampleInputFile">Upload Video</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input name="video" required accept="mp4,avi,3gp,mov,mpeg" type="file" class="custom-file-input" id="exampleInputFile">
                                        <label class="custom-file-label" for="exampleInputFile">Choose Video File</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="exampleInputPassword1">Description</label>
                                <textarea type="text" rows="10" name="details" class="form-control Summernote"><?php echo $cr->details; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" name="update_class_recording" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Clip Recording  Modal -->

<!-- Delete Modal -->
<div class="modal fade" id="delete-<?php echo $cr->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center text-danger">
                <h4>Delete <?php echo $cr->class_name; ?> Class Recording ?</h4>
                <br>
                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                <a href="module_class_recordings?delete=<?php echo $cr->id; ?>&view=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
            </div>
        </div>
    </div>
</div>