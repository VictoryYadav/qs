<?php $this->load->view('layouts/customer/head'); ?>
<style>
 body{
      margin: 0;
      padding: 0;
      height: 100%;

    }
    .user_card {
      height: 455px;
      width: 350px;
      margin-top: 60px;
      margin-bottom: auto;
      background: #f9d99e;
      /*background: #dbbd89;*/
      position: relative;
      display: flex;
      justify-content: center;
      flex-direction: column;
      padding: 10px;
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
      -webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
      -moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
      border-radius: 5px;

    }
    .brand_logo_container {
      position: absolute;
      height: 110px;
      width: 110px;
      top: -60px;
      border-radius: 50%;
      background: #fff;
      padding: 10px;
      text-align: center;
    }
    .brand_logo {
      height: 90px;
      width: 90px;
      border-radius: 50%;
      border: 2px solid white;
    }
    .form_container {
      margin-top: 50px;
    }
    .login_btn {
      /*width: 100%;*/
      background: #ee0004 !important;
      /*background: #c0392b !important;*/
      color: white !important;
    }
    .login_btn:focus {
      box-shadow: none !important;
      outline: 0px !important;
    }
    .login_container {
      padding: 0 2rem;
    }
    .input-group-text {
      background: #dd3b2a !important;
      color: white !important;
      border: 0 !important;
      border-radius: 0.25rem 0 0 0.25rem !important;
    }

    #otp{
      border: 1px solid #c54242;
    }

    .input_user,
    .input_pass:focus {
      box-shadow: none !important;
      outline: 0px !important;
    }
    .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
      background-color: #c0392b !important;
    }

    /*select2*/
    .select2-container--default .select2-selection--single {
      background-color: #f9d99e;
      border: 1px solid #ced4da;
      border-radius: 2px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #717070;
        line-height: 28px;
        font-size: 12px;
    }
    .select2-container .select2-selection--single {
      height: 29px;
    }
</style>
</head>

<body>

    
    <?php $this->load->view('layouts/customer/top'); ?>

    <section class="common-section p-2">

        <div class="container h-100" id="signupBlock">
          <div class="d-flex justify-content-center h-100">
            <div class="user_card">
              <div class="d-flex justify-content-center">
                <div class="brand_logo_container">
                  <img src="<?= base_url(); ?>theme/images/Eat-Out-Icon.png" class="brand_logo" alt="Logo">
                </div>
              </div>
              <div class="d-flex justify-content-center form_container">
                <form method="post" id="signupForm">

                  <div class="input-group mb-3">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fa fa-flag"></i></span>
                    </div>
                    <select name="CountryCd" class="form-control CountryCd select2 custom-select" required="" >
                        <option value=""><?= $this->lang->line('select'); ?></option>
                        <?php 
                        foreach ($country as $key) { ?>
                            <option value="<?= $key['phone_code']; ?>" <?php if($CountryCd == $key['phone_code']){ echo 'selected'; } ?>><?= $key['country_name']; ?></option>
                        <?php } ?>                   
                    </select>
                  </div>

                  <div class="input-group mb-3">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fa fa-phone"></i></span>
                    </div>
                    <input type="text" name="MobileNo" class="form-control" placeholder="<?= $this->lang->line('enterMobile'); ?>" required="" autocomplete="off" minlength="10" maxlength="10" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" id="MobileNo">
                    <small id="signupMsg" class="text-danger" style="font-size: 10px;"></small>
                  </div>

                  <div class="input-group mb-3">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    </div>
                    <input type="email" name="email" class="form-control" placeholder="<?= $this->lang->line('email'); ?>" autocomplete="off">
                  </div>

                  <div class="input-group mb-3">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fa fa-user"></i></span>
                    </div>
                    <input type="text" name="FName" class="form-control" placeholder="<?= $this->lang->line('firstName'); ?>">
                  </div>

                  <div class="input-group mb-3">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fa fa-user"></i></span>
                    </div>
                    <input type="text" name="LName" class="form-control" placeholder="<?= $this->lang->line('lastName'); ?>">
                  </div>

                  <div class="input-group mb-3">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fa fa-user"></i></span>
                    </div>
                    <select name="Gender" class="form-control">
                        <option value=""><?= $this->lang->line('select'); ?></option>
                        <option value="1"><?= $this->lang->line('male'); ?></option>
                        <option value="2"><?= $this->lang->line('female'); ?></option>
                        <option value="3"><?= $this->lang->line('transgender'); ?></option>                    
                    </select>
                  </div>

                  <div class="input-group mb-3">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fa fa-birthday-cake"></i></span>
                    </div>
                    <?php $date = date('Y-m-d', strtotime("-20 years")); ?>
                    <input type="date" name="DOB" class="form-control" placeholder="Enter DOB" value="<?= $date; ?>">
                  </div>

                  <div class="d-flex justify-content-center mt-3 login_container">
                      <input type="submit" class="btn btn-sm login_btn form-control" value="<?= $this->lang->line('signup'); ?>" style="font-size: 12px !important;font-weight: bold;">
                  </div>
                </form>
              </div>
          
              <div class="mt-4">
                <div class="d-flex justify-content-center links">
                  <span style="font-size: 12px;"><?= $this->lang->line('haveAnAccount'); ?>?&nbsp;&nbsp;<a href="<?= base_url('customer/login');?>" style="color:red;"><?= $this->lang->line('log_in'); ?></a></span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="container h-100" id="otpBlock" style="display: none;">
          <div class="d-flex justify-content-center h-100">
            <div class="user_card">
              <form method="post" id="otpForm">
                  <div class="row">
                      <div class="col-md-9 mx-auto">
                          <div class="form-group">
                            <label for="" style="color: #fff;"><?= $this->lang->line('enterOTP'); ?></label>
                              <input type="number" name="otp" id="otp" class="form-control" placeholder="<?= $this->lang->line('enterOTP'); ?>" autocomplete="off" required="">
                              <span class="text-danger" id="errorMsg" style="font-size: 9px;"></span>
                          </div>
                          <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('verifyOTP'); ?>">
                          <button class="btn btn-sm btn-danger login_btn" type="button" onclick="resendOTP()"><?= $this->lang->line('resendOTP'); ?></button>
                      </div>
                  </div>
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

  $(document).ready(function() {
    $('.CountryCd').select2();
  });

  window.onload = function() {
    document.getElementById("MobileNo").focus();
  }

  var mobile = ''; 
   $('#signupForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        // console.log(data[1].value);
        mobile = data[0].value; 
        
        $.post('<?= base_url('customer/signup') ?>',data,function(res){
            if(res.status == 'success'){
              $('#otpBlock').show();
              $('#signupBlock').hide();
            }else{
              $('#signupMsg').html(res.response);
              $('#signupBlock').show();
              $('#otpBlock').hide();
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