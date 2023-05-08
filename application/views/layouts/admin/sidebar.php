<div id="sidebar-menu">
    <!-- Left Menu Start -->
    <ul class="metismenu list-unstyled" id="side-menu">
        <li class="menu-title">Menu</li>

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
                <span><?= $menu['Name']; ?></span>
            </a>
        </li>
        <?php }
             } 
            } ?>
        
    </ul>
</div>