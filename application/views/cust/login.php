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
                            <small id="loginMsg" class="text-danger" style="font-size: 10px;"></small>
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

    <!-- Modal -->
<div class="modal fade" id="welcomeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="labelName">Name</h6>
        <button type="button" class="close" onclick="goHome()" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Your earlier visits to this outlet : <b><span id="lableVisit">0</span></b></p>
        <p>Your average rating for this outlet  : <b><span id="lableRating">0</span></b></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm" onclick="goHome()">Ok</button>
      </div>
    </div>
  </div>
</div>

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
              $('#loginMsg').html(res.response);
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
                if(res.response.visit > 0){
                    $('#labelName').html(res.response.name);
                    $('#lableVisit').html(res.response.visit);
                    $('#lableRating').val(res.response.rating);
                    $('#welcomeModal').modal('show');
                }else{
                    window.location = '<?= base_url('customer'); ?>';
                }
            }else{
              $('#errorMsg').html(res.response);
            }
        });
    });

   function goHome(){
        window.location = '<?= base_url('customer'); ?>';
   }
</script>

</html>