<?php $this->load->view('admin/header'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="page-header">Dashboard</h2>
                </div>
                <!-- /.col-lg-12 -->
            </div>
                              <?php $message = $this->session->userdata('message');?>
                                <?php if ($message) : ?> 
                                <div class="alert alert-success"><?php echo $message; ?></div>
                                <?php $this->session->unset_userdata('message'); ?>
                              <?php endif; ?>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Overview
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Sheet / Account</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($details as $v_details) : ?>
                                        <tr>
                                            <td><a href="<?php echo base_url(); ?>super_admin/get_amounts/<?php echo $v_details['id']; ?>"><?php echo $v_details['name']; ?></a></td>
                                            <td><?php echo $v_details['balance']; ?></td>
                                        </tr>

                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>


<?php $this->load->view('admin/footer'); ?>