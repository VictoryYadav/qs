<?php
    $menuList = menuList();
    if($this->session->userdata('cur_password') !='eo1234'){ ?>
<div id="sidebar-menu">
    <!-- Left Menu Start -->
    <ul class="metismenu list-unstyled" id="side-menu">

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="mdi mdi-email-variant"></i>
                <span>Masters</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <?php foreach ($menuList as $menu) {
                    if($menu['roleGroup'] == 1){
                        $url = base_url('restaurant').'/'.$menu['pageUrl'];
                        if(!empty($menu['pageUrl'])){ ?>
                            <li>
                            <a href="<?= $url; ?>" class="waves-effect">
                                <span><?= $menu['LngName']; ?></span>
                            </a>
                        </li>
                        <?php }
                             } 
                         } ?>
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="mdi mdi-email-variant"></i>
                <span>Operations</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <?php foreach ($menuList as $menu) {
                    if($menu['roleGroup'] == 2){
                        $url = base_url('restaurant').'/'.$menu['pageUrl'];
                        if(!empty($menu['pageUrl'])){ ?>
                            <li>
                            <a href="<?= $url; ?>" class="waves-effect">
                                <span><?= $menu['LngName']; ?></span>
                            </a>
                        </li>
                        <?php }
                             } 
                         } ?>
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="mdi mdi-email-variant"></i>
                <span>Reports</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <?php foreach ($menuList as $menu) {
                    if($menu['roleGroup'] == 3){
                        $url = base_url('restaurant').'/'.$menu['pageUrl'];
                        if(!empty($menu['pageUrl'])){ ?>
                            <li>
                            <a href="<?= $url; ?>" class="waves-effect">
                                <span><?= $menu['LngName']; ?></span>
                            </a>
                        </li>
                        <?php }
                             } 
                         } ?>
            </ul>
        </li>


        <?php if( authuser()->mobile == '9029296666'){ ?>
        <li><a href="<?= base_url('support/new_customer_create'); ?>">New Restaurant</a></li>

        <li><a href="<?= base_url('support/loyality'); ?>">Loyalty</a></li>
        <?php } ?>

        <!-- <li><a href="<?= base_url('restaurant/rprint'); ?>">RPrint</a></li> -->
    </ul>
</div>

<?php } ?>