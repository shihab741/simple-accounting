<?php 
    $this->load->view('admin/header'); 
?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-md-9">
                    <h2 class="page-header">Sheets</h2>
                </div>
                <div class="col-md-3" align="right">
                    <button type="button" name="add" id="add_data" class="btn btn-primary btn-sm page-header">Add new sheet</button>
                </div>
            </div>



                              <?php $message = $this->session->userdata('message');?>
                                <?php if ($message) : ?> 
                                    <?php echo $message; ?>
                                <?php $this->session->unset_userdata('message'); ?>
                              <?php endif; ?>


<span id="form_output"></span>


    <!-- /.panel-heading -->
    <div class="panel-body">
        <div class="table-responsive">
            <table id="item_data" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>

            </table>
        </div>
        <!-- /.table-responsive -->

    </div>
    <!-- /.panel-body -->


<div id="sheetModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="sheet_form">
                <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                   <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <span id="form_output_modal"></span>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" id="name" class="form-control" />
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="id" value="" />
                    <input type="hidden" name="button_action" id="button_action" value="insert" />
                    <input type="submit" name="submit" id="action" value="Add" class="btn btn-info" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>



      <script src="<?php echo base_url(); ?>vendor/datatable-assets/jquery.dataTables.min.js"></script>  
      <script src="<?php echo base_url(); ?>vendor/datatable-assets/dataTables.bootstrap.min.js"></script>          
      <link rel="stylesheet" href="<?php echo base_url(); ?>vendor/datatable-assets/dataTables.bootstrap.min.css" /> 

 <script type="text/javascript" language="javascript" >  
 $(document).ready(function(){  
      var items = $('#item_data').DataTable({  
           "processing":true,  
           "serverSide":true,  
           "order":[],  
           "ajax":{  
                url:"<?php echo base_url() . 'Super_admin/ajax_sheets'; ?>",  
                type:"POST"  
           },  
           "columnDefs":[  
                {  
                     "targets":[1],  
                     "orderable":false,  
                },  
           ], 
        "pageLength": 10 
      });  

    $('#add_data').click(function(){
        $('#sheetModal').modal('show');
        $('#sheet_form')[0].reset();
        $('#form_output').html('');
        $('#form_output_modal').html('');
        $('.modal-title').text('Add new sheet');
        $('#button_action').val('insert');
        $('#action').val('Add');
    });


    $(document).on('click', '.edit', function(){
        var id = $(this).attr("id");
        $('#form_output').html('');
        $.ajax({
            url:"<?php echo base_url(); ?>Super_admin/get_sheet_name",
            method:'POST',
            data:{id:id},
            dataType:'json',
            success:function(data)
            {
                $('#name').val(data.name);
                $('#id').val(id);
                $('#sheetModal').modal('show');
                $('#action').val('Save');
                $('.modal-title').text('Change sheet name');
                $('#button_action').val('update');
            }
        })
    });


    $('#sheet_form').on('submit', function(event){
        event.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url:"<?php echo base_url(); ?>Super_admin/add_or_update_sheet",
            method:"POST",
            data:form_data,
            dataType:"json",
            success:function(data)
            {
                $('#form_output').html(data.success);
                $('#sheet_form')[0].reset();
                $('#action').val('');
                $('.modal-title').text('');
                $('#button_action').val('');
                $('#item_data').DataTable().ajax.reload();
                $('#sheetModal').modal('hide');
            }
        })
    });

    $(document).on('click', '.delete', function(){
        var id = $(this).attr("id");
        if(confirm("Are you sure to delete this sheet and all of it's entries?"))
        {
            $.ajax({
                url:"<?php echo base_url(); ?>Super_admin/delete_sheet/"+id,
                mehtod:"get",
                dataType:"json",
                success:function(data)
                {
                    $('#form_output').html(data.success);
                    $('#item_data').DataTable().ajax.reload();
                }
            })
        }
        else
        {
            return false;
        }
    }); 



 });  
 </script> 

<?php $this->load->view('admin/footer'); ?>