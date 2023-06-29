<div class="navbar menu-footer fixed-bottom" >
    <div class="btn-group dropup">
        <a href="#news" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url(); ?>assets/img/menu.svg" width="33" height="20">
            <h6 style="font-size: 12px;">Account</h6>           
        </a>
        <div class="dropdown-menu">
            <?php if(!empty($this->session->userdata('CustId'))){ ?>
            <a class="dropdown-item" href="/cust_registration.php">Edit Profile</a>
            <a class="dropdown-item" href="/cust_registration.php">Transaction</a>
            <a class="dropdown-item" href="reserve_table.php">Book Table</a>
            <a class="dropdown-item" href="/cust_registration.php">Refer Outlet</a>
            <a class="dropdown-item" href="#">Username(<?= $_SESSION['signup']['MobileNo']; ?>)</a>
            <a class="dropdown-item" href="<?= base_url('customer/logout'); ?>">Logout</a>
        <?php } else { ?>
            <a class="dropdown-item" href="<?= base_url('customer/login'); ?>">Login</a>
        <?php } ?>
        </div>
    </div>

    <div class="btn-group dropup">
        <a href="#news" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url(); ?>assets/img/feedback.svg" width="33" height="20">
            <h6 style="font-size: 12px;"><?= $language['about_us']?></h6>          
            </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="#">T &amp; C</a>
            <a class="dropdown-item" href="#">Testimonials</a>
            <a class="dropdown-item" href="#">Contact Us</a>
        </div>
    </div>
    <div class="btn-group dropup">
        <a href="#news" class="dropdown-toggle" data-toggle="modal" data-target="#offers-modal">
            <!-- <a data-toggle="modal" data-target="#offers-modal"> -->
            <img src="<?php echo base_url(); ?>assets/img/home.svg" width="33" height="20">
        <h6 style="font-size: 12px;"><?= $language['offers']?></h6>
        </a>
    </div>
    <div class="btn-group dropup">
        <a href="#news" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url(); ?>assets/img/inbox.svg" width="33" height="20">
        <h6 style="font-size: 12px;">Order List</h6>
        </a>
        <div class="dropdown-menu" style="right: 0; left: auto;">
            <?php if(!empty($this->session->userdata('CustId'))){ ?>
            <a class="dropdown-item" href="<?= base_url('customer/cart'); ?>">Order List</a>
        <?php } ?>
            <a class="dropdown-item" href="send_to_kitchen.php">Current Order</a>
        </div>
    </div>
</div>