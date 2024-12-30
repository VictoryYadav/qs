<?php $this->load->view('layouts/gen/head'); ?>

    <body data-topbar="dark">

        <!-- Begin page -->
        <div id="layout-wrapper">

            <?php $this->load->view('layouts/gen/top'); ?>

            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <?php $this->load->view('layouts/gen/sidebar'); ?>
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
                        
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="float-right text-muted font-size-13">Last 3 month</span>
                                        <h5 class="card-title mb-3">Workload</h5>                                    
                                        <div id="donut-example" class="morris-charts workloed-chart" style="height: 273px;" dir="ltr"></div> 
                                        <ul class="list-unstyled text-center text-muted mb-0">
                                            <li class="list-inline-item font-size-13"><i class="mdi mdi-album font-size-16 text-purple mr-2"></i>External</li>
                                            <li class="list-inline-item font-size-13"><i class="mdi mdi-album font-size-16 text-pink mr-2"></i>Internal</li>
                                            <li class="list-inline-item font-size-13"><i class="mdi mdi-album font-size-16 text-light mr-2"></i>Other</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>                                                              
                        </div><!--end row-->
                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <?php $this->load->view('layouts/admin/footer'); ?>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <?php $this->load->view('layouts/gen/color'); ?>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <?php $this->load->view('layouts/gen/scripts'); ?>