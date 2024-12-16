<?php $this->load->view('layouts/customer/head'); ?>
<style>
    body{
      margin: 0;
      padding: 0;
      height: 100%;

    }
    label{
      color: #000;
    }

    .user_card {
      height: 350px;
      width: 350px;
      margin-top: 75px;
      margin-bottom: auto;
      /*background: #f9d99e;*/
      background: #ffebc6;
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
      margin-top: 40px;
    }
    .login_btn {
      /*width: 100%;*/
      background: #ee0004 !important;
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
      background: #ef3926 !important;
      color: white !important;
      border: 0 !important;
      border-radius: 0.25rem 0 0 0.25rem !important;
    }

    #emailMobile, #otp, #CountryCd{
      border: 1px solid #c54242;
    }

    .input_user,
    .input_pass:focus {
      box-shadow: none !important;
      outline: 0px !important;
    }
    /*select2*/
    .select2-container--default .select2-selection--single {
      background-color: #ffebc6;
      border: 1px solid #b9404c;
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

        <div class="container h-100" id="loginBlock">
          <div class="d-flex justify-content-center h-100">
            <div class="user_card">
              <div class="d-flex justify-content-center">
                <div class="brand_logo_container">
                  <img src="<?= base_url(); ?>theme/images/Eat-Out-Icon.png" class="brand_logo" alt="Logo">
                </div>
              </div>
              <div class="d-flex justify-content-center form_container">
                <form method="post" id="loginForm">

                  <div class="input-group mb-3">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fa fa-flag"></i></span>
                    </div>
                    <select name="CountryCd" class="form-control CountryCd select2 custom-select" required="" id="CountryCd">
                        <option value=""><?= $this->lang->line('select'); ?></option>
                        <?php 
                        foreach ($country as $key) { ?>
                            <option value="<?= $key['phone_code']; ?>" <?php if($CountryCd == $key['phone_code']){ echo 'selected'; } ?>><?= $key['country_name']; ?></option>
                        <?php } ?>                   
                    </select>
                  </div>

                  <div class="input-group mb-3">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fa fa-user"></i></span>
                    </div>
                    <input type="number" name="emailMobile" class="form-control input_user" placeholder="<?= $this->lang->line('enterMobile'); ?>" required="" maxlength="10" id="emailMobile">
                  </div>
                  <div>
                    <small id="loginMsg" class="text-danger" style="font-size: 10px;"></small>
                  </div>
                  
                  <div class="">
                      <input type="submit" class="btn btn-sm login_btn form-control" value="<?= $this->lang->line('log_in'); ?>" style="font-size: 12px !important;font-weight: bold;">
                  </div>
                </form>
              </div>
          
              <div class="mt-4">
                <div class="d-flex justify-content-center links">
                  <span style="font-size: 12px;"><?= $this->lang->line('accountNotCreated'); ?>?&nbsp;&nbsp;<a href="<?= base_url('customer/signup');?>" style="color: #dd3b2a;"><?= $this->lang->line('signup'); ?></a></span>
                </div>
                <!-- <div class="d-flex justify-content-center links">
                  <a href="#">Forgot your password?</a>
                </div> -->
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
                            <label for=""><?= $this->lang->line('enterOTP'); ?></label>
                              <input type="number" name="otp" class="form-control" placeholder="<?= $this->lang->line('enterOTP'); ?>" autocomplete="off" required="" id="otp">
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

    <!-- Modal -->
<div class="modal fade" id="welcomeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: #e1af75;">
        <h6 class="modal-title text-white" id="labelName"><?= $this->lang->line('name'); ?></h6>
      </div>
      <div class="modal-body">
        <p>Your earlier visits to this outlet : <b><span id="lableVisit">0</span></b></p>
        <p>Your average rating for this outlet  : <b><span id="lableRating">-</span></b></p>
      </div>
      <div class="modal-footer" style="background: #e9d2b6;padding: -0.25rem !important;">
        <button type="button" class="btn btn-success btn-sm" onclick="goHome()" style="font-size: 12px;"><?= $this->lang->line('ok'); ?></button>
      </div>
    </div>
  </div>
</div>

</body>

<script type="text/javascript">

  $(document).ready(function() {
    $('.CountryCd').select2();
  });

  window.onload = function() {
    document.getElementById("emailMobile").focus();
    // document.getElementById("otp").focus();
  }
  
  var mobile = ''; 
    $('#loginForm').on('submit', function(e){
        e.preventDefault();
        var data = $(this).serializeArray();
        // console.log(data[0].value);
        mobile = data[0].value;

        $.post('<?= base_url('customer/login') ?>',data,function(res){
            if(res.status == 'success'){
              document.getElementById("otp").focus();
              $('#otpBlock').show();
              $('#loginBlock').hide();
            }else{
              $('#loginMsg').html(res.response);
              $('#loginBlock').show();
              $('#otpBlock').hide();
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
                    $('#lableRating').html(res.response.rating);
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

   function resendOTP(){
    var page = 'Resend Login';
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