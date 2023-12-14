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
          <div class="row">
            <div class="col-md-6 mx-auto">
              <form method="post" id="signupForm">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <input type="text" name="MobileNo" class="form-control" placeholder="<?= $this->lang->line('enterMobile'); ?>" required="" autocomplete="off" minlength="10" maxlength="10" pattern="[6-9]{1}[0-9]{9}" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                              <small id="signupMsg" class="text-danger" style="font-size: 10px;"></small>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <input type="email" name="email" class="form-control" placeholder="Enter Email (Bills will be sent on this Email)" autocomplete="off">
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <input type="text" name="FName" class="form-control" placeholder="Enter Firstname">
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <input type="text" name="LName" class="form-control" placeholder="Enter Lastname">
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <select name="Gender" class="form-control">
                                  <option value="">Select Gender</option>
                                  <option value="0">Male</option>
                                  <option value="1">Female</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <input type="date" name="DOB" class="form-control" placeholder="Enter DOB">
                          </div>
                      </div>
                  </div>
                  <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('submit'); ?>">
                  <a href="<?= base_url('customer/login');?>"><span style="font-size: 12px;"><?= $this->lang->line('log_in'); ?></span></a>
              </form>

              <form method="post" id="otpForm" style="display: none;">
                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <input type="number" name="otp" class="form-control" placeholder="<?= $this->lang->line('enterOTP'); ?>" autocomplete="off" required="">
                              <span class="text-danger" id="errorMsg" style="font-size: 9px;"></span>
                          </div>
                      </div>
                  </div>
                  <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('verifyOTP'); ?>">
                  <button class="btn btn-sm btn-warning" type="button" onclick="resendOTP()"><?= $this->lang->line('resendOTP'); ?></button>
              </form>
            </div>
          </div>
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
  var mobile = ''; 
   $('#signupForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        // console.log(data[1].value);
        mobile = data[0].value; 
        
        $.post('<?= base_url('customer/signup') ?>',data,function(res){
            if(res.status == 'success'){
              $('#otpForm').show();
              $('#signupForm').hide();
            }else{
              $('#signupMsg').html(res.response);
              $('#signupForm').show();
              $('#otpForm').hide();
            }
        });
    });

   $('#otpForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('customer/verifyOTP') ?>',data,function(res){
            if(res.status == 'success'){
                window.location = '<?= base_url('customer'); ?>';
            }else{
              $('#errorMsg').html(res.response);
            }
        });
    });

   function resendOTP(){
    var page = 'Resend Signup';
      $.post('<?= base_url('customer/resendOTP') ?>',{mobile:mobile,page:page},function(res){
            if(res.status == 'success'){
                $('#errorMsg').html(res.response);
            }else{
              $('#errorMsg').html(res.response);
            }
      });
   }

</script>

</html>