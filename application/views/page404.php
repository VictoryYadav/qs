<?php $this->load->view('layouts/admin/head'); ?>
        
            <!-- ========== Left Sidebar Start ========== -->
            
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
                            <div class="col-md-6 mx-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-5 align-self-center">
                                                <img src="assets/images/error.svg" alt="" class="img-fluid">
                                            </div>
                                            <div class="col-md-7">
                                                <div class="error-content text-center">
                                                    <h1 class="">404!</h1>
                                                    <h3 class="text-primary">Incorrect Details !</br></br> Please Scan QR Code again.</h3><br>
                                                    <!-- <a class="btn btn-primary mb-5 waves-effect waves-light" href="index.html">Back to Dashboard</a> -->
                                                </div>
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


