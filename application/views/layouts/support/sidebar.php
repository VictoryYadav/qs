
<div id="sidebar-menu">
    <!-- Left Menu Start -->
    <ul class="metismenu list-unstyled" id="side-menu">
        <li class="menu-title"><?= $this->lang->line('menu');?></li>
        <?php if(authuser()->userType == 1){ ?>
        <li><a href="<?= base_url('support/new_customer_create'); ?>">New Restaurant</a></li>

        <li><a href="<?= base_url('support/users'); ?>">Users</a></li>
        <li><a href="<?= base_url('support/user_access'); ?>">Access Rest</a></li>
        <li><a href="<?= base_url('support/access_assign'); ?>">Assign Rest</a></li>
        <?php } ?>
        <li><a href="<?= base_url('support/rest_list'); ?>">Rest List</a></li>

        <!-- <li><a href="<?= base_url('support/loyality'); ?>">Loyalty</a></li> -->
        
    </ul>
</div>
