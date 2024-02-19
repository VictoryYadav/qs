<?php
        if(authuser()->cur_password !='QS1234'){ ?>
<div id="sidebar-menu">
    <!-- Left Menu Start -->
    <ul class="metismenu list-unstyled" id="side-menu">
        <li class="menu-title"><?= $this->lang->line('menu');?></li>

        <?php
        $menuList = menuList();

        if(!empty($menuList)){
            foreach ($menuList as $menu) {
                $url = base_url('restaurant').'/'.$menu['pageUrl'];
                if($menu['pageUrl'] == 'dashboard'){
                    $url = base_url('dashboard');
                }
                if(!empty($menu['pageUrl'])){ ?>
         
        <li>
            <a href="<?= $url; ?>" class="waves-effect">
                <i class="mdi mdi-speedometer"></i>
                <span><?= $menu['LngName']; ?></span>
            </a>
        </li>
        <?php }
             } 
            } 
            ?>

        <li>
            <a href="#" class="has-arrow waves-effect">
                <i class="mdi mdi-cellphone-link"></i>
                <span>Reports</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="<?= base_url('restaurant/abc_report'); ?>">ABC Report</a></li>
                <li><a href="#">Report2</a></li>
                <li><a href="#">Report3</a></li>
            </ul>
        </li>
        
    </ul>
</div>

<?php } ?>