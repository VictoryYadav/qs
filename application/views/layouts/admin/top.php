<style type="text/css">
    body[data-topbar=dark] #page-topbar {
    background-color: #000500 !important;
}
</style>
<body data-topbar="dark">

        <!-- Begin page -->
        <div id="layout-wrapper">

            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="<?php echo base_url('dashboard'); ?>" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="<?= base_url() ?>assets_admin/images/QSLogo.png" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="<?= base_url() ?>assets_admin/images/QSLogo.png" alt="" height="17">
                                </span>
                            </a>

                            <a href="<?php echo base_url('dashboard'); ?>" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="<?= base_url() ?>assets_admin/images/QSLogo.png" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="<?= base_url() ?>assets_admin/images/QSLogo.png" alt="" style="height: 70px;">
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                            <i class="mdi mdi-menu"></i>
                        </button>


                    </div>

                     <!-- Search input -->
                     <div class="search-wrap" id="search-wrap">
                        <div class="search-bar">
                            <input class="search-input form-control" placeholder="Search" />
                            <a href="#" class="close-search toggle-search" data-target="#search-wrap">
                                <i class="mdi mdi-close-circle"></i>
                            </a>
                        </div>
                    </div>

                    <div class="d-flex">

                        <div class="dropdown d-none d-lg-inline-block ml-1">
                            <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                                <i class="mdi mdi-fullscreen"></i>
                            </button>
                        </div>

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="<?= base_url() ?>assets_admin/images/users/user-1.jpg"
                                    alt="Header Avatar">
                                <span class="d-none d-xl-inline-block ml-1"><?php echo authuser()->RestName; ?></span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <!-- item-->
                                <!-- <a class="dropdown-item" href="<?= base_url('student/profile') ?>"><i class="dripicons-user d-inlne-block text-muted mr-2"></i> Profile</a>
                                <a class="dropdown-item" href="#"><i class="dripicons-wallet d-inlne-block text-muted mr-2"></i> My Wallet</a>
                                <a class="dropdown-item d-block" href="#"><i class="dripicons-gear d-inlne-block text-muted mr-2"></i> Settings</a> -->
                                <a class="dropdown-item" href="<?php echo base_url('dashboard'); ?>"><i class="mdi mdi-speedometer"></i> Dashboard</a>
                                <a class="dropdown-item" href="<?php echo base_url('restorent/change_password'); ?>"><i class="dripicons-lock d-inlne-block text-muted mr-2"></i> Change Password</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo base_url('logout'); ?>"><i class="dripicons-exit d-inlne-block text-muted mr-2"></i> Logout</a>
                            </div>
                        </div>

                        <!-- <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                                <i class="mdi mdi-spin mdi-settings"></i>
                            </button>
                        </div> -->
            
                    </div>
                </div>
            </header>