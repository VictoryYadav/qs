<?php $this->load->view('layouts/customer/head'); ?>
<style>

</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <section class="common-section p-2">
        <div class="container">
            <form method="post" id="loginForm">
                <div class="row">
                    <div class="col-md-6 mx-auto">
                        <div class="form-group">
                            <input type="text" name="emailMobile" class="form-control" placeholder="Enter Mobile or Email" required="" autocomplete="off">
                        </div>

                        <input type="submit" class="btn btn-sm btn-success" value="Login">
                        <a href="<?= base_url('customer/signup');?>"><span style="font-size: 12px;">Sign Up</span></a>
                    </div>
                    
                </div>
            </form>

            <form method="post" id="otpForm" style="display: none;">
                <div class="row">
                    <div class="col-md-6 mx-auto">
                        <div class="form-group">
                            <input type="number" name="otp" class="form-control" placeholder="Enter OTP" autocomplete="off" required="">
                            <span class="text-danger" id="errorMsg" style="font-size: 9px;"></span>
                        </div>
                        <input type="submit" class="btn btn-sm btn-success" value="Verify OTP">
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
    <!-- end footer section -->


    <!-- Js Plugins -->
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

<script type="text/javascript">
    $('#loginForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('customer/login') ?>',data,function(res){
            if(res.status == 'success'){
              $('#otpForm').show();
              $('#loginForm').hide();
            }else{
              $('#loginForm').show();
              $('#otpForm').hide();
            }
        });
    });

   $('#otpForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('customer/loginVerify') ?>',data,function(res){
            if(res.status == 'success'){
                window.location = '<?= base_url('customer'); ?>';
            }else{
              $('#errorMsg').html(res.response);
            }
        });
    });
</script>

</html>