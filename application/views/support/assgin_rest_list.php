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
                                                    <th><?= $this->lang->line('country'); ?></th>
                                                    <th><?= $this->lang->line('city'); ?></th>
                                                    <th>Access</th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($rests)){
                                                        $i=1;

                                                        $type = '';
                                                        foreach ($rests as $key) {
                                                            $EID = $key['EID'];
                                                            $CatgId = $key['CatgId'];
                                                            
                                                            if($key['suppUserId'] > 0){
                                                                $type = 'Main';
                                                            }

                                                            if($key['suppUserIdAlt'] > 0){
                                                                $type = 'Alternate';
                                                            }

                                                            if($key['suppUserId'] > 0 && $key['suppUserIdAlt'] > 0){
                                                                $type = 'Both';
                                                            }
                                                     ?>
                                                    
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><a href="<?= base_url("support/rest_login/$EID/$CatgId"); ?>"><?= $key['Name']; ?></a>
                                                    </td>
                                                    <td><?= $key['country_name']; ?>
                                                    </td>
                                                    <td><?= $key['city_name']; ?></a>
                                                    </td>
                                                    <td>
                                                        <?= $type; ?>
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