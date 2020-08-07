
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<script>
$(document).ready(function(){

  var date = new Date();

   $('.input-daterange').datepicker({
      todayBtn: 'linked',
      format: 'yyyy-mm-dd',
      autoclose: true
   });

});
</script> 












    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url(); ?>accounts_admin_assets/vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="<?php echo base_url(); ?>accounts_admin_assets/vendor/raphael/raphael.min.js"></script>
    <script src="<?php echo base_url(); ?>accounts_admin_assets/vendor/morrisjs/morris.min.js"></script>
    <script src="<?php echo base_url(); ?>accounts_admin_assets/data/morris-data.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url(); ?>accounts_admin_assets/dist/js/sb-admin-2.js"></script>

</body>

</html>
