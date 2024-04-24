<?php $this->load->view('layouts/admin/head'); ?>
<style>
/*#cashAmtR{
    font-size: 20px;
}*/
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

    /*for landscape view */
    @media (orientation:portrait) {
        .Po_to_land1 {
            display: none;
        }
    }

    @media (orientation:landscape) {
        .Po_to_land {
            display: none;
        }
        #top {
            width: 100%;
        } 
    }

    #top {
        position: fixed;
        /*top: 0;*/
        /*left: 0;*/
        z-index: 999;
        width: 79%;
        background: #fff;
        /*height: 23px;*/
    }

/*mobile screen only*/
@media only screen and (max-width: 480px) {
    #top {
        width: 88%;
    }   
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
            <!-- <div class="Po_to_land" style="width:100%;height:100%;">
                <img src="<?= base_url();?>assets/img/ptl.gif" alt="" width:33px; style="padding-left:29px;">
                <span style="padding: 2px;">Screen Available only Landscape mode on Mobile Devices.</span>
            </div> -->
            <!-- <div class="main-content Po_to_land1"> -->
            <div class="main-content Po_to_land11111">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body" id="top">
                                        <div class="row">
                                            <div class="col-md-6 col-6">
                                                <select class="form-control form-control-sm" id="kitchen-code" onchange="getTableView();">
                                                        <option value="0" settle="1"><?= $this->lang->line('select'); ?></option>
                                                        <?php 
                                                        if(!empty($SettingTableViewAccess)){
                                                            $len = sizeof($SettingTableViewAccess);

                                                        foreach ($SettingTableViewAccess as $key => $data) { ?>
                                                            <option value="<?= $data['CCd'] ?>" settle="0" <?php if($len == 1){ echo 'selected'; } ?>><?= $data['Name'] ?></option>
                                                    <?php } } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-6 text-right">
                                                <button title="Bill Options" class="btn btn-sm btn-warning" style="display: none;" id="btnBillOption"><i class="far fa-eye"></i>
                                                </button>
                                                <button title="Cash Collect" class="btn btn-sm btn-info" style="display: none;" id="btnCash"><i class="fas fa-money-check"></i>
                                                </button>
                                                <button class="btn btn-primary btn-sm" title="Bill Create" id="billCreatebtn" style="display: none;">
                                                    <i class="fas fa-file-invoice"></i>
                                                </button>
                                                
                                                <?php
                                                if (($EType == 5) && ($this->session->userdata('BillMergeOpt') ==1)) {
                                                    ?>
                                                <a class="btn btn-secondary btn-sm" title="Bill Spilt" id="billSplit" target="_blank" style="display: none;">
                                                    <i class="mdi mdi-file-table-box-multiple-outline"></i>
                                                </a>

                                                <?php
                                                 }
                                                if (($EType == 5) && ($this->session->userdata('Move') > 0)) {
                                                    ?>
                                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#move_table_modal" title="Move Table">
                                                    <i class="far fa-user"></i>
                                                </button>
                                                <?php } ?>
                                                <?php
                                                if (($EType == 5) && ($this->session->userdata('JoinTable') > 0)) {
                                                    ?>
                                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#merge_table_modal" title="Join Table">
                                                        <i class="dripicons-network-3"></i>.
                                                    </button>
                                                <?php } ?>

                                                <button class="btn btn-sm btn-danger" onclick="refreshPage()" >
                                                    <i class="mdi mdi-speedometer-slow"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-12 mt-2">
                                                <span id="hlep_table_list"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 57px;">
                            <div class="col-md-7 col-7">
                                <div class="card">
                                    <div class="card-body">
                                        <ul class="nav nav-tabs nav-tabs-custom" id="myTab" role="tablist">
                                          <li class="nav-item">
                                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#OrderList" role="tab" aria-controls="home" aria-selected="true" onclick="changeTableView('orderWise');">   <?= $this->lang->line('orderWise'); ?>
                                            </a>
                                          </li>
                                          <li class="nav-item">
                                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#TableList" role="tab" aria-controls="profile" aria-selected="false" onclick="changeTableView('tableWise');">
                                                <?= $this->lang->line('tableWise'); ?>
                                                </a>
                                          </li>
                                        </ul>

                                <div class="tab-content">
                                    <div class="tab-pane p-3 active" id="OrderList" role="tabpanel">
                                        <div class="table-responsive">
                                            <div class="items-data" id="order-view-parent">
                                                <table class="table" id="order-view-table" class="display">
                                                    <thead class="table-header">
                                                        <tr>
                                                            <th><?= $this->lang->line('tableNo');?></th>
                                                            <th><?= $this->lang->line('orderAmount');?></th>
                                                            <th><?= $this->lang->line('fromTime');?></th>
                                                            <th><?= $this->lang->line('mobile');?></th>
                                                            <th><?= $this->lang->line('visitNo');?></th>
                                                            <!-- <th>Acc/Rej</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody id="table-view">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                  
                                    <div class="tab-pane fade p-3 " id="TableList" role="tabpanel">
                                        <div class="table-responsive">
                                            <div class="items-data1" id="order-view-parent1">
                                                <table class="table" id="order-view-table1" class="display">
                                                    <thead class="table-header">
                                                        <tr>
                                                            <th><?= $this->lang->line('tableNo');?></th>
                                                            <th><?= $this->lang->line('orderAmount');?></th>
                                                            <th><?= $this->lang->line('fromTime');?></th>
                                                            <th><?= $this->lang->line('mobile');?></th>
                                                            <th><?= $this->lang->line('visitNo');?></th>
                                                            <!-- <th>Acc/Rej</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody id="table-view1">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                        
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5 col-5">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <div class="items-table">
                                                <table id="item-detail-table1" class="table table-bordered">
                                                    <thead class="table-header">
                                                        <tr>
                                                            <th><?= $this->lang->line('item');?></th>
                                                            <th><?= $this->lang->line('qqty');?></th>
                                                            <!-- <th>DQty</th> -->
                                        <?php if($this->session->userdata('EDT') == 1){ ?>
                                                            <th><?= $this->lang->line('edt');?></th>
                                                            <?php } ?>
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
                    <div class="table-responsive">
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
    </div>


