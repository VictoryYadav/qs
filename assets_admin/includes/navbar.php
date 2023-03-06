		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<li class="nav-header">
						<div class="dropdown profile-element" style="margin-bottom:-20px;"> 
							<span>
								<a href="index.php">
									<img alt="" class="" src="<?=base_url()?>assets/img/logo.png" style="margin-left:15%; margin-top:-15%; width:70%; height:auto; border-radius:50%;"/>
								</a>
							</span>
						</div>
						<div class="logo-element">
							<div>
								<a href="index.php">
									<img src="assets/img/logo.png" style="width:50px; height:auto; border-radius:50%;"/>
								</a>
							</div>
						</div>
					</li>
					<li <?php if($page == 'index.php'){ ?> class="active"<?php } ?>>
						<a href="<?= base_url()?>home"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
					</li>
					<!-- <li <?php if($act == 'title'){ ?> class="active"<?php } ?>>
						<a href="#"><i class="fa fa-users"></i> <span class="nav-label">Title</span><span class="fa arrow"></span></a>
						<ul class="nav nav-second-level collapse">
							<li <?php if($page == 'all.php'){ ?> class="active"<?php } ?>><a href="all.php">All Title</a></li>
							<li <?php if($page == 'add.php'){ ?> class="active"<?php } ?>><a href="add.php">Add New Title</a></li>
						</ul>
					</li> -->
					<li <?php if($page == 'checkpost.php'){ ?> class="active"<?php } ?>>
						<a href="<?= base_url()?>check-post"><i class="fa fa-users"></i> <span class="nav-label">CheckPost</span></a>
					</li>

					<li <?php if($page == 'all_user.php'){ ?> class="active"<?php } ?>>
						<a href="<?= base_url()?>all-users"><i class="fa fa-users"></i> <span class="nav-label">Users</span></a>
					</li>

					<li <?php if($page == 'attendance.php'){ ?> class="active"<?php } ?>>
						<a href="<?= base_url()?>attendance"><i class="fa fa-users"></i> <span class="nav-label">Attendance</span></a>
					</li>
					
				</ul>
			</div>
		</nav>