<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title; ?></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" ></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<form method="post" >
				<h3>Create Rest User</h3>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="">Firstname</label>
						<input type="text" class="form-control form-control-sm" name="FName" required="">
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="">Lastname</label>
						<input type="text" class="form-control form-control-sm" name="LName" >
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="">Mobile</label>
						<input type="number" class="form-control form-control-sm" name="MobileNo" required="">
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="">Password</label>
						<input type="text" class="form-control form-control-sm" name="Passwd" required="" autocomplete="off">
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="">Gender</label>
						<select name="Gender" id="" class="form-control form-control-sm" required="]">
							<option value="">Select</option>
							<option value="1">Male</option>
							<option value="2">Female</option>
							<option value="3">Transgender</option>
						</select>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="">Email</label>
						<input type="email" class="form-control form-control-sm" name="PEmail" required="" autocomplete="off">
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<?php
						$dateP = date('Y-m-d', strtotime("-20 years", strtotime(date('Y-m-d'))));
						 ?>
						<label for="">DOB</label>
						<input type="date" class="form-control form-control-sm" name="DOB" required="" value="<?= $dateP; ?>">
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="">User Type</label>
						<select name="UTyp" id="" class="form-control form-control-sm" required="">
							<option value="9">Admin</option>
						</select>
					</div>
				</div>

			</div>

			<div class="text-center">
				<input type="submit" class="btn btn-sm btn-success" value="Submit">
			</div>
			<div>
				<?php if($this->session->flashdata('success')): ?>
						<div class="alert alert-success" role="alert"><?= $this->session->flashdata('success') ?></div>
					<div class="text-center">
						<a href="<?= base_url(); ?>login?o=<?= $EID; ?>&c=0">Log In</a>
					</div>
				<?php endif; ?>
				<?php if($this->session->flashdata('error')): ?>
						<div class="alert alert-danger" role="alert"><?= $this->session->flashdata('error') ?></div>
					<?php endif; ?>
			</div>
		</form>
		
	</div>
</body>
</html>