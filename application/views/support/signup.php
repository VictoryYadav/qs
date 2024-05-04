<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="assets_admin/img/favicon.png" type="image/x-icon" />
	<meta name="robots" content="noindex, nofollow">
    <title><?= $title; ?> | Eat-Out</title>
    <link href="<?php echo base_url(); ?>assets_admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets_admin/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets_admin/css/animate.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets_admin/css/style.css" rel="stylesheet">
</head>
<body style="background-image:url(<?php echo base_url(); ?>assets_admin/images/bg.jpg); background-repeat:no-repeat; background-size: cover;">
    <div style="background: rgba(0, 0, 0, 0); padding-top:3%; min-height: 100%;">
		<div class="middle-box text-center loginscreen animated fadeInDown" style="background: rgba(6, 6, 6, 0.55); margin-top:0;">
			<div style="padding:20px;">
				<div style="margin-bottom: 30px;">
					<img src="theme/images/Eat-out-Final-1.png" style="width: 120px; height: auto; border-radius: 7px;" alt="Title" />
				</div>
				<form class="m-t" method="POST" id="signupForm">
					<div class="form-group">
						<select name="countryCd" id="countryCd" class="form-control form-control-sm" required="">
							<option value=""><?= $this->lang->line('select'); ?></option>
							<?php 
                        foreach ($country as $key) { ?>
                            <option value="<?= $key['phone_code']; ?>" ><?= $key['country_name']; ?></option>
                        <?php } ?>  
						</select>
					</div>

					<div class="form-group">
						<input type="tel" class="form-control form-control-sm" placeholder="Phone" name="mobileNo" id="mobileNo" required="" autocomplete="off" />
					</div>

					<div class="form-group">
						<input type="password" class="form-control form-control-sm" placeholder="Password" name="pwd" required="" autocomplete="off" />
					</div>

					<div class="form-group">
						<input type="text" class="form-control form-control-sm" placeholder="FullName" name="fullname" autocomplete="off" />
					</div>

					<div class="form-group">
						<input type="email" class="form-control form-control-sm" placeholder="Email" name="email" autocomplete="off" />
					</div>

					<div class="form-group">
						<?php $date = date('Y-m-d', strtotime("-20 years")); ?>
						<input type="date" class="form-control form-control-sm" placeholder="date" name="DOB" autocomplete="off" value="$date" />
					</div>

					<div class="form-group">
						<select name="gender" id="gender" class="form-control form-control-sm">
							<option value=""><?= $this->lang->line('select'); ?></option>
							<option value="1"><?= $this->lang->line('male'); ?></option>
	                        <option value="2"><?= $this->lang->line('female'); ?></option>
	                        <option value="3"><?= $this->lang->line('transgender'); ?></option> 
						</select>
					</div>
					
					<?php if($this->session->flashdata('error')): ?>
						<div class="alert alert-danger" role="alert"><?= $this->session->flashdata('error') ?></div>
					<?php endif; ?>
					<button type="submit" class="btn btn-primary block full-width m-b"><?= $this->lang->line('signup'); ?></button>
				</form>
			</div>
		</div>
    </div>
    <script src="<?php echo base_url(); ?>assets_admin/js/jquery-3.1.1.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_admin/js/bootstrap.min.js"></script>
</body>
<script type="text/javascript">

	$(document).ready(function() {
	    $('#countryCd').select2();
	});

	window.onload = function() {
	   document.getElementById("mobileNo").focus();
	}

   $('#signupForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        
        $.post('<?= base_url('SAuth/signup') ?>',data,function(res){
            if(res.status == 'success'){
            	window.location = `${res.response}`;
                return false;
            }else{
              alert(res.response);
            }
        });
    });

</script>
</html>