<?php $this->load->view('admin/header'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">Edit entry for <?php echo $sheetName->name; ?></h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>
                              <?php $message = $this->session->userdata('message');?>
                                <?php if ($message) : ?> 
                                <div class="alert alert-success"><?php echo $message; ?></div>
                                <?php $this->session->unset_userdata('message'); ?>
                              <?php endif; ?>

<?php echo form_open('super_admin/saving_edited_entry'); ?>

                            <fieldset>
                                <input type="hidden" name="id" value="<?php echo $entry->id; ?>" >
                                <input type="hidden" name="sheet_id" value="<?php echo $entry->sheet_id; ?>" >

                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" name="description" class="form-control" value="<?php echo $entry->description; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="number" step="any" name="amount" class="form-control" value="<?php echo $entry->amount; ?>">
                                </div>
                                <div class="form-group">
                                            <label>Debit/Credit</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios1" value="0" <?php echo($entry->type == 0) ? 'checked' : '' ?>>Debit
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="type" id="optionsRadios2" value="1" <?php echo($entry->type == 1) ? 'checked' : '' ?>>Credit
                                                </label>
                                            </div>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-primary btn-block">Save updated information</button>
                            </fieldset>

<?php echo form_close(); ?>

<?php echo form_open('super_admin/delete_entry'); ?>
    <input type="hidden" name="id" value="<?php echo $entry->id; ?>" >
    <input type="hidden" name="sheet_id" value="<?php echo $entry->sheet_id; ?>" >
    <button type="submit" class="btn btn-danger btn-block" style="margin-top: 5px;" onclick="return checkDeletion();">Delete this entry</button>
<?php echo form_close(); ?>

<?php $this->load->view('admin/footer'); ?>