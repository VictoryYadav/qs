<div id="sidebar-menu">
    <!-- Left Menu Start -->
    <ul class="metismenu list-unstyled" id="side-menu">
        <li class="menu-title">Main</li>

        <li>
            <a href="javascript: void(0);" class="waves-effect">
                <i class="mdi mdi-speedometer"></i>
                <span class="badge badge-pill badge-danger float-right">9+</span>
                <span>Dashboards</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="index.html">Dashboard 1</a></li>
                <li><a href="index-2.html">Dashboard 2</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="mdi mdi-email-variant"></i>
                <span>Email</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="email-inbox.html">Inbox</a></li>
                <li><a href="email-read.html">Read Email</a></li>
                <li><a href="email-compose.html">Compose Email</a></li>
            </ul>
        </li>

        <li>
            <a href="<?= base_url('general/profile'); ?>" class=" waves-effect">
                <i class="mdi mdi-calendar"></i>
                <span>Profile</span>
            </a>
        </li>

        <li>
            <a href="<?= base_url('general/bill_history'); ?>" class=" waves-effect">
                <i class="mdi mdi-calendar"></i>
                <span>History</span>
            </a>
        </li>

        <li>
            <a href="<?= base_url('general/rest_details'); ?>" class=" waves-effect">
                <i class="mdi mdi-calendar"></i>
                <span>Search</span>
            </a>
        </li>

    </ul>
</div>