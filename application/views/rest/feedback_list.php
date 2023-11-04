<?php $this->load->view('layouts/admin/head'); ?>
<style>
    .topics tr {
     line-height: 8px !important; 
     font-size: 11px;
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

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18 text-center"><?php echo $title; ?>
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
                                            <table id="paymentTBL" class="table table-bordered topics">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th>Subject</th>
                                                    <th>Type</th>
                                                    <th>Description</th>
                                                    <th>Date</th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($list)){
                                                        $i=1;
                                                        foreach ($list as $key) {
                                                     ?>
                                                    
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $key['fullname']; ?></td>
                                                    <td><?= $key['phone']; ?></td>
                                                    <td><?= $key['email']; ?></td>
                                                    <td><?= $key['subject']; ?></td>
                                                    <td><?= $key['type']; ?></td>
                                                    <td><?= $key['description']; ?></td>
                                                    <td><?= date('d-M-Y', strtotime($key['created_at'])); ?></td>
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
        $('#paymentTBL').DataTable();
    });

</script>