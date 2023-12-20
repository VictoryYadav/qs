<?php $this->load->view('layouts/admin/head'); ?>
<style>
    .topics tr {
     line-height: 8px !important; 
     font-size: 11px;
 }

 .custom-control {
    position: relative;
    display: block;
     min-height: 0rem; 
    padding-left: 1.5rem;
}
</style>
        <?php $this->load->view('layouts/admin/top'); ?>
            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <?php $this->load->view('layouts/admin/sidebar'); ?>
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
                                            <table id="disable_users" class="table table-bordered topics">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('userName'); ?></th>
                                                    <th><?= $this->lang->line('mobile'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($users)){
                                                        $i=1;
                                                        foreach ($users as $key) {
                                                     ?>
                                                    
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo $key['FName'].' '.$key['LName']; ?></td>
                                                    <td><?php echo $key['MobileNo']; ?></td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck1" onchange="enableDisable(<?= $key['RUserId'];?>, this);" <?= ($key['Stat'] == 3 ? 'checked' : '');?>>
                                                            <label class="custom-control-label" for="customCheck1" style="font-size: 11px;font-weight: normal;"><?= ($key['Stat'] == 3) ? $this->lang->line('disabled') : $this->lang->line('enabled');?></label>
                                                        </div>     
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

                <?php $this->load->view('layouts/admin/footer'); ?>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <?php $this->load->view('layouts/admin/color'); ?>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        
        <?php $this->load->view('layouts/admin/script'); ?>


<script type="text/javascript">
    $(document).ready(function () {
        $('#disable_users').DataTable();
    });

    function enableDisable(id, input) {
    console.log(id);
    if ($(input).prop('checked') == true) {
        console.log("Disable User");

        $.post('<?= base_url('restaurant/user_disable') ?>',{type:'disable',id:id},function(res){
            if(res.status == 'success'){
              alert(res.response);
              location.reload();
            }else{
              alert(res.response);
            }
        });

    }else{
        console.log("Enable User");
        $.post('<?= base_url('restaurant/user_disable') ?>',{type:'enable',id:id},function(res){
            if(res.status == 'success'){
              alert(res.response);
              location.reload();
            }else{
              alert(res.response);
            }
        });
    }
}

</script>