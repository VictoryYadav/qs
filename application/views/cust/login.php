<?php $this->load->view('layouts/customer/head'); ?>
<style>
    body{
      margin: 0;
      padding: 0;
      height: 100%;

    }
    .user_card {
      height: 350px;
      width: 350px;
      margin-top: 75px;
      margin-bottom: auto;
      background: #efcfab;
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
      background: #dd3b2a !important;
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
    .input_user,
    .input_pass:focus {
      box-shadow: none !important;
      outline: 0px !important;
    }
    .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
      background-color: #c0392b !important;
    }
</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

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
                      <span class="input-group-text"><i class="fa fa-user"></i></span>
                    </div>
                    <input type="number" name="emailMobile" class="form-control input_user" placeholder="<?= $this->lang->line('enterMobile'); ?>" required="" maxlength="10" id="emailMobile">
                  </div>
                  <div>
                    <small id="loginMsg" class="text-danger" style="font-size: 10px;"></small>
                  </div>
                  
                  <div class="d-flex justify-content-center mt-3 login_container">
                      <!-- <button type="button" name="button" class="btn btn-sm login_btn">Login</button> -->
                      <input type="submit" class="btn btn-sm login_btn form-control" value="<?= $this->lang->line('log_in'); ?>">
                  </div>
                </form>
              </div>
          
              <div class="mt-4">
                <div class="d-flex justify-content-center links">
                  Don't have an account?&nbsp;&nbsp;<a href="<?= base_url('customer/signup');?>"><span style="font-size: 12px;color: #dd3b2a;"><?= $this->lang->line('signup'); ?></span></a>
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
      <div class="modal-header">
        <h6 class="modal-title" id="labelName">Name</h6>
        <button type="button" class="close" onclick="goHome()" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Your earlier visits to this outlet : <b><span id="lableVisit">0</span></b></p>
        <p>Your average rating for this outlet  : <b><span id="lableRating">-</span></b></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm" onclick="goHome()"><?= $this->lang->line('ok'); ?></button>
      </div>
    </div>
  </div>
</div>

</body>

<script type="text/javascript">
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