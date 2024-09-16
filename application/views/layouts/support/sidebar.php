
<div id="sidebar-menu">
    <!-- Left Menu Start -->
    <ul class="metismenu list-unstyled" id="side-menu">
        <li class="menu-title"><?= $this->lang->line('menu');?></li>
        <?php if(authuser()->userType == 1 || authuser()->userType == 9){ ?>
        <li><a href="<?= base_url('support/new_customer_create'); ?>">New Restaurant</a></li>
        <?php } ?>
        <?php if(authuser()->userType == 9){ ?>
        <li><a href="<?= base_url('support/new_user'); ?>">Add Support User</a></li>

        <li><a href="<?= base_url('support/users'); ?>">Support Users</a></li>
        <li><a href="<?= base_url('support/user_access'); ?>">Transfer User</a></li>
        <li><a href="<?= base_url('support/alter_user_access'); ?>">Transfer User(Stand By)</a></li>
        <li><a href="<?= base_url('support/access_assign'); ?>">Assign Rest</a></li>

        <li><a href="<?= base_url('support/transfer_rest_to_user'); ?>">Transfer Rest (Main)</a></li>
        <li><a href="<?= base_url('support/transfer_rest_to_user_alter'); ?>">Transfer Rest (Stand By)</a></li>
        <?php } ?>

        <?php if(authuser()->userType == 2 || authuser()->userType == 9){ ?>
        <li><a href="<?= base_url('support/rest_list'); ?>">Acces Restaurant</a></li>
        <?php } ?>

        <!-- <li><a href="<?= base_url('support/loyality'); ?>">Loyalty</a></li> -->
        
    </ul>
</div>
