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
                                                        <label for=""><?= $this->lang->line('qrCodePrint'); ?></label>
                                                            <select name="qrcode" id="qrcode" class="form-control form-control-sm" onchange="showTable()">
                                                                <option value="table"><?= $this->lang->line('table'); ?></option>
                                                                <option value="stall"><?= $this->lang->line('stallSeat'); ?></option>
                                                            </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3 col-6 stall_show" style="display: none;">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('fromStallSeat'); ?></label>
                                                        <input type="text" name="from_stall" class="form-control form-control-sm stall_req" required="" placeholder="From Stall" value="1" onblur="changeValue(this)">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6  stall_show" style="display: none;">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('toStallSeat'); ?></label>
                                                        <input type="text" name="to_stall" class="form-control form-control-sm stall_req" required="" placeholder="To Stall" value="1" onblur="changeValue(this)">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6 table_show">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('fromTable'); ?></label>
                                                        <input type="text" name="from_table" class="form-control form-control-sm table_req" placeholder="Table" required="" value="1" onblur="changeValue(this)">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6 table_show">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('toTable'); ?></label>
                                                        <input type="text" name="to_table" class="form-control form-control-sm table_req" placeholder="Table" required="" value="1" onblur="changeValue(this)">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('linkGenerate'); ?>">
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
                                                <?= $key['tblNo'] ?> - 
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
    showTable();
    function showTable(){
        var qrcode = $('#qrcode').val();

        $('.stall_show').hide();
        $('.table_show').show();
        $('.stall_req').prop('required',false);
        $('.table_req').prop('required',true);

        if(qrcode == 'stall'){
            $('.stall_show').show();
            $('.table_show').hide();
            $('.table_req').prop('required',false);
            $('.stall_req').prop('required',true);
        }else{
            $('.stall_show').hide();
            $('.table_show').show();
            $('.stall_req').prop('required',false);
            $('.table_req').prop('required',true);
        }
    }

    function changeValue(input) {
        var val = $(input).val();
        $(input).val(convertToUnicodeNo(val));
    }

</script>