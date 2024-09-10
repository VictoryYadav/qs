<?php
        if($this->session->userdata('cur_password') !='eo1234'){ ?>
<div id="sidebar-menu">
    <!-- Left Menu Start -->
    <ul class="metismenu list-unstyled" id="side-menu">

        <?php
        $menuList = menuList();
        
        if(!empty($menuList)){
            if($menuList[0]['roleGroup'] > 0){
                ?>
                <li class="menu-title">
                    <b style="color: black;">Masters</b>
                </li>
                <?php 
            foreach ($menuList as $menu) {
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
         }
            } 
        }
            ?>

            <?php
        

        if(!empty($menuList)){
            if($menuList[0]['roleGroup'] > 0){
                ?>
                <li class="menu-title">
                    <b style="color: black;">Operations</b>
                </li>
                <?php 
            foreach ($menuList as $menu) {
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
         }
            } 
        }
            ?>

            <?php
        

        if(!empty($menuList)){
            if($menuList[0]['roleGroup'] > 0){
                ?>
                <li class="menu-title">
                    <b style="color: black;">Reports</b>
                </li>
                <?php 
            foreach ($menuList as $menu) {
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
         }
            } 
        }
            ?>

        <?php if( authuser()->mobile == '9029296666'){ ?>
        <li><a href="<?= base_url('support/new_customer_create'); ?>">New Restaurant</a></li>

        <li><a href="<?= base_url('support/loyality'); ?>">Loyalty</a></li>
        <?php } ?>
    </ul>
</div>

<?php } ?>