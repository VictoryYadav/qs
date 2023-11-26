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
                                            <!-- <div class="col-md-3 form-group col-6">
                                                <label>Order Type</label>
                                                <select class="form-control form-control-sm" id="order-type">
                                                    <option value="0">Select</option>
                                                    <option value="101">3rd Party</option>
                                                    <option value="105">Take Away</option>
                                                    <option value="110">Deliver</option>
                                                    <option value="8">Sit-In</option>
                                                </select>
                                            </div> -->
                                            <?php if($OType == 8) { ?>
                                            <div class="col-md-3 form-group col-6">
                                                <label>Table No</label>
                                                <select class="form-control form-control-sm" id="table-id" onchange="get_table_order_items(this)">
                                                    <option value="0">Select</option>
                                                    <?php foreach ($tablesAlloted as $data) : ?>
                                                        <option value="<?= $data['TableNo'] ?>"><?= $data['MergeNo'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <?php } ?>
                                            <?php if($OType == 101) { ?>
                                            <div class="col-md-3 form-group col-6">
                                                <label>3rd Party</label>
                                                <select class="form-control form-control-sm" id="3rd-party">
                                                    <option value="0">Select</option>
                                                    <?php foreach ($thirdOrdersData as $data) : ?>
                                                        <option value="<?= $data['3PId'] ?>"><?= $data['Name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-3 form-group col-6">
                                                <label>3rd Party Ref No</label>
                                                <input type="text" id="3rd-party-refNo" class="form-control form-control-sm">
                                            </div>
                                        <?php } ?>
                                            <div class="col-md-3 form-group col-6">
                                                <label>Phone No</label>
                                                <input type="text" id="phone" class="form-control form-control-sm">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-9 form-group col-6">
                                                <label>Customer Address</label>
                                                <input type="text" class="form-control form-control-sm" id="cust-address" <?php if($OType != 110){ echo 'disabled'; } ?>>
                                            </div>

                                            <div class="col-md-3 form-group col-6">
                                                <label>Item Amt</label>
                                                <input type="text" id="total-value" readonly="" value="0" class="form-control form-control-sm">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-7 form-group col-6">
                                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#item-list-modal" title="Kitchen Order Ticket">KOT 
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                                <?php if($OType == 8){ ?>
                                                <button class="btn btn-success btn-sm send-to-kitchen" data_type="save_to_kitchen" id="btnOrder">Order</button>
                                                <?php } ?>
                                                <?php if($OType != 8){ ?>
                                                <button class="btn btn-warning btn-sm send-to-kitchen" data_type="bill" id="btnBill">Bill</button>
                                                <?php } ?>
                                            </div>

                                            <!-- <div class="col-md-4 form-group text-center col-8">

                                                <button class="btn btn-success send-to-kitchen" data_type="save_to_kitchen">Send to Kitchen</button>
                                                <button class="btn btn-warning send-to-kitchen" data_type="bill">Bill</button>
                                            </div> -->
                                            
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                <table class="table table-bordered" id="tableData">
                                                    <thead>
                                                        <tr>
                                                            <th>Item</th>
                                                            <th>Qty</th>
                                                            <th>Rate</th>
                                                            <th>Value</th>
                                                            <th>TA</th>
                                                            <th>Remark</th>
                                                            <th>Action</th>
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

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="billViewTbl">
                                                <thead>
                                                    <tr>
                                                        <th>Bill No</th>
                                                        <th>Bill Date</th>
                                                        <th>Bill Amt</th>
                                                        <th>Phone No</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if(!empty($bills)){
                                                        foreach ($bills as $key) {
                                                     ?>
                                                    <tr>
                                                        <td>
                                                            <a href="<?php echo base_url('restaurant/bill/'.$key['BillId']); ?>" target="_blank"><?= $key['BillNo']; ?>
                                                            </a>
                                                        </td>
                                                        <td><?= date('d-M-Y',strtotime($key['billTime'])); ?></td>

                                                        <td><?= $key['PaidAmt']; ?></td>   
                                                        <td ><?= $key['CellNo']; ?></td>
                                                        <td>
                                                            
                                                            <a href="<?php echo base_url('restaurant/print/'.$key['BillId']); ?>" class='btn btn-warning btn-sm'>
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                            <button title="Cash Collect" class="btn btn-sm btn-info" id="btnCash" onclick="cashCollect(<?= $key['BillId']; ?>,<?= $key['OType']; ?>,<?= $key['TableNo']; ?>,<?= $key['MergeNo']; ?>,'<?= $key['CellNo']; ?>',<?= $key['PaidAmt']; ?>,<?= $key['CNo']; ?>,<?= $key['EID']; ?>)"><i class="fas fa-money-check"></i>
                                                </button>
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
                    <h6>Cash Collect</h6>
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
                                    <th>Mode</th>
                                    <th>Amount</th>
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

        function itemSlected(itemId, itemName, itemValue, itemKitCd, PckCharge,Itm_Portion, TaxType) {
            var orderType = $('#order-type').val();
            ch = '';
            if(orderType != 8){
                ch = 'checked disabled';
            }
            console.log(itemId, itemName, itemValue, itemKitCd);
            $("#item-search-result").html('');
            $("#search-item").val('');
            $("#item-list-modal").modal('hide');
            var template = `
            <tr class="item-id" data-id="${itemId}" kitcd-id="${itemKitCd}" pckcharge ="${PckCharge}" Itm_Portion ="${Itm_Portion}">
                <td>${itemName}</td>
                <td style="width:50px;"><input type="number" class="form-control form-control-sm item-qty" min="1" value="1" onblur="calculateValue(this)"style="width:50px;" ></td>
                <td class="item-rate">${itemValue}</td>
                <td class="item-value">${itemValue}</td>
                <td><input type="checkbox" value="1" class="is_take_away" `+ch+`></td>
                <td><input type="text" class="form-control form-control-sm item-remarks" style="width:100%;"></td>
                <td style=" text-align: center; ">
                <button type="button" onclick="deleteItem(this)" class="btn btn-sm btn-danger btn-rounded">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
                <input type="hidden" value="${TaxType}" class="taxtype">
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

        function calculateTotal() {
            var totalValue = 0;

            $(".item-value").each(function(index, el) {
                totalValue += parseInt($(this).text());
            });

            $("#total-value").val(totalValue);
        }

        function calculateValue(input) {
            var qty = $(input).val();
            var rate = $(input).parent('td').next('td').text();
            $(input).parent('td').next('td').next('td').text(qty * rate);
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
                                        printItemName = item.ItemNm;
                                    }else if(IMcCd == 1){
                                        printItemName = item.ItemId+' - '+item.ItemNm;
                                    }else if(IMcCd == 2){
                                        printItemName = item.IMcCd+' - '+item.ItemNm;
                                    }
                                    
                                    template += `
                                <li onclick="itemSlected(${item.ItemId}, '${item.ItemNm}', ${item.Value}, ${item.KitCd},${item.PckCharge},${item.Itm_Portion}, ${item.TaxType});" style="cursor: pointer;">${printItemName}</li>
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
                $('.send-to-kitchen').attr("disabled", "disabled");

                var data_type = $(this).attr('data_type');
                // alert(data_type);
                var orderType = $("#order-type").val();
                console.log('vv '+orderType);
                if (orderType != 8 ) {
                    // var tableNo = 'TA';
                    var tableNo = orderType;
                } else {
                    var tableNo = $("#table-id").val();
                }
                var thirdParty = $("#3rd-party").val();
                var thirdPartyRef = $("#3rd-party-refNo").val();
                var customerAddress = $("#cust-address").val();
                var customerPhone = $("#phone").val();
                // var thirdParty = 0;

                // var thirdPartyRef = 0;

                // var customerAddress = 0;

                // var customerPhone = 1111111111;
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
                var dataType = $(this).attr('data_type');
                // Page Validation
                if (orderType == 0) {
                    formFill = false;
                    alert("Please Select Order Type");
                } else if (itemCount < 2) {
                    formFill = false;
                    alert("Please Enter Atleast 1 Item");
                } else if (orderType == 1) {
                    if (thirdParty == 0) {
                        formFill = false;
                        alert("Please Select 3rd Party");
                    }
                    if (thirdPartyRef == '') {
                        formFill = false;
                        alert("Please Enter 3rd Party Ref Number");
                    }

                } else if (orderType == 2) {
                    if (customerPhone == "") {
                        formFill = false;
                        alert("Enter Customer Phone Number");
                    }

                } else if (orderType == 3) {
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

                console.log(orderType, thirdParty, thirdPartyRef, totalValue, itemCount, formFill);

                if (formFill) {
                    $(".item-id").each(function(index, el) {
                        itemIds.push($(this).attr('data-id'));
                        Itm_Portion.push($(this).attr('Itm_Portion'));
                        itemKitCds.push($(this).attr('kitcd-id'));
                        pckValue.push($(this).attr('pckcharge'));
                    });

                    $(".item-qty").each(function(index, el) {
                        itemQty.push($(this).val());
                    });

                    $(".taxtype").each(function(index, el) {
                        taxtype.push($(this).val());
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
                        item_value.push($(this).text());
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
                            take_away:take_away
                        },

                        dataType: "json",
                        success: (response) => {
                            console.log(response);
                            if (response.status) {
                                if (data_type == 'bill') {
                                    alert("Order Billed Successfully");
                                    window.location = "<?= base_url('restaurant/bill/'); ?>"+response.data.billId;
                                }else{
                                alert("Order Placed Successfully");
                                var MCNo = response.data.MCNo;
                                var MergeNo = response.data.MergeNo;
                                var FKOTNo = response.data.FKOTNo;
                                
                                
                                // location.reload();
                                    if(orderType == 8){
                                        <?php if($this->session->userdata('sitinKOTPrint') > 0){ ?>
                                            window.location = "<?= base_url('restaurant/kot_print/'); ?>"+MCNo+'/'+MergeNo+'/'+FKOTNo;
                                        <?php } ?>
                                    }else{
                                        window.location = "<?= base_url('restaurant/kot_print/'); ?>"+MCNo+'/'+MergeNo+'/'+FKOTNo;    
                                    }
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
            var table_no = el.value;
            $.ajax({
              type:'post',
              url:"<?php echo base_url('restaurant/order_ajax_3p'); ?>",
              data:{get_table_order_items:1, table_no:table_no},
              success:function(data){
                $('#order-table-body').html('');
                var a = JSON.parse(data);
                // alert(a.length);
                var billStat = a[0].kmBillStat;
                var b = '';
                if(billStat > 0){
                    alert('Cannot add more items, as bill has been generated for this table')
                }else{
                    for(i = 0;i<a.length;i++){
                        cno = a[i].CNo;
                        var ch = '';
                        // alert(a[i].TA);
                        if(a[i].TA == 1){
                            ch = 'checked';
                        }
                        b+='<tr>';
                        b+='<td>'+a[i].ItemNm+'</td><td>'+a[i].Qty+'</td><td>'+a[i].ItmRate+'</td><td class="item-value">'+a[i].Value+'</td><td><input type="checkbox" value="'+a[i].TA+'" '+ch+' disabled /></td><td>'+a[i].CustRmks+'</td>';
                        b+='</tr>';
                    }

                    $('#phone').val(a[0].CellNo);

                    $('#order-table-body').append(b);
                    calculateTotal();
                }
              }
          });

        }

        function cashCollect(billId, oType, tableNo, mergeNo, cellNo, paidAmt, MCNo,EID){
            var tbl = '<input type="hidden" name="BillId" value="'+billId+'"/><input type="hidden" name="oType" value="'+oType+'"/><input type="hidden" name="TableNo" value="'+tableNo+'"/><input type="hidden" name="MCNo" value="'+MCNo+'"/><input type="hidden" name="EID" value="'+EID+'"/><input type="hidden" name="MergeNo" value="'+mergeNo+'"/><input type="hidden" name="CellNo" value="'+cellNo+'"/><input type="hidden" name="TotBillAmt" value="'+paidAmt+'"/><input type="text" name="PaidAmt" value="'+paidAmt+'" required class="form-control form-control-sm" />'

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
          
            var PaidAmt = data[8].value;
            var TotBillAmt = data[7].value;
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
    </script>