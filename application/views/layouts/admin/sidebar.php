<div id="sidebar-menu">
    <!-- Left Menu Start -->
    <ul class="metismenu list-unstyled" id="side-menu">
        <li class="menu-title">Menu</li>

        <li>
            <a href="<?php echo base_url('dashboard'); ?>" class="waves-effect">
                <i class="mdi mdi-speedometer"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="mdi mdi-email-variant"></i>
                <span>User</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="<?php echo base_url('restaurant/add_user'); ?>">Add User</a></li>
                <li><a href="<?php echo base_url('restaurant/user_disable'); ?>">User Disable</a></li>
                <li><a href="<?php echo base_url('restaurant/user_access'); ?>">User Access</a></li>
            </ul>
        </li>
        
        <li>
            <a href="<?php echo base_url('restaurant/role_assign'); ?>" class="waves-effect">
                <i class="mdi mdi-speedometer"></i>
                <span>Roles Assignment</span>
            </a>
        </li>


        <!-- <li>
            <a href="<?php echo base_url('restaurant/merge_table'); ?>" class="waves-effect">
                <i class="mdi mdi-speedometer"></i>
                <span>Table Join/Unjoin</span>
            </a>
        </li> -->

        <li>
            <a href="<?php echo base_url('restaurant/offers_list'); ?>" class="waves-effect">
                <i class="mdi mdi-speedometer"></i>
                <span>Offers List</span>
            </a>
        </li>

        <li>
            <a href="<?php echo base_url('restaurant/cash_bill'); ?>" class="waves-effect">
                <i class="mdi mdi-speedometer"></i>
                <span>Bill Settlement</span>
            </a>
        </li>

        <li>
            <a href="<?php echo base_url('restaurant/sitting_table'); ?>" class="waves-effect">
                <i class="mdi mdi-speedometer"></i>
                <span>Table View</span>
            </a>
        </li>

        <li>
            <a href="<?php echo base_url('restaurant/order_dispense'); ?>" class="waves-effect">
                <i class="mdi mdi-speedometer"></i>
                <span>Order Dispense</span>
            </a>
        </li>

        <li>
            <a href="<?php echo base_url('restaurant/set_theme'); ?>" class="waves-effect">
                <i class="mdi mdi-speedometer"></i>
                <span>Set Theme</span>
            </a>
        </li>

        <li>
            <a href="<?php echo base_url('restaurant/stock_list'); ?>" class="waves-effect">
                <i class="mdi mdi-speedometer"></i>
                <span>Stock</span>
            </a>
        </li>

        <li>
            <a href="<?php echo base_url('restaurant/offline_orders'); ?>" class="waves-effect">
                <i class="mdi mdi-speedometer"></i>
                <span>Offline Orders</span>
            </a>
        </li>

        <li>
            <a href="<?php echo base_url('restaurant/item_list'); ?>" class="waves-effect">
                <i class="mdi mdi-speedometer"></i>
                <span>Item Details</span>
            </a>
        </li>


    </ul>
</div>