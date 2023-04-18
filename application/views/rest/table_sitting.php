<?php $this->load->view('layouts/admin/head'); ?>
<style>
        .h-deliver {
            background-color: lightgreen;
        }

        .login-page {
            background-color: #1091e8;
        }

        .left-components {
            padding: 20px 15px 20px 20px;
            margin-bottom: 0px;
        }

        img {
            cursor: zoom-in;
        }

        .left-menu-logo {
            padding: 20px;
            text-align: center;
        }

        .left-menu ul li a {
            padding: 5px 10px;
            font-size: 1rem;
            border-radius: 50px;
            border: 1px solid #fff;
            display: block;
            color: #fff;
            text-decoration: none;
        }

        .left-menu .active {
            background-color: #0073ad;
        }

        .left-menu ul li {
            margin-bottom: 20px;
            text-align: center;
        }

        .arrow-down {
            width: 0;
            height: 0;
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            margin-left: 20px;
            border-top: 15px solid #fff;
            margin-bottom: 20px;
        }

        #table-view .tableView-table {
            background-color: #fff;
        }

        .table-numbers .table thead th {
            font-size: 12px;
            border: none;
        }

        .table-numbers .table td {
            font-size: 12px;
        }

        .tableno {
            display: inline-block;
            padding: 10px 20px;
            background-color: #003779;
            margin-bottom: 0px;
            color: #fff;

        }

        .success-table-status {
            background-color: lightgreen !important;
        }

        /*thead, tbody { display: block; }

.table-numbers tbody {
    max-height: 85px;
    overflow-y: auto;
    overflow-x: hidden;
}*/
        #table-view {
            max-height: 500px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .items-table .table thead th {
            font-size: 12px;
            border: none;
        }

        .items-table .table td {
            font-size: 12px;
        }

        .items-table {
            color: #fff;
        }

        .items-data {
            max-height: 370px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .items-data::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #F5F5F5;
        }

        .items-data::-webkit-scrollbar {
            width: 6px;
            background-color: #F5F5F5;
        }

        .items-data::-webkit-scrollbar-thumb {
            background-color: #aaa;
            border-radius: 10px;
        }

        #table-view::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #F5F5F5;
        }

        #table-view::-webkit-scrollbar {
            width: 6px;
            background-color: #F5F5F5;
        }

        #table-view::-webkit-scrollbar-thumb {
            background-color: #aaa;
            border-radius: 10px;
        }

        .dashboard-menu {
            background-color: #0086ea;
        }

        .dropdown-menu.show {
            right: 0px !important;
            left: unset;
            text-align: center;
        }

        .tableno-btns {
            background-color: #f8b500;
            color: #fff;
            /*margin-right: 10px;*/
            padding: 2px 10px;
            font-size: 14px;
        }

        .tableno-row-btns {
            display: inline-block;
            position: relative;
            padding: 7px;
            background: #f2f2f2;
            margin-left: 85px;
            /*margin-left: 0px;
    text-align: center;
    width: 100%;*/
        }

        .btn-go {
            background-color: #f8b500;
            color: #fff;
            margin-left: -5px;
            padding: 4px 10px;
            border-radius: 0px;
            margin-bottom: 4px;
        }

        .search-input {
            border: none;
            width: 50px;
            padding: 5px;
        }

        .table {
            margin-bottom: 0px;
        }

        .tableView-table {
            /*padding-bottom: 25px;*/
            margin-bottom: 35px;
        }

        .tableno-data {
            max-height: 500px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .cust-img {
            width: 50px;
            height: 40px;
            border-radius: 50%;
            float: right;
        }

        /*td.focus {
            border: solid 1px black;
        }*/

        #mydiv {
            position: absolute;
            z-index: 9;
            background-color: #f1f1f1;
            border: 1px solid #d3d3d3;
            text-align: center;
        }

        .card-header:hover {
            cursor: -webkit-grabbing;
            cursor: grabbing;
        }
        .active-row{
            outline: 2px solid;
        }
    </style>
        <?php $this->load->view('layouts/admin/top'); ?>
            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <?php $this->load->view('layouts/admin/sidebar'); ?>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18"><?php echo $title; ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 col-6">
                                                <select class="form-control form-control-sm" id="kitchen-code" onchange="getTableView();">
                                                    <?php
                                                    if (count($SettingTableViewAccess) == 1) { ?>
                                                        <option value="<?= $SettingTableViewAccess[0]['CCd'] ?>" settle="<?= $SettingTableViewAccess[0]['Settle'] ?>"><?= $SettingTableViewAccess[0]['Name'] ?></option>
                                                    <?php } else {
                                                        ?>
                                                        <option value="0" style='display:none;' settle="1">Select Cashier</option>
                                                        <?php foreach ($SettingTableViewAccess as $key => $data) : ?>
                                                            <option value="<?= $data['CCd'] ?>" settle="<?= $data['Settle'] ?>"><?= $data['Name'] ?></option>
                                                    <?php endforeach;
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#move_table_modal" style="margin-left: 10px;right: 90px;position: absolute;height: 24px;cursor: pointer;">
                                                    <i class="far fa-user"></i>
                                                </button>
                                                <?php
                                                if ($EType == 5) {
                                                    ?>
                                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#merge_table_modal" style="margin-left: 15px;right: 52px;position: absolute;height: 24px;cursor: pointer;">
                                                        <i class="dripicons-network-3"></i>.
                                                    </button>
                                                <?php } ?>

                                                <button class="btn btn-sm btn-danger" onclick="refreshPage()" style="margin-left: 15px;right:18px;position: absolute;cursor: pointer;height: 24px;">
                                                    <i class="mdi mdi-speedometer-slow"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-12">
                                                <span id="hlep_table_list"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <div class="items-data" id="order-view-parent">
                                                <table class="table" id="order-view-table" class="display">
                                                    <thead class="table-header">
                                                        <tr>
                                                            <th>Table No</th>
                                                            <th>Ord AMT</th>
                                                            <th>From Time</th>
                                                            <th>Cell NO</th>
                                                            <th>Action</th>
                                                            <!-- <th>Acc/Rej</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody id="table-view">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <div class="items-table">
                                                <table id="item-detail-table1" class="table table-bordered">
                                                    <thead class="table-header">
                                                        <tr>
                                                            <th>Item</th>
                                                            <th>OQty</th>
                                                            <th>AQty</th>
                                                            <th>DQty</th>
                                                            <th>EDT</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="item-detail-body1"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- bill settlement -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <p>Bill settlement talbe</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- old -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="pending-kitchen-body">
                                            <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                                <div class="col-md-7 table-numbers" style="padding-top: 20px;">
                                                    <div id="mydiv" style="display: none;">
                                                        <div class="card" style="border:3px solid rgba(0,0,0,.125)">
                                                            <div class="card-header" style="     background-color: #7d7d7d;color: white; padding: 0px;">
                                                                <h6 class="modal-title">KOT Details</h6>
                                                            </div>
                                                            <div class="">
                                                                <table class="table table-bordered">
                                                                    <thead class="table-header">
                                                                        <tr>
                                                                            <th style="border:1px solid #dee2e6;">KOT</th>
                                                                            <th style="border:1px solid #dee2e6;">Qty</th>
                                                                            <th style="border:1px solid #dee2e6;">PN0</th>
                                                                            <th style="border:1px solid #dee2e6;">Print</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="kot-list"></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div >
                                                        <div class="tableView-table">


                                                            <div class="form-group">
                                                                Table View
                                                                <select class="form-control form-control-sm col-md-4" id="kitchen-code" onchange="getTableView();">
                                                                    <?php
                                                                    if (count($SettingTableViewAccess) == 1) { ?>
                                                                        <option value="<?= $SettingTableViewAccess[0]['CCd'] ?>" settle="<?= $SettingTableViewAccess[0]['Settle'] ?>"><?= $SettingTableViewAccess[0]['Name'] ?></option>
                                                                    <?php } else {
                                                                        ?>
                                                                        <option value="0" style='display:none;' settle="1">Select Cashier</option>
                                                                        <?php foreach ($SettingTableViewAccess as $key => $data) : ?>
                                                                            <option value="<?= $data['CCd'] ?>" settle="<?= $data['Settle'] ?>"><?= $data['Name'] ?></option>
                                                                    <?php endforeach;
                                                                    } ?>
                                                                </select>
                                                                <span id="hlep_table_list">
                                                                    
                                                                </span>

                                                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#move_table_modal" style="margin-left: 10px;right: 90px;position: absolute;height: 24px;cursor: pointer;">
                                                                    <i class="far fa-user"></i>
                                                                </button>
                                                                <?php
                                                                if ($EType == 5) {
                                                                    ?>
                                                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#merge_table_modal" style="margin-left: 15px;right: 52px;position: absolute;height: 24px;cursor: pointer;">
                                                                        <i class="dripicons-network-3"></i>.
                                                                    </button>
                                                                <?php } ?>

                                                                <button class="btn btn-sm btn-danger" onclick="refreshPage()" style="margin-left: 15px;right:18px;position: absolute;cursor: pointer;height: 24px;">
                                                                    <i class="mdi mdi-speedometer-slow"></i>
                                                                </button>

                                                            </div>
                                                            <div class="items-data" id="order-view-parent">
                                                                <table class="table text-center" id="order-view-table" class="display" style="width:100%">
                                                                    <thead class="table-header">
                                                                        <tr style="background-color: #51519a;color: #FFF;">
                                                                            <th>Table No</th>
                                                                            <th>Ord AMT</th>
                                                                            <th>From Time</th>
                                                                            <th>Cell NO</th>
                                                                            <th>Action</th>
                                                                            <!-- <th>Acc/Rej</th> -->
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="table-view">
                                                                    </tbody>
                                                                </table>
                                                                
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <!-- Item Table -->
                                                <div class="col-md-5" style="padding-top: 20px;">
                                                    <div class="form-group" style="color: #000;">
                                                        Item View
                                                        <?php if ($Kitchen == 1) : ?>
                                                            <!-- <button style="margin-left: 15px;cursor:pointer;" onclick="handleDelivery()">
                                                                <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                                            </button>
                                                            <button style="margin-left: 15px;cursor:pointer;" onclick="handleReassign()">
                                                                <i class="fa fa-retweet" aria-hidden="true"></i>
                                                            </button> -->
                                                        <?php endif; ?>
                                                        <!-- <button class="btn btn-sm btn-primary" style="margin-left: 15px;cursor:pointer;border-radius: 50px;" onclick="handleDecline()">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </button> -->
                                                    </div>
                                                    <div class="items-table" style="max-height: 370px;overflow: auto;">
                                                        <table id="item-detail-table1" class="table table-bordered">
                                                            <thead class="table-header">
                                                                <tr style="background-color: #51519a;color: #FFF;">
                                                                    <th>Item</th>
                                                                    <th>OQty</th>
                                                                    <th>AQty</th>
                                                                    <th>DQty</th>
                                                                    <th>EDT</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="item-detail-body1"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div style="">
                                                            <table class="table table-bordered text-center" id="bill_data_table" style="display: none;">
                                                                <thead>
                                                                    <tr style="background-color: #51519a;color: #FFF;">
                                                                        <th>Bill No</th>
                                                                        <th>Bill Date</th>
                                                                        <?php if($EType == 5){?>
                                                                            <th>Table No</th>
                                                                        <?php } else {?>
                                                                            <th>Cell No</th>
                                                                        <?php } ?>

                                                                        <th>Bill Amt</th>
                                                                        <th>Online Amt</th>
                                                                        <th>Amount</th>
                                                                        <th>Mode</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="bill_table">
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                        </div>

                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <?php $this->load->view('layouts/admin/footer'); ?>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <?php $this->load->view('layouts/admin/color'); ?>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        
        <?php $this->load->view('layouts/admin/script'); ?>


<!-- Modal -->
    <div class="modal fade" id="meargeModel" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Merge / UnMerge</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        
                        <label for="sel1">Select table:</label>
                        <select onchange="selectParentTable()" class="form-control" id="mainTable">
                            <option value="" hidden>Select Main Table No</option>
                            <?php
                            foreach ($selectMergeTable as $key => $value) {
                                echo '  <option value="' . $value['TableNo'] . '">' . $value['TableNo'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <form id="meargeForm" method="post">
                    </form>

                </div>
            </div>
        </div>
    </div>
    </div>


    <!-- The Modal -->
    <div class="modal" id="allocate-item">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header text-center" style="background-color: rgb(243, 243, 103); padding: 5px; display: block;">
                    <h4 class="modal-title">Item Auto-Assign</h4>
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-md-8">
                            <input readonly="" type="text" name="itemName" class="form-control" id="item-name">
                        </div>
                        <div class="col-md-2">
                            <input readonly="" type="text" name="itemPortion" class="form-control" id="item-portion">
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="itemQty" class="form-control" id="item-qty" min="1">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <input type="text" name="customerRemarks" readonly="" id="customer-remarks" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" id="auto-item-prepare" data-dismiss="modal">Auto Assign</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal" id="manual-item">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Item</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    Modal Body
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Detail Modal -->
    <!-- The Modal -->
    <div class="modal" id="order-detail-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Order Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                </div>
                <!-- Modal footer -->
            </div>
        </div>
    </div>

    <!-- Reassign Modal -->
    <!-- The Modal -->
    <div class="modal" id="reassign-order-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Order Reassign</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label>From Table</label>
                        <select class="form-control" id="from-assign-table" read-only="">
                        </select>
                    </div>

                    <div class="form-group">
                        <label>To Table</label>
                        <select class="form-control" id="to-assign-table" onchange="">
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Qty</label>
                        <input id="from-reassign-qty" type="number" max="" min="1">
                    </div>

                    <div class="form-group">
                        <button id="assign-order" class="btn btn-primary">Assign</button>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Decline reason Modal -->
    <!-- The Modal -->
    <div class="modal" id="decline-order-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="decline-title">Decline Reason</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select Decline Reason</label>
                        <select class="form-control" id="decline-order" onchange="">
                            <option value="0">Select</option>
                            <option value="1">Out of stock</option>
                            <option value="2">No longer Prepared</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="new_orders" style="max-height: 600px;overflow: auto;">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header text-center" style="background-color: #51519a !important;color: #FFF;">
                    <h6>NEW ORDERS</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true" style="color: #FFF;">&times;</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">

                    <table class="table table-bordered text-center">
                        <thead>
                            <tr style="background: #b5bbea;">
                                <th>Table No</th>
                                <th>Order Value</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="new_order_list">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


<div class="modal" id="merge_table_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h6>TABLE - JOIN / UNJOIN</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 500px;overflow: auto;">

                <ul class="nav nav-tabs nav-tabs-custom" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Join</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Unjoin</a>
                  </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane p-3 active" id="home" role="tabpanel">
                        <p class="font-size-13 mb-0">
                            <form method="post">
                                <div id="unmerge_tables" class="row">
                                </div>
                                <div class="text-center">
                                    <button id="merge-table" type="button" class="btn btn-success btn-rounded btn-sm mt-4">Join Tables</button>
                                </div>
                                <div class="col-md-12 text-center" id="no-tables" style="display: none;">
                                    <h1>No Tables are Free</h1>
                                </div>
                            </form>
                        </p>
                    </div>
                  <!-- <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form method="post">
                        <div class="merge-table-main">
                            <div class="row" >
                                <div class="col-md-12 ">
                                    <div class="row" id="unmerge-table">
                                        <div class="col-md-12 merge-table-data">
                                            <div id="unmerge_tables" class="row">
                                            </div>
                                            <div class="text-center">
                                                <button id="merge-table" type="button" class="btn btn-primary" style="box-shadow: inset 0 0 0 2000px rgb(31 35 60 / 80%);">Join Tables</button>
                                            </div>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                    <div class="col-md-12 text-center" id="no-tables" style="display: none;">
                                        <h1 style="margin-top: 30px;">No Tables are Free</h1>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                  </div> -->
                  <div class="tab-pane fade p-3 " id="profile" role="tabpanel">
                    <form method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <select class="form-control" id="merged_tables" onchange="get_each_table()">
                                    <option value="">Select Tables</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3" id="merged-table-body">
                        </div>

                        <div class="text-center mt-4">
                            <input type="hidden" id="selected_merge_no">
                            <button type="button" id="unmerge-table-btn" class="btn btn-danger btn-rounded btn-sm" >Unjoin Tables</button>
                        </div>

                        <div class="col-md-12 text-center" id="no-tables" style="display: none;">
                            <h1 style="margin-top: 30px;">No Tables are Free</h1>
                        </div>

                    </form>
                  </div>
                  <!-- <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <form method="post">
                        <div class="merge-table-main text-center">
                            <div class="row text-center" style="margin-left: 0px;margin-right: 0px;">


                                <div class="col-md-10 text-center" style="padding-top: 20px;padding-left: 30px;padding-right: 45px;">
                                    <select class="form-control" id="merged_tables" onchange="get_each_table()">
                                        <option value="">Select Tables</option>
                                    </select>
                                    

                                <div class="row row-margin text-center" id="mergeed-table">

                                    

                                    <div class="col-md-8 merge-table-data">

                                        <table class="table text-center">

                                            <thead>

                                                <tr>

                                                    <th>Action</th>

                                                </tr>

                                            </thead>

                                            <tbody id="merged-table-body"></tbody>

                                        </table>

                                        <div class="text-center">
                                            <input type="hidden" id="selected_merge_no">
                                            <button type="button" id="unmerge-table-btn" class="btn btn-primary" style="box-shadow: inset 0 0 0 2000px rgb(31 35 60 / 80%);">Unjoin Tables</button>

                                        </div>

                                    </div>

                                    <div class="col-md-2"></div>

                                </div>

                                <div class="col-md-12 text-center" id="no-tables" style="display: none;">

                                    <h1 style="margin-top: 30px;">No Tables are Free</h1>

                                </div>

                                </div>

                            </div>

                        </div>
                    </form>
                  </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="move_table_modal">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header">
                <h6>MOVE TABLE</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 500px;overflow: auto;">
                <form method="post" action="ajax/sittin_table_view_ajax.php">
                    <input type="hidden" name="move_table" value="1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>From</label>
                                <select class="form-control select2 custom-select" required="" name="from_table" id="from_table" onchange="get_phone_num()" style="width: 100%;">
                                    <option value="">Table No</option>
                                    <?php foreach($captured_tables as $key){?>
                                        <option value="<?= $key['TableNo']?>"><?= $key['TableNo']?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6" >
                            <div class="form-group">
                                <label>To</label>
                                <select class="form-control select2 custom-select" required="" name="to_table" style="width: 100%;" id="to_table">
                                    <option value="">Table No</option>
                                    <?php foreach($available_tables as $key){?>
                                        <option value="<?= $key['TableNo']?>"><?= $key['TableNo']?></option>
                                    <?php }?>
                                </select>

                            </div>

                        </div>
                        <br>
                        <div class="col-md-12 text-left" id="num_list">
                            
                        </div>

                        <div class="text-right p-4 col-md-12"><button type="submit" class="btn btn-sm btn-primary">MOVE</button></div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <div class="modal" id="help">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="color: black;">Table <button class="btn btn-lg btn-warning" style="border-radius: 50px;" id="help_table"></button> need assist</h4>
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                </div>
                <!-- Modal body -->
                <!-- <div class="modal-body">
                    <div>
                        <span>
                            Can you Please help me out.
                        </span>
                        <button class="btn btn-primary" onclick="view_help()">OK</button>
                    </div>
                </div> -->
                <!-- Modal footer -->
            </div>
        </div>
    </div>
    <div class="modal" id="print_kot">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="text-center pt-2">
                    <div class="text-center"><h5 onclick="print_kots()" style="cursor: pointer;">Print KOT No <span id="kot_no"> </span></h5></div>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div>
                        <table class="table text-center">
                            <thead>
                                <tr style="background-color: #51519a;color: #FFF;">
                                    <th></th>
                                    <th>Kitchen</th>
                                </tr>
                            </thead>
                            <tbody id="print_kot_data">

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Modal footer -->
            </div>
        </div>
    </div>
    <div class="modal" id="settled_table">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <!-- <div class="text-center bg-success text-white" style="cursor: pointer;">
                    <b>Confirm Settlements</b>
                </div> -->
                <div class="modal-header bg-success text-white">
                    <h6 onclick="confirm_settle('all')" style="cursor: pointer;">Confirm All Settlements</h6>
                    <button type="button" class="close text-white" style="background-color: #4407ff;" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr style="background: #b5bbea;">
                                <th>Table No</th>
                                <th>Bill No</th>
                                <th>Amount</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody id="settled_table_data">
                            
                        </tbody>
                    </table>
                </div>
                <!-- Modal footer -->
            </div>
        </div>
    </div>

<script type="text/javascript">
    // $(document).ready(function () {
    //     $('#table_view').DataTable();
    //     $('#item_table').DataTable();
    // });

</script>

<script>

    $(document).ready(function () {
        $('#from_table, #to_table').select2();
    });
        var bill_data;
        if ($("#kitchen-code option:selected").attr("settle") == 0) {
            $('#cashBill_settle').css('display', 'inline-block');
        }
        // not completed
        function printReport() {
            $.ajax({
                url: 'ajax/printReport.php'
            });
        }
        //Global Variables
        var globalItemId = 0;
        var globalTableNo = 0;
        var globalCustId = 0;
        var globalAQty = 0;

        function resetGlobal() {
            globalItemId = 0;
            globalTableNo = 0;
            globalCustId = 0;
            globalAQty = 0;
        }

        function getTableView() {
            var STVCd = $('#kitchen-code').val();
            $.ajax({
                url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                type: "post",
                data: {
                    getTableOrderDetails: 1,
                    STVCd: STVCd
                },
                dataType: 'json',
                success: response => {
                    var template = ``;
                    console.log(response);
                    if (response.status) {
                        response.kitchenData.forEach(tableData);
                    }
                    // $('#order-view-table').remove();
                    // $('#order-view-parent').html(tableStructure);
                    // console.log(template);
                    destroyDataTableForOrder();
                    // getTableView();
                    
                    $('#mydiv').hide();
                    dataTableForOrder();
                    // resetGlobal();
                },
                error: (xhr, status, error) => {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });

            // set globle variable for paycash transaction.
            window.payCash_settle = $("#kitchen-code option:selected").attr("settle");
            //coin hide/display section
            if ($("#kitchen-code option:selected").attr("settle") == 0) {
                $('#cashBill_settle').css('display', 'inline-block');
            } else {
                $('#cashBill_settle').css('display', 'none');
            }
        }
        function tableData(item, index){
            // console.log(item);
            var template = '';
            if (item.BillStat > 0) {
                        template += `<tr onclick="getRowBill(${item.BillId})" id="${item.TableNo}" tableNo="${item.TableNo}" custId="${item.CustId}" cNo="${item.CNo}"  style="background-color: #FB8E7E;" class="${item.BillStat > 0 ? ' bill-paid' : ''} ${item.NEW_KOT > 0 ? ' new_order' : ''} ">
                <td>${item.MergeNo}</td>
                <td>${item.Amt}</td>
                <td>${item.StTime}</td>
                <td>${item.CellNo}</td>
                <td><button class="btn btn-sm btn-success" onclick="handleKot(${item.TableNo},${item.CustId},${item.CNo})"><i class="fa fa-eye"></i></button></td>
                `;
            } else if (item.NEW_KOT == 1) {
                template += `<tr onclick="getRowBill(0)" id="${item.TableNo}" tableNo="${item.TableNo}" custId="${item.CustId}" cNo="${item.CNo}"   style="background-color: #FDCF76;" class="${item.BillStat > 0 ? ' bill-paid' : ''} ${item.NEW_KOT > 0 ? ' new_order' : ''} " >
            <td>${item.MergeNo}</td>
            <td>${item.Amt}</td>
            <td>${item.StTime}</td>
            <td>${item.CellNo}</td>
            <td><button class="btn btn-sm btn-success" onclick="handleKot(${item.TableNo},${item.CustId},${item.CNo})"><i class="fa fa-eye"></i></button></td>
            `;
            } else {
                template += `<tr id="${item.TableNo}" onclick="getRowBill(0)" tableNo="${item.TableNo}" custId="${item.CustId}" cNo="${item.CNo}"  class="${item.BillStat > 0 ? ' bill-paid' : ''}  ${item.NEW_KOT > 0 ? 'new_order' : ''} " >
            <td>${item.MergeNo}</td>
            <td>${item.Amt}</td>
            <td>${item.StTime}</td>
            <td>${item.CellNo}</td>
            <td><button class="btn btn-sm btn-success" onclick="handleKot(${item.TableNo},${item.CustId},${item.CNo})"><i class="fa fa-eye"></i></button></td>
            `;

            }
        //  if (item.Stat == 0) {
        //      template += `
        //  <td>
        //      <i onclick="acceptTable('${item.TableNo}', ${item.CustId},${item.CNo})" class="fa fa-check-square" aria-hidden="true"></i>
        //      <i onclick="rejectTable('${item.TableNo}', ${item.CustId},${item.CNo})" class="fa fa-window-close" aria-hidden="true" style="margin-left: 10px;"></i>
        //  </td>
        // `;
        //  } else if (item.Stat == 1) {
        //      template += `
        //  <td>
        //      <p style="margin-bottom:0px !important;">Accept
        //              <a onclick="payCash('${item.TableNo}',${item.CustId}, ${item.CNo},${item.MergeNo})"><i class="fa fa-arrows" aria-hidden="true" style="padding-left: 15px;"></i></a>

        //      </p>
        //  </td>
        // `;
        //  } else {
        //      template += `
        //  <td>
        //      <p>Reject</p>
        //  </td>
        // `;
        //  }
            if(item.BillStat < 0){
                template+= `<td><p>`;
                var data = item;
                template+=`<button class="btn" v-on:click="setPaidAmount(index, data.BillId,data.CNo,data.TableNo,data.CustId, data.BillNo, data.TotAmt, data.PaymtMode);" style="background: #FFFFFF; height:25px; padding:0px; margin-right:15px;">
                                <img src="images/tick2.png" width="25" height="15"></img>
                            </button>
                            
                            <!-- v-on:click="PrintBill(data.BillId, index,<?= $EID;?>);" Bill Printing -->
                            <a :href="'vtrend:billid='+data.BillId+'&eid=<?= $EID;?>&kotno=0&s=<?= $_SESSION['DynamicDB'];?>'">
                            <button class='btn btn-warning' style="background: #FFFFFF; height:25px; padding:0px">
                                <img src="images/printer.png" width="25" height="25"></img>
                            </button></a>
                            <!-- Bill Cancellation -->
                            <button class="btn btn-danger" style="background: #FFFFFF; height:25px; padding:0px; margin-left: 10px;" v-on:click="rejectBill(data.BillId, index, data.CNo, data.TableNo, data.CustId);">
                                <img src="https://pngimage.net/wp-content/uploads/2018/05/cross-png-icon-8.png" width="25" height="25"></img>
                            </button>`;

                template+=`</p></td>`;
            }

            template += `</tr>`;
            $("#table-view").append(template);
            
        }
        function print_kots(){
            var v = document.getElementsByName('ukots[]');
            // alert(v.length);
            for(i = 0;i<v.length;i++){
                if(v[i].checked){
                    a = 'vtrend:billid=0&eid=<?= $EID;?>&kotno='+v[i].value+'&s=<?= $_SESSION['DynamicDB'];?>';
                    window.location.href=a;
                }
            }
        }
        function getRowBill(id){
            if(id > 0){
                var STVCd = $('#kitchen-code').val();
                $.ajax({
                    url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                    type: "post",
                    data: {
                        BillId: id,
                        STVCd: STVCd,
                        getBill: 1
                    },
                    success: function(data) {
                        // if(id > 0){
                            var temp = JSON.parse(data);
                            $('#bill_data_table').show();
                            data = temp.bill;
                            // alert(temp.payment_modes.length);
                             var et = '<?= $EType?>';
                            var b = '<tr><td><a v-bind:href="bill_rcpt.php?restaurant=1&billId="' + data.BillNo+'" target="_blank" >'+
                                    data.BillNo+'</a></td>';
                            b+='<td>'+data.BillDate+'</td>';
                            if(et == 5){
                                b+='<td>'+data.TableNo+'</td>';
                            }else{
                                b+='<td>'+data.MobileNo+'</td>';
                            }
                            b+='<td>'+ data.TotAmt +'</td>';
                            // if(data.PaymtMode == 'Cash'){
                                b+='<td>'+0+'</td>';
                                b+='<td><input type="number" name="PaidAmt" id="PaidAmt"  v-model="data.BillValue"  value="data.BillValue" style="width: 125px;"></td>';
                                var s = '<select id="cash_payment_mode"><option value="">Select Mode</option>';
                                for(i=0;i<temp.payment_modes.length;i++){
                                    s+='<option value="'+temp.payment_modes[i].PMNo+'">'+temp.payment_modes[i].Name+'</option>';
                                }
                                s+='</select>';
                                b+='<td>'+s+'</td>';
                                // b+=';
                            // }else{
                            //     b+='<td>'+data.PaidAmt+'</td><td>0</td><td>Online</td>';
                            // }
                            
                            b+='<td><button class="btn btn-sm btn-success btn-rounded" onclick="setPaidAmount('+data.BillId+','+data.CNo+','+data.TableNo+','+data.CustId+','+data.BillNo+','+data.TotAmt+',\''+data.PaymtMode+'\');"><i class="fas fa-check-double"></i></button>|<a href="billid='+data.BillId+'&eid=<?= $EID;?>&kotno=0&s=<?= $_SESSION["DynamicDB"]?>"><button class="btn btn-warning btn-rounded btn-sm"><i class="fas fa-print"></i></button></a>|<button class="btn btn-danger btn-rounded btn-sm" onclick="rejectBill('+data.BillId+','+data+','+ data.CNo+','+ data.TableNo+','+ data.CustId+');"><i class="fas fa-window-close"></i></button></td>';
                            // alert(b);
                            $('#bill_table').html(b);
                            bill_data = data;
                            $('#bill_data_table').show();

                        // }else{
                        //  $('#bill_data_table').hide();
                        // }
                        console.log(data);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                });

            }else{
                $('#bill_data_table').hide();
            }
        }
        function destroyDataTableForOrder() {
            var table = $('#order-view-table').DataTable();
            table.destroy();
        }

        function dataTableForOrder() {
            // tetsing me
            console.log('test');
            // destroy datatable
            // $('#order-view-table').destroy();
            var orderTable = $('#order-view-table').DataTable({
                keys: true,
                searching: false, paging: false, info: false
            });

            console.log('vvv'+orderTable);

            orderTable
                .on('key-focus', function(e, datatable, cell) {
                    console.log('vvv');
                    // $('table#order-view-table > tbody > tr').css('background-color', 'white');
                    // $('table#order-view-table > tbody > tr.all-delivered').css('background-color', 'lightgreen');
                    // $('table#order-view-table > tbody > tr.any-assigned').css('background-color', 'yellow');
                    $('table#order-view-table > tbody > tr.new_order').css('background-color', '#FDCF76');
                    $('table#order-view-table > tbody > tr.bill-paid').css('background-color', '#FB8E7E');
                    $('table#order-view-table > tbody > tr.bill-paid').removeClass('active-row');
                    $('table#order-view-table > tbody > tr.new_order').removeClass('active-row');
                    // 
                    
                    // $('td.focus').parent().css('background-color', '#85ffb6');
                    // $('td.focus').parent().css('background-color', '#85ffb6');
                    $('td.focus').parent().addClass('active-row');
                    var tableNo = $('td.focus').parent().attr('tableNo');
                    var custId = $('td.focus').parent().attr('custId');
                    var cNo = $('td.focus').parent().attr('cno');
                    handleKot(tableNo, custId, cNo);
                    getAllItems(tableNo, custId, cNo);
                    resetGlobal();
                });
        }

        // No need
        function destroyDataTableForItem() {
            // var table = $('#item-detail-table').DataTable();
            // table.destroy();
        }

        function dataTableForItem() {
            // $("#item-detail-table").DataTable();
        }

        $(document).ready(function() {
            // Make the DIV element draggable:
            dragElement(document.getElementById("mydiv"));

            function dragElement(elmnt) {
                var pos1 = 0,
                    pos2 = 0,
                    pos3 = 0,
                    pos4 = 0;
                if (document.getElementById(elmnt.id + "header")) {
                    // if present, the header is where you move the DIV from:
                    document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
                } else {
                    // otherwise, move the DIV from anywhere inside the DIV:
                    elmnt.onmousedown = dragMouseDown;
                }

                function dragMouseDown(e) {
                    e = e || window.event;
                    e.preventDefault();
                    // get the mouse cursor position at startup:
                    pos3 = e.clientX;
                    pos4 = e.clientY;
                    document.onmouseup = closeDragElement;
                    // call a function whenever the cursor moves:
                    document.onmousemove = elementDrag;
                }

                function elementDrag(e) {
                    e = e || window.event;
                    e.preventDefault();
                    // calculate the new cursor position:
                    pos1 = pos3 - e.clientX;
                    pos2 = pos4 - e.clientY;
                    pos3 = e.clientX;
                    pos4 = e.clientY;
                    // set the element's new position:
                    elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
                    elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
                }

                function closeDragElement() {
                    // stop moving when mouse button is released:
                    document.onmouseup = null;
                    document.onmousemove = null;
                }
            }

            getTableView();
            dataTableForItem();

            $("#search-table").click(function(event) {
                var tableNo = $("#search-table-value").val();

                if (tableNo !== '') {
                    console.log(tableNo);
                } else {
                    alert("specify table no before search");
                }
            });

            // <?php if ($Kitchen == 0) : ?>
            //  setInterval(function(){
            //      $(".modal").modal('hide');
            //      destroyDataTableForOrder();
            //      refreshPage();
            //  }, 15000);
            // <?php endif; ?>


        });
    </script>

    <!-- handle Casher Action -->
    <script>
        // not completed
        function payCash(TableNo, custId, cNo, mergeNo) {

            $.ajax({
                url: "ajax/payCash_ajax.php",
                type: "post",
                data: {
                    tableNo: TableNo,
                    custId: custId,
                    cNo: cNo,
                    MergeNo:mergeNo
                },
                success: function(data) {
                    if (window.payCash_settle == 1) {
                        window.location = "rest_cash_bill.php";
                    } else {
                        refreshPage();
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }

        function acceptTable(tableNo, custId, cNo) {

            if (confirm(`Orders from Table No ${tableNo} will be accepted`)) {
                $.ajax({
                    url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                    type: "post",
                    data: {
                        acceptTable: 1,
                        tableNo: tableNo,
                        custId: custId,
                        cNo: cNo,
                    },
                    dataType: "json",
                    success: (response) => {
                        // console.log(response);
                        if (response.status == 1) {
                            destroyDataTableForOrder();
                            getTableView();
                        }
                    },
                    error: (xhr, status, error) => {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                });
            }
        }

        function rejectTable(tableNo, custId, cNo) {
            // console.log("Table Rejected");

            if (confirm(`Table No ${tableNo} is Rejected`)) {
                $.ajax({
                    url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                    type: "post",
                    data: {
                        rejectTable: 1,
                        tableNo: tableNo,
                        custId: custId,
                        cNo: cNo
                    },
                    dataType: "json",
                    success: (response) => {
                        console.log(response);
                        if (response.status == 1) {
                            destroyDataTableForOrder();
                            getTableView();
                        }
                    },
                    error: (xhr, status, error) => {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                });
            }
        }

        function handleKot(tableNo, custId, cNo) {
            console.log(tableNo, custId, cNo);
            var eid = '<?= $_SESSION['EID']; ?>';
            // console.log('SES_EID'+eid);
            // $('#mydiv').show();

            $.ajax({
                url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                type: "POST",
                data: {
                    getKot_data: 1,
                    tableNo: tableNo,
                    custId: custId,
                    cNo: cNo
                },
                dataType: "json",
                success: (response) => {
                    // console.log(response);
                    var template = ``;
                    var t = '';
                    var new_head = 0;
                    if (response.status == 1) {
                        response.kots.forEach((item) => {
                            t1 = item. FKOTNo;
                            if(t != t1){
                                t = item. FKOTNo;
                                new_head = 1;
                            }
                            // console.log(tr);
                            // var tr = ${item.TableNo};
                            if (item.KOTPrintNo == 1) {

                                // $("#"+item.TableNo).css("background-color","grey");
                                // <a href="print/print.php?kotNo=${item.UKOTNo}&kitcd=${item.KitCd}"></a>
                                // $("#table-view").css("background-color", "#0100ff3b"); 
                                if(new_head == 1){
                                    template += `<tr style="background-color: #a1aff3;"><td colspan="3"><b>KOT No: ${item.FKOTNo}</b></td>
                                    <td colspan="2" class="text-center" style=""><b> <span style="text-align: right;margin-left: 40px;" onclick="getKitchenData(${item.CNo}, ${item.FKOTNo})"><i class="fa fa-print" aria-hidden="true" style="cursor: pointer;font-size: 18px;"></i></span></b></td>
                                    </tr>`;
                                    new_head = 0;
                                }
                                var b = '';
                                b += '<tr style="background: #FFF;"><td>'+item.ItemNm+'</td><td>'+item.Qty+'</td><td>'+item.AQty+'</td><td>'+item.DQty+'</td><td>'+item.EDT+'</td></tr>';
                                template+=b;
                            } else {
                                // $("#table-view").css("background-color", "#0100ff3b"); 
                                if(new_head == 1){
                                    template += `<tr style="background-color: #a1aff3;"><td colspan="3"><b>KOT No: ${item.FKOTNo}</b></td>
                                    <td colspan="2" class="text-right" ><b><span style="text-align: right;margin-left: 40px;" onclick="getKitchenData(${item.CNo}, ${item.FKOTNo})"><i class="fa fa-print" aria-hidden="true" style="cursor: pointer;font-size: 18px;"></i></span></b></td>
                                    </tr>`;
                                    new_head = 0;
                                }
                                var b = '';
                                b += '<tr style="background: #FFF;"><td>'+item.ItemNm+'</td><td>'+item.Qty+'</td><td>'+item.AQty+'</td><td>'+item.DQty+'</td><td>'+item.EDT+'</td></tr>';
                                template+=b;
                            }

                        });
                    } else {
                        console.log(response.msg);
                    }
                    $("#item-detail-body1").html(template);
                },
                error: (xhr, status, error) => {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }
        function getKitchenData(CNo, FKOTNo){
            $.ajax({
                url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                type: "POST",
                data: {
                    getKitchenData: 1,
                    FKOTNo: FKOTNo,
                    CNo: CNo
                },
                dataType: "json",
                success: (response) => {
                    console.log(response);
                    var template = '';
                    if (response.status == 1) {
                        if(response.data.length > 1){
                            response.data.forEach((item) => {
                                template += '<tr><td><input name="ukots[]" checked type="checkbox" value="'+item.UKOTNo+'" ></td><td>'+item.KitName+'</td></tr>';
                            });
                            $("#print_kot_data").html(template);
                            $('#kot_no').html(FKOTNo);
                            $('#print_kot').modal('show');
                        }else{
                            if(response.data.length){
                                a = 'vtrend:billid=0&eid=<?= $EID;?>&kotno='+response.data[0].UKOTNo+'&s=<?= $_SESSION['DynamicDB'];?>';
                                window.location.href=a;
                            }
                        }
                    } else {
                        console.log(response.msg);
                    }
                    // destroyDataTableForItem();
                    // alert(template);
                    
                    // dataTableForItem();
                },
                error: (xhr, status, error) => {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }
        function getAllItems(tableNo, custId, cNo) {
            // console.log("TABLE_"+tableNo+"CUSTID_"+custId);
            $.ajax({
                url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                type: "POST",
                data: {
                    getAllItems: 1,
                    tableNo: tableNo,
                    custId: custId,
                    cNo: cNo
                },
                dataType: "json",
                success: (response) => {
                    // console.log(response);
                    var template = ``;
                    if (response.status == 1) {
                        response.itemDetails.forEach((item) => {
                            template += `
                            <tr onclick="handleSelectedItem(${item.ItemId}, '${item.TableNo}', ${item.CustId}, ${item.CNo}, ${item.AQty}, this)" class="${item.AQty > 0 ? 'allocated-item' : ''} ${item.Qty == item.DQty ? 'delivered-item' : ''}" style="background-color:${item.AQty > 0 ? 'yellow' : ''} ${item.Qty == item.DQty ? 'lightgreen' : ''};">
                                <td style="width: 300px;">${item.ItemNm}</td>
                                <td>${item.Qty}</td>
                                <td>${item.AQty}</td>
                                <td>${item.DQty}</td>
                                <td>EDT</td>
                            </tr>
                        `;
                        });
                    } else {
                        console.log(response.msg);
                    }
                    destroyDataTableForItem();
                    $("#item-detail-body").html(template);
                    dataTableForItem();
                },
                error: (xhr, status, error) => {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }

        function getItemsByKot(uKotNo, cNo, input) {
            $('#kot-list tr').css('background-color', 'white');
            $(input).css('background-color', '#0100ff3b');
            resetGlobal();

            $.ajax({
                url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                type: "POST",
                data: {
                    getItemsByKot: 1,
                    uKotNo: uKotNo,
                    cNo: cNo
                },
                dataType: "json",
                success: (response) => {
                    // console.log(response);
                    var template = ``;
                    if (response.status == 1) {
                        response.itemDetails.forEach((item) => {
                            template += `
                            <tr onclick="handleSelectedItem(${item.ItemId}, '${item.TableNo}', ${item.CustId}, ${item.CNo},  ${item.AQty}, this)" class="${item.AQty > 0 ? 'allocated-item' : ''} ${item.Qty == item.DQty ? 'delivered-item' : ''}" style="background-color:${item.AQty > 0 ? 'yellow' : ''} ${item.Qty == item.DQty ? 'lightgreen' : ''};">
                                <td style="width: 300px;">${item.ItemNm}</td>
                                <td>${item.Qty}</td>
                                <td>${item.AQty}</td>
                                <td>${item.DQty}</td>
                                <td>EDT</td>
                            </tr>
                        `;
                        });
                    } else {
                        console.log(response.msg);
                    }
                    destroyDataTableForItem();
                    $("#item-detail-body").html(template);
                    dataTableForItem();
                },
                error: (xhr, status, error) => {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }

        function handleSelectedItem(itemId, tableNo, custId, cNo, aQty, input) {
            console.log(itemId, tableNo, custId, cNo);
            globalItemId = itemId;
            globalTableNo = tableNo;
            globalCustId = custId;
            globalCNo = cNo;
            globalAQty = aQty;
            $('#item-detail-body tr').css('background-color', 'white');
            $('#item-detail-body tr.allocated-item').css('background-color', 'yellow');
            $('#item-detail-body tr.delivered-item').css('background-color', 'lightgreen');
            $(input).css('background-color', '#d4d4d4');
        }

        function refreshPage() {
            $("#item-detail-body").html('');
            destroyDataTableForOrder();
            getTableView();
        }

        function handleDelivery() {
            console.log(globalItemId, globalTableNo, globalCustId, globalAQty);
            // Check row is Selected
            if (globalItemId !== 0) {
                // Check item is assigned
                if (globalAQty !== 0) {
                    $.ajax({
                        url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                        type: "post",
                        data: {
                            deliverOrder: 1,
                            itemId: globalItemId,
                            tableNo: globalTableNo,
                            custId: globalCustId,
                            cNo: globalCNo
                        },
                        dataType: 'json',
                        success: response => {
                            // console.log(response);
                            $.ajax({
                                url: "<?php echo base_url('restaurant/sentNotification'); ?>",
                                type: "GET",
                                data: {
                                    CustId: globalCustId,
                                    message: "your order is assigned !",
                                    title: "Order Assigned"
                                },
                                success: function(data) {
                                    console.log(data);
                                },
                                error: function(xhr, status, error) {
                                    console.log(xhr);
                                    console.log(status);
                                    console.log(error);
                                }
                            });
                            getAllItems(globalTableNo, globalCustId);
                        },
                        error: (xhr, status, error) => {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        }
                    });
                } else {
                    alert("No items is assign to deliver");
                }
            } else {
                alert("Please Select Order Item");
            }
        }

        function handleReassign() {
            // Check row is Selected
            if (globalItemId == 0) {
                alert("Please Select Order Item");
            } else {
                if (globalAQty == 0) {
                    alert("No items is assign to reassign");
                } else {
                    $("#reassign-order-modal").modal('show');
                    var template = `
                <option>${globalTableNo}</option>
            `;
                    $("#from-assign-table").html(template);

                    $("#from-reassign-qty").val(globalAQty);
                    $("#from-reassign-qty").attr('max', globalAQty);

                    $.ajax({
                        url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                        type: "post",
                        data: {
                            handleReassign: 1,
                            itemId: globalItemId,
                            tableNo: globalTableNo,
                            custId: globalCustId,
                            cNo: globalCNo
                        },
                        dataType: 'json',
                        success: (response) => {
                            console.log(response);
                            if (response.status) {
                                var template = `
                            <option value="0">Select</option>
                        `;
                                response.kitchenData.forEach((item) => {
                                    template += `
                                <option value="${item.OrdNo}">${item.TableNo}</option>
                            `;
                                });
                            } else {
                                var template = `
                            <option value="0">No Tables Found</option>
                        `;
                            }

                            $("#to-assign-table").html(template);

                        },
                        error: (xhr, status, error) => {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        }
                    });
                }
            }
        }

        $("#assign-order").click(function() {
            var assignToOrderId = $("#to-assign-table").val();
            var assignQty = $("#from-reassign-qty").val();
            // console.log(assignToOrderId, assignQty);

            if (assignToOrderId == 0) {
                alert("Please Select the Table Where You Want The Item Assigned");
            } else {
                $.ajax({
                    url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                    type: "post",
                    data: {
                        reassignOrder: 1,
                        itemId: globalItemId,
                        tableNo: globalTableNo,
                        custId: globalCustId,
                        assignToOrderId: assignToOrderId,
                        assignQty: assignQty,
                        cNo: globalCNo
                    },
                    dataType: 'json',
                    success: (response) => {
                        console.log(response);
                        if (response.status) {
                            $("#reassign-order-modal").modal('hide');
                            getAllItems(globalTableNo, globalCustId);
                        }
                    },
                    error: (xhr, status, error) => {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                });
            }
        });

        function handleDecline() {
            if (globalItemId == 0) {
                alert("Please Select Order Item");
            } else {
                // Check Item Stat
                $.ajax({
                    url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                    type: "post",
                    data: {
                        checkStatForDecline: 1,
                        itemId: globalItemId,
                        tableNo: globalTableNo,
                        custId: globalCustId,
                        cNo: globalCNo
                    },
                    dataType: 'json',
                    success: (response) => {
                        // console.log(response);
                        if (response.status == 1) {
                            $("#decline-title").text(`Decline - ${response.checkItemStat.ItemNm}`);
                            $("#decline-order-modal").modal('show');
                        } else {
                            alert("Item already assigned or delivered on this table");
                            if (confirm("Check For Item on other Tables")) {
                                declineItemForEid();
                            }
                        }
                    },
                    error: (xhr, status, error) => {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                });
            }
        }

        $("#decline-order").change(function() {
            $("#decline-order-modal").modal('hide');
            var declineReason = $("#decline-order").val();
            $("#decline-order").val(0);
            // console.log(declineReason);
            // Decline Item
            $.ajax({
                url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                type: "post",
                data: {
                    declineItem: 1,
                    itemId: globalItemId,
                    tableNo: globalTableNo,
                    custId: globalCustId,
                    declineReason: declineReason,
                    cNo: globalCNo
                },
                dataType: "json",
                success: (response) => {
                    console.log(response);
                    if (response.status) {
                        if (confirm("Check For Item on other Tables")) {
                            declineItemForEid();
                        }
                        // getAllItems(globalTableNo, globalCustId);
                        refreshPage();
                    }

                    var name = response.msg;
                    console.log(name);
                    $.ajax({
                        url: "<?php echo base_url('sentNotification'); ?>",
                        type: "GET",
                        data: {
                            CustId: globalCustId,
                            message: "Order for " + name + " has been Declined. Please raise alternate order",
                            title: "Order Decline"
                        },
                        success: function(data) {
                            console.log(data);
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        }
                    });
                },
                error: (xhr, status, error) => {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        });

        function declineItemForEid() {
            $.ajax({
                url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                type: "post",
                data: {
                    declineItemForEid: 1,
                    itemId: globalItemId,
                },
                dataType: 'json',
                success: (response) => {
                    console.log(response);
                    if (response.status) {
                        alert("Declined from all tables successfully");
                        if (confirm("Disable from Menu")) {
                            disableFromMenu();
                        }
                    }
                },
                error: (xhr, status, error) => {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }

        function disableFromMenu() {
            $.ajax({
                url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                type: "post",
                data: {
                    disableFromMenu: 1,
                    itemId: globalItemId,
                },
                dataType: 'json',
                success: (response) => {
                    console.log(response);
                    if (response.status) {
                        alert("Item disabled from menu");
                    }
                },
                error: (xhr, status, error) => {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }

        // function handleDeliveryBill(kotNo){
        //  if (kotNo !== 0) {
        //      console.log(kotNo);
        //  }else {
        //      alert("Please Select Order");
        //  }
        // }
    </script>
    <script type="text/javascript">
        function selectParentTable() {

            $.ajax({
                url: "<?php echo base_url('restaurant/mergetable_ajax'); ?>",
                type: 'POST',
                data: {
                    tableNo: $('#mainTable').val()
                },
                success: function(response) {
                    console.log(response);

                    var html = response;
                    html += '<button type="button" onclick="meargeTableCheckbox()" class="btn btn-success">Submit</button>';
                    $('#meargeForm').html(html);
                }
            });
        }


        function alreadyMearge(event) {
            console.log($(event).is(':checked'));
            if ($(event).is(':checked')) {
                alert('Already Merged');
            } else {}
        };

        function meargeTableCheckbox() {

            $.ajax({
                type: 'post',
                url: "<?php echo base_url('restaurant/savemergedata_ajax'); ?>",
                data: $('#meargeForm').serialize(),
                success: function(response) {
                    //   if(response){
                    alert(response + ' Tables merge to Table No - ' + $('#mainTable').val());
                    $('#meargeModel').modal('hide');
                    //   }
                }
            });
        }
        function setPaidAmount(id , CNo , TableNo , CustId, billNo, billAmt, pymtMode) {
            // console.log(this.billData[index].PaidAmt);
            // if (parseFloat(this.billData[index].TotAmt) > parseFloat(this.billData[index].PaidAmt)) {
                // alert("Recieved Amount is less than Bill Amount");
            // }else{
                // alert(bill_data.PaymtMode);
                if(bill_data.PaymtMode == 'Cash' && $('#cash_payment_mode').val() == ''){
                    alert('Please Select Payment Mode !');
                }else{
                    var mo = '';
                    var am = $('#PaidAmt').val();
                    if(bill_data.PaymtMode == 'Cash'){
                        mo = $('#cash_payment_mode').val();
                    }else{
                        mo = pymtMode;
                        am = billAmt;
                    }
                    formData = new FormData();
                    formData.append('setPaidAmount', 1);
                    formData.append('paidAmount', am);
                    formData.append('mode', mo);
                    formData.append('id', id);
                    formData.append('CNo', CNo);
                    formData.append('TableNo', TableNo);
                    formData.append('CustId', CustId);
                    formData.append('billNo', billNo);
                    formData.append('billAmt', billAmt);
                    formData.append('pymtMode', pymtMode);
                    // console.log($('#selRt').val());
                    axios.post("<?php echo base_url('restaurant/rest_cash_bill_ajax'); ?>", formData)
                    .then(response => {
                        // console.log(response.data);
                        if(response.data.status == 1) {
                            alert("Successfully Updated");
                            // getTableView();
                            location.reload();
                        }else {
                            console.log("error in updating billing");
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
                }

            // }
        }

        function PrintBill(id,index,eid){
            console.log("ashu");
            // window.location.href = 'print/print_rest.php?billId='+id;
            window.location.href = "vtrend:billid="+id+"&eid="+eid+"&s=<?= $_SESSION['DynamicDB'];?>";
        }

        function rejectBill(id, index, CNo, TableNo, CustId) {
            if (confirm(`Confirm Reject For Bill No: ${this.billData[index].BillNo}`)) {
                // console.log("reject");
                // console.log("bill id "+ id);
                formData = new FormData();
                formData.append('rejectBill', 1);
                formData.append('id', id);
                formData.append('CNo', CNo);
                formData.append('TableNo', TableNo);
                formData.append('CustId', CustId);
                axios.post("<?php echo base_url('restaurant/rest_cash_bill_ajax'); ?>", formData)
                .then(response => {
                    // console.log(response.data);
                    if (response.data.status ==1) {
                        this.getBill();
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            }else {
                console.log("not Rejected");
            }
        }
        var help_table_id = '';
        var list_id = '';
        function check_call_bell(){
            $.ajax({
                url: "<?php echo base_url('restaurant/customer_landing_page_ajax'); ?>",
                type: "post",
                data: {
                    check_call_help: 1,
                    list_id : list_id
                },
                success: function(data) {
                    // alert(data.table_no);
                    data = JSON.parse(data);
                    // alert();
                    if(data != ''){
                        $('#help_table').html(data.table_no);
                        help_table_id = data.id;
                        if(list_id == ''){
                            list_id += data.id;
                        }else{
                            list_id += ','+data.id;
                        }
                        var a = '<button class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="'+data.created_at+'" id="help_table_'+help_table_id+'" onclick="respond_call_help('+help_table_id+')">'+data.table_no+'</button> &nbsp;&nbsp;';
                        $('#hlep_table_list').append(a);
                        if(data.viewed == 0){
                            $('#help').modal('show');
                        }
                    }else{
                        $('#help').modal('hide');
                    }
                }
            });
        }
        function view_help(){
            $.ajax({
                url: "<?php echo base_url('restaurant/customer_landing_page_ajax'); ?>",
                type: "post",
                data: {
                    view_call_help: 1,
                    help_table_id :help_table_id
                },
                success: function(data) {
                    // alert(data);
                    if(data == 1){
                        $('#help').modal('hide');
                    }else{
                        // $('#help').modal('hide');
                        alert("Something went wrong");
                    }
                }
            });
        }
        function respond_call_help(id){
            if(confirm("Assistance Provided?")){
                $.ajax({
                    url: "<?php echo base_url('restaurant/customer_landing_page_ajax'); ?>",
                    type: "post",
                    data: {
                        respond_call_help: 1,
                        help_table_id :id
                    },
                    success: function(data) {
                        // alert(data);
                        if(data == 1){
                            $('#help_table_'+id).hide();
                            alert("Successfully Updated");
                        }else{
                            // $('#help').modal('hide');
                            alert("Something went wrong");
                        }
                    }
                });
            }
        }
        function check_new_orders(){
            // if(confirm("Assistance Provided?")){
                var v = '<?= $TableAcceptReqd?>';
                if(v == 0){
                    $.ajax({
                        url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                        type: "post",
                        data: {
                            check_new_orders: 1
                        },
                        success: function(data) {
                            // alert(data);
                            if(data != ''){
                                $('#new_orders').modal('show');
                                $('#new_order_list').html(data);
                            }else{
                                $('#new_orders').modal('hide');
                            }
                        }
                    });
                }
            // }
        }

        function check_settled_table(){
            // if(confirm("Assistance Provided?")){
                // if(v == 0){
                    $.ajax({
                        url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                        type: "post",
                        data: {
                            check_settled_table: 1
                        },
                        success: function(data) {
                            // alert(data);
                            if(data != 0){

                                $('#settled_table').modal('show');
                                $('#settled_table_data').html(data);
                            }
                            
                        }
                    });
                //}
            // }
        }
        function confirm_settle(cno){
            // if(confirm("Assistance Provided?")){
                // if(v == 0){
                    $.ajax({
                        url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                        type: "post",
                        data: {
                            confirm_settle: 1,
                            CNo: cno
                        },
                        success: function(data) {
                            // alert(data);
                            if(data == 1){
                                $('#settled_table').modal('hide');
                                alert("Successfully Confirmed");
                            }
                        }
                    });
                //}
            // }
        }
        function accept_order(id){
            $.ajax({
                url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                type: "post",
                data: {
                    change_order_status: 1,
                    cno:id,
                    status: 1
                },
                success: function(data) {
                    // alert(data);
                    if(data == 1){
                        alert("Order Accepted");
                    }else{
                        alert("Something went wrong");
                    }
                    
                }
            });
        }
        function reject_order(id){
            $.ajax({
                url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                type: "post",
                data: {
                    change_order_status: 1,
                    cno: id,
                    status: 4
                },
                success: function(data) {
                    // alert(data);
                    if(data == 1){
                        alert("Order Rejected");
                    }else{
                        alert("Something went wrong");
                    }
                    
                }
            });
        }
        // setInterval(function(){ check_call_bell(); }, 3000);
        // setInterval(function(){ check_new_orders(); }, 5000);
        // setInterval(function(){ check_settled_table(); }, 7000);
        check_new_orders();
        // $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        // })
    </script>


    <script type="text/javascript">
        function getUnmergeTables(){

    $.ajax({
        url: '<?php echo base_url('restaurant/merge_table_ajax'); ?>',
        type: 'POST',
        data:{
            getUnmergeTables: 1
        },
        dataType: 'json',
        success: function(response) {
            console.log(response);
            if (response.status) {
                $("#unmerge-table").show();
                availableTables = '';
                response.tables.forEach(function(table) {
                    var b = `<div class="col-md-4">`+`<input type="checkbox" class="form-check-input" value="`+table.TableNo+`" id="`+table.TableNo+`"><label class="form-check-label" for="`+table.TableNo+`">TableNo `+table.TableNo+`</label>`;
                    availableTables += `<div class="col-md-4">`+`<input type="checkbox" class="form-check-input" value="`+table.TableNo+`" id="`+table.TableNo+`" /><label class="form-check-label" for="`+table.TableNo+`">TableNo `+table.TableNo+`</label></div>`;
                });

                $("#unmerge_tables").html(availableTables);
            }else {
                $("#merge-table-body").html('');
                $("#unmerge-table").hide();
                $("#no-tables").show();
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr);
            console.log(status);
            console.log(error);
        }
    });
}

function getMmergedTables(){
    $.ajax({
        url: '<?php echo base_url('restaurant/merge_table_ajax'); ?>',
        type: 'POST',
        data:{
            getMergedTables: 1
        },
        dataType: 'json',
        success: function(response) {
            console.log(response);
            if (response.status) {
                $("#merged-table").show();
                availableTables = ``;
                opt = '';
                response.tables.forEach(function(table) {
                    availableTables += `
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input" id="`+table.MergeNo+`">
                                <label class="form-check-label" for="`+table.MergeNo+`">TableNo `+table.MergeNo+`</label>
                            </td>
                        </tr>
                    `;
                    opt+='<option value="'+table.MergeNo+'">'+table.MergeNo+'</option>';
                });

                $('#merged_tables').append(opt);
            }else {
                $("#merged-table-body").html('');
                $("#merged-table").hide();
                $("#no-tables").show();
            }
        },

        error: function(xhr, status, error) {
            console.log(xhr);
            console.log(status);
            console.log(error);
        }
    });
}



$(document).ready(function() {

    getUnmergeTables();
    getMmergedTables();

    $("#merge-table").click(function(event) {
        var selectedTables = [];
        $(".form-check-input").each(function(index, el) {
            if($(this).is(':checked')) {
                selectedTables.push($(this).attr('id'));
            }
        });

        if (selectedTables.length > 1) {
            $.ajax({
                url: "<?php echo base_url('restaurant/merge_table_ajax'); ?>",
                type: "post",
                data: {
                    mergeTables: 1,
                    selectedTables: JSON.stringify(selectedTables)
                },

                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.status == 1) {
                        getUnmergeTables();
                    }else if (response.status == 0) {
                        alert(response.msg);
                    }else {
                        console.log(response.msg);
                    }
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }else {

        }
    });
    
});
$("#unmerge-table-btn").click(function(event) {
        var selectedTables = [];
        var deselectedTables = [];
// alert("sss");
        $(".form-check-inputt").each(function(index, el) {
            if($(this).is(':checked')) {
                selectedTables.push($(this).attr('id'));
            }else{
                deselectedTables.push($(this).attr('id'));
            }
        });

        // alert(selectedTables);
        var check = true;
        if(selectedTables.length < 2){
            if(confirm("All the tables will get unmerged. Are you sure want to continue?")){
                check = true;
            }else{
                check = false;
            }
        }
        // alert(check);
        if (deselectedTables.length > 0 && check) {
            $.ajax({
                url: "<?php echo base_url('restaurant/merge_table_ajax'); ?>",
                type: "post",
                data: {
                    unmergeTables: 1,
                    selectedTables: JSON.stringify(selectedTables),
                    deselectedTables: JSON.stringify(deselectedTables),
                    MergeNo: $('#selected_merge_no').val()
                },

                dataType: 'json',
                success: function(response) {
                    // console.log(response);

                    // if (response.status == 1) {

                    //  getUnmergeTables();

                    // }else if (response.status == 0) {

                    //  alert(response.msg);

                    // }else {

                    //  console.log(response.msg);

                    // }
                    get_each_table();
                    location.reload();

                },

                error: function(xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }else {
            // alert("You can select Min 2 and Max 4 Tables");
        }
    });
function get_each_table(){
    var v = $('#merged_tables').val();
    $('#selected_merge_no').val(v);
    if(v != ''){
        $.ajax({
            url: '<?php echo base_url('restaurant/merge_table_ajax'); ?>',
            type: 'POST',
            data:{
                getEachTable: 1,
                MergeNo: v
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.status) {
                    $("#merged-table").show();
                    availableTables = ``;
                    response.tables.forEach(function(table) {
                        // availableTables += `

                        //     <tr>

                        //         <td>

                        //             <input type="checkbox" class="form-check-inputt" id="`+table.TableNo+`" merge_no="`+v+`" value"`+table.TableNo+`" checked onchange="">

                        //             <label class="form-check-label" for="`+table.TableNo+`">TableNo `+table.TableNo+`</label>

                        //         </td>

                        //     </tr>

                        // `;

                        availableTables += `<div class="col-md-4">`+`<input type="checkbox" class="form-check-inputt" id="`+table.TableNo+`" merge_no="`+v+`" value"`+table.TableNo+`" checked onchange=""><label class="form-check-label" for="`+table.TableNo+`">&nbsp;TableNo `+table.TableNo+`</label></div>`;

                    });
                    $("#merged-table-body").html(availableTables);
                }else {
                    $("#merged-table-body").html('');
                    $("#merged-table").hide();
                    $("#no-tables").show();
                }
            },

            error: function(xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }else{
        $("#merged-table-body").html('');
    }
}

function unmerge_table(el){
    // alert(el.value);
    // var v = $('#el.')
    $.ajax({
        url: '<?php echo base_url('restaurant/merge_table_ajax'); ?>',
        type: 'POST',
        data:{
            UnmergeTable: 1,
            TableNo: el.id,
            MergeNo:$('#'+el.id).attr('merge_no')
        },
        dataType: 'json',
        success: function(response) {
        }
    });
}

function get_phone_num(){
    // alert(el.value);
    var v = $('#from_table').val();
    $.ajax({
        url: '<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>',
        type: 'POST',
        data:{
            get_phone_num: 1,
            from_table: v
        },
        success: function(response) {
            if(response != 0){
                $('#num_list').html(response);
            }
        }
    });
}
    </script>