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
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="dispnes_order" class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>User Name</th>
                                                    <th>Mobile No</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($companies)){
                                                        foreach ($companies as $key) {
                                                     ?>
                                                    
                                                <tr>
                                                    <td><?php echo $key['Name']; ?></td>
                                                    <td><?php echo $key['country_name']; ?></td>
                                                    <td><?php echo $key['city_name']; ?></td>
                                                    <td><?php echo $key['insName']; ?></td>
                                                </tr>
                                                <?php }
                                                 }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <h3>Order Details</h3>
                                        <div class="table-responsive">
                                            <table id="order_details" class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>User Name</th>
                                                    <th>Mobile No</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($companies)){
                                                        foreach ($companies as $key) {
                                                     ?>
                                                    
                                                <tr>
                                                    <td><?php echo $key['Name']; ?></td>
                                                    <td><?php echo $key['country_name']; ?></td>
                                                    <td><?php echo $key['city_name']; ?></td>
                                                    <td><?php echo $key['insName']; ?></td>
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
        $('#dispnes_order').DataTable();
        $('#order_details').DataTable();
    });

</script>