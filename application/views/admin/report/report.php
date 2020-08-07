<?php 
    $this->load->view('admin/header'); 
?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-9">
                    <h2 class="page-header"><?php echo $sheetName->name; ?> <small>(<?php echo $dateForHeading; ?>)</small></h2>
                </div>
                <div class="col-md-3" align="right">
                     <?php echo form_open('super_admin/new_entry'); ?>
                        <input type="hidden" name="sheet_id" value="<?php echo $sheetID; ?>">
                        <button type="submit" class="btn btn-primary page-header">Add new</button>
                     <?php echo form_close(); ?>
                </div>
            </div>

                              <?php $message = $this->session->userdata('message');?>
                                <?php if ($message) : ?> 
                                <div class="alert alert-success"><?php echo $message; ?></div>
                                <?php $this->session->unset_userdata('message'); ?>
                              <?php endif; ?>



    <!-- /.panel-heading -->
    <div class="panel-body">

    <div class="well">
        <div class="row">
            <?php echo form_open('super_admin/get_filtered_amounts'); ?>
                <input type="hidden" name="sheet_id" value="<?php echo $sheetID; ?>">
                <div class="col-md-8">
                   <div class="input-group input-daterange">
                        <input type="text" name="from_date" id="from_date" readonly class="form-control" />
                        <div class="input-group-addon">to</div>
                        <input type="text"  name="to_date" id="to_date" readonly class="form-control" />         
                   </div>          
                </div>
                <div class="col-md-1">
                    <br>
                </div>
                <div class="col-md-3">
                   <button type="submit" class="btn btn-info btn-sm">Filter</button>
                   <a href="<?php echo base_url(); ?>super_admin/get_amounts/<?php echo $sheetID; ?>" class="btn btn-warning btn-sm">This month only</a>       
                </div>
            <?php echo form_close(); ?>
        </div>    
    </div> 


        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                
                <?php 
                    $totalDebit = 0;
                    $totalCredit = 0;
                    $presentBalance = $initial_amount;
                ?>

                    <tr>
                        <td>---</td>
                        <td>Brought forward (B/F)</td>
                        <td>---</td>
                        <td>---</td>
                        <td><?php echo number_format($initial_amount, 2); ?></td>
                    </tr>

                    <?php foreach($entries as $entry) : ?>
                    <tr>
                        
                        <td><a href="<?php echo base_url(); ?>/super_admin/edit_entry/<?php echo $entry->id; ?>">
                            <?php 
                                $date = date_create($entry->date);
                                $formatedDate = date_format($date, "d, M, Y");
                                echo $formatedDate;
                            ?>     
                        </a></td>



                    <td><?php echo $entry->description; ?></td>
                    
                        <?php if($entry->type == 0) : ?>

                            <td><?php echo number_format($entry->amount, 2); ?></td>
                            <td>....</td>
                            <?php 
                                $totalDebit = $totalDebit + $entry->amount; 
                                $presentBalance = $presentBalance - $entry->amount;
                            ?>

                        <?php else : ?>

                            <td>....</td>
                            <td><?php echo number_format($entry->amount, 2); ?></td>
                            <?php 
                                $totalCredit = $totalCredit + $entry->amount; 
                                $presentBalance = $presentBalance + $entry->amount;
                            ?>
                            
                        <?php endif; ?>

                        <td><?php echo number_format($presentBalance, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr style="font-weight: bold;">
                        <td></td>
                        <td></td>
                        <td>Total debit</td>
                        <td>Total credit</td>
                        <td>Present balance</td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td></td>
                        <td></td>
                        <td><?php echo number_format($totalDebit, 2); ?></td>
                        <td><?php echo number_format($totalCredit, 2); ?></td>
                        <td><?php echo number_format($presentBalance, 2); ?></td>
                    </tr>

                </tbody>
            </table>
        </div>
        <!-- /.table-responsive -->
                <?php echo form_open('super_admin/excel_report'); ?>
                    <input type="hidden" name="sheet_id" value="<?php echo $sheetID; ?>">
                    <input type="hidden" name="from_date" value="<?php echo $fromDate; ?>">
                    <?php if(isset($toDate)): ?>
                        <input type="hidden" name="to_date" value="<?php echo $toDate; ?>">
                    <?php endif; ?>
                    <input type="hidden" name="fileNamePrefix" value="<?php echo $sheetName->name; ?>_<?php echo $dateForHeading; ?>_generated_on_">
                    <input type="hidden" name="with_actual" value="no">
                    <button type="submit" class="pull-right btn btn-warning btn-large" style="margin-right:40px"><i class="fa fa-file-excel-o"></i> Export to Excel</button>
                <?php echo form_close(); ?>
    </div>
    <!-- /.panel-body -->







<?php $this->load->view('admin/footer'); ?>