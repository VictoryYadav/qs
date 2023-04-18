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

                                    <a class="btn btn-sm btn-primary float-right" href="<?php echo base_url('restaurant/new_offer'); ?>">
                                                <i class="fa fa-plus"></i> New Offer
                                            </a>
                                </div>

                                <?php if($this->session->flashdata('success')): ?>
                                    <div class="alert alert-success" role="alert"><?= $this->session->flashdata('success') ?></div>
                                <?php endif; ?>

                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="offer_list_table" class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Scheme Name</th>
                                                    <th>Scheme Type</th>
                                                    <th>Scheme Category</th>
                                                    <th style="width: 65px;">From Date</th>
                                                    <th style="width: 65px;">To Date</th>
                                                    <th>From Day</th>
                                                    <th>To Day</th>
                                                    <th style="width: 81px;">Action</th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($offers)){
                                                        $i=1;
                                                        foreach ($offers as $key) {
                                                     ?>
                                                    
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo $key['SchNm']; ?></td>
                                                    <td><?php echo !empty($key['SchTyp'])?getSchemeType($key['SchTyp']):'-'; ?></td>
                                                    <td><?php echo !empty($key['SchCatg'])?getSchemeCat($key['SchCatg']):'-'; ?></td>
                                                    <td><?php echo date('d-M-Y',strtotime($key['FrmDt'])); ?></td>
                                                    <td><?php echo date('d-M-Y',strtotime($key['ToDt'])); ?></td>
                                                    <td><?php echo !empty($key['FrmDayNo'])?getDay($key['FrmDayNo']):'-'; ?></td>
                                                    <td><?php echo !empty($key['ToDayNo'])?getDay($key['ToDayNo']):'-'; ?></td>
                                                    <td>
                                                        <a class="btn btn-sm btn-info" href="<?php echo base_url('restaurant/edit_offer/'.$key['SchCd']); ?>" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a> 
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
        $('#offer_list_table').DataTable();
    });

</script>