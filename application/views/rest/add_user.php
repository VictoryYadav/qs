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
                            <div class="col-md-8 mx-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>First Name</label>
                                                        <input type="text" name="" class="form-control" placeholder="First Name">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Last Name</label>
                                                        <input type="text" name="" class="form-control" placeholder="Last Name">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Mobile</label>
                                                        <input type="tel" name="" class="form-control" placeholder="Phone">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Date</label>
                                                        <input type="date" name="" class="form-control" >
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" name="" class="form-control" >
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>User Gender</label><br>
                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="customRadio7" name="dd" class="custom-control-input">
                                                                <label class="custom-control-label" for="customRadio7">HTML</label>
                                                            </div>
                                                        </div>

                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="customRadio8" name="dd" class="custom-control-input">
                                                                <label class="custom-control-label" for="customRadio8">css</label>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>User Type</label>
                                                        <select class="form-control" required="">
                                                            <option value="">Choose</option>
                                                            <option value="">Normal</option>
                                                            <option value="">Manager</option>
                                                            <option value="">Admin</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Restorent Name</label>
                                                        <select class="form-control" required="">
                                                            <option value=""><?php echo authuser()->RestName; ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="Submit">
                                            </div>
                                        </form>
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