<?php

$folder = 'e'.authuser()->EID; 

?>

<section class="header-section">
    <div class="container p-2">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-4">
                <ul class="list-inline product-meta">
                    <li class="list-inline-item">
                        <a href="<?= base_url('customer'); ?>">
                            <img src="<?= base_url() ?>theme/images/Eat-Out-Icon.png" alt="" style="width: 30px;height: 28px;">
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a data-toggle="modal" data-target="#item-list-modal">
                            <img src="<?= base_url() ?>assets/img/search.png" alt="Quick Service" style="width: 30px;height: 28px;" >
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-8 col-sm-8 col-8 text-right">
                <ul class="list-inline product-meta">
                    <li class="list-inline-item">
                        <a onclick="call_help()" id="yellow_bell">
                            <img src="<?= base_url() ?>assets/img/yellow_bell.jpg" style="height: 28px;">
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#!">
                            <img src="<?= base_url() ?>assets/img/language1.png" style="height: 22px;">
                        </a>
                        <!-- Dropdown list -->
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item @@dashboardPage" href="dashboard.html">Dashboard</a></li>
                                <li><a class="dropdown-item @@dashboardMyAds" href="dashboard-my-ads.html">Dashboard My Ads</a></li>
                            </ul>
                    </li>
                    <li class="list-inline-item">
                        <span id="red_bell" style="display: none;">
                            <img src="<?= base_url() ?>assets/img/red_bell1.png" style="height: 30px;">
                        </span>
                        <img src="<?= base_url('uploads/'.$folder.'/logo.jpg') ?>" width="auto" height="28px;">
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>