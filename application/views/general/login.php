<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="assets_admin/img/favicon.png" type="image/x-icon" />
	<meta name="robots" content="noindex, nofollow">
    <title><?= $title; ?> | Eat-Out</title>
    <link href="<?= base_url(); ?>theme/images/Eat-Out-Icon.png" rel="shortcut icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" >
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" >
    <style>
            /* Coded with love by Mutiullah Samim */
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            background: #f6f6f6 !important;
        }
        .user_card {
            height: 400px;
            width: 350px;
            margin-top: auto;
            margin-bottom: auto;
            background: #fff;
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
            height: 170px;
            width: 170px;
            top: -75px;
            border-radius: 50%;
            background: #dadddd;
            padding: 10px;
            text-align: center;
        }
        .brand_logo {
            height: 150px;
            width: 150px;
            border-radius: 50%;
            border: 2px solid white;
        }
        .form_container {
            margin-top: 100px;
        }
        .login_btn {
            /*width: 100%;*/
            background: #00c602 !important;
            color: white !important;
            font-weight: 500;
        }
        .login_btn:focus {
            box-shadow: none !important;
            outline: 0px !important;
        }
        .login_btn:hover {
          background-color: #2ce72e !important;
          color: white;
        }

        .login_container {
            padding: 0 2rem;
        }
        .input-group{
            padding: 1px;
            border: 1px solid #ced4da;
        }

        .input-group-text {
            background: #fff !important;
            color: #4f5153 !important;
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

        .form-control{
            border:none;
        }
    </style>
</head>
<body >
    <div class="container h-100">
        <div class="d-flex justify-content-center h-100">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <img src="<?= base_url() ?>theme/images/Eat-Out-loader.png" class="brand_logo" alt="Logo">
                    </div>
                </div>

                <div class="d-flex justify-content-center form_container" >
                    <form method="POST" id="loginForm" class="loginBlock">
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <select class="form-control input_user" placeholder="Country" name="CountryCd" required="">
                                <option value="">Select</option>
                                <?php 
                                foreach ($country as $key) { ?>
                                    <option value="<?= $key['phone_code']; ?>" <?php if($key['phone_code'] == 91){ echo 'selected'; } ?>><?= $key['country_name']; ?></option>
                                <?php } ?>  
                            </select>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" class="form-control input_user" placeholder="Phone" name="MobileNo" required="" autocomplete="off" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="10" maxlength="12">
                        </div>

                        <div class="">
                            <button type="submit" name="button" class="btn login_btn form-control">Login</button>
                        	<?php if($this->session->flashdata('error')): ?>
								<div class="alert alert-danger" role="alert"><?= $this->session->flashdata('error') ?></div>
							<?php endif; ?>
                        </div>
                    </form>

                    <form method="post" id="otpForm" class="otpBlock" style="display: none;">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control input_user" placeholder="<?= $this->lang->line('enterOTP'); ?>" name="otp" required="" autocomplete="off" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="4" maxlength="4" id="otp">
                        </div>
                        <div class="">
                              <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('verifyOTP'); ?>">
                              <button class="btn btn-sm btn-danger" type="button" onclick="resendOTP()"><?= $this->lang->line('resendOTP'); ?></button>
                              
                        </div>
                      </form>
                </div>

            </div>
        </div>
    </div>
    <script src="<?php echo base_url(); ?>assets_admin/js/jquery-3.1.1.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_admin/js/bootstrap.js"></script>
</body>
<script>

    $('#loginForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        
        $.post('<?= base_url('general/login') ?>',data,function(res){
            
            if(res.status == 'success'){
              document.getElementById("otp").focus();
              $('.otpBlock').show();
              $('.loginBlock').hide();
            }else{
              alert(res.message);
              $('.loginBlock').show();
              $('.otpBlock').hide();
            }
        });
    });

    $('#otpForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('general/loginVerify') ?>',data,function(res){
            if(res.status == 'success'){
                window.location = '<?= base_url('general'); ?>';
            }else{
              alert(res.message);
            }
        });
    });
</script>
</html>