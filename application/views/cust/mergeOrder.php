<?php $this->load->view('layouts/customer/head'); ?>
<style>

    .bill-body {
        padding-bottom: 70px;
        height: -webkit-fill-available;
    }

    .hideDiv {
        display: none;
    }

    .pay-bill {
        border: 1px solid #fff;
        width: 125px;
        border-radius: 20px;
        margin-top: 10px;
    }

    .order-list {
        padding-left: 15px;
        padding-right: 15px;
    }

    .order-list::-webkit-scrollbar {
        width: 5px;
    }

    .order-list::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0);
    }

    .order-list::-webkit-scrollbar-thumb {
        background-color: white;
        /*outline: 1px solid slategrey;*/   
    }

    .main-ammount {
        padding: 0 15px;
    }

    .bill-ord-amt {
        font-size: 14px;
        font-weight: bold;
    }

    .remove-row-margin {
        margin-left: 0px;
        margin-right: 0px;
    }

    .bill_box{
        height: 314px;
        overflow-y: scroll;
    }

    #tips{
        width: 60px; 
        float: right;
        border-radius:15px;
        font-size:13px;
        text-align:center;
        border: 1px solid #ced4da;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }

    .backbtn 
    {
        background-color: <?php echo $this->session->userdata('menuBtn'); ?>;
        color: <?php echo $this->session->userdata('menuBtnClr'); ?>;
        margin-right: -5px !important;
        border-radius: 1.5rem 0 0 1.5rem;
        width: 30%;
    }

    .backbtn:hover
    {
        background-color: #efd4b3;
        color:#fff;   
    }
    .paybtn 
    {
        background: <?php echo $this->session->userdata('successBtn'); ?>;
        color: <?php echo $this->session->userdata('successBtnClr'); ?>;
        margin-left: -5px !important;
        border-radius: 0 1.5rem 1.5rem 0;
        width: 32%;
    }

    .paybtn:hover
    {
        background: #03bb2c;
        color: #fff;
        border-radius: 0 1.5rem 1.5rem 0;
    }
</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <section class="common-section p-2 dashboard-container">
        <div class="container">
            <form id="splitForm" action="<?= base_url('customer/splitOrder/'.$MergeNo); ?>" method="post">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" style="margin-left: 16px;">
                      <table class="fixed_headers" style="font-size: 12px;width: 100%;">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= $this->lang->line('cellNo'); ?></th>
                                <th><?= $this->lang->line('orderAmount'); ?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                            if(!empty($orders)){
                                $total = 0;
                                $split_mobile = array();
                                $cn_no = array();
                                foreach ($orders as $ord) {
                                    $cn_no[] = $ord['CNo'];
                                    $total = $total + $ord['OrdAmt'];
                                    $split_mobile[] = $ord['CellNo'];
                            ?>

                            <tr onclick="getOrderDetails(<?= $ord['CNo']; ?>, '<?= $ord['Fullname']; ?>')" style="cursor: pointer;">
                                <input type="hidden" name="mobile[]" value="<?= $ord['CellNo']; ?>">
                                <input type="hidden" name="cust_id[]" value="<?= $ord['CustId']; ?>">
                                <td>
                                    <input type="hidden" name="CNo[]" value="<?= $ord['CNo']; ?>">
                                     <input type="checkbox" name="chck_cno[]" checked="" onchange="calcValue(<?= $ord['CNo']; ?>,<?= $ord['OrdAmt']; ?>)" id="item_<?= $ord['CNo']; ?>" class="cnoClass" value="<?= $ord['CNo']; ?>">

                                </td>
                                <td>
                                    <?= $ord['CellNo']; ?> <small>(<?= $ord['Fullname']; ?>) <?php if(!empty($ord['pcent'])){ echo 'Disc @ '. $ord['pcent'].'%'; } ?></small>
                                </td>
                                <td>
                                    <?= convertToUnicodeNumber($ord['OrdAmt']); ?>
                                </td>
                            </tr>
                        <?php }
                        ?>
                        <input type="hidden" value="<?= implode(',', $cn_no); ?>" id="allCNo">
                        <?php }
                            $this->session->set_userdata('split_mobile', $split_mobile);
                         ?>
                        <tr>
                           <td></td> <td><b><?= $this->lang->line('itemTotal'); ?></b></td>
                           <td id="grandTotal" style="font-weight: bold;">
                            <input type="hidden" name="grossItemAmt" value="<?= $total; ?>">
                            <?= convertToUnicodeNumber($total); ?>
                           </td>
                        </tr>
                        </tbody>

                      </table>
                    </div>
                </div>
            </div>
            <!-- checkout -->
            <div class="bill-body mt-2" style="font-size: 12px;">
                <div class="bill_box">
                </div>
                <div class="main-ammount discount_and_other_charges_section" style="display:none">
                    <div style="border-bottom: 1px solid;margin-top: 5px;margin-bottom: 5px;color: #fff;"></div>
                    <table style="width:100%;" style="display:none">
                        <tr class="TotItemDisc_sec">
                            <th style="font-weight: normal;" id="cgst-h"> <?= $this->lang->line('totalDiscount'); ?> </th>
                            <td class="text-right" id="TotItemDisc"></td>
                        </tr>
                        <tr class="TotPckCharge_sec" style="display:none">
                            <th style="font-weight: normal;" id="sgst-h"> <?= $this->lang->line('packingCharge'); ?> </th>
                            <td class="text-right" id="TotPckCharge"></td>
                        </tr>
                        <tr class="DelCharge_sec" style="display:none">
                            <th style="font-weight: normal;" id="sgst-h"> <?= $this->lang->line('deliveryCharge'); ?> </th>
                            <td class="text-right" id="DelCharge"></td>
                        </tr>
                    </table>
                </div>

                <div style="border-bottom: 1px solid;margin-top: 5px;margin-bottom: 5px;color: #fff;">
                </div>

                <div class="row paybl" style="margin-left: 0px;margin-right: 0px;">
                    <div class="col-8">
                        <label class="bill-ord-amt" style="margin-bottom: 7px;"><?= $this->lang->line('payable'); ?> <small>(<?php echo  $this->lang->line('roundedoff'); ?>)</small></label>
                    </div>
                    <div class="col-4">
                        <input type="hidden" id="payableAmt">
                        <input type="hidden" name="payable" id="payableAmount">
                        
                        <input type="hidden" name="totalAmt" id="totalAmt">
                        <p class="bill-ord-amt text-right" id="payable" style="    margin-bottom: 7px;margin-right: 15px;"></p>
                    </div>
                </div>

                <div style="border-bottom: 1px solid;margin-bottom:5px;color: #fff;"></div>

                <div class="row">
                    <div class="col-12 text-center">
                        <?php if($this->session->userdata('billSplitOpt') > 0 && ($EType == 5)){ ?>
                        <button class="btn btn-sm backbtn" type="submit" name="btnName" value="splitBill">
                        <?php echo  $this->lang->line('splitbill'); ?>
                        </button>
                        <?php } ?>
                         <button class="btn btn-sm paybtn" type="submit" name="btnName" value="payNow">
                            <?php echo  $this->lang->line('payNow'); ?>
                        </button>
                            
                    </div>
                </div>
            </div>
            </form>
            
            <!-- end checkout -->
        </div>
    </section>

    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
    <!-- end footer section -->

    <!-- offers modal -->
    <div class="modal" id="orderDetails">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #dbbd89;">
                    <p class="modal-title offers-txt text-white">Orders</p>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">
                      <table class="table table-sm" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th><?= $this->lang->line('item'); ?></th>
                                    <th><?= $this->lang->line('quantity'); ?></th>
                                    <th><?= $this->lang->line('value'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="orderBody1">
                            </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Js Plugins -->
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

<script type="text/javascript">
    var MergeNo = '<?= $MergeNo; ?>';
    var AllCNo = $('#allCNo').val();
    var CNo = 0; 
   $(document).ready(function() {
        merge_checkout(MergeNo, AllCNo);
    });

   function merge_checkout(MergeNo, AllCNo) {
        // selected checkbox
        var selectedFruits = [];
        var checkboxes = document.querySelectorAll('input[name="chck_cno[]"]');
        var countBox = 0;
        checkboxes.forEach(function(checkbox) {
          // checkbox.addEventListener('change', function() {
            if (checkbox.checked) {
              selectedFruits.push(checkbox.value);
              countBox++;
            } else {
              var index = selectedFruits.indexOf(checkbox.value);
              if (index !== -1) {
                selectedFruits.splice(index, 1);
              }
            }

            console.log('chkbox '+selectedFruits+', box '+countBox);
          // });
        });
        // end of selected checkbox
        if(countBox >= 2){
            $(".cnoClass").removeAttr("disabled");
            $.ajax({
                url: "<?= base_url('customer/merge_checkout'); ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    MergeNo: MergeNo,
                    AllCNo:AllCNo,
                    CNo:selectedFruits
                },
                success: response => {

                    console.log(response.kitcheData);
                    var template = ``;
                    var sub_total = 0;
                    var html = ``;
                    var html_body = ``;
                    var grand_total = 0;
                    var initil_value = response.kitcheData[0]['TaxType'];
                    var del_charge = response.kitcheData[0]['DelCharge'];
                    var pck_charge = response.kitcheData[0]['TotPckCharge'];
                    var ServChrg = response.kitcheData[0]['ServChrg'];
                    var MCNo = response.kitcheData[0]['MCNo'];
                    
                    var disc = parseInt(response.kitcheData[0]['totBillDiscAmt']) + parseInt(response.kitcheData[0]['TotItemDisc']) + parseFloat(response.kitcheData[0]['RtngDiscAmt']);
                    
                    var hr_line = `<div style="border-bottom: 1px solid;margin-top: 5px; margin-bottom: 5px;color: #fff;"></div>`;
                    var itemAmount = 0;
                    response.kitcheData.forEach(item => {
                        var ta = (item.TA != 0)?'[TA]':'';
                        
                        if(initil_value == item.TaxType){
                            html += `<tr>`;
                            if(item.Itm_Portion > 4){

                                html += `<td>${item.ItemNm} (${item.Itm_Portion} )${ta} </td>`;
                            }else{
                                html += `<td>${item.ItemNm} ${ta} </td>`;
                            }
                            
                            html += `<td class="text-center">${convertToUnicodeNo(item.Qty)}</td>`;
                            html += `<td class="text-center">${convertToUnicodeNo(item.OrigRate)}</td>`;
                            html += `<td class="text-right">${convertToUnicodeNo(item.OrdAmt)}</td>`;
                            html += `</tr>`;

                            sub_total = sub_total +  parseInt(item.OrdAmt);
                            initil_value = item.TaxType;

                        }else{

                            html += `<tr style="border-top: 1px solid white;">`;
                            html += `<td><b><?= $this->lang->line('itemTotal'); ?> :</b> </td>`;
                            html += `<td class="text-center"></td>`;
                            html += `<td class="text-center"></td>`;
                            html += `<td class="text-right"><b>${convertToUnicodeNo(sub_total)}</b></td>`;
                            html += `</tr>`;

                            var sub_total_temp = sub_total;
                            
                            for (let index = 0; index < response.TaxData[initil_value].length; index++) {
                                const element = response.TaxData[initil_value][index];
                                var total_percent = parseFloat(sub_total_temp) * (parseFloat(element['TaxPcent'])/100);
                                    if(element['Included'] != 0 ){

                                        html += `<tr>`;
                                        html += `<td>${element['ShortName']} ${convertToUnicodeNo(element['TaxPcent'])} % </td>`;
                                        html += `<td class="text-center"></td>`;
                                        html += `<td class="text-center"></td>`;
                                        html += `<td class="text-right">${convertToUnicodeNo(parseFloat(element['SubAmtTax']).toFixed(2))}</td>`;

                                        html += `</tr>`;
                                        
                                    }

                                    if(element['Included'] >= 5){

                                        sub_total = parseFloat(sub_total_temp) + parseFloat(element['SubAmtTax']);

                                    }
                                
                            }

                            grand_total = grand_total + sub_total;
                            itemAmount = parseFloat(itemAmount) + parseFloat(sub_total.toFixed(2));

                            html += `<tr style="border-top: 1px solid white;border-bottom: 3px solid white;">`;
                            html += `<td><b><?= $this->lang->line('subTotal'); ?> :</b> </td>`;
                            html += `<td class="text-center"></td>`;
                            html += `<td class="text-center"></td>`;
                            html += `<td class="text-right"><b style="color: orange;">${convertToUnicodeNo(sub_total.toFixed(2))}</b></td>`;
                            html += `</tr> <tr style="height: 25px;"></tr>`;

                            initil_value = item.TaxType;

                            sub_total = 0;

                            html += `<tr>`;
                            html += `<td>${item.ItemNm} ${ta}</td>`;
                            html += `<td class="text-center">${convertToUnicodeNo(item.Qty)}</td>`;
                            html += `<td class="text-center">${convertToUnicodeNo(item.ItmRate)}</td>`;
                            html += `<td class="text-right">${convertToUnicodeNo(item.OrdAmt)}</td>`;
                            html += `</tr>`;

                            sub_total = sub_total +  parseInt(item.OrdAmt);
                        }
                    });

                    html += `<tr style="border-top: 1px solid white;">`;
                    html += `<td><b><?= $this->lang->line('itemTotal'); ?> :</b> </td>`;
                    html += `<td class="text-center"></td>`;
                    html += `<td class="text-center"></td>`;
                    html += `<td class="text-right"><b>${convertToUnicodeNo(sub_total)}</b></td>`;
                    html += `</tr>`;

                    var sub_total_temp = sub_total;
                    var totSGST = 0;
                    for (let index = 0; index < response.TaxData[initil_value].length; index++) {
                        const element = response.TaxData[initil_value][index];
                        
                            if(element['Included'] != 0 ){
                                totSGST = totSGST + parseFloat(element['TaxPcent']);
                                html += `<tr>`;
                                html += `<td>${element['ShortName']} ${convertToUnicodeNo(element['TaxPcent'])} % </td>`;
                                html += `<td class="text-center"></td>`;
                                html += `<td class="text-center"></td>`;
                                html += `<td class="text-right">${convertToUnicodeNo(parseFloat(element['SubAmtTax']).toFixed(2))}</td>`;
                                html += `</tr>`;

                            }

                            if(element['Included'] >= 5){
                                sub_total = parseFloat(sub_total_temp) + parseFloat(element['SubAmtTax']);
                            }

                            sub_total_temp = sub_total;

                    }
                    grand_total = grand_total + sub_total;
                    html += `<input type="hidden" name="tot_sgst" value="`+totSGST+`" ><tr style="border-top: 1px solid white;border-bottom: 3px solid white;">`;
                            html += `<td><b><?= $this->lang->line('subTotal'); ?> :</b> </td>`;
                            html += `<td class="text-center"></td>`;
                            html += `<td class="text-center"></td>`;
                            html += `<td class="text-right"><b style="color: orange;">${convertToUnicodeNo(sub_total.toFixed(2))}</b></td>`;
                            html += `</tr>`;

                    itemAmount = parseFloat(itemAmount) + parseFloat(sub_total.toFixed(2));
                    
                    sub_total = 0;

                    html_body +=`<div class="order-list col-12">`;
                    html_body +=`<table class="fixed_headers" style="width:100%">`;
                    html_body +=`<thead>`;
                    html_body +=`<tr>`;
                    html_body +=`<th><?= $this->lang->line('menuItem'); ?></th>`;
                    html_body +=`<th class="text-center"><?= $this->lang->line('quantity'); ?></th>`;
                    html_body +=`<th class="text-center"><?= $this->lang->line('rate'); ?></th>`;
                    html_body +=`<th class="text-right"><?= $this->lang->line('amount'); ?></th>`;
                    html_body +=`</tr>`;
                    html_body +=`</thead>`;
                    html_body +=`<tbody id="order-amount-table-body">`;
                    html_body += html;
                    html_body +=`</tbody>`;
                    html_body +=`</table>`;
                    html_body +=`</div>`;
                    
                    var serv = ServChrg/100*grand_total;
                    
                    if(serv > 0){
                        html_body +=`<div class="main-ammount">`;
                        html_body +=`<table style="width:100%;">`;
                        html_body +=`<tr>`;
                        html_body +=`<th><?= $this->lang->line('serviceCharge'); ?> @ `+convertToUnicodeNo(ServChrg)+` %</th>`;
                        html_body +=`<td class="text-right">`+convertToUnicodeNo(serv.toFixed(2))+`</td>`;
                        html_body +=`</tr>`;
                        html_body +=`</table>`;
                        html_body +=`</div>`;
                        grand_total = grand_total + serv;
                    }
                    
                    if(disc > 0){
                        html_body +=`<div class="main-ammount">`;
                        html_body +=`<table style="width:100%;">`;
                        html_body +=`<tr>`;
                        html_body +=`   <th style="font-style: italic"><?= $this->lang->line('totalDiscount'); ?></th>`;
                        html_body +=`   <th class="text-right" style="font-style: italic">`+convertToUnicodeNo(disc)+`</th>`;
                        html_body +=`</tr>`;
                        html_body +=`</table>`;
                        html_body +=`</div>`;
                        grand_total = grand_total - disc;
                    }

                    if(del_charge > 0){
                        html_body +=`<div class="main-ammount">`;
                        html_body +=`<table style="width:100%;">`;
                        html_body +=`<tr>`;
                        html_body +=`   <th style="font-style: italic"><?= $this->lang->line('deliveryCharge'); ?></th>`;
                        html_body +=`   <th class="text-right" style="font-style: italic">`+convertToUnicodeNo(del_charge)+`</th>`;
                        html_body +=`</tr>`;
                        html_body +=`</table>`;
                        html_body +=`</div>`;
                        grand_total = grand_total + parseInt(del_charge);
                    }

                    if(pck_charge > 0){
                        html_body +=`<div class="main-ammount">`;
                        html_body +=`<table style="width:100%;">`;
                        html_body +=`<tr>`;
                        html_body +=`   <th style="font-style: italic"><?= $this->lang->line('packingCharge'); ?></th>`;
                        html_body +=`   <th class="text-right" style="font-style: italic">`+convertToUnicodeNo(pck_charge)+`</th>`;
                        html_body +=`</tr>`;
                        html_body +=`</table>`;
                        html_body +=`</div>`;
                        grand_total = grand_total + parseInt(pck_charge);
                    }

                    var itemGrossAmt = (grand_total - serv).toFixed(2);

                    var tt = '<?php echo $Tips?>';
                    if(tt == 1){
                        html_body +=`<div class="main-ammount">`;
                        html_body +=`<table style="width:100%;">`;
                        html_body +=`<tr class="<?= ($Tips == 0 ? 'hideDiv' : ''); ?>">`;
                        html_body +=`   <th><?= $this->lang->line('tips'); ?></th>`;
                        html_body +=`   <td><input type="hidden" name="MCNo" value="`+MCNo+`"><input id="tips" type="text" class="" value="0" onchange="change_tip()">
                        <input id="tipsVal" type="hidden" value="0" name="tip"></td>`;
                        html_body +=`</tr>`;
                        html_body +=`</table>`;
                        html_body +=`</div>`;
                    }

                    grand_total = Math.round(grand_total);

                    $('.bill_box').html(html_body);
                    $("#payable").text(convertToUnicodeNo(grand_total));
                    $("#payableAmt").val(grand_total);
                    $("#totalAmt").val(itemAmount);
                    // $("#totalAmt").val(itemGrossAmt);
                    $("#payableAmount").val(grand_total);

                },
                error: (xhr, status, error) => {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }else{
            // $('.cnoClass').attr('checked');
            $(".cnoClass").attr("disabled", true);
            alert('Minimum Two orders are required for Merging Orders.');
            return false;
        }
    }

    function change_tip(){
        var tips = $("#tips").val();
        var payableAmt  = $('#payableAmt').val();

        tips = convertDigitToEnglish(tips);

        var total =parseFloat( payableAmt ) + parseInt(tips);

        $("#payable").text(convertToUnicodeNo(total));
        $("#payableAmount").val(total);
        $("#tipsVal").val(tips);
        $("#tips").val(convertToUnicodeNo(tips));
    }

   function getOrderDetails(CNo, fullname){
    console.log('ff '+CNo);
        $.post('<?= base_url('customer/get_merge_order') ?>',{CNo:CNo},function(res){
            if(res.status == 'success'){
              
              var data = res.response;
              var temp = ``;
              var total = 0;
              var totalText = "<?= $this->lang->line('total'); ?>";
              // for (var i = 0; i < data.length; i++) {
              //   total = parseInt(total) + parseInt(data[i].Qty * data[i].ItmRate);
              //     temp += '<tr><td>'+data[i].ItemNm+'</td><td>'+convertToUnicodeNo(data[i].Qty)+'</td><td>'+convertToUnicodeNo(data[i].Qty * data[i].ItmRate)+'</td></tr>';
              // }
              // temp +='<tr><td></td><td>'+totalText+'</td><td><b>'+convertToUnicodeNo(total)+'</b></td></tr>';

              if(data.length > 0){
                data.forEach((item, index) => {
                    total = parseInt(total) + parseInt(item.Qty * item.ItmRate);
                    temp += `<tr><td>${item.ItemNm}</td><td>${item.Qty}</td><td>${item.Qty * item.ItmRate}</td></tr>`;     
                });

                temp += `<tr><td></td><td>${totalText}</td><td><b>${total}</b></td></tr>`;
              }

              $(`#orderBody1`).html(temp);
              $('#orderDetails').modal('show');
              $('.offers-txt').html(data[0].CellNo+' '+fullname);
            }else{
            }
        });
   }

   function calcValue(itemId, val){
        var grossTotal = $('#grandTotal').text();
        var total = 0;
        if ($('#item_'+itemId).is(':checked')) {
            total = parseInt(grossTotal) + parseInt(val);
        }else{
            total = parseInt(grossTotal) - parseInt(val);
        }
       $('#grandTotal').html(total);    
       CNo = itemId;
       merge_checkout(MergeNo, AllCNo, CNo);
   }

   function splitBill(){
        var mobile = $('#splitForm').serializeArray();
        var payable = $('#payableAmt').val();
        $.post('<?= base_url('customer/splitBill') ?>',{mobile:mobile, payable:payable},function(res){
        }
        );
   }

</script>

</html>