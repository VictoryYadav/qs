<?php $this->load->view('layouts/admin/head'); ?>
        
            <!-- ========== Left Sidebar Start ========== -->
            
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-6">
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


    

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        
        <?php $this->load->view('layouts/admin/script'); ?>


