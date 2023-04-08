<?php $this->load->view('layouts/admin/head'); ?>
 
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
                                        <div class="Po_to_land" style="width:100%;height:100%;">
                                        <!-- <img src="assets/img/ptl.gif" alt="" width:33px; style="padding-left:29px;"> -->
                                        </div>
                                        <div class="Po_to_land1">
                                            <div class="pending-kitchen-body">
                                                <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                                    <div class="col-md-7  col-sm-7 table-numbers" style="padding-top: 20px;">
                                                        <div id="mydiv" style="display: none;">
                                                            <div class="card" style="border:3px solid rgba(0,0,0,.125)">
                                                                <div class="card-header" style="background-color: lightgreen; padding: 0px;">
                                                                    <h4 class="modal-title">Order Details</h4>
                                                                </div>
                                                                <div class="">
                                                                    <table class="table table-bordered">
                                                                        <thead class="table-header">
                                                                            <tr>
                                                                                <th>Item Name</th>
                                                                                <th>Qty</th>
                                                                                <th>AMT</th>
                                                                                <th>AQty</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="order-list"></tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="tableView-table">
                                                                <div class="form-group" style="color: white">
                                                                    <span style="color: #000;">Dispense :</span>
                                                                    <select class="col-md-4" id="kitchen-code" onchange="getTableView();" style="width: auto;">  
                                                                        <?php
                                                                        if(count($DispenseAccess) == 1){?>
                                                                            <option value="<?= $DispenseAccess[0]['DCd']?>"><?= $DispenseAccess[0]['Name']?></option>
                                                                        <?php }else{
                                                                        ?>
                                                                        <option value="0" style='display:none;'>Select</option>
                                                                        <?php foreach($DispenseAccess as $key => $data):?>
                                                                        <option value="<?= $data['DCd']?>"><?= $data['Name']?></option>
                                                                        <?php endforeach;} ?>
                                                                    </select>
                                                                <?php
                                                                 if(($EType == 1 && $Cash>0) || $EType == 5){ 
                                                                ?>
                                                                    <!-- <a
                                                                        href="/rest_cash_bill.php"
                                                                        style="cursor: pointer; position: absolute; right: 18px;cursor:pointer;">
                                                                        <img src="assets/img/coin.png" width="20px" height="20px" style="cursor:pointer;">
                                                                    </a> -->

                                                                 <?php } 
                                                                     ?>
                                                                    
                                                                    
                                                                </div>
                                                                <div class="items-data" id="order-view-parent">
                                                                    <table class="display" id="order-view-table">
                                                                        <thead class="table-header">
                                                                            <tr>
                                                                                <th>Bill No</th>
                                                                                <th>OQty</th>
                                                                                <th class="hidden-sm">AQty</th>
                                                                                <th>Cell NO</th>
                                                                                <th>3P</th>
                                                                                <th>3P Ref</th>
                                                                                <th>Deliver</th>
                                                                                <th>Order</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="table-view"></tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 col-sm-5 items-table" style="padding-top: 20px;">
                                                        <div>
                                                            <!-- Header -->
                                                            <?php //include_once 'rest_header.php'; ?>
                                                            <div class="text-left form-group">
                                                                <span style="color: #000;">Order Details :</span>
                                                                <!-- <?php foreach($kitchenData as $data): ?>
                                                                <label style="margin-left: 10px;">
                                                                    <input type="radio" name="kitchen" value="<?= $data['KitCd']; ?>">
                                                                    <?= $data['KitName']; ?>
                                                                </label>
                                                                <?php endforeach; ?> -->
                                                            </div>
                                                            <div class="items-data" id="item-table-parent">
                                                                <table class="display" id="item-view-table" style="width:100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Item Name</th>
                                                                            <th>PQty</th>
                                                                            <th>Take Away</th>
                                                                            <th>Remark</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="item-view-tbody1" style="color:white;"></tbody>
                                                                </table>
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
                                                        <div
                                                            class="modal-header text-center"
                                                            style="background-color: rgb(243, 243, 103); padding: 5px; display: block;">
                                                            <h4 class="modal-title">Item Auto-Assign</h4>
                                                            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            -->
                                                        </div>
                                                        <!-- Modal body -->
                                                        <div class="modal-body">
                                                            <div class="row form-group">
                                                                <div class="col-md-8">
                                                                    <input
                                                                        readonly=""
                                                                        type="text"
                                                                        name="itemName"
                                                                        class="form-control"
                                                                        id="item-name">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input
                                                                        readonly=""
                                                                        type="text"
                                                                        name="itemPortion"
                                                                        class="form-control"
                                                                        id="item-portion">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input type="number" name="itemQty" class="form-control" id="item-qty" min="1">
                                                                </div>
                                                            </div>
                                                            <div class="row form-group">
                                                                <div class="col-md-12">
                                                                    <input
                                                                        type="text"
                                                                        name="customerRemarks"
                                                                        readonly=""
                                                                        id="customer-remarks"
                                                                        class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 text-center">
                                                                    <button
                                                                        type="button"
                                                                        class="btn btn-primary"
                                                                        id="auto-item-prepare"
                                                                        data-dismiss="modal">Auto Assign</button>
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
                                                            <!-- <table class="table table-bordered"> <thead> <tr> <th>Item Name</th>
                                                            <th>Qty</th> <th>AMT</th> <th>AQty</th> </tr> </thead> <tbody
                                                            id="order-list"></tbody> </table> -->
                                                        </div>
                                                        <!-- Modal footer -->
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- order_deliver_otp Modal -->
                                            <div class="modal fade" id="order_deliver_otp" role="dialog">
                                                <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h4 class="modal-title order_deliver_header">OTP for </h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body" style="padding: 1.3rem;">
                                                    <form class="form-inline" style="margin-bottom: 1px;place-content: center;">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="confirm_otp_field" placeholder="Please Enter OTP">
                                                        </div>
                                                    </form>
                                                    <p id="confirm_order_error" style=" position: absolute; display: none; color: red; font-weight: 600;">OTP is Incorret</p>
                                                    <p id="confirm_order_success" style=" position: absolute; display: none; color: green; font-weight: 600;">Order Delivered Successfully.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button id="order_verify_button" CNo="" custId="" billNo="" start="" type="button" class="btn btn-primary" style="width: 100%;" >Verify</button>
                                                    </div>
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