<div class="modal" id="merge_table_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h6><?= $this->lang->line('tableJoinUnjoin'); ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 500px;overflow: auto;">

                <ul class="nav nav-tabs nav-tabs-custom" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?= $this->lang->line('join'); ?></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?= $this->lang->line('unJoin'); ?></a>
                  </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane p-3 active" id="home" role="tabpanel">
                        <p class="font-size-13 mb-0">
                            <form method="post">
                                <div id="unmerge_tables" class="row">
                                </div>
                                <div class="text-center">
                                    <button id="merge-table" type="button" class="btn btn-success btn-rounded btn-sm mt-4"><?= $this->lang->line('joinTables'); ?></button>
                                </div>
                                <div class="col-md-12 text-center" id="notables" style="display: none;">
                                    <h1><?= $this->lang->line('noTablesAreFree'); ?></h1>
                                </div>
                            </form>
                        </p>
                    </div>
                  
                  <div class="tab-pane fade p-3 " id="profile" role="tabpanel">
                    <form method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <select class="form-control" id="merged_tables" onchange="get_each_table()">
                                    <option value=""><?= $this->lang->line('selectTable'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3" id="merged-table-body">
                        </div>

                        <div class="text-center mt-4">
                            <input type="hidden" id="selected_merge_no">
                            <button type="button" id="unmerge-table-btn" class="btn btn-danger btn-rounded btn-sm" ><?= $this->lang->line('unJoinTables'); ?></button>
                        </div>

                        <div class="col-md-12 text-center" id="no-tables" style="display: none;">
                            <!-- <h1 style="margin-top: 30px;"><?= $this->lang->line('noTablesAreFree'); ?></h1> -->
                        </div>

                    </form>
                  </div>
                  
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="move_table_modal">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header">
                <h6><?= $this->lang->line('moveTable'); ?></h6>
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
                                <label><?= $this->lang->line('from'); ?></label>
                                <select class="form-control select2 custom-select" required="" name="from_table" id="from_table" onchange="get_phone_num()" style="width: 100%;">
                                    <option value=""><?= $this->lang->line('tableNo'); ?></option>
                                    <?php foreach($captured_tables as $key){?>
                                        <option value="<?= $key['TableNo']?>"><?= convertToUnicodeNumber($key['TableNo']); ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6" >
                            <div class="form-group">
                                <label><?= $this->lang->line('to'); ?></label>
                                <select class="form-control select2 custom-select" required="" name="to_table" style="width: 100%;" id="to_table">
                                    <option value=""><?= $this->lang->line('tableNo'); ?></option>
                                    <?php foreach($available_tables as $key){?>
                                        <option value="<?= $key['TableNo']?>"><?= convertToUnicodeNumber($key['TableNo']); ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="col-md-12 text-left" id="num_list">
                            
                        </div>

                        <div class="text-right p-4 col-md-12">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <?= $this->lang->line('move'); ?>
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="combine_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h6><?= $this->lang->line('tableAmount'); ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 500px;overflow: auto;">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr style="background: #b5bbea;">
                                <th>#</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="combine_list">
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-1">
                    <h5 class="card-title mb-4" id="combine_list_title"></h5>
                    <input type="hidden" name="update_mergeNo" id="update_mergeNo">
                    <input type="hidden" name="update_MCNo" id="update_MCNo">
                    <input type="hidden" name="update_tableFilter" id="update_tableFilter">
                    <input type="hidden" name="update_custId" id="update_custId">
                    
                    <button class="btn btn-sm btn-success" onclick="updateMCNo()"><?= $this->lang->line('yes'); ?></button>
                    <button class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><?= $this->lang->line('no'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="modal" id="help" style="width: 300px;height: 300px;position: absolute;left: 50%;top: 50%;margin-left: -150px; margin-top: -150px;">
        <div class="modal-dialog">
            <div class="modal-content">
                
                <div class="modal-header">
                    <h4 class="modal-title">Table <button class="btn btn-sm btn-warning btn-rounded" id="help_table"></button> Need Assist</h4>
                    
                </div>
               
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
                    <div class="table-responsive">
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
                    <div class="table-responsive">
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
                </div>
                <!-- Modal footer -->
            </div>
        </div>
    </div>

    <div class="modal fade" id="assistModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Eatout&nbsp; &nbsp;&nbsp;[Table No. <span id="tbl_no"></span>]</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="assistForm">
                <input type="hidden" id="help_table_text_id" name="help_table_text_id">
                <input type="hidden" name="respond_call_help" value="1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <select name="status" class="form-control" required="">
                                <option value="">Select</option>
                                <option value="1">Assign</option>
                                <option value="3">Close</option>
                                <option value="0">Pending</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <input type="submit" class="btn btn-sm btn-success" value="Submit">
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- bill option model -->
    <div class="modal" id="billModel">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">
                    <h6><?= $this->lang->line('billOptions'); ?></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 500px;overflow: auto;">
                    <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><?= $this->lang->line('billNo'); ?></th>
                                        <th><?= $this->lang->line('itemAmount'); ?></th>
                                        <th><?= $this->lang->line('payable'); ?></th>
                                        <th><?= $this->lang->line('mode'); ?></th>
                                        <th><?= $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody id="billOptionBody">
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end bill option model -->
    <!-- cach collect -->
    <div class="modal" id="cashCollectModel">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">
                    <h6><?= $this->lang->line('cashCollect'); ?></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 500px;overflow: auto;">
                    <div class="row mb-2">
                        <!-- <div class="col-md-3">
                            <span id="fullname">vijay</span>
                        </div> -->
                        <div class="col-md-6">
                            <i class="fas fa-phone-volume" style="color:green;"></i>&nbsp;&nbsp; <span id="mobileno"></span>
                        </div>
                    </div>
                    <form method="post" id="cashForm">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?= $this->lang->line('billNo'); ?></th>
                                    <th><?= $this->lang->line('tableNo'); ?></th>
                                    <th><?= $this->lang->line('billAmount'); ?></th>
                                    <th><?= $this->lang->line('paymentType'); ?></th>
                                    <th><?= $this->lang->line('amount'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cashBody">
                            </tbody>
                        </table>
                    </div>
                    </form>
                    <div class="row mt-4 OTPBlock" style="display: none;">
                        <form method="post" id="paymentForm">
                            <input type="hidden" name="paymentMode" id="paymentMode">
                            <input type="hidden" name="billId" id="paymentBillId">
                            <input type="hidden" name="paymentCustId" id="paymentCustId">
                            <input type="hidden" name="paymentMCNo" id="paymentMCNo">
                            <input type="hidden" name="paymentMergeNo" id="paymentMergeNo">
                            <input type="hidden" name="billAmount" id="paymentAmount">
                            <input type="hidden" name="paymentMobile" id="paymentMobile">
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="number" name="otp" class="form-control form-control-sm" required="" placeholder="OTP">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-sm btn-success" value="Verify">
                                        <button type="button" class="btn btn-sm btn-danger" onclick="sendOTP()">Send</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <small class="text-danger" id="paymentSMS"></small>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- bill Discount model -->
    <div class="modal" id="billDiscountModel">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">
                    <h6>Bill Discount</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 500px;overflow: auto;">
                    <form method="post" id="billDiscForm">
                        <input type="hidden" name="billMergeNo" id="billMergeNo">
                        <input type="hidden" name="tableFilter" id="tableFilter">
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <input type="number" name="billDiscPer" id="billDiscPer" class="form-control form-control-sm" required="" value="0" readonly="">
                            </div>
                            <div class="col-md-6 col-6">
                                <button class="btn btn-sm btn-success" onclick="billCreateA()">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
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

var tableFilter = 'orderWise';
// var tableFilter = 'tableWise';
getTableView();

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
            var CCd = $('#kitchen-code').val();
            $.ajax({
                url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                type: "post",
                data: {
                    getTableOrderDetails: 1,
                    CCd: CCd,
                    filter:tableFilter
                },
                dataType: 'json',
                success: response => {
                    var template = ``;
                    console.log(response);
                    if (response.status) {
                        // response.kitchenData.forEach(tableData);
                            var template = '';
                            var bgcolor = '';
                        response.kitchenData.forEach((item, index) =>{
                            if(item.BillStat == 0 && item.OType == 8){
                                bgcolor = '#d5d2d2'; // grey
                            }else if(item.BillStat == 1 && item.custPymt == 0 && item.payRest == 0){
                                // bill generated  by customer
                                bgcolor = '#fff192'; // yellow
                            }else if(item.BillStat == 5 && item.custPymt == 0 && item.payRest == 0){
                                // bill generated  by restaurant
                                bgcolor = '#B1F9A4';//green
                            }else if(item.BillStat == 1 && item.custPymt == 1){
                                // bill generated and paid by customer
                                bgcolor = '#FBBF77'; // ORRANGE
                            }else if(item.BillStat == 5 && (item.custPymt == 1 || item.payRest == 0 )){
                                // bill generated rest and paid by customer
                                bgcolor = '#B3E5FC'; // blue
                            }

                            template += `<tr  id="${item.TableNo}" mergeNo="'${item.MergeNo}'" custId="${item.CustId}" mCNo="${item.MCNo}" billStat="${item.BillStat}" oTyp="${item.OType}"   style="background-color: ${bgcolor};" class="" >
                            <td><input type="radio" name="selectOption" onchange="handleKot('${item.MergeNo}',${item.CustId},${item.MCNo},${item.BillStat},${item.OType})"> &nbsp;${convertToUnicodeNo(item.MergeNo)}
                            <?php if($this->session->userdata('tableSharing') > 0){ ?>
                            -${convertToUnicodeNo(item.SeatNo)}
                        <?php } ?>
                            </td>
                            <td>${convertToUnicodeNo(item.Amt)}</td>
                            <td>${convertToUnicodeNo(item.StTime)}</td>
                            <td>${convertToUnicodeNo(item.CellNo)}</td>
                            <td>${convertToUnicodeNo(item.visitNo)}
                            <input type="hidden" name="custId" value="${item.CustId}">
                            <input type="hidden" name="MCNo" value="${item.MCNo}">
                            <input type="hidden" name="MergeNo" value="'${item.MergeNo}'">
                            <input type="hidden" name="billId" value="">
                            </td>`;

                            template += `</tr>`;
                        });

                            if(tableFilter == 'tableWise'){
                                $("#table-view1").html(template);
                            }else{
                                $("#table-view").html(template);
                            }
                    }
                    
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
       
        function changeTableView(val){
            tableFilter = val;
            $("#item-detail-body1").empty();
            getTableView();
        }

        function handleKot(mergeNo, custId, MCNo, BillStat, oTyp) {
            console.log(mergeNo, custId, MCNo, BillStat, oTyp);
            
            var eid = '<?= $_SESSION['EID']; ?>';
            var kotNo = "<?= $this->lang->line('kotNo'); ?>";
            // console.log('SES_EID'+eid);
            // $('#mydiv').show();

            $('#btnBillOption').hide();
            $('#btnCash').hide();
            // $('#billCreatebtn').show();
            if(BillStat > 0){
                $('#btnBillOption').attr('onclick', "billOptions("+custId+","+MCNo+",'"+mergeNo+"')");
                $('#btnBillOption').show();

                $('#btnCash').attr('onclick', "cashCollect("+custId+","+MCNo+",'"+mergeNo+"',"+oTyp+")");
                $('#btnCash').show();
                $('#billCreatebtn').hide();
                $('#billSplit').hide();
            }else{
                
                if(tableFilter == 'tableWise'){
                    // $('#billCreatebtn').attr('onclick', "billMerge('"+mergeNo+"',"+custId+", '"+tableFilter+"')");
                    checkCNoForTable(mergeNo,tableFilter, MCNo, custId);
                }else{
                    $('#billCreatebtn').attr('onclick', "billCreate('"+mergeNo+"',"+custId+", '"+tableFilter+"')");    
                }
                $('#billCreatebtn').show();

                var url = "<?php echo base_url('restaurant/splitBill/'); ?>"+MCNo+'/'+mergeNo+'/'+tableFilter;
                $('#billSplit').attr("href", url);
                $('#billSplit').show();
            }
            $.ajax({
                url: "<?php echo base_url('restaurant/sittin_table_view_ajax'); ?>",
                type: "POST",
                data: {
                    getKot_data: 1,
                    mergeNo: mergeNo,
                    custId: custId,
                    cNo: MCNo,
                    tableFilter : tableFilter
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
                                t = item.FKOTNo;
                                new_head = 1;
                            }
                            // console.log(tr);
                            // var tr = ${item.TableNo};
                            if (item.KOTPrintNo == 1) {
 
                                if(new_head == 1){
                                    template += `<tr style="background-color: #a1aff3;"><td colspan="3"><b>${kotNo}: ${convertToUnicodeNo(item.FKOTNo)}</b><b> <span style="text-align: right;margin-left: 40px;" onclick="getKitchenData(${item.MCNo}, '${item.MergeNo}',${item.FKOTNo}, ${item.KOTNo})"><i class="fa fa-print" aria-hidden="true" style="cursor: pointer;font-size: 18px;"></i></span></b></td>
                                    </tr>`;
                                    new_head = 0;
                                }
                                var b = '';
                                b += '<tr style="background: #FFF;"><td>'+item.ItemNm+'</td>\
                                <td>'+convertToUnicodeNo(item.Qty)+'</td>\
                                <?php if($this->session->userdata('EDT') == 1){ ?>
                                <td>'+convertToUnicodeNo(item.EDT)+'</td>\
                                <?php } ?>
                                </tr>';
                                template+=b;
                            } else {
                                // $("#table-view").css("background-color", "#0100ff3b"); 
                                if(new_head == 1){
                                    template += `<tr style="background-color: #a1aff3;"><td colspan="3"><b>${kotNo}: ${(item.FKOTNo)}</b><b> <span style="text-align: right;margin-left: 40px;" onclick="getKitchenData(${item.MCNo}, '${item.MergeNo}',${item.FKOTNo}, ${item.KOTNo})"><i class="fa fa-print" aria-hidden="true" style="cursor: pointer;font-size: 18px;"></i></span></b></td>
                                    </tr>`;
                                    new_head = 0;
                                }
                                var b = '';
                                b += '<tr style="background: #FFF;"><td>'+item.ItemNm+'</td><td>'+convertToUnicodeNo(item.Qty)+'</td>\
                                <?php if($this->session->userdata('EDT') == 1){ ?>
                                <td>'+convertToUnicodeNo(item.EDT)+'</td>\
                                <?php } ?>
                                </tr>';
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

        function checkCNoForTable(MergeNo, tableFilter, MCNo, custId){
            // console.log(mergeNo)
            var title = "<?= $this->lang->line('CombineTheFollowingAmountsFromTable'); ?>";
            $.post('<?= base_url('restaurant/checkCNoForTable') ?>',{MergeNo:MergeNo},function(response){

                if(response.status == 'success') {
                    var data = response.response;
                    var temp = '';
                    var count = 0;
                    for(i=0; i<data.length; i++){
                        count++;
                        temp += `<tr><td>${count}</td><td>${data[i].OrdAmt}</td></tr>`;
                    }
                    $('#combine_list').html(temp);
                    title = title+' '+MergeNo;
                    $('#combine_list_title').html(title);
                    $('#update_mergeNo').val(MergeNo);
                    $('#update_MCNo').val(MCNo);
                    $('#update_tableFilter').val(tableFilter);
                    $('#update_custId').val(custId);
                    $('#combine_modal').modal('show');
                }else {
                    alert(response.status);
                }
            });
        }

        updateMCNo = () => {
            var MergeNo = $('#update_mergeNo').val();
            var MCNo = $('#update_MCNo').val();
            var custId = $('#update_custId').val();
            var tableFilter = $('#update_tableFilter').val();

            $.post('<?= base_url('restaurant/updateMCNoForTable') ?>',{MergeNo:MergeNo,MCNo:MCNo},function(response){

                if(response.status == 'success') {
                    // alert(response.status);
                    billCreate(MergeNo, custId, tableFilter);
                }else {
                    alert(response.status);
                }
                // location.reload();
            });
            
        }

    </script>

    <!-- handle Casher Action -->
    <script>

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

        function billOptions(custId, MCNo, mergeNo){
            $.post('<?= base_url('restaurant/get_billing_details') ?>',{custId:custId,MCNo:MCNo,mergeNo:mergeNo},function(res){
                if(res.status == 'success'){
                  var data = res.response;
                  var temp = '';
                
                  if(data.length > 0){
                      for (var i =0;  i< data.length; i++) {
                        var bilUrl = "<?= base_url('restaurant/bill/'); ?>"+data[i].BillId;
                        var settleBtn = '';

                        if(Math.round(data[i].bpPaidAmt) >= Math.round(data[i].PaidAmt) ){

                            settleBtn = '<button class="btn btn-sm btn-success" onclick="setPaidAmount('+data[i].BillId+','+data[i].CNo+',\''+data[i].MergeNo+'\','+data[i].CustId+','+data[i].BillNo+','+data[i].TotBillAmt+',\''+data[i].pymtName+'\')">\
                                            <i class="fas fa-check-double"></i> \
                                        </button>';
                        } 
                          temp +='<tr>\
                                    <td><a href="'+bilUrl+'">'+convertToUnicodeNo(data[i].BillNo)+'</a></td>\
                                    <td>'+convertToUnicodeNo(data[i].BillValue)+'</td>\
                                    <td>'+convertToUnicodeNo(data[i].PaidAmt)+'</td>\
                                    <td>'+data[i].pymtName+'</td>\
                                    <td>\
                                    <?php if($this->session->userdata('AutoSettle') == 0){ ?>
                                        '+settleBtn+'\
                                    <?php } ?>
                                        <a class="btn btn-sm btn-info" href="<?= base_url('restaurant/print/'); ?>'+data[i].BillId+'">\
                                            <i class="fas fa-print"></i> \
                                        </a>\
                                        <button class="btn btn-sm btn-danger" onclick="rejectBill('+data[i].BillId+','+i+','+ data[i].CNo+','+ data[i].MergeNo+','+ data[i].CustId+')">\
                                            <i class="fas fa-window-close"></i> \
                                        </button>\
                                        </td>\
                                </tr>';
                      }
                    }else{
                        temp +='<tr style="color:red;text-align:center;"><td colspan="5">Payment Not Found!</td></tr>';
                    }
                  $('#billOptionBody').html(temp);
                  $('#billModel').modal('show');
                }else{
                  alert(res.response);
                }
            });
        }

        function getKitchenData(MCNo, MergeNo, FKOTNo, KOTNo){
            // window.location.href="<?= base_url('restaurant/kot_print/'); ?>"+MCNo+'/'+MergeNo+'/'+FKOTNo;

            window.open(
              "<?= base_url('restaurant/kot_print/'); ?>"+MCNo+'/'+MergeNo+'/'+FKOTNo+'/'+KOTNo,
              '_blank' 
            );
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
                    
                    $("#item-detail-body").html(template);
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

        refreshPage = () => {
            getTableView();
            <?php if($this->session->userdata('CustAssist') > 0){ ?>
                check_call_bell();
            <?php } ?>
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
        function setPaidAmount(id , CNo , MergeNo , CustId, billNo, billAmt, pymtMode) {

            $.post('<?= base_url('restaurant/bill_settle') ?>',{billId:id,CNo:CNo,MergeNo:MergeNo,CustId:CustId,billNo:billNo,billAmt:billAmt},function(response){

                if(response.status == 'success') {
                    alert(response.response);
                    // refreshPage();
                    // getTableView();
                }else {
                    alert(response.response);
                }
                $('#billModel').modal('hide');
                location.reload();
            });
        }

        function rejectBill(id, index, CNo, TableNo, CustId) {
           
        }

        var help_table_id = '';
        var list_id = '';
        check_call_bell();
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
                    console.log(data);
                    if(data != ''){

                        var a = '';
                        for(var i=0; i<data.length; i++){
                            var sts = 'danger';
                            if(data[i].response_status == 1){
                                sts = 'success';
                            }
                            a += '<button class="btn btn-sm btn-'+sts+' btn-rounded" data-toggle="tooltip" data-placement="top" title="'+data[i].created_at+'" id="help_table_'+data[i].id+'" onclick="respond_call_help('+data[i].id+','+data[i].table_no+')">'+data[i].table_no+'</button> &nbsp;&nbsp;'
                        }
                        // var a = '<button class="btn btn-sm btn-danger btn-rounded" data-toggle="tooltip" data-placement="top" title="'+data.created_at+'" id="help_table_'+help_table_id+'" onclick="respond_call_help('+help_table_id+')">'+data.table_no+'</button> &nbsp;&nbsp;';
                        // $('#hlep_table_list').append(a);
                        $('#hlep_table_list').html(a);
                        if(data.viewed == 0){
                            // $('#help').modal('show');
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
        function respond_call_help(id, tblNo){
            $('#assistModal').modal('show');
            $('#help_table_text_id').val(id);
            $('#tbl_no').html(tblNo);
        }

        $('#assistForm').on('submit', function(e){
            e.preventDefault();
            var id = $('#help_table_text_id').val();
            var data = $(this).serializeArray();

            $.ajax({
                    url: "<?php echo base_url('restaurant/customer_landing_page_ajax'); ?>",
                    type: "post",
                    data: data,
                    success: function(data) {
                        // alert(data);
                        $('#assistModal').modal('hide');
                    }
                });            
        })

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
        <?php if($this->session->userdata('new_order') > 0){ ?>
            check_new_orders();
        <?php } ?>
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
                    var TableNoL = "<?= $this->lang->line('tableNo'); ?>";
                    var b = `<div class="col-md-4 col-6">`+`<input type="checkbox" class="form-check-input" value="`+table.TableNo+`" id="`+table.TableNo+`"><label class="form-check-label" for="`+table.TableNo+`">TableNo `+table.TableNo+`</label>`;
                    availableTables += `<div class="col-md-4 col-6">`+`<input type="checkbox" class="form-check-input" value="`+table.TableNo+`" id="`+table.TableNo+`" /><label class="form-check-label" for="`+table.TableNo+`">`+TableNoL+`  `+convertToUnicodeNo(table.TableNo)+`</label></div>`;
                });

                $("#unmerge_tables").html(availableTables);
                $("#notables").hide();
            }else {
                $("#merge-table-body").html('');
                $("#unmerge-table").hide();
                $("#notables").show();
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

function billCreate(mergeNo, custId, tableFilter){
    var mergeNo = "'"+mergeNo+"'";
        // console.log(mergeNo);
    var disc = "<?php echo $this->session->userdata('Discount'); ?>";
    if(disc > 0){
        if(custId > 0){
            $.post('<?= base_url('restaurant/checkCustDiscount') ?>',{custId:custId},function(res){
                if(res.status == 'success'){
                    var data = res.response;
                    if(data.Disc > 0){
                        $('#billDiscPer').val(data.Disc);
                        $('#billMergeNo').val(mergeNo);
                        $('#tableFilter').val(tableFilter);
                        $('#billDiscountModel').modal('show');
                    }else{
                        // alert('Bill Create Without Discount');
                    billCreateWitoutDisc(mergeNo, tableFilter);
                    }
                }else{
                  alert(res.response);
                }
            });
        }else{
            billCreateWitoutDisc(mergeNo, tableFilter);    
        }
    }else{
        billCreateWitoutDisc(mergeNo, tableFilter);
    }
}

function billCreateWitoutDisc(mergeNo, tableFilter){
    var custDiscPer = 0;
    $.post('<?= base_url('restaurant/billCreateRest') ?>',{mergeNo:mergeNo,custDiscPer:custDiscPer, tableFilter:tableFilter},function(res){
        if(res.status == 'success'){
            var billId = res.response;
          window.location = "<?php echo base_url('restaurant/bill/'); ?>"+billId;
          return false;
        }else{
          alert(res.response);
          
        }
    });
}

function billCreateA(){
    var mergeNo = $('#billMergeNo').val();
    var custDiscPer = $('#billDiscPer').val();
    var tableFilter = ('#tableFilter').val();
    $.post('<?= base_url('restaurant/billCreateRest') ?>',{mergeNo:mergeNo, custDiscPer:custDiscPer, tableFilter:tableFilter},function(res){
        if(res.status == 'success'){
            var billId = res.response;
          window.location = "<?php echo base_url('restaurant/bill/'); ?>"+billId;
        }else{
          alert(res.response);
          
        }
    });
}

function cashCollect(custId, MCNo, mergeNo, oType){
    
    $.post('<?= base_url('restaurant/get_cash_collect') ?>',{custId:custId,MCNo:MCNo,mergeNo:mergeNo},function(res){
        if(res.status == 'success'){
          var data = res.response.bills;
          var sts = res.response.sts;
          var payModes = res.response.payModes;
          var paymentReceived = "<?= $this->lang->line('paymentReceived'); ?>";

          
          $("#cashAmtR").prop("disabled", true);

          if(oType == 7){
            // alert(oType)
            $('#cashAmtR').prop('readonly', true);
          }

          if(sts > 0){
                if(data.length > 0){
                    $('#mobileno').html(data[0].CellNo);
                    var temp = '';
                    for(let j = 0; j < data.length; j++){
                        var pm = `<select name='PymtType' id='PymtType' required='' class='form-control form-control-sm' onchange='changeModes(${data[j].BillId}, ${data[j].CustId}, ${data[j].PaidAmt}, ${data[j].CellNo}, ${data[j].MergeNo}, ${data[j].CNo})' >`;

                          for (var i =0;  i< payModes.length; i++) {
                              pm +='<option value="'+payModes[i].PymtMode+'">'+payModes[i].Name+'</option>';
                            }
                            pm +='</option>';
                        temp +='<tr>\
                                    <td>'+convertToUnicodeNo(data[j].BillNo)+'</td>\
                                    <td>'+convertToUnicodeNo(data[j].MergeNo)+'</td>\
                                    <td>'+convertToUnicodeNo(data[j].PaidAmt)+'</td>\
                                    <td>'+pm+'</td>\
                                    <td>\
                                    <input type="hidden" name="oType" value="'+oType+'"/>\
                                    <input type="hidden" name="TableNo" value="'+data[j].TableNo+'"/>\
                                        <input type="hidden" name="BillId" value="'+data[j].BillId+'"/>\
                                        <input type="hidden" name="MCNo" value="'+data[j].CNo+'"/>\
                                        <input type="hidden" name="EID" value="'+data[j].EID+'"/>\
                                        <input type="hidden" name="MergeNo" value="'+data[j].MergeNo+'"/>\
                                        <input type="hidden" name="CellNo" value="'+data[j].CellNo+'"/>\
                                        <input type="hidden" name="TotBillAmt" value="'+data[j].PaidAmt+'"/>\
                                        <input type="text" name="PaidAmt" style="width:70px;" required id="cashAmtR" value="'+convertToUnicodeNo(data[j].PaidAmt)+'" onblur="changeValue(this)" />\
                                        <input type="hidden" name="CustId" value="'+data[j].CustId+'"/>\
                                        </td>\
                                    <td>\
                                        <button type="button" onclick="cashCollectData()" class="btn btn-sm btn-success" id="cashBtn_'+data[j].BillId+'">\
                                            <i class="fas fa-save"></i>\
                                        </button>\
                                        </td>\
                                </tr>';
                    }
                }

            }else{
                temp = '<tr><td colspan="6" class="text-center">'+paymentReceived+'</td></tr>';
            } 

          
          $('#cashBody').html(temp);
          $('#cashCollectModel').modal('show');
        }else{
          alert(res.response);
        }
    });
}

 function changeModes(billId, custId, billAmount, mobile, MergeNo, MCNo){

    var pmode = $('#PymtType').val();

    if( mobile.toString().length < 10 && (pmode >= 20 && pmode <= 30)){
        alert(`You can not select payment ${pmode} mode because invalid mobile no`);
        $('#PymtType').val('');
        return false;
    }else if(mobile.toString().length >= 10 && (pmode >= 20 && pmode <= 30)){
            
            $('#paymentBillId').val(billId);
            $('#paymentMobile').val(mobile);
            $('#paymentAmount').val(billAmount);
            $('#paymentMode').val(pmode);
            $('#paymentCustId').val(custId);
            $('#paymentMergeNo').val(MergeNo);
            $('#paymentMCNo').val(MCNo);
            $(`#cashBtn_${billId}`).prop("disabled", true);
            $('.OTPBlock').show();
        }
}

function sendOTP(){
    var mobile = $('#paymentMobile').val();
    $.post('<?= base_url('restaurant/send_payment_otp') ?>',{mobile:mobile},function(res){
            if(res.status == 'success'){
              $('#paymentSMS').html(res.response);  
            }else{ 
              alert(res.response);  
            }
    });
}

$('#paymentForm').on('submit', function(e){
    e.preventDefault();
    var data = $(this).serializeArray();

    var billId = data[1]['value'];
    
    $.post('<?= base_url('restaurant/settle_bill_without_payment') ?>', data, function(res){
        if(res.status == 'success'){
            $(`#cashBtn_${billId}`).prop("disabled", false);
            $('.OTPBlock').hide();
            $('#cashCollectModel').modal('hide');
            getTableView();
        }else{ 
          $('#paymentSMS').html(res.response);
        }
    });
})

function cashCollectData(){
    var data = $('#cashForm').serializeArray();
  
    var TotBillAmt = data[8].value;
    var PaidAmt = data[9].value;
    
    PaidAmt = convertDigitToEnglish(PaidAmt);
    data[9].value = PaidAmt;
  if(parseFloat(PaidAmt) >= parseFloat(TotBillAmt)){
    $.post('<?= base_url('restaurant/collect_payment') ?>',data,function(res){
        if(res.status == 'success'){
          alert(res.response);
            getTableView();
        }else{
          alert(res.response);
        }
        // location.reload();
        $('#cashCollectModel').modal('hide');
        getTableView();
    });
  }else{
    alert('Amount has to be greater than or equal to Bill Amount.');
  }
}

function changeValue(input) {
    var val = $(input).val();
    $(input).val(convertToUnicodeNo(val));
}

</script>