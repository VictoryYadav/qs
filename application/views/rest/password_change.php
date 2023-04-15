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
                            <div class="col-md-6 mx-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" id="changePassword">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Current Password</label>
                                                        <input type="text" name="old_password" class="form-control" placeholder="Password" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>New Password</label>
                                                        <input type="text" name="password" class="form-control" placeholder="New Password" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Confirm New Password</label>
                                                        <input type="text" name="c_password" class="form-control" placeholder="Confirm Password" required="">
                                                    </div>
                                                </div>

                                                

                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="GET OTP">
                                            </div>
                                        </form>

                                        <form method="post" id="sendOtp" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Enter OTP</label>
                                                        <input type="number" name="otp" class="form-control" placeholder="OTP" required="" id="otp_text">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-danger" value="Verify OTP">
                                                <a href="#" onclick="resend()">Resend OTP</a>
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

    $('#changePassword').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/change_password') ?>',data,function(res){
                if(res.status == 'success'){
                  alert(res.response);
                  $('#sendOtp').show();
                  $('#changePassword').hide();
                  // location.reload();
                }else{
                  alert(res.response);
                  $('#changePassword').show();
                  $('#sendOtp').hide();
                }
            });

    });

    $('#sendOtp').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/verifyOTP') ?>',data,function(res){
                if(res.status == 'success'){
                  alert(res.response);
                  location.reload();
                }else{
                  alert(res.response);
                  $('#sendOtp').show();
                  $('#otp_text').val('');
                }
            });

    });

    function resend(){
        var old = "<?php echo $this->session->userdata('old_pwd'); ?>";
        var pass = "<?php echo $this->session->userdata('new_pwd'); ?>";
        console.log(old);
        console.log(pass);
        $.post('<?= base_url('restaurant/change_password') ?>',{password:pass,c_password:pass,old_password:old},function(res){
                if(res.status == 'success'){
                  alert(res.response);
                }else{
                  alert(res.response);
                }
                $('#sendOtp').show();
                $('#otp_text').val('');
            });
    }

</script>