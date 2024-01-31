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

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <input type="hidden" id="order-type" value="<?= $OType; ?>">
                                            <?php if($OType == 8) { ?>
                                            <div class="col-md-3 form-group col-6">
                                                <label><?= $this->lang->line('tableNo'); ?></label>
                                                <select class="form-control form-control-sm" id="table-id" onchange="get_table_order_items(this)">
                                                    <option value="0" capacity="0"><?= $this->lang->line('select'); ?></option>
                                                    <?php foreach ($tablesAlloted as $data) : ?>
                                                        <option value="<?= $data['MergeNo'] ?>" capacity="<?= $data['Capacity'] ?>" ccd="<?= $data['CCd'] ?>"><?= convertToUnicodeNumber($data['MergeNo']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <?php 
                                            if($this->session->userdata('tableSharing') > 0){
                                            ?>
                                            <div class="col-md-3 form-group col-6">
                                                <label><?= $this->lang->line('seatNo'); ?></label>
                                                <select class="form-control form-control-sm" id="seatNo" name="seatNo" onchange="changeSeatNo()">
                                                    <option value="1">1</option>
                                                </select>
                                            </div>
                                            <?php  }else{ ?>
                                                <input type="hidden" id="seatNo" name="seatNo" value="1">
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if($OType == 101) { ?>
                                            <div class="col-md-3 form-group col-6">
                                                <label><?= $this->lang->line('thirdParty'); ?></label>
                                                <select class="form-control form-control-sm" id="3rd-party">
                                                    <option value="0"><?= $this->lang->line('select'); ?></option>
                                                    <?php foreach ($thirdOrdersData as $data) : ?>
                                                        <option value="<?= $data['3PId'] ?>"><?= $data['LngName']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-3 form-group col-6">
                                                <label><?= $this->lang->line('thirdPartyRefNo'); ?></label>
                                                <input type="text" id="3rd-party-refNo" class="form-control form-control-sm">
                                            </div>
                                        <?php } ?>
                                            <div class="col-md-3 form-group col-6">
                                                <label><?= $this->lang->line('mobile'); ?></label>
                                                <input type="text" id="phone" class="form-control form-control-sm" minlength="10" maxlength="10" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" onchange="getCustDetails()">
                                                <small class="text-danger msgPhone"></small>
                                                <input type="hidden" name="seatNoOld" id="seatNoOld" value="1">
                                            </div>
                                            
                                            <div class="col-md-3 form-group col-6">
                                                <?php if($OType == 105) { ?>
                                                <label><?= $this->lang->line('counter'); ?>: <?= $cashier[0]['Name']; ?></label>
                                                <?php } ?>
                                                <input type="hidden" id="ccd" name="ccd" class="form-control" value="<?= $cashier[0]['CCd']; ?>" />
                                            </div>
                                        
                                        </div>

                                        <div class="row">
                                            <div class="col-md-9 form-group col-6">
                                                <label><?= $this->lang->line('customerAddress'); ?></label>
                                                <input type="text" class="form-control form-control-sm" id="cust-address" <?php if($OType != 110){ echo 'disabled'; } ?>>
                                            </div>

                                            <div class="col-md-3 form-group col-6">
                                                <label><?= $this->lang->line('itemAmount'); ?></label>
                                                <input type="hidden" id="total-value" readonly="" value="0" class="form-control form-control-sm">
                                                <input type="text" id="total-valueView" readonly="" value="0" class="form-control form-control-sm">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-7 form-group col-6">
                                                
                                                <button class="btn btn-primary btn-sm" title="Kitchen Order Ticket" onclick="searchKOT()"><?= $this->lang->line('kot'); ?> 
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                                <?php if($OType == 8){ ?>
                                                <button class="btn btn-success btn-sm send-to-kitchen" data_type="save_to_kitchen" id="btnOrder"><?= $this->lang->line('order'); ?></button>
                                                <?php } ?>
                                                <?php if($OType != 8){ ?>
                                                <button class="btn btn-warning btn-sm send-to-kitchen" data_type="bill" id="btnBill"><?= $this->lang->line('bill'); ?></button>
                                                <?php } ?>
                                            </div>
                                            
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                <table class="table table-bordered" id="tableData">
                                                    <thead>
                                                        <tr>
                                                            <th><?= $this->lang->line('item'); ?></th>
                                                            <th><?= $this->lang->line('quantity'); ?></th>
                                                            <th><?= $this->lang->line('portion'); ?></th>
                                                            <th><?= $this->lang->line('rate'); ?></th>
                                                            <th><?= $this->lang->line('value'); ?></th>
                                                            <th><?= $this->lang->line('takeAway'); ?></th>
                                                            <th><?= $this->lang->line('remarks'); ?></th>
                                                            <th><?= $this->lang->line('action'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="order-table-body"></tbody>
                                                </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if($OType != 8){ ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="billViewTbl">
                                                <thead>
                                                    <tr>
                                                        <th><?= $this->lang->line('bilNo'); ?></th>
                                                        <th><?= $this->lang->line('billDate'); ?></th>
                                                        <th><?= $this->lang->line('billAmount'); ?></th>
                                                        <th><?= $this->lang->line('mobile'); ?></th>
                                                        <th><?= $this->lang->line('action'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if(!empty($bills)){
                                                        foreach ($bills as $key) {
                                                     ?>
                                                    <tr>
                                                        <td>
                                                            <a href="<?php echo base_url('restaurant/bill/'.$key['BillId']); ?>" target="_blank"><?= convertToUnicodeNumber($key['BillNo']); ?>
                                                            </a>
                                                        </td>
                                                        <td><?= date('d-M-Y',strtotime($key['billTime'])); ?></td>

                                                        <td><?= convertToUnicodeNumber($key['PaidAmt']); ?></td>   
                                                        <td ><?= convertToUnicodeNumber($key['CellNo']); ?></td>
                                                        <td>
                                                            <a href="<?php echo base_url('restaurant/kot_print/'.$key['CNo'].'/'.$key['MergeNo'].'/'.$key['FKOTNo']); ?>" class='btn btn-primary btn-sm'>
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                            <a href="<?php echo base_url('restaurant/print/'.$key['BillId']); ?>" class='btn btn-warning btn-sm'>
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                            <button title="Cash Collect" class="btn btn-sm btn-info" id="btnCash" onclick="cashCollect(<?= $key['BillId']; ?>,<?= $key['OType']; ?>,<?= $key['TableNo']; ?>,<?= $key['MergeNo']; ?>,'<?= $key['CellNo']; ?>',<?= $key['PaidAmt']; ?>,<?= $key['CNo']; ?>,<?= $key['EID']; ?>)"><i class="fas fa-money-check"></i>
                                                </button>
                                                <?php if($this->session->userdata('AutoSettle') == 0){ ?>

                                                <button class="btn btn-sm btn-success" onclick="setPaidAmount(<?= $key['BillId']; ?>,<?= $key['CNo']; ?>,<?= $key['MergeNo']; ?>,<?= $key['CustId']; ?>,<?= $key['BillNo']; ?>,<?= $key['PaidAmt']; ?>)">
                                            <i class="fas fa-check-double"></i>
                                        </button>
                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php } } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        
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
        
    <!-- The Modal -->
    <div class="modal" id="item-list-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center" style="display: block; padding: 5px; background-color: darkblue; color: white;">
                    <h4 class="modal-title">Add item</h4>
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" id="search-item" class="form-control" placeholder="Enter the item Name">
                            <div id="item-search-result"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    <form method="post" id="cashForm">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?= $this->lang->line('mode'); ?></th>
                                    <th><?= $this->lang->line('amount'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cashBody">
                                <tr>
                                    <td>
                                        <select name="" id="" class="form-control form-control-sm">
                                        <?php
                                        foreach ($payModes as $key) {
                                         ?>
                                         <option value="<?= $key['Name']; ?>"><?= $key['Name']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </td>
                                    <td id="cashBodyTd">
                                    </td>
                                    <td>
                                        <button type="button" onclick="cashCollectData()" class="btn btn-sm btn-success">
                                        <i class="fas fa-save"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

        <?php $this->load->view('layouts/admin/script'); ?>


<script type="text/javascript">

        var cno = 0;
        // Delete item from table
        function deleteItem(event) {
            $(event).parent().parent().remove();
        }

        function itemSlected(itemId, itemName, itemValue, itemKitCd, PckCharge,Itm_Portion, TaxType, PrepTime, DCd) {
            var orderType = $('#order-type').val();
            ch = '';
            if(orderType != 8){
                ch = 'checked disabled';
            }
            
            if(Itm_Portion > 4){
                getPortionLists(itemId, Itm_Portion);
            }
            // console.log(itemId, itemName, itemValue, itemKitCd);
            $("#item-search-result").html('');
            $("#search-item").val('');
            $("#item-list-modal").modal('hide');
            var qty = 1; // default quantity
            var template = `
            <tr class="item-id" data-id="${itemId}" kitcd-id="${itemKitCd}" pckcharge ="${PckCharge}" Itm_Portion ="${Itm_Portion}">
                <td>${itemName}</td>
                <td style="width:50px;"><input type="text" class="form-control form-control-sm item-qty" min="1" value="${convertToUnicodeNo(qty)}" onblur="calculateValue(this)" style="width:50px;" ></td>
                <td><select class="form-control form-control-sm item-portion" id="select_${itemId}" onchange="changePortion(${itemId})"><option></option></select></td>
                <td class="item-rate" id="rate_${itemId}">${convertToUnicodeNo(itemValue)}</td>
                <td class="item-value" id="value_${itemId}">${convertToUnicodeNo(itemValue)}</td>
                <td><input type="checkbox" value="1" class="is_take_away" `+ch+`></td>
                <td><input type="text" class="form-control form-control-sm item-remarks" style="width:100%;"></td>
                <td style=" text-align: center; ">
                <button type="button" onclick="deleteItem(this)" class="btn btn-sm btn-danger btn-rounded">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
                <input type="hidden" value="${TaxType}" class="taxtype">
                <input type="hidden" value="${PrepTime}" class="preptime">
                <input type="hidden" value="${DCd}" class="DCd">
                
                </td>
            </tr>`;
                // check if table is empty 
            if (document.querySelectorAll('#tableData tbody tr').length == 0) {
                $("#order-table-body").append(template);
            }else{
                $("#order-table-body").find('tr:first').before(template);
            }

            calculateTotal();
        }

        function getPortionLists(itemId, Itm_Portion){
            
            var mergeNo = $('#table-id').val();
            $.post('<?= base_url('restaurant/get_portions') ?>',{itemId:itemId, mergeNo:mergeNo},function(res){
                if(res.status == 'success'){
                  // alert(res.response);
                  var temp = ``;
                  res.response.forEach((item, index) => {
                    var select = '';
                    if(Itm_Portion == item.IPCd)
                        {
                            select = 'selected';
                        }
                        temp += `<option value="${item.IPCd}" ${select} rate="${item.OrigRate}">${item.Name}</option>`;
                    });
                  
                  $('#select_'+itemId).html(temp);
                }else{
                  alert(res.response);
                }
            });
        }

        changePortion =(itemId) => {
            var portion = $('#select_'+itemId).val();
            var rate = $('option:selected','#select_'+itemId).attr('rate');

            $('#rate_'+itemId).text(rate);
            $('#value_'+itemId).text(rate);

            calculateTotal();
        }

        function calculateTotal() {
            var totalValue = 0;
            var val = 0;
            $(".item-value").each(function(index, el) {
                val = $(this).text();
                val = convertDigitToEnglish(val);
                totalValue += parseInt(val);
                // totalValue += parseInt($(this).text());
            });

            $("#total-value").val(totalValue);
            $("#total-valueView").val(convertToUnicodeNo(totalValue));
        }

        function calculateValue(input) {
            var total = 0;
            var qty = $(input).val();
            var rate = $(input).parent('td').next('td').text();
            qty = convertDigitToEnglish(qty);
            rate = convertDigitToEnglish(rate);
            total = qty * rate;
            $(input).val(convertToUnicodeNo(qty));
            $(input).parent('td').next('td').next('td').text(convertToUnicodeNo(total));
            calculateTotal();
        }

        $(document).ready(function() {

            $("#search-item").keyup(function(event) {
                var itemName = $(this).val();
                if (itemName != '') {
                    $.ajax({
                        url: "<?php echo base_url('restaurant/order_ajax_3p'); ?>",
                        type: "post",
                        data: {
                            searchItem: 1,
                            itemName: itemName
                        },
                        dataType: "json",
                        success: (response) => {
                            console.log(response);
                            if (response.status) {
                                var template = `<ul>`;
                                response.items.forEach((item) => {
                                    var printItemName = '';
                                    var IMcCd = "<?php echo $this->session->userdata('IMcCdOpt'); ?>";
                                    if(IMcCd == 0){
                                        printItemName = item.LngName;
                                    }else if(IMcCd == 1){
                                        printItemName = item.ItemId+' - '+item.LngName+'-'+item.portionName;
                                    }else if(IMcCd == 2){
                                        printItemName = item.IMcCd+' - '+item.LngName+'-'+item.portionName;
                                    }
                                    
                                    template += `
                                <li onclick="itemSlected(${item.ItemId}, '${item.LngName}', ${item.OrigRate}, ${item.KitCd},${item.PckCharge},${item.Itm_Portion}, ${item.TaxType}, ${item.PrepTime}, ${item.DCd});" style="cursor: pointer;">${printItemName}</li>
                            `;
                                });
                                template += `</ul>`;
                            } else {
                                var template = `
                            <ul>
                                <li>No Item Found</li>
                            </ul>
                        `;
                            }
                            $("#item-search-result").html(template);
                        },
                        error: (xhr, status, error) => {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        }
                    });
                } else {
                    $("#item-search-result").html('');
                }
            });

            $(".send-to-kitchen").click(function(event) {

                var data_type = $(this).attr('data_type');
                // alert(data_type);
                var orderType = $("#order-type").val();
                console.log('vv '+orderType);
                var seatNo = 0;
                var oldSeatNo = 0;
                if (orderType != 8 ) {
                    // var tableNo = 'TA';
                    var tableNo = orderType;
                } else {
                    var tableNo = $("#table-id").val();
                    seatNo = $('#seatNo').val();
                    oldSeatNo = $('#seatNoOld').val();
                }
                var thirdParty = $("#3rd-party").val();
                var thirdPartyRef = $("#3rd-party-refNo").val();
                var customerAddress = $("#cust-address").val();
                var customerPhone = $("#phone").val();
                var CCd = 0;
                var totalValue = $("#total-value").val();
                var itemCount = $("tr").length;
                var formFill = true;
                var itemIds = [];
                var Itm_Portion = [];
                var itemKitCds = [];
                var itemQty = [];
                var taxtype = [];
                var itemRemarks = [];
                var item_value = [];
                var pckValue = [];
                var take_away = [];
                var prep_time = [];
                var dcd_value = [];
                var dataType = $(this).attr('data_type');
                // Page Validation
                if (orderType == 0) {
                    formFill = false;
                    alert("Please Select Order Type");
                } else if (itemCount < 2) {
                    formFill = false;
                    alert("Please Enter Atleast 1 Item");
                } else if (orderType == 101) {
                    if (thirdParty == 0) {
                        formFill = false;
                        alert("Please Select 3rd Party");
                    }
                    if (thirdPartyRef == '') {
                        formFill = false;
                        alert("Please Enter 3rd Party Ref Number");
                    }

                } else if (orderType == 105) {
                    CCd = $("#ccd").val();
                    if (customerPhone == "") {
                        formFill = false;
                        alert("Enter Customer Phone Number");
                    }
                    if (customerPhone == "") {
                        formFill = false;
                        alert("Enter Customer Phone Number");
                    }

                } else if (orderType == 110) {
                    if (customerAddress == "") {
                        formFill = false;
                        alert("Enter Customer Address");
                    }
                    if (customerPhone == "") {
                        formFill = false;
                        alert("Enter Customer Phone Number");
                    }
                } else if (orderType == 8 ) {
                    if (tableNo == 0) {
                        formFill = false;
                        alert("Please Select Table No");
                    }
                }

                if (formFill) {
                    $('.send-to-kitchen').attr("disabled", "disabled");

                    $(".item-id").each(function(index, el) {
                        itemIds.push($(this).attr('data-id'));
                        // Itm_Portion.push($(this).attr('Itm_Portion'));
                        itemKitCds.push($(this).attr('kitcd-id'));
                        pckValue.push($(this).attr('pckcharge'));
                    });

                    $(".item-qty").each(function(index, el) {
                        itemQty.push( convertDigitToEnglish($(this).val()) );
                    });

                    $(".taxtype").each(function(index, el) {
                        taxtype.push($(this).val());
                    });

                    $(".preptime").each(function(index, el) {
                        prep_time.push($(this).val());
                    });

                    $(".DCd").each(function(index, el) {
                        dcd_value.push($(this).val());
                    });

                    $(".item-remarks").each(function(index, el) {
                        itemRemarks.push($(this).val());
                    });

                    $(".is_take_away").each(function(index, el) {
                        // alert(el.checked);
                        var ch = 0;
                        if(el.checked){
                            ch = 1;
                        }
                        take_away.push(ch);
                    });

                    $(".item-rate").each(function(index, el) {
                        item_value.push( convertDigitToEnglish($(this).text()) );
                    });

                    $(".item-portion").each(function(index, el) {
                        Itm_Portion.push( $(this).val() );
                    });

                    var Uphone = $('#phone').val();
                    
                    <?php if($OType != 101){ ?>
                        thirdParty = 0;
                        thirdPartyRef = 0;
                    <?php } ?>

                    $.ajax({
                        url: "<?php echo base_url('restaurant/order_ajax_3p'); ?>",
                        type: "post",
                        data: {
                            sendToKitchen: 1,
                            orderType: orderType,
                            tableNo: tableNo,
                            thirdParty: thirdParty,
                            thirdPartyRef: thirdPartyRef,
                            totalValue: totalValue,
                            itemIds: itemIds,
                            itemKitCds: itemKitCds,
                            itemQty: itemQty,
                            itemRemarks: itemRemarks,
                            ItmRates: item_value,
                            Itm_Portion:Itm_Portion,
                            Stat: 1,
                            phone: Uphone,
                            pckValue: pckValue,
                            data_type : dataType,
                            CNo:cno,
                            taxtype:taxtype,
                            take_away:take_away,
                            prep_time:prep_time,
                            seatNo:seatNo,
                            oldSeatNo:oldSeatNo,
                            DCd : dcd_value,
                            customerAddress:customerAddress,
                            CCd:CCd
                        },

                        dataType: "json",
                        success: (response) => {
                            console.log(response);
                            if (response.status) {
                                if (data_type == 'bill') {
                                    alert("<?= $this->lang->line('orderBillledSuccessfully'); ?>");
                                    window.location = "<?= base_url('restaurant/bill/'); ?>"+response.data.billId;
                                    return false;
                                }else{
                                alert("<?= $this->lang->line('orderPlacedSuccessfully'); ?>");
                                
                                var sitinKOTPrint = response.data.sitinKOTPrint;
                                // alert(response.data.url)
                                    if(orderType == 8){
                                        if(sitinKOTPrint > 0){
                                            window.location = `${response.data.url}`;
                                         }else{
                                            location.reload();
                                         }
                                    }else{
                                        window.location = `${response.data.url}`;
                                    }
                                    return false;
                                }

                                location.reload();
                            } else {
                                alert("Failed To Place Order");
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
        });

        function get_table_order_items(el){
            $('#order-table-body').html('');
            var mergeNo = el.value;
            var capacity = $('option:selected', el).attr('capacity');
            var ccd = $('option:selected', el).attr('ccd');

            var seatOption = '';
            for(i = 1;i<= capacity; i++){
                seatOption+=`<option value="${i}">${i}</option>`;
            }
            $('#seatNo').html(seatOption);
            $('#ccd').val(ccd);
            changeSeatNo();
        }

        function changeSeatNo(){
            var mergeNo = $('#table-id').val();
            var seatNo = $('#seatNo').val();
            ajaxCallData(mergeNo, seatNo);
        }

        function ajaxCallData(mergeNo, seatNo){
            
            $.ajax({
              type:'post',
              url:"<?php echo base_url('restaurant/order_ajax_3p'); ?>",
              data:{get_table_order_items:1, mergeNo:mergeNo, seatNo:seatNo},
              success:function(data){
                $('#order-table-body').html('');
                    var a = JSON.parse(data);
                    if(a.length > 0){
                        var billStat = a[0].kmBillStat;
                        var oldSeatNo = a[0].SeatNo;
                        var b = '';
                        if(billStat > 0){
                            alert('Cannot add more items, as bill has been generated for this table')
                        }else{
                            for(i = 0;i<a.length;i++){
                                cno = a[i].CNo;
                                var ch = '';
                                if(a[i].TA == 1){
                                    ch = 'checked';
                                }

                                if(a[i].Itm_Portion > 4){
                                    getPortionLists(a[i].ItemId, a[i].Itm_Portion);
                                }

                                b+='<tr>';
                                b+='<td>'+a[i].ItemNm+'</td><td>'+convertToUnicodeNo(a[i].Qty)+'</td><td><select class="form-control form-control-sm item-select" id="select_'+a[i].ItemId+'" onchange="changePortion('+a[i].ItemId+')" disabled><option></option></select></td><td>'+convertToUnicodeNo(a[i].ItmRate)+'</td><td class="item-value">'+convertToUnicodeNo(a[i].OrigRate)+'</td><td><input type="checkbox" value="'+a[i].TA+'" '+ch+' disabled /></td><td>'+a[i].CustRmks+'</td><td></td>';
                                b+='</tr>';
                            }

                            $('#phone').val(a[0].CellNo);
                            $('#phone').prop('readonly', true);
                            $('#seatNoOld').val(oldSeatNo);
                            $('#order-table-body').append(b);
                            calculateTotal();
                        }
                    }else{
                        $('#phone').val('');
                        $('#phone').prop('readonly', false);
                        $('#seatNoOld').val(1);
                    }
                  }
            });
        }

        function cashCollect(billId, oType, tableNo, mergeNo, cellNo, paidAmt, MCNo,EID){
            var tbl = '<input type="hidden" name="BillId" value="'+billId+'"/><input type="hidden" name="oType" value="'+oType+'"/><input type="hidden" name="TableNo" value="'+tableNo+'"/><input type="hidden" name="MCNo" value="'+MCNo+'"/><input type="hidden" name="EID" value="'+EID+'"/><input type="hidden" name="MergeNo" value="'+mergeNo+'"/><input type="hidden" name="CellNo" value="'+cellNo+'"/><input type="hidden" name="TotBillAmt" value="'+paidAmt+'"/><input type="text" name="PaidAmt" value="'+convertToUnicodeNo(paidAmt)+'" required class="form-control form-control-sm" onblur="changeValue(this)" />'

            // var temp ='<tr>\
            //             <td>'+data.BillNo+'</td>\
            //             <td>'+data.TableNo+'</td>\
            //             <td>'+data.PaidAmt+'</td>\
            //             <td>'+pm+'</td>';
            $('#cashBodyTd').html(tbl);

            $('#cashCollectModel').modal('show');
        }

        function cashCollectData(){
            var data = $('#cashForm').serializeArray();
          
            var TotBillAmt = data[7].value;
            var PaidAmt = data[8].value;

            PaidAmt = convertDigitToEnglish(PaidAmt);
            data[8].value = PaidAmt;

          // console.log(PaidAmt+' , '+TotBillAmt);
          if(parseFloat(PaidAmt) >= parseFloat(TotBillAmt)){
            $.post('<?= base_url('restaurant/collect_payment') ?>',data,function(res){
                if(res.status == 'success'){
                  alert(res.response);
                }else{
                  alert(res.response);
                }
                location.reload();
            });
          }else{
            alert('Amount has to be greater than or equal to Bill Amount.');
          }
        }

        const searchKOT = () => {
            var OType = "<?= $OType; ?>";
            // console.log('hilll '+OType);
            if(OType == 8){
                var table = $('#table-id').val();
                if(table > 0){
                    $('#item-list-modal').modal('show');
                }else{
                    alert('Please select table first.');
                }
            }else{
                $('#item-list-modal').modal('show');
            }
        }

        // settle payments
        function setPaidAmount(billId , CNo , MergeNo , CustId, billNo, billAmt) {

            $.post('<?= base_url('restaurant/bill_settle') ?>',{billId:billId,CNo:CNo,MergeNo:MergeNo,CustId:CustId,billNo:billNo,billAmt:billAmt},function(response){

                if(response.status == 'success') {
                        alert("Successfully Settled");
                }else {
                    alert("Not Settled");
                }
                location.reload();
            });
        }

        function changeValue(input) {
            var val = $(input).val();
            $(input).val(convertToUnicodeNo(val));
        }

        function getCustDetails(){
            
            var phone = $('#phone').val();
            if(phone.length == 10){
                $.post('<?= base_url('restaurant/get_cust_details') ?>',{phone:phone},function(response){

                if(response.status == 'success') {
                        var data = response.response;
                        $('#cust-address').val(data.DelAddress);
                }else {
                    $('.msgPhone').html(response.response);
                }
            });
            }else{
                $('.msgPhone').html('Enter Valid Phone');
            }
            // console.log(phone);
            
        }

    </script>