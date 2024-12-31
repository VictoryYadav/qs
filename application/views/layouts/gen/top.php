<style>
     body[data-topbar=dark] #page-topbar {
    background-color: #fff !important;
}
</style>
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="<?= base_url(); ?>theme/images/Eat-out-Final-1.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="<?= base_url(); ?>theme/images/Eat-out-Final-1.png" alt="" height="17">
                    </span>
                </a>

                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="<?= base_url(); ?>theme/images/Eat-out-Final-1.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="<?= base_url(); ?>theme/images/Eat-out-Final-1.png" alt="" height="50">
                    </span>
                </a> -->
            </div>

        </div>  

        <div class="" style="font-size: 16px;color: #000;font-weight: 500;">
            <span class="logo-lg">
                <img src="<?= base_url(); ?>theme/images/Eat-out-Final-1.png" alt="" height="50">
            </span>
        </div>      

        <div class="d-flex">

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php if($this->session->userdata('GenCustId') > 0){ ?>
                    <span class="d-xl-inline-block ml-1" style="color: black;"> Hi <?= $this->session->userdata('GenFullname'); ?></span>
                    <?php } ?>
                </button>
                <!-- <div class="dropdown-menu dropdown-menu-right">
                    <?php if($this->session->userdata('GenCustId') > 0){ ?>
                    <a class="dropdown-item" href="<?= base_url('general/profile'); ?>"><i class="dripicons-user d-inlne-block text-muted mr-2"></i> Profile</a>
                    
                    <a class="dropdown-item" href="<?= base_url('general/logout'); ?>"><i class="dripicons-exit d-inlne-block text-muted mr-2"></i> Logout</a>
                    <?php } ?>
                </div> -->
            </div>

        </div>
    </div>
</header>