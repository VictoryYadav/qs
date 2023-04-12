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

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18"><?php echo $title; ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4 align-self-center">
                                                <div class="icon-info">
                                                    <i class="mdi mdi-diamond text-warning"></i>
                                                </div> 
                                            </div>
                                            <div class="col-8 align-self-center text-center">
                                                <div class="ml-2 text-right">
                                                    <p class="mb-1 text-muted font-size-13">Projects</p>
                                                    <h4 class="mt-0 mb-1 font-20">35</h4>                                                                                                                                           
                                                </div>
                                            </div>                    
                                        </div>
                                        <div class="progress mt-2" style="height:3px;">
                                            <div class="progress-bar progress-animated  bg-warning" role="progressbar" style="max-width: 55%;" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4 align-self-center">
                                                <div class="icon-info">
                                                    <i class="mdi mdi-account-multiple text-purple"></i>
                                                </div> 
                                            </div>
                                            <div class="col-8 align-self-center text-center">
                                                <div class="ml-2 text-right">
                                                    <p class="mb-1 text-muted font-size-13">Member</p>
                                                    <h4 class="mt-0 mb-1 font-20">12</h4>                                                                                                                                           
                                                </div>
                                            </div>                    
                                        </div>
                                        <div class="progress mt-2" style="height:3px;">
                                            <div class="progress-bar progress-animated  bg-purple" role="progressbar" style="max-width: 55%;" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>                                        
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4 align-self-center">
                                                <div class="icon-info">
                                                    <i class="mdi mdi-playlist-check text-success"></i>
                                                </div> 
                                            </div>
                                            <div class="col-8 align-self-center text-center">
                                                <div class="ml-2 text-right">
                                                    <p class="mb-0 text-muted font-size-13">Tasks</p>
                                                    <span class="mt-0 font-20"><strong>40</strong></span>
                                                    <span class="badge badge-soft-success mt-1 shadow-none">Active</span>                                                                                                                                     
                                                </div>
                                            </div>                    
                                        </div>
                                        <div class="progress mt-2" style="height:3px;">
                                            <div class="progress-bar progress-animated  bg-success" role="progressbar" style="max-width: 35%;" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4 col-4 align-self-center">
                                                <div class="icon-info">
                                                    <i class="mdi mdi-coin text-pink"></i>
                                                </div> 
                                            </div>
                                            <div class="col-sm-8 col-8 align-self-center text-center">
                                                <div class="ml-2 text-right">
                                                    <p class="mb-1 text-muted font-size-13">Budget</p>
                                                    <h4 class="mt-0 mb-1 font-20">$18090</h4>                                                                                                                                           
                                                </div>
                                            </div>                    
                                        </div>
                                        <div class="progress mt-2" style="height:3px;">
                                            <div class="progress-bar progress-animated  bg-pink" role="progressbar" style="max-width: 35%;" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
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
    
</script>