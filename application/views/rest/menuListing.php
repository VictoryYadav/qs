<?php $this->load->view('layouts/admin/head'); ?>
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
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h3>Masters</h3>
                                        <?php
                                            if(!empty($menus)){
                                                foreach ($menus as $row) {
                                                    $url = base_url('restaurant').'/'.$row['pageUrl'];
                    
                                                    if($row['pageUrl'] == 'dashboard'){
                                                        $url = base_url('dashboard');
                                                    }
                                                    if($row['roleGroup'] == 1){
                                                 ?>
                                                 <div class="col-md-12">
                                                    <a href="<?= $url; ?>" target="_blank"><?= $row['LngName']; ?></a>
                                                 </div>
                                        <?php }  } } ?>
                                            </div>

                                            <div class="col-md-4">
                                                <h3>Operations</h3>
                                            
                                        <?php
                                            if(!empty($menus)){
                                                foreach ($menus as $row) {
                                                    $url = base_url('restaurant').'/'.$row['pageUrl'];
                    
                                                    if($row['pageUrl'] == 'dashboard'){
                                                        $url = base_url('dashboard');
                                                    }
                                                    if($row['roleGroup'] == 2){
                                                 ?><div class="col-md-12">
                                                        <a href="<?= $url; ?>" target="_blank"><?= $row['LngName']; ?></a>
                                                    </div>
                                        <?php }  } } ?>
                                            </div>

                                            <div class="col-md-4">
                                                <h3>Reports</h3>
                                            
                                        <?php
                                            if(!empty($menus)){
                                                foreach ($menus as $row) {
                                                    $url = base_url('restaurant').'/'.$row['pageUrl'];
                    
                                                    if($row['pageUrl'] == 'dashboard'){
                                                        $url = base_url('dashboard');
                                                    }
                                                    if($row['roleGroup'] == 3){
                                                 ?><div class="col-md-12">
                                                        <a href="<?= $url; ?>" target="_blank"><?= $row['LngName']; ?></a>
                                                    </div>
                                        <?php }  } } ?>
                                            </div>

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
        
    });

</script>