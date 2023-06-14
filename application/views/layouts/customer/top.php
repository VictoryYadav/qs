<header class="header" style="background: #f5f5f5;">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-6">
                <div class="header__top__left">
                    <ul>
                        <li><img src="<?= base_url() ?>assets_admin/images/QSLogo.png" alt="" style="width: 30px;height: 28px;"></li>
                        <li><img src="<?= base_url() ?>assets/img/search.png" alt="Quick Service" style="width: 30px;height: 28px;" data-toggle="modal" data-target="#item-list-modal"></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-6">
                <div class="header__top__right">
                    
                    <!-- <div class="header__top__right__language">
                        <div>English</div>
                        <span class="arrow_carrot-down"></span>
                        <ul>
                            <li><a href="#">Spanis</a></li>
                            <li><a href="#">English</a></li>
                        </ul>
                    </div> -->
                    <div class="header__top__right__auth">
                        <span onclick="call_help()" style="cursor: pointer;" id="yellow_bell">
                            <img src="<?= base_url() ?>assets/img/yellow_bell.jpg" style="height: 28px;">
                        </span>
                        <span>
                            <img src="<?= base_url() ?>assets/img/language1.png" style="height: 22px;">
                            <!-- <div class="header__top__right__language">
                                <span class="arrow_carrot-down"></span>
                                <ul>
                                    <li><a href="#">Spanis</a></li>
                                    <li><a href="#">English</a></li>
                                </ul>
                            </div> -->

                        </span>
                        <span id="red_bell" style="display: none;">
                            <img src="<?= base_url() ?>assets/img/red_bell1.png" style="height: 30px;">
                        </span>
                        <img src="<?= base_url() ?>uploads/e51/logo.jpg" width="auto" height="28px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>