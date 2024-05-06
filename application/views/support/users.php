<?php $this->load->view('layouts/support/head'); ?>
        <?php $this->load->view('layouts/support/top'); ?>
            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <?php $this->load->view('layouts/support/sidebar'); ?>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="userTBL" class="table table-bordered topics">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('mobile'); ?></th>
                                                    <th><?= $this->lang->line('email'); ?></th>
                                                    <th><?= $this->lang->line('mode'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($users)){
                                                        $i=1;
                                                        foreach ($users as $key) {
                                                            $sts = ($key['stat'] == 0)? $this->lang->line('active'):$this->lang->line('inactive');

                                                            $clr = ($key['stat'] == 0)?'success':'danger';
                                                     ?>
                                                    
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo $key['fullname']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $key['mobileNo']; ?>
                                                        </td>
                                                    <td><?php echo $key['email']; ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-boxed  badge-<?= $clr; ?>" style="cursor: pointer;" onclick="changeStatus(<?= $key['userId']; ?>, <?= $key['stat']; ?>);"><?= $sts; ?></span>
                                                    </td>
                                                </tr>
                                                <?php }
                                                 }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <?php $this->load->view('layouts/support/footer'); ?>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <?php $this->load->view('layouts/support/color'); ?>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        
        <?php $this->load->view('layouts/support/script'); ?>


<script type="text/javascript">

    $(document).ready(function () {
        $('#userTBL').DataTable();
    });

    function changeStatus(userId, stat){

        $.post('<?= base_url('support/users') ?>',{userId:userId, stat:stat},function(res){
            if(res.status == 'success'){
              alert(res.response);
            }else{
              alert(res.response);
            }
            location.reload();
        });
    }
</script>