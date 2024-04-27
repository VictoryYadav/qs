<?php $this->load->view('layouts/admin/head'); ?>
<style>
    .addcss{
        z-index: 999;position: absolute;overflow: scroll;height: 170px;background: #fff;
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
                                                        <option value="<?= $data['MergeNo'] ?>" capacity="<?= $data['Capacity'] ?>" ccd="<?= $data['CCd'] ?>" tableNo="<?= $data['TableNo']; ?>"><?= convertToUnicodeNumber($data['MergeNo']); ?></option>
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
                                                <label><?= $this->lang->line('country'); ?></label>
                                                <select name="CountryCd" class="form-control form-control-sm select2 custom-select" id="CountryCd">
                                                <option value=""><?= $this->lang->line('select'); ?></option>
                                                <?php 
                                                foreach ($country as $key) { ?>
                                                    <option value="<?= $key['phone_code']; ?>" <?php if($CountryCd == $key['phone_code']){ echo 'selected'; } ?>><?= $key['country_name']; ?></option>
                                                <?php } ?>                   
                                            </select>
                                            </div>

                                            <div class="col-md-3 form-group col-6">
                                                <label><?= $this->lang->line('mobile'); ?></label>
                                                <input type="text" id="phone" class="form-control form-control-sm" minlength="10" maxlength="10" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" onchange="getCustDetails()">
                                                <small class="text-danger msgPhone"></small>
                                                <input type="hidden" name="seatNoOld" id="seatNoOld" value="1">
                                            </div>
                                            
                                            <div class="col-md-3 form-group col-6">
                                                
                                                <label><?= $this->lang->line('counter'); ?>: <?php
                                                 echo (!empty($cashier)) ? $cashier[0]['Name']:'No Cashier'; ?></label>
                                                
                                                <input type="hidden" id="ccd" name="ccd" class="form-control" value="<?php echo (!empty($cashier)) ? $cashier[0]['CCd']:0; ?>" />
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
                                            <!-- vijay -->
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <input type="text" id="search-item" class="form-control form-control-sm" placeholder="Search Item Name" style="border-radius: 50px;z-index: 5;" autocomplete="off">
                                                <div id="item-search-result"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- <button class="btn btn-primary btn-sm" title="Kitchen Order Ticket" onclick="searchKOT()"><?= $this->lang->line('kot'); ?> 
                                                    <i class="fa fa-plus"></i>
                                                </button> -->
                                                <?php if($OType == 8){ ?>
                                                <button class="btn btn-success btn-sm send-to-kitchen" data_type="save_to_kitchen" id="btnOrder"><?= $this->lang->line('order'); ?></button>
                                                <?php } ?>
                                                <?php if($OType != 8){ ?>
                                                <button class="btn btn-warning btn-sm send-to-kitchen" data_type="bill" id="btnBill"><?= $this->lang->line('bill'); ?></button>

                                                <button class="btn btn-info btn-sm send-to-kitchen" data_type="kot_bill" ><?= $this->lang->line('kot'); ?> + <?= $this->lang->line('bill'); ?></button>

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
                                                            <th><?= $this->lang->line('portion'); ?></th>
                                                            <th><?= $this->lang->line('quantity'); ?></th>
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
                                                            <a href="<?php echo base_url('restaurant/kot_print/'.$key['CNo'].'/'.$key['MergeNo'].'/'.$key['FKOTNo'].'/'.$key['KOTNo']); ?>" class='btn btn-primary btn-sm tippy-btn' title="KOT Print" data-tippy-placement="top">
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                            <a href="<?php echo base_url('restaurant/print/'.$key['BillId']); ?>" class='btn btn-warning btn-sm tippy-btn' title=" Print" data-tippy-placement="top">
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                            <button class="btn btn-sm btn-info tippy-btn" title="Cash Collect" data-tippy-placement="top" id="btnCash" onclick="cashCollect(<?= $key['BillId']; ?>,<?= $key['OType']; ?>,<?= $key['TableNo']; ?>,<?= $key['MergeNo']; ?>,'<?= $key['CellNo']; ?>',<?= $key['PaidAmt']; ?>,<?= $key['CNo']; ?>,<?= $key['EID']; ?>)"><i class="fas fa-money-check"></i>
                                                </button>
                                                <?php if($this->session->userdata('AutoSettle') == 0){ ?>

                                                <button class="btn btn-sm btn-success tippy-btn" title="Bill Settle" data-tippy-placement="top" onclick="setPaidAmount(<?= $key['BillId']; ?>,<?= $key['CNo']; ?>,<?= $key['MergeNo']; ?>,<?= $key['CustId']; ?>,<?= $key['BillNo']; ?>,<?= $key['PaidAmt']; ?>)">
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
                            <!-- <input type="text" id="search-item" class="form-control" placeholder="Enter the item Name">
                            <div id="item-search-result"></div> -->
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

    <!-- offers modal -->
    <div class="modal fade bs-example-modal-center offersModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel"><?= $this->lang->line('offers'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="offerForm">
                        <div class="form-group">
                            <label for=""><?= $this->lang->line('select'); ?> <?= $this->lang->line('offers'); ?></label>
                            <div id="offerBody"></div>
                        </div>
                        <input type="submit" class="btn btn-sm btn-success" value="Submit">
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- customOfferModal -->
    <div class="modal fade bs-example-modal-center customOfferModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Custom <?= $this->lang->line('offers'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="customOfferForm">
                        <input type="hidden" id="custom_trow">
                        <input type="hidden" id="custom_itemId">
                        <input type="hidden" id="custom_ordNo">
                        
                        <div class="widget category" style="width: 100%;display: none;" id="radioOption">
                        </div>

                        <div class="widget category" style="width: 100%;display: none;" id="checkboxOption">
                            <h5 class="widget-header" id="chkHeader"></h5>
                            <ul class="category-list" id="chkList"></ul>
                        </div>
                        <input type="hidden" id="custOfferAmount" value="0">
                        <div id="custOfferAmountView" class="text-danger text-center">0</div>

                        <input type="submit" class="btn btn-sm btn-success" value="Submit">
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

        <?php $this->load->view('layouts/admin/script'); ?>


<script type="text/javascript">

    $(document).ready(function() {
        $('#CountryCd').select2();

        $("#search-item").keyup(function(event) {

            var OType = $("#order-type").val();
            
            if(OType == 8){
                var table = $('#table-id').val();
                if(table < 1){
                    alert('Please select table first.');
                    return false;
                }
            }

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
                            var template = `<ul style="list-style:none;">`;
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
                                
                                template += `<li onclick="itemSlected_new(${item.ItemId}, ${item.ItemId}, '${item.LngName}', ${item.OrigRate}, ${item.OrigRate}, ${item.KitCd},${item.PckCharge},${item.Itm_Portion}, ${item.TaxType}, ${item.PrepTime}, ${item.DCd}, ${item.ItemTyp}, ${item.CID}, ${item.MCatgId}, 0, 0, 0, 0, ${item.FID}, ${item.ItemSale}, 0);" style="cursor: pointer;">${printItemName}</li>`;

                                // template += `<li onclick="itemSlected(${item.ItemId}, ${item.ItemId}, '${item.LngName}', ${item.OrigRate}, ${item.OrigRate}, ${item.KitCd},${item.PckCharge},${item.Itm_Portion}, ${item.TaxType}, ${item.PrepTime}, ${item.DCd}, ${item.ItemTyp}, ${item.CID}, ${item.MCatgId}, 0, 0, 0, 0, ${item.FID});" style="cursor: pointer;">${printItemName}</li>`;

                            });
                            template += `</ul>`;
                        } else {
                            var template = `
                        <ul>
                            <li>No Item Found</li>
                        </ul>
                    `;
                        }
                        $("#item-search-result").addClass('addcss');

                        $("#item-search-result").html(template);
                    },
                    error: (xhr, status, error) => {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                });
            } else {
                $("#item-search-result").removeClass('addcss');

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
            var CountryCd = $("#CountryCd").val();
            var CCd = $("#ccd").val();
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
            var item_rate = [];
            var itemRate = [];
            var origRate = [];
            var pckValue = [];
            var take_away = [];
            var prep_time = [];
            var dcd_value = [];
            var SchCd = []; var SDetCd = [];
            var CustItem = []; var CustItemDesc =[];

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

            } else if (orderType == 110) {
                if (CountryCd == "") {
                    formFill = false;
                    alert("Enter Country Code");
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
            var checkItem = []
            $(".item-id").each(function(index, el) {
                checkItem.push($(this).attr('data-id'));
            });

            if(checkItem.length < 1){
                formFill = false;
                alert("Please Select atleast one item!!");
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
                    item_rate.push( convertDigitToEnglish($(this).text()) );
                });

                $(".item-value").each(function(index, el) {
                    item_value.push( convertDigitToEnglish($(this).text()) );
                });

                $(".itemRates").each(function(index, el) {
                    itemRate.push( convertDigitToEnglish($(this).val()) );
                });

                $(".origRates").each(function(index, el) {
                    origRate.push( convertDigitToEnglish($(this).val()) );
                });

                $(".item-portion").each(function(index, el) {
                    Itm_Portion.push( $(this).val() );
                });

                $(".SchCd").each(function(index, el) {
                    SchCd.push( $(this).val() );
                });

                $(".SDetCd").each(function(index, el) {
                    SDetCd.push( $(this).val() );
                });

                $(".SDetCd").each(function(index, el) {
                    CustItem.push( $(this).val() );
                });
                $(".CustItemDesc").each(function(index, el) {
                    CustItemDesc.push( $(this).val() );
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
                        ItmRates: itemRate,
                        origRates: origRate,
                        // origRates: item_value,
                        Itm_Portion:Itm_Portion,
                        Stat: 1,
                        phone: Uphone,
                        CountryCd: CountryCd,
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
                        CCd:CCd,
                        SchCd : SchCd,
                        SDetCd : SDetCd,
                        CustItem : CustItem,
                        CustItemDesc : CustItemDesc
                    },

                    dataType: "json",
                    success: (response) => {
                        console.log(response);
                        if (response.status) {
                            if (data_type == 'bill') {
                                alert("<?= $this->lang->line('orderBillledSuccessfully'); ?>");
                                // window.location = "<?= base_url('restaurant/kot_print/'); ?>"+response.data.MCNo+'/'+response.data.MergeNo+'/'+response.data.FKOTNo;
                                window.location = "<?= base_url('restaurant/bill/'); ?>"+response.data.billId;
                                 
                                return false;
                            }else if (data_type == 'kot_bill') {
                                alert("<?= $this->lang->line('orderBillledSuccessfully'); ?>");
                                window.location = "<?= base_url('restaurant/kot_billing/'); ?>"+response.data.billId+'/'+response.data.MCNo+'/'+response.data.MergeNo+'/'+response.data.FKOTNo+'/'+response.data.KOTNo;
                                 
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

    var OType = $('#order-type').val();
        var cno = 0;
        var trow = 0;
        // Delete item from table
        function deleteItem(event,trow, itemId, OrdNo) {
            // $(event).parent().parent().remove();
            var SchCd = $(`#SchCd_${trow}_${itemId}`).val();
            
            if(SchCd > 0){
                if (confirm("Any offers related to this item will also get deleted.")) {
                    // $("#tableData .trow_"+trow).remove();
                    deleteTempItem(itemId, OrdNo);
                }
                return false;
            }else{
                // $("#tableData .trow_"+trow).remove();
                deleteTempItem(itemId, OrdNo);
            }
        }

        function deleteTempItem(ItemId, OrdNo){
            $.post('<?= base_url('restaurant/delete_temp_kitchen') ?>',{ ItemId: ItemId, OrdNo:OrdNo },function(res){
                if(res.status == 'success'){
                    getTableData();
                }else{
                    alert(res.response);
                }
            });
        }

        function getTableData(){
            var TableNo = 0;
            var orderType = $('#order-type').val();
            if(orderType == 8){
                TableNo = $('#table-id').val();
            }else{
                TableNo = orderType;
            }
            $.post('<?= base_url('restaurant/get_temp_kitchen_data') ?>',{ TableNo:TableNo },function(res){
                if(res.status == 'success'){
                  var data = res.response;
                  var template = ``;
                  if(data.length > 0){
                    var trow = 0;
                    var disabled =``;
                    var readonly = ``;
                    var ch = ``;
                    var orderType = $('#order-type').val();

                    cno = data[0]['CNo'];
                    $('#phone').val(data[0]['CellNo']);
                    $('#cust-address').val(data[0]['custAddr']);

                    data.forEach((item, index) => {
                        trow++;
                        
                        getPortionLists(item.ItemId, trow, item.Itm_Portion);
                        
                        if(item.SchCd < 1){
                            checkItemOffersNew(item.ItemId, item.ItemTyp, item.CID, item.MCatgId, trow, item.OrdNo, item.Itm_Portion, item.ItemSale);
                        }

                        var customOfferBtn = ``;
                        if(item.ItemTyp > 0){
                            customOfferBtn = `<button type="button" onclick="getCustomItems(${trow}, ${item.ItemId}, ${item.ItemTyp}, ${item.Itm_Portion}, ${item.FID}, ${item.OrdNo})" class="btn btn-sm btn-success btn-rounded">
                                <i class="fas fa-gift" aria-hidden="true"></i>
                            </button>`;
                        }

                        var item_name = item.itemName;
                        if(item.CustItemDesc != 'Std'){
                            item_name = item_name +'-'+item.CustItemDesc;
                        }

                        if(item.CNo > 0){
                            readonly = 'readonly';
                            disabled = 'disabled';
                             if(orderType != 8){
                                ch = 'checked disabled';
                            }else{
                                ch = 'disabled';
                            }   
                            template += `<tr >
                                    <td>${item_name}</td>
                                    <td><select class="form-control form-control-sm item-portion" id="select_${trow}_${item.ItemId}" onchange="changePortion(${trow}, ${item.ItemId})" ${disabled}><option></option></select></td>
                                    <td style="width:50px;"><input type="text" class="form-control form-control-sm item-qty" min="1" value="${convertToUnicodeNo(item.Qty)}" onblur="calculateValue(this)" style="width:50px;" id="qty_${trow}_${item.ItemId}" ${readonly}></td>
                                    <td class="item-rate" id="rate_${trow}_${item.ItemId}">${convertToUnicodeNo(item.ItmRate)}</td>
                                    <td class="item-value" id="value_${trow}_${item.ItemId}">${convertToUnicodeNo(item.ItmRate * item.Qty)}</td>
                                    <td><input type="checkbox" value="1" class="is_take_away" ${ch}></td>
                                    <td>${item.CustRmks}</td>
                                    <td style=" text-align: center; ">
                                    
                                    </td>
                                </tr>`;    
                        }else{
                            if(item.SchCd > 0){
                                readonly = 'readonly';
                                disabled = 'disabled';
                            }else{
                                readonly = '';
                                disabled = '';
                            }

                            if(orderType != 8){
                                ch = 'checked disabled';
                            }else{
                                ch = '';
                            }

                            template += `<tr class="item-id trow_${trow}" data-id="${item.ItemId}" kitcd-id="${item.KitCd}" pckcharge ="${item.PckCharge}" Itm_Portion ="${item.Itm_Portion}" >
                                    <td><a id="offerAnchor_${item.ItemId}""><span id="itemName_${trow}_${item.ItemId}">${item_name}</span></a></td>
                                    <td><select class="form-control form-control-sm item-portion" id="select_${trow}_${item.ItemId}" onchange="changePortion(${trow}, ${item.ItemId})" ${disabled}><option></option></select></td>
                                    <td style="width:50px;"><input type="text" class="form-control form-control-sm item-qty" min="1" value="${convertToUnicodeNo(item.Qty)}" onblur="calculateValue(this)" style="width:50px;" id="qty_${trow}_${item.ItemId}" ${readonly}></td>
                                    <td class="item-rate" id="rate_${trow}_${item.ItemId}">${convertToUnicodeNo(item.ItmRate)}</td>
                                    <td class="item-value" id="value_${trow}_${item.ItemId}">${convertToUnicodeNo(item.ItmRate * item.Qty)}</td>
                                    <td><input type="checkbox" value="1" class="is_take_away" ${ch}></td>
                                    <td><input type="text" class="form-control form-control-sm item-remarks" style="width:100%;"></td>
                                    <td style=" text-align: center; ">
                                    ${customOfferBtn}
                                    <button type="button" onclick="deleteItem(this, ${trow}, ${item.ItemId}, ${item.OrdNo})" class="btn btn-sm btn-danger btn-rounded">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                    <input type="hidden" value="${item.ItmRate}" class="itemRates" >
                                    <input type="hidden" value="${item.OrigRate}" class="origRates" >
                                    <input type="hidden" value="${item.TaxType}" class="taxtype">
                                    <input type="hidden" value="${item.PrepTime}" class="preptime">
                                    <input type="hidden" value="${item.DCd}" class="DCd">
                                    <input type="hidden" value="${item.SchCd}" class="SchCd" id="SchCd_${trow}_${item.ItemId}">
                                    <input type="hidden" value="${item.SDetCd}" class="SDetCd" id="SDetCd_${trow}_${item.ItemId}">
                                    <input type="hidden" value="${item.CustItem}" class="CustItem" id="CustItem_${trow}_${item.ItemId}">
                                    <input type="hidden" value="${item.CustItemDesc}" class="CustItemDesc" id="CustItemDesc_${trow}_${item.ItemId}">
                                    
                                    </td>
                                </tr>`; 
                        }

                        

                                // calculateValue(this);
                    });

                    $("#order-table-body").html(template);
                    calculateTotal(); 

                  }
                         
                }else{
                  alert(res.response);
                }
            });
        }

        function itemSlected_new(itemId, disItemId, itemName, origValue, itemValue, itemKitCd, PckCharge,Itm_Portion, TaxType, PrepTime, DCd, ItemTyp, CID, MCatgId, SchCd, SDetCd, Qty, tableRow, FID, ItemSale, OrdNo) {

            var TableNo = 0;
            var CellNo = $('#phone').val();
            var customerAddress = $('#cust-address').val();
            var orderType = $('#order-type').val();

            if(orderType == 8){
                TableNo = $('#table-id').val();
            }else{
                TableNo = orderType;
            }

            $.post('<?= base_url('restaurant/insert_temp_kitchen') ?>',{ ItemId:itemId, itemName:itemName, OrigRate:origValue, ItmRate:origValue, KitCd:itemKitCd,  ItemTyp:ItemTyp, PckCharge:PckCharge, Itm_Portion:Itm_Portion, TaxType:TaxType, PrepTime:PrepTime, DCd:DCd, CID:CID, MCatgId:MCatgId , TableNo:TableNo, CellNo:CellNo, orderType:orderType, FID:FID, Qty:Qty, SchCd:SchCd, SDetCd:SDetCd, OrdNo:OrdNo,customerAddress:customerAddress, ItemSale:ItemSale},function(res){
                if(res.status == 'success'){
                  // var data = res.response;
                  $('#item-list-modal').modal('hide');
                  getTableData();

                }else{
                  alert(res.response);
                }
            });
    
        }

        function itemSlected(itemId, disItemId, itemName, origValue, itemValue, itemKitCd, PckCharge,Itm_Portion, TaxType, PrepTime, DCd, ItemTyp, CID, MCatgId, SchCd, SDetCd, Qty, tableRow, FID) {

            var TableNo = $('#table-id').val();
            var CellNo = $('#phone').val();

            $.post('<?= base_url('restaurant/insert_temp_kitchen') ?>',{ ItemId:itemId, itemName:itemName, OrigRate:origValue, ItmRate:origValue, KitCd:itemKitCd,  ItemTyp:ItemTyp, PckCharge:PckCharge, Itm_Portion:Itm_Portion, TaxType:TaxType, PrepTime:PrepTime, DCd:DCd, CID:CID, MCatgId:MCatgId , TableNo:TableNo, CellNo:CellNo, orderType:0 },function(res){
                if(res.status == 'success'){
                  // var data = res.response;
                  $("#item-list-modal").modal('hide');
                  getTableData();

                }else{
                  alert(res.response);
                }
            });

            return false;
            // old code 4 april
            if(tableRow > 0){
                trow  = tableRow;
            }else{
                trow  = trow + 1;
            }
            
            var orderType = $('#order-type').val();
            ch = '';
            if(orderType != 8){
                ch = 'checked disabled';
            }
            
            getPortionLists(itemId, trow, Itm_Portion);
            
            if(SchCd < 1){
                checkItemOffers(itemId, ItemTyp, CID, MCatgId, trow);
            }

            var customOfferBtn = ``;
            if(ItemTyp > 0){
                customOfferBtn = `<button type="button" onclick="getCustomItems(${trow}, ${itemId}, ${ItemTyp}, ${Itm_Portion}, ${FID})" class="btn btn-sm btn-success btn-rounded">
                    <i class="fas fa-gift" aria-hidden="true"></i>
                </button>`;
            }

            var readonly = '';
            var disabled = '';
            if(SchCd > 0){
                readonly = 'readonly';
                disabled = 'disabled';
            }
            
            $("#item-search-result").html('');
            $("#search-item").val('');
            $("#item-list-modal").modal('hide');
            var qty = (Qty > 0)?Qty:1;
            
            var template = `
            <tr class="item-id trow_${trow}" data-id="${itemId}" kitcd-id="${itemKitCd}" pckcharge ="${PckCharge}" Itm_Portion ="${Itm_Portion}" trow="${trow}">
                <td><a id="offerAnchor_${itemId}""><span id="itemName_${trow}_${itemId}">${itemName}</span></a></td>
                <td><select class="form-control form-control-sm item-portion" id="select_${trow}_${itemId}" onchange="changePortion(${trow}, ${itemId})" ${disabled}><option></option></select></td>
                <td style="width:50px;"><input type="text" class="form-control form-control-sm item-qty" min="1" value="${convertToUnicodeNo(qty)}" onblur="calculateValue(this)" style="width:50px;" id="qty_${trow}_${itemId}" ${readonly}></td>
                <td class="item-rate" id="rate_${trow}_${itemId}">${convertToUnicodeNo(itemValue)}</td>
                <td class="item-value" id="value_${trow}_${itemId}">${convertToUnicodeNo(origValue)}</td>
                <td><input type="checkbox" value="1" class="is_take_away" `+ch+`></td>
                <td><input type="text" class="form-control form-control-sm item-remarks" style="width:100%;"></td>
                <td style=" text-align: center; ">
                ${customOfferBtn}
                <button type="button" onclick="deleteItem(this, ${trow}, ${itemId})" class="btn btn-sm btn-danger btn-rounded">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
                <input type="hidden" value="${TaxType}" class="taxtype">
                <input type="hidden" value="${PrepTime}" class="preptime">
                <input type="hidden" value="${DCd}" class="DCd">
                <input type="hidden" value="${SchCd}" class="SchCd" id="SchCd_${trow}_${itemId}">
                <input type="hidden" value="${SDetCd}" class="SDetCd" id="SDetCd_${trow}_${itemId}">

                <input type="hidden" value="0" class="CustItem" id="CustItem_${trow}_${itemId}">
                <input type="hidden" value="" class="CustItemDesc" id="CustItemDesc_${trow}_${itemId}">
                
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

        function getPortionLists(itemId, trow, Itm_Portion){
            var TableNo = OType;
            if(OType == 8){
                var TableNo = $('option:selected', $('#table-id')).attr('tableNo');
            } 

            $.post('<?= base_url('restaurant/get_portions') ?>',{itemId:itemId, TableNo:TableNo},function(res){
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
                  
                  $(`#select_${trow}_${itemId}`).html(temp);
                }else{
                  alert(res.response);
                }
            });
        }

        checkItemOffersNew = (ItemId, ItemTyp, CID, MCatgId, trow, OrdNo, Itm_Portion,ItemSale) =>{
            $.post('<?= base_url('restaurant/get_item_offer') ?>',{ItemId:ItemId, ItemTyp:ItemTyp, CID:CID, MCatgId:MCatgId, Itm_Portion:Itm_Portion, ItemSale:ItemSale },function(res){
                if(res.status == 'success'){
                  var data = res.response;
                  if(data.length> 0){
                    
                    // $('#offerAnchor').attr('data-toggle', "modal");
                    // $('#offerAnchor').attr('data-animation', "bounce");
                    // $('#offerAnchor').attr('data-target', ".bs-example-modal-center");
                    $('#offerAnchor_'+ItemId).attr('onclick', "showOfferModalNew("+ItemId+","+ItemTyp+","+CID+", "+MCatgId+", "+trow+", "+OrdNo+", "+Itm_Portion+", "+ItemSale+")");
                    // $('#offerAnchor_'+ItemId).attr('onclick', "showOfferModal("+ItemId+","+ItemTyp+","+CID+", "+MCatgId+", "+trow+")");
                    $('#offerAnchor_'+ItemId).css({"background-color": "yellow", "cursor": "pointer"});

                  }
                }else{
                  alert(res.response);
                }
            });
        }

        checkItemOffers = (ItemId, ItemTyp, CID, MCatgId, trow) =>{
            $.post('<?= base_url('restaurant/get_item_offer') ?>',{ItemId:ItemId, ItemTyp:ItemTyp, CID:CID, MCatgId:MCatgId},function(res){
                if(res.status == 'success'){
                  var data = res.response;
                  if(data.length> 0){
                    
                    // $('#offerAnchor').attr('data-toggle', "modal");
                    // $('#offerAnchor').attr('data-animation', "bounce");
                    // $('#offerAnchor').attr('data-target', ".bs-example-modal-center");
                    $('#offerAnchor_'+ItemId).attr('onclick', "showOfferModal("+ItemId+","+ItemTyp+","+CID+", "+MCatgId+", "+trow+")");
                    $('#offerAnchor_'+ItemId).css({"background-color": "yellow", "cursor": "pointer"});

                  }
                }else{
                  alert(res.response);
                }
            });
        }

        showOfferModalNew = (ItemId, ItemTyp, CID, MCatgId, trow, OrdNo, Itm_Portion, ItemSale) =>{
            $.post('<?= base_url('restaurant/get_item_offer') ?>',{ItemId:ItemId, ItemTyp:ItemTyp, CID:CID, MCatgId:MCatgId, Itm_Portion:Itm_Portion, ItemSale:ItemSale},function(res){
                if(res.status == 'success'){
                  var data = res.response;
                  if(data.length> 0){
                    var temp = `<select class="form-control" id="schemeOffer">`;

                    data.forEach((item, index) => {
                        temp += `<option value="${item.SchCd}" itemid="${ItemId}" disitemid="${item.Disc_ItemId}" itemname="${item.discName}" itemvalue="${item.itmVal}" itemkitcd="${item.KitCd}" pckcharge="${item.PckCharge}" itm_portion="${item.IPCd}" disc_ipcd="${item.Disc_IPCd}" taxtype="${item.TaxType}"  preptime="${item.PrepTime}" dcd="${item.DCd}"  itemtyp="${ItemTyp}" cid="${item.CID}" mcatgid="${item.MCatgId}" qty="${item.Qty}" disqty="${item.Disc_Qty}" sdetcd="${item.SDetCd}" rowid="${ItemId}" trow="${trow}" fid="${item.FID}" discitempcent="${item.DiscItemPcent}" schtyp="${item.SchTyp}" ordno="${OrdNo}" itemsale="${ItemSale}">${item.SchNm} - ${item.SchDesc} </option>`;
                    });
                    temp += `</select>`;

                  }
                  $('#offerBody').html(temp);
                  $('.offersModal').modal('show');
                  
                }else{
                  alert(res.response);
                }
            });
        }

        showOfferModal = (ItemId, ItemTyp, CID, MCatgId, trow) =>{
            $.post('<?= base_url('restaurant/get_item_offer') ?>',{ItemId:ItemId, ItemTyp:ItemTyp, CID:CID, MCatgId:MCatgId},function(res){
                if(res.status == 'success'){
                  var data = res.response;
                  if(data.length> 0){
                    var temp = `<select class="form-control" id="schemeOffer">`;

                    data.forEach((item, index) => {
                        temp += `<option value="${item.SchCd}" itemid="${ItemId}" disitemid="${item.Disc_ItemId}" itemname="${item.discName}" itemvalue="${item.itmVal}" itemkitcd="${item.KitCd}" pckcharge="${item.PckCharge}" itm_portion="${item.IPCd}" disc_ipcd="${item.Disc_IPCd}" taxtype="${item.TaxType}"  preptime="${item.PrepTime}" dcd="${item.DCd}"  itemtyp="${ItemTyp}" cid="${item.CID}" mcatgid="${item.MCatgId}" qty="${item.Qty}" disqty="${item.Disc_Qty}" sdetcd="${item.SDetCd}" rowid="${ItemId}" trow="${trow}" fid="${item.FID}" discitempcent="${item.DiscItemPcent}" schtyp="${item.SchTyp}">${item.SchNm} - ${item.SchDesc} </option>`;
                    });
                    temp += `</select>`;

                  }
                  $('#offerBody').html(temp);
                  $('.offersModal').modal('show');
                  
                }else{
                  alert(res.response);
                }
            });
        }

        $('#offerForm').on('submit', function(e){
            e.preventDefault();
            var SchCd = $('#schemeOffer').val();
            var Qty = $('option:selected', $('#schemeOffer')).attr('qty');
            var disQty = $('option:selected', $('#schemeOffer')).attr('disqty');
            var SDetCd = $('option:selected', $('#schemeOffer')).attr('sdetcd');

            var itemId = $('option:selected', $('#schemeOffer')).attr('itemid');
            var disItemid = $('option:selected', $('#schemeOffer')).attr('disitemid');
            
            var itemName = $('option:selected', $('#schemeOffer')).attr('itemname');
            var origVal = $('option:selected', $('#schemeOffer')).attr('itemvalue');
            var itemKitCd = $('option:selected', $('#schemeOffer')).attr('itemkitcd');
            var PckCharge = $('option:selected', $('#schemeOffer')).attr('pckcharge');
            var Itm_Portion = $('option:selected', $('#schemeOffer')).attr('itm_portion');
            var disc_ipcd = $('option:selected', $('#schemeOffer')).attr('disc_ipcd');
            
            var TaxType = $('option:selected', $('#schemeOffer')).attr('taxtype');
            var PrepTime = $('option:selected', $('#schemeOffer')).attr('preptime');
            var DCd = $('option:selected', $('#schemeOffer')).attr('dcd');
            var ItemTyp = $('option:selected', $('#schemeOffer')).attr('itemtyp');
            var CID = $('option:selected', $('#schemeOffer')).attr('cid');
            var MCatgId = $('option:selected', $('#schemeOffer')).attr('mcatgid');
            var FID = $('option:selected', $('#schemeOffer')).attr('fid');
            var ItemSale = $('option:selected', $('#schemeOffer')).attr('itemsale');
            var discitempcent = $('option:selected', $('#schemeOffer')).attr('discitempcent');

            var rowid = $('option:selected', $('#schemeOffer')).attr('rowid');
            var trow = $('option:selected', $('#schemeOffer')).attr('trow');

            var OrdNo = $('option:selected', $('#schemeOffer')).attr('ordno');

            $(`#SchCd_${trow}_${rowid}`).val(SchCd);
            $(`#SDetCd_${trow}_${rowid}`).val(SDetCd);

            $(`#qty_${trow}_${rowid}`).attr('readonly', 'true');
            $(`#select_${trow}_${rowid}`).attr('disabled', 'true');
            
            $('#offerAnchor_'+rowid).removeAttr('onclick');
            $('#offerAnchor_'+rowid).css({"background-color": "white"});

            $('.offersModal').modal('hide');

            var itemValue = origVal;
            if(disItemid > 0){
             itemValue = parseInt(origVal) - (parseInt(origVal) * parseInt(discitempcent) / 100);
            }

            $(`#qty_${trow}_${itemId}`).val(Qty);

            var tableRow = trow + 1;

            itemSlected_new(itemId, disItemid, itemName, origVal, itemValue, itemKitCd, PckCharge, disc_ipcd, TaxType, PrepTime, DCd, ItemTyp, CID, MCatgId, SchCd, SDetCd, disQty, tableRow, FID, ItemSale, OrdNo);

            // itemSlected(itemId, disItemid, itemName, origVal, itemValue, itemKitCd, PckCharge, disc_ipcd, TaxType, PrepTime, DCd, ItemTyp, CID, MCatgId, SchCd, SDetCd, disQty, tableRow, FID);

        })

        changePortion =(trow, itemId) => {
            var portion = $(`#select_${trow}_${itemId}`).val();
            var rate = $('option:selected','#select_'+trow+'_'+itemId).attr('rate');

            $(`#rate_${trow}_${itemId}`).text(rate);
            $(`#value_${trow}_${itemId}`).text(rate);

            calculateTotal();
        }

        var groupNameList = [];
        getCustomItems = (trow, ItemId, ItemTyp, Itm_Portion, FID, OrdNo) =>{
            $.post('<?= base_url('restaurant/get_custom_items') ?>',{ItemId:ItemId, ItemTyp:ItemTyp, Itm_Portion:Itm_Portion, FID:FID},function(res){
                if(res.status == 'success'){

                  var customItem = res.response;
                        radioList = customItem;
                        
                        for(i=0; i< customItem.length; i++){
                            // alert(customItem[i].ItemGrpName);
                            if(customItem[i].GrpType == 1){
                                groupNameList.push(customItem[i].ItemGrpName);
                                
                                var tempRadio = '<h5 class="widget-header" id="radioHeader">'+customItem[i].ItemGrpName+'</h5>\
                                        <ul class="category-list">';
                                var details = customItem[i].Details;
                                
                                for(var r=0; r < details.length; r++){
                                    var name = "'"+details[r].Name+"'";
                                    tempRadio += '<li><input type="radio" name="'+customItem[i].ItemGrpName+'" value="'+details[r].ItemOptCd+'" rate="'+details[r].Rate+'" onclick="calculateTotalc('+customItem[i].ItemGrpCd+', '+i+', '+name+', event)" /> '+details[r].Name+' <span class="float-right">('+details[r].Rate+')</span></li>';
                                }
                                tempRadio += '</ul>';
                                $('#radioOption').html(tempRadio);
                                $('#radioOption').show();
                            }else if(customItem[i].GrpType == 2){
                                
                                var tempCHK = '<h5 class="widget-header" id="radioHeader">'+customItem[i].ItemGrpName+'</h5>\
                                        <ul class="category-list">';

                                var details = customItem[i].Details;
                                
                                for(var c=0; c < details.length; c++){
                                    var name = "'"+details[c].Name+"'";
                                    tempCHK += '<li><input type="checkbox" name="'+customItem[i].ItemGrpName+'" value="'+details[c].ItemOptCd+'" rate="'+details[c].Rate+'" onclick="calculateTotalc('+customItem[i].ItemGrpCd+', '+c+', '+name+', event)" /> '+details[c].Name+' <span class="float-right">('+details[c].Rate+')</span></li>';
                                }
                                tempCHK += '</ul>';
                                $('#checkboxOption').html(tempCHK);
                                $('#checkboxOption').show();
                            }
                        }
                        
                $("#custom_trow").val(trow);
                $("#custom_itemId").val(ItemId);
                $("#custom_ordNo").val(OrdNo);

                $('#custOfferAmount').val($(`#rate_${trow}_${ItemId}`).text());
                $('#custOfferAmountView').text($(`#rate_${trow}_${ItemId}`).text());
                $('.customOfferModal').modal('show');
                  
                }else{
                  alert(res.response);
                }
            });
        }

        var radioRate = [];
        var raidoGrpCd= [];
        var radioName= [];
        var checkboxVal= [];
        var checkboxRate= [];
        var checkboxItemCd= [];
        var checkboxGrpCd= "";
        var checkboxName= [];
        var custOfferTotal = 0;
        var custOfferName = [];

        function calculateTotalc(itemGrpCd, index, itemName, event) {

            element = event.currentTarget;
            var rate = element.getAttribute('rate');
            // console.log('calc '+index, event.target.type, rate, itemName);
            if (event.target.type == "radio") {
                this.radioRate[index] = parseInt(rate);
                this.raidoGrpCd[index] = itemGrpCd;
                this.radioName[index] = itemName;
            } else {
                // console.log(event.target.checked);
                if (event.target.checked) {
                    this.checkboxRate[index] = parseInt(rate);
                    this.checkboxName[index] = itemName;
                } else {
                    this.checkboxRate[index] = 0;
                    this.checkboxName[index] = 0;
                }
            }

            getTotalc();
        }

        getTotalc = () =>{
            
            var itemAmount =  $('#custOfferAmount').val();
            var radioTotal = 0;
            this.radioRate.forEach(item => {
                radioTotal += parseInt(item);
            });

            var checkTotal = 0;
            this.checkboxRate.forEach(item => {
                checkTotal += parseInt(item);
            });

            this.custOfferTotal = parseInt(itemAmount) + parseInt(radioTotal) + parseInt(checkTotal);
            
            $('#custOfferAmountView').text(this.custOfferTotal);
        }

        $('#customOfferForm').on('submit', function(e){
            e.preventDefault();
            // mandatory radio options
            if(groupNameList.length > 0){
                var mandatory = false;
                //groupNameList
                for(var g=0; g<groupNameList.length;g++){ 
                    //comment on and check this code mandatory = false;
                    var groupName = document.getElementsByName(groupNameList[g]); 
                      for(var i=0; i<groupName.length;i++){ 
                          if(groupName[i].checked == true){ 
                              mandatory = true;     
                          } 
                      } 
                  }
                   
                  if(!mandatory){ 
                      alert("Please Choose the Required Field!!"); 
                      return false; 
                  } 
            }

            var trow = $("#custom_trow").val();
            var ItemId = $("#custom_itemId").val();
            var ordNo = $("#custom_ordNo").val();
            var custOfferAmountView = $('#custOfferAmountView').text();


            $(`#select_${trow}_${ItemId}`).attr('disabled', 'true');
            $(`#rate_${trow}_${ItemId}`).text(custOfferAmountView);

            if(radioName.length > 0){
                radioName.forEach(item =>{
                    custOfferName.push(item);
                });
            }

            if(checkboxName.length > 0){
                checkboxName.forEach(item =>{
                    custOfferName.push(item);
                });
            }
            var itemName = $(`#itemName_${trow}_${ItemId}`).text();
            // convert array to string
            itemName = itemName+' - '+custOfferName.join(", ");
            $(`#itemName_${trow}_${ItemId}`).text(itemName);

            $(`#CustItem_${trow}_${ItemId}`).val(1);
            $(`#CustItemDesc_${trow}_${ItemId}`).val(custOfferName.join(", "));
            
            customCalculateValue(trow, ItemId);

            $.post('<?= base_url('restaurant/update_customItem_onTempKitchen') ?>',{ItemId:ItemId, OrdNo:ordNo, CustItemDesc:custOfferName.join(", "), OrigRates:custOfferAmountView},function(res){
                if(res.status == 'success'){
                    getTableData();
                    $('.customOfferModal').modal('hide');
                }else{

                }
            });



        });

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

        function customCalculateValue(trow, ItemId) {
            var total = 0;
            var qty = $(`#qty_${trow}_${ItemId}`).val();
            var rate = $(`#rate_${trow}_${ItemId}`).text();
            qty = convertDigitToEnglish(qty);
            rate = convertDigitToEnglish(rate);
            total = parseInt(qty) * parseInt(rate);

            $(`#qty_${trow}_${ItemId}`).val(convertToUnicodeNo(qty));
            $(`#rate_${trow}_${ItemId}`).text(convertToUnicodeNo(total));
            $(`#value_${trow}_${ItemId}`).text(convertToUnicodeNo(total));
            calculateTotal();
        }

        function get_table_order_items(el){
            var check_ccd = $('#ccd').val();
            if(check_ccd == 0){
                alert('Cashier Setup / Role not defined!');
                $('#table-id').val(0);
                return false;
            }
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
            getTableData();
            // changeSeatNo();
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
                            var trow = 0;
                            for(i = 0;i<a.length;i++){
                                trow++;
                                cno = a[i].CNo;
                                var ch = '';
                                if(a[i].TA == 1){
                                    ch = 'checked';
                                }

                                getPortionLists(a[i].ItemId, trow,  a[i].Itm_Portion);

                                b+='<tr>';
                                b+='<td>'+a[i].ItemNm+'</td><td><select class="form-control form-control-sm item-select" id="select_'+trow+'_'+a[i].ItemId+'" onchange="changePortion('+a[i].ItemId+', '+trow+')" disabled><option></option></select></td><td>'+convertToUnicodeNo(a[i].Qty)+'</td><td>'+convertToUnicodeNo(a[i].ItmRate)+'</td><td class="item-value">'+convertToUnicodeNo(a[i].OrigRate)+'</td><td><input type="checkbox" value="'+a[i].TA+'" '+ch+' disabled /></td><td>'+a[i].CustRmks+'</td><td></td>';
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
                if(table != '' && table != '0'){
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