<?php
	$_SESSION["page"] = "index";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Common</title>
    <?php $this->load->view('layouts/admin/head'); ?>
</head>
<body>
    <div id="wrapper">
		<?php $this->load->view('layouts/admin/navbar'); ?>
		<div id="page-wrapper" class="gray-bg">
			<?php $this->load->view('layouts/admin/topbar'); ?>
			<div class="wrapper wrapper-content">
				<div class="row">
					<a href="<?= base_url()?>admin/home">
						<div class="col-lg-3">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<span class="label label-success pull-right"><i class="fa fa-list"></i></span>
									<h5>Leads</h5>
								</div>
								<div class="ibox-content">
									<h1 class="no-margins">321</h1>
									<small>Total leads</small>
								</div>
							</div>
						</div>
					</a>
					<a href="<?= base_url()?>admin/user">
						<div class="col-lg-3">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<span class="label label-info pull-right"><i class="fa fa-briefcase"></i></span>
									<h5>Job Post</h5>
								</div>
								<div class="ibox-content">
									<h1 class="no-margins">123</h1>
									<small>Total job post</small>
								</div>
							</div>
						</div>
					</a>
					
				</div>
			</div>
			<?php $this->load->view('layouts/admin/footer'); ?>
        </div>
    </div>
	<?php $this->load->view('layouts/admin/scripts'); ?>
</body>
</html>