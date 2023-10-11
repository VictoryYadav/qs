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
                                <div class="page-title-box align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18 text-center"><?php echo $title; ?>
                                    </h4>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-md-8 mx-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <?php if($this->session->flashdata('success')): ?>
                                            <div class="alert alert-success" role="alert" id="alertBlock"><?= $this->session->flashdata('success') ?></div>
                                            <?php endif; ?>
                                            
                                        <form method="post" >
                                            <div class="row">
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label>EID</label>
                                                        <input type="number" name="eid" class="form-control form-control-sm" placeholder="EID" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label>Chain No</label>
                                                        <input type="number" name="chain" class="form-control form-control-sm" placeholder="Chain No" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label>Table</label>
                                                        <input type="number" name="table" class="form-control form-control-sm" placeholder="Table" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label>Stock</label>
                                                        <input type="number" name="stock" class="form-control form-control-sm" required="" placeholder="Stock">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="Generate">
                                            </div>
                                        </form>
                                    </div>
                                </div>
    
                            </div>
                        </div>

                        <div class="row">

                            <?php 
                            if(!empty($lists)){
                                foreach ($lists as $key) {
                            ?>
                            <div class="col-md-3 col-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <!-- <?= $key['img']; ?> -->
                                            <img src="<?= base_url('uploads/qrcode/'.$key['img']); ?>" alt="qrcode" class="img-thumbnail">
                                            <div class="">
                                                <a href="<?= base_url('uploads/qrcode/'.$key['img']); ?>" download>
                                                <i class="fas fa-download"></i>
                                                </a>
                                            </div>          
                                            <p class="font-size-13 text-muted"><?= $key['link']; ?></p>
                                        </div>                                    
                                    </div>
                                </div>
                            </div>
                            <?php } } ?>

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