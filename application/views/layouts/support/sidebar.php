
<div id="sidebar-menu">
    <!-- Left Menu Start -->
    <ul class="metismenu list-unstyled" id="side-menu">
        <li class="menu-title"><?= $this->lang->line('menu');?></li>

        <li>
            <a href="#" class="has-arrow waves-effect">
                <i class="mdi mdi-cellphone-link"></i>
                <span>Reports</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="<?= base_url('restaurant/abc_report'); ?>">ABC Report</a></li>
                <li><a href="<?= base_url('restaurant/tax_report'); ?>">Tax Report</a></li>
                <li><a href="<?= base_url('restaurant/income_report'); ?>">Income Report</a></li>
                <li><a href="<?= base_url('restaurant/sales_report'); ?>">Sales Report</a></li>
                <li><a href="<?= base_url('restaurant/item_sales_report'); ?>">Item Sales Report</a></li>
                
                <li><a href="<?= base_url('restaurant/contribution_report'); ?>">Contribution Report</a></li>
                <li><a href="<?= base_url('restaurant/sale_summary'); ?>">Sale Summary</a></li>
                <li><a href="<?= base_url('restaurant/onaccount_sale_summary'); ?>">Onaccount Sale Summary</a></li>
            </ul>
        </li>
        
        <li><a href="<?= base_url('support/new_customer_create'); ?>">New Restaurant</a></li>

        <li><a href="<?= base_url('support/loyality'); ?>">Loyalty</a></li>
        
    </ul>
</div>