<script type="text/javascript">
    $(document).ready(function () {
        $('#order-view-table').DataTable();
        $('#order_details').DataTable();
    });


</script>

<script>
            // Global Variables var kotNo = 0; var pQty = ''; var aQty = '';

            function getTableView() {
                var DispCd = $('#kitchen-code').val();
                if(DispCd > 0){
                    $.ajax({
                        url: "<?php echo base_url('restorent/order_delivery'); ?>",
                        type: "post",
                        data: {
                            getOrderDetails: 1,
                            DispCd : DispCd
                        },
                        dataType: 'json',
                        success: response => {
                            var template = ``;
                            console.log(response);
                            // alert(response.status);
                            if (response.status) {
                                var start = 1;
                                response
                                    .kitchenData
                                    .forEach(item => {
                                        // alert(item.CellNo);
                                        //if (item.OType > 7) {
                                            template += `
                                <tr id="${ (
                                                start == 1
                                                    ? 'start'+start
                                                    : 'start'+start
                                            )}" data-id="${item.KOTNO}" class="specific-order ${ (
                                                item.Qty - item.AQty == 0
                                                    ? 'success-table-status'
                                                    : ''
                                            )}" cno="${item.CNo}">
                                    <td>${item.BillNo}</td>
                                    <td>${item.Qty}</td>
                                    <td>${item.AQty}</td>
                                    <td>${item.CellNo}</td>
                                    <td>${item.TPId}</td>
                                    <td>${item.TPRefNo}</td>
                                    <td>

                                        <button onclick="handleDelivery('${item.CNo}', ${item.CustId},${item.BillNo}, ${start})" class="btn btn-sm btn-primary">
                                            <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                    <td>

                                        <button onclick="handleDetails('${item.CNo}')" class="btn btn-sm btn-success">
                                        <i class="fa fa-eye"></i>
                                        </button>

                                        <a href="vtrend:billid=0&eid=<?= $EID;?>&kotno=${item.UKOTNo}" class="btn btn-sm btn-warning">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a>

                                        <a onclick="sendNotification(${item.CustId},0,${item.BillNo},1)" class="btn btn-sm btn-danger">
                                            <i class="fa fa-bullhorn"></i>
                                        </a>

                                    </td>
                                </tr>
                            `;
                                        //}

                                        start++;
                                    });
                            }
                            $('#order-view-table').remove();
                            $('#order-view-parent').html(tableStructure);
                            $("#table-view").html(template);
                            $('#mydiv').hide();
                            dataTableForOrder();
                        },
                        error: (xhr, status, error) => {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        }
                    });
                }
            }

            function sendNotification(CustId, otp, billNo,flag) {
                if (flag == 1) {
                <?php if($EType == 1 && $Fest == 1){ ?>
                            var title = $('#kitchen-code').val();
                            var message = 'Your order ' + billNo +
                            ' is ready.\nPlease pick up from counter.';
                        <?php }else{ ?>
                            var title = '<?= $RestName;?>';
                            var message = 'Your order ' + billNo +
                                ' is ready.\nPlease pick up from counter.';
                    <?php }?>
                } else if(flag == 0) {
                    <?php if($EType == 1 && $Fest == 1){ ?>
                        var title = 'Order Delivery '+$('#kitchen-code').val();
                        var message = 'Your order ' + billNo + ' has been delivered.';
                    <?php }else{ ?>
                        var title = 'Order Delivery <?= $RestName;?>';
                        var message = 'Your order ' + billNo + ' has been delivered.';
                    <?php }?>
                } else if(flag == 2){
                    var title = "<?= $RestName;?> BillNo " +billNo;
                    var message = "OTP : " +otp;
                }
                $.ajax({
                    url: "<?php echo base_url('restorent/sentNotification'); ?>",
                    type: "get",
                    data: {
                        CustId: CustId,
                        message: message,
                        title: title,
                        flag :flag,
                        billno:billNo
                    },
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }

                });
            }

            function itemView() {
                // var DispCd = $('#kitchen-code').val();
                $.ajax({
                    url: "<?php echo base_url('restorent/order_delivery'); ?>",
                    type: "post",
                    data: {
                        getKtichenItem: 1
                    },
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if (response.status) {
                            var itemView = ``;

                            response
                                .kitchenData
                                .forEach(function (item) {
                                    itemView += `
                        <tr class="item-row" item-id="` + item.ItemId +
                                            `" item-portion="` + (
                                        item.TA == 0
                                            ? 'N'
                                            : 'TA'
                                    ) + `" remarks="` + item.CustRmks + `">
                            <td>` + item.ItemNm +
                                            `</td>
                            <td>` + item.Qty + `</td>
                            <td>` + (
                                        item.TA == 0
                                            ? 'N'
                                            : 'TA'
                                    ) + `</td>
                            <td>` + item.CustRmks +
                                            `</td>
                        </tr>
                    `;
                                });
                            $("#item-view-table").remove();
                            $("#item-table-parent").html(tableStructureItem);
                            $("#item-view-tbody").html(itemView);
                        } else {
                            $("#item-view-table").remove();
                            $("#item-table-parent").html(tableStructureItem);
                            $("#item-view-tbody").html('');
                            $('#mydiv').hide();
                        }
                        dataTableForItem();
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                });
            }

            function dataTableForItem() {
                var kitchenTable = $('#item-view-table').DataTable({ keys: true,searching: false, paging: false, info: false});

                kitchenTable.on('key-focus', function (e, datatable, cell) {
                    $('table#item-view-table > tbody > tr').css('background-color', 'white');
                    $('td.focus')
                        .parent()
                        .css('background-color', '#f3f367');
                })
            }

            function dataTableForOrder() {

                var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

                    if (isMobile) {
                        var orderTable = $('#order-view-table').DataTable({
                            keys: true,
                            searching: false, paging: false, info: false,
                            "columnDefs": [
                            {
                                "targets": [ 2 ],
                                "visible": false,
                                "searchable":false
                            },
                            {
                                "targets": [ 4 ],
                                "visible": false,
                                "searchable":false
                            },
                            {
                                "targets": [ 5 ],
                                "visible": false,
                                "searchable":false
                            }
                        ]
                            });
                    } else {
                        var orderTable = $('#order-view-table').DataTable({
                        keys: true,searching: false, paging: false, info: false
                        });
                    }
                
                orderTable.on('key-focus', function (e, datatable, cell) {
                    // $('tr').css('background-color', 'white'); $('#mydiv').show();
                    $('table#order-view-table > tbody > tr').css('background-color', 'white');
                    $('td.focus')
                        .parent()
                        .css('background-color', 'orange');
                    var cno = $('td.focus')
                        .parent()
                        .attr('cno');
                        
                    handleDetails(cno);
                });
            }

            $(document).ready(function () {
                // Make the DIV element draggable:
                dragElement(document.getElementById("mydiv"));

                function dragElement(elmnt) {
                    var pos1 = 0,
                        pos2 = 0,
                        pos3 = 0,
                        pos4 = 0;
                    if (document.getElementById(elmnt.id + "header")) {
                        // if present, the header is where you move the DIV from:
                        document
                            .getElementById(elmnt.id + "header")
                            .onmousedown = dragMouseDown;
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
                itemView();

                $("#search-table").click(function (event) {
                    var tableNo = $("#search-table-value").val();

                    if (tableNo !== '') {
                        console.log(tableNo);
                    } else {
                        alert("specify table no before search");
                    }
                });
            });
        </script>

        <!-- To handle Item View Double Click -->
        <script>
            // Global Variables
            var itemQty = 0;
            var itemId = 0;
            $(document).on('dblclick', '.item-row', function (event) {
                event.preventDefault();
                var itemName = ($(this).children('td').eq(0).text());
                var itemPortion = $(this).attr('item-portion');
                itemQty = ($(this).children('td').eq(1).text());
                var customerRemarks = $(this).attr('remarks');
                itemId = $(this).attr('item-id');

                $("#item-name").val(itemName);
                $("#item-qty").val(itemQty);
                $("#item-qty").attr('max', itemQty);
                $("#item-portion").val(itemPortion);
                $("#customer-remarks").val(customerRemarks);

                $("#allocate-item").modal('toggle');
            });

            $("#item-qty").keyup(function (event) {
                if (parseInt($(this).val()) > itemQty) {
                    alert("Not Allowed");
                    $(this).val(itemQty);
                }

                if ($(this).val() < 1) {
                    alert("Not Allowed");
                    $(this).val(1);
                }
            });

            $("#assign-order").click(function () {
                var assignToOrderId = $("#to-assign-table").val();
                var assignQty = $("#from-reassign-qty").val();
                console.log(orderId, assignToOrderId, assignQty);

                if (assignToOrderId == 0) {
                    alert("Please Select the Table Where You Want The Item Assigned");
                } else {
                    $.ajax({
                        url: "<?php echo base_url('restorent/order_delivery'); ?>",
                        type: "post",
                        data: {
                            assignOrder: 1,
                            assignToOrderId: assignToOrderId,
                            orderId: orderId,
                            assignQty: assignQty
                        },
                        dataType: 'json',
                        success: (response) => {
                            console.log(response);
                            if (response.status) {
                                $("#reassign-order-modal").modal('hide');
                                getTableView();
                                itemView();
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
        </script>

        <!-- auto item complete -->
        <script>
            $("#auto-item-prepare").click(function (event) {
                var itemQty = $("#item-qty").val();
                var itemPortion = $("#item-portion").val();
                var customerRemarks = $("#customer-remarks").val();

                // console.log(itemId, itemPortion, itemQty, customerRemarks);

                $.ajax({
                    url: "<?php echo base_url('restorent/order_delivery'); ?>",
                    type: "post",
                    data: {
                        autoItemPrepare: 1,
                        itemId: itemId,
                        itemQty: itemQty,
                        itemPortion: itemPortion,
                        customerRemarks: customerRemarks
                    },
                    dataType: "text",
                    success: function (response) {
                        console.log(response);
                        getTableView();
                        itemView();
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                });
            });
        </script>
        <!-- handle Casher Action -->
        <script>

            function handleDetails(CNo) {
                // alert(uKotNo);
                var DispCd = $('#kitchen-code').val();
                if (CNo !== 0) {
                    // console.log(uKotNo);
                    // $("#order-detail-modal").modal('show');
                    $.ajax({
                        url: "<?php echo base_url('restorent/order_delivery'); ?>",
                        type: "post",
                        data: {
                            getOrderList: 1,
                            CNo: CNo,
                            DispCd :DispCd
                        },
                        dataType: 'json',
                        success: (response) => {
                            console.log(response);
                            var template = ``;
                            if (response.status) {

                                response
                                    .orderList
                                    .forEach((item) => {
                                        template += `
                            <tr class="${ (
                                            item.Qty == item.AQty
                                                ? 'h-deliver'
                                                : ''
                                        )}">
                                <td>${item.ItemNm}</td>
                                <td>${item.Qty}</td>
                                <td>${item.TA}</td>
                                <td>${item.CustRmks}</td>
                            </tr>
                        `;
                                    });
                            }

                            $("#order-list").html(template);
                            $("#item-view-tbody1").html(template);
                        },
                        error: (xhr, status, error) => {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        }
                    });
                } else {
                    alert("Please Select Order");
                }
            }

            function handleDelivery(CNo, custId, billNo, start) {
               
                var v = <?= $CheckOTP?>;
                if (CNo !== 0 && v == 1) {
                    $.ajax({
                        url:"<?php echo base_url('restorent/order_delivery'); ?>",
                        type:'post',
                        data:{
                            otpUpdate:1,
                            billNo:billNo
                        },
                        success:function(response){
                            window.confirm_otp = response;
                            sendNotification(custId, window.confirm_otp, billNo,2);
                            $('.order_deliver_header').html('OTP for Bill No : '+ billNo);
                            $('#order_verify_button').attr('CNo',CNo);
                            // $('#order_verify_button').attr('orderType',orderType);
                            $('#order_verify_button').attr('custId',custId);
                            $('#order_verify_button').attr('billNo',billNo);
                            $('#order_verify_button').attr('start',start);
                            $('#confirm_otp_field').val('');
                            $('#order_deliver_otp').modal('toggle');
                        }
                    })
                } else {
                    if(confirm("Are you sure want to continue?")){
                        if (CNo !== 0 && v == 0) {
                            var CNo = CNo;
                            var custId =custId;
                            var billNo = billNo;
                            var start = start;
                            $.ajax({
                                url: "<?php echo base_url('restorent/order_delivery'); ?>",
                                type: "post",
                                data: {
                                    deliverOrder: 1,
                                    CNo: CNo
                                },
                                dataType: 'json',
                                success: response => {
                                    // alert(response.status);
                                    if (response.status) {
                                        // sendNotification(custId, 0, billNo,0);
                                        // getTableView();
                                        // itemView();
                                        // if (orderType != 0) {
                                        //     window.open(
                                        //         `rest_bill_print.php?billId=${response.billId}`,
                                        //         '_blank'
                                        //     );
                                        // }
                                        
                                        setTimeout(function(){
                                            alert("Successfully Delivered");
                                            $('#start'+start).remove();
                                        },2000);
                                    } else {
                                        console.log(response);
                                    }
                                },
                                error: (xhr, status, error) => {
                                    console.log(xhr);
                                    console.log(status);
                                    console.log(error);
                                }
                            });
                        }else{
                            alert("Please Select Order");
                        }
                    }
                }
            }

            $('#order_verify_button').on('click',function(){
                if(window.confirm_otp == $('#confirm_otp_field').val()){
                    var CNo = $(this).attr('CNo');
                    var custId =$(this).attr('custId');
                    var billNo = $(this).attr('billNo');
                    var start = $(this).attr('start');
                    $.ajax({
                        url: "<?php echo base_url('restorent/order_delivery'); ?>",
                        type: "post",
                        data: {
                            deliverOrder: 1,
                            CNo: CNo
                        },
                        dataType: 'json',
                        success: response => {
                            // alert(response.status);
                            if (response.status) {
                                // sendNotification(custId, 0, billNo,0);
                                // getTableView();
                                // itemView();
                                // if (orderType != 0) {
                                //     window.open(
                                //         `rest_bill_print.php?billId=${response.billId}`,
                                //         '_blank'
                                //     );
                                // }
                                $('#confirm_order_success').fadeIn();
                                
                                setTimeout(function(){
                                    $('#confirm_otp_field').val('');
                                    $('#confirm_order_success').fadeOut();
                                    $('#order_deliver_otp').modal('toggle');
                                    $('#start'+start).remove();
                                },2000);
                            } else {
                                console.log(response);
                            }
                        },
                        error: (xhr, status, error) => {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        }
                    });
                }else{
                    $('#confirm_order_error').fadeIn();
                    setTimeout(function(){
                        $('#confirm_order_error').fadeOut();
                    },2000);
                }
            })

            function handleDeliveryBill(kotNo) {
                if (kotNo !== 0) {
                    console.log(kotNo);
                } else {
                    alert("Please Select Order");
                }
            }

            var tableStructure = '<table class="table" id="order-view-table" class="display" ' + '> \ <thead>\ <tr>\
                <th>Bill No</th>\
                <th>OQty</t' +
                        'h>\
                <th>AQty</th>\
                <th>Cell NO</th>\
                <th>3P</th>\
                <th>3P Ref</th>' +
                        '\
                <th>Deliver</th>\
                <th>Order</th>\
                </tr>\
                </thead>\
                <tbody id="table-' + 'view">\
                </tbody>\
                </table>';

            var tableStructureItem = '<table class="table" id="item-view-table" class="display" >' + '\ <thead>\ <tr>\
                <th>Item Name</th>\
                <th>PQty</t' +
                        'h>\
                <th>Take Away</th>\
                <th>Remark</th>\
                </tr>\ </thead>\ <tbody id=' + '"item-view-tbody1"></tbody>\ </table>'
        
        </script>

