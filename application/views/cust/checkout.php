<?php $this->load->view('layouts/customer/head'); ?>
<style>
    #myOverlay {
        position: absolute;
        height: 100%;
        width: 100%;
    }

    #myOverlay {
        background: #0a88ff;
        z-index: 2;
        display: none;
    }

    #loadingGIF {
        position: absolute;
        top: 40%;
        left: 34%;
        z-index: 3;
        display: none;
    }

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
        margin-right: -5px !important;
        border-radius: 1.5rem 0 0 1.5rem;
        background-color: <?php echo $this->session->userdata('menuBtn'); ?>;
        color: <?php echo $this->session->userdata('menuBtnClr'); ?>;
        /*width: 30%;*/
    }

    .backbtn:hover
    {
        background-color: #efd4b3;
        color:#fff;   
    }

    .orderbtn 
    {
        background: <?php echo $this->session->userdata('orderBtn'); ?>;
        color: <?php echo $this->session->userdata('orderBtnClr'); ?>;
        margin-left: 0px !important;
        /*width: 33%;*/
    }

    .orderbtn:hover
    {
        background: #e97832;
        color: #fff;
        margin-left: 0px !important;
    }

    .paybtn 
    {
        background: <?php echo $this->session->userdata('successBtn'); ?>;
        color: <?php echo $this->session->userdata('successBtnClr'); ?>;
        margin-left: -5px !important;
        border-radius: 0 1.5rem 1.5rem 0;
        /*width: 32%;*/
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

    <!-- Shoping Cart Section Begin -->
    <section class="common-section p-2 dashboard-container">
        <div class="container">
            <div class="bill-body" style="font-size: 12px;">
                <div class="bill_box">
                </div>

                <div style="border-bottom: 1px solid;margin-top: 5px;margin-bottom: 5px;color: #fff;">
                </div>

                <div class="row paybl" style="margin-left: 0px;margin-right: 0px;">
                    <div class="col-8">
                        <label class="bill-ord-amt" style="margin-bottom: 7px;"><?php echo  $this->lang->line('payable'); ?><small>(<?php echo  $this->lang->line('roundedoff'); ?>)</small></label>
                    </div>
                    <div class="col-4">
                        <input type="hidden" name="payable" id="payableAmt">
                        <input type="hidden" name="totalAmt" id="totalAmt">
                        <p class="bill-ord-amt text-right" id="payable" style="    margin-bottom: 7px;margin-right: 15px;"></p>
                    </div>
                </div>

                <div style="border-bottom: 1px solid;margin-bottom:5px;color: #fff;"></div>

                <div class="row" style="margin: 0px;">
                    <div class="col-12 text-center" width="100%;">
                        <?php 
                            $width = "49%";
                        if($this->session->userdata('BillMergeOpt') > 0 && ($EType == 5)){ 
                            $width = "33%";
                        }
                         ?>

                        <a href="<?= base_url('customer'); ?>" class="btn btn-sm backbtn" style="width:<?= $width; ?>"><?php echo  $this->lang->line('menu'); ?></a>
                         <?php 
                         if($mergeCount <= 1){
                         if($this->session->userdata('BillMergeOpt') > 0 && ($EType == 5)){ ?>
                        <a href="<?= base_url('customer/merge_order/'.$MergeNo); ?>" class="btn orderbtn btn-sm" style="width:<?= $width; ?>"><?><?php echo  $this->lang->line('mergeorder'); ?></a>
                        <?php } } ?>
                        <button class="btn btn-sm paybtn" onclick="payNow()" style="width:<?= $width; ?>"><?php echo  $this->lang->line('payNow'); ?></button>
                        
                        <?php if($this->session->flashdata('error')): ?>
                            <div class="">
                                <span class="text-danger">
                                    <?= $this->session->flashdata('error') ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div id="myOverlay"></div>

            <div id="loadingGIF">
                <p id="total-amount_loader" style="text-align: center;position: absolute;color: white;margin-top: -33px;/* width: 159%; */font-size: 15px;font-weight: 900;"> </p>
                <img src="<?= base_url();?>assets/img/loading.gif" style="height: 100px; width: 100px;">
                <p style="text-align: center; position: absolute; color: white">Please Pay To Cashier</p>
            </div>    
        </div>
    </section>

    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
    
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

<script>

const socket = new WebSocket('ws://localhost:8080');
socket.onopen = function(event) {
    // console.log("Connected to WebSocket server");
};

    window.onload = function() {
        // document.getElementById("tips").focus();
      }
      var EType = "<?php echo $EType; ?>";
      if(EType == 5){
        kotPrint();
        function kotPrint(){
            $.post('<?= base_url('customer/kot_print_data') ?>',function(res){
                if(res.status == 'success'){
                    var data = res.response;
                    var str = ``;
                    data.forEach(item => {
                        str = `${item.MCNo}_${item.MergeNo}_${item.FKOTNo}_${item.KOTNo}`;
                        socket.send(str);
                    });
                }else{
                  alert(res.response); 
                }
            });
          }
      }

    var regular_discount = 0;
    var grand_total = 0;

    function getBillAmount() {
        $.ajax({
            url: "<?= base_url('customer/bill_ajax'); ?>",
            type: 'POST',
            dataType: 'json',
            data: {
                getBillAmount: 1,
            },
            success: response => {
                if(response.status == 'success'){
                    var template = ``;
                    var sub_total = 0;
                    var html = ``;
                    var html_body = ``;
                    var initil_value = response.kitcheData[0]['TaxType'];
                    var del_charge = response.kitcheData[0]['DelCharge'];
                    var pck_charge = response.kitcheData[0]['TotPckCharge'];
                    var ServChrg = response.kitcheData[0]['ServChrg'];
                    var discountDT = response.discountDT;
                    
                    var disc = parseFloat(response.kitchen_main_data['BillDiscAmt']) + parseFloat(response.kitcheData[0]['TotItemDisc']) + parseFloat(response.kitcheData[0]['RtngDiscAmt']);
                    
                    var hr_line = `<div style="border-bottom: 1px solid;margin-top: 5px; margin-bottom: 5px;color: #fff;"></div>`;

                    var itemAmount = 0;

                    response.kitcheData.forEach(item => {
                        
                        var ta = '';
                        if(item.TA == 1){
                         ta = '[TA]';
                        }

                        if(item.TA == 2){
                         ta = '[Charity]';
                        }

                        var CustItemDesc = '';
                        if(item.CustItemDesc != 'Std' && item.CustItemDesc != ''){
                            CustItemDesc = item.CustItemDesc;
                        }
                        
                        var portionss = (item.Portions != 'Std')?'('+item.Portions+')':'';
                        if(initil_value == item.TaxType){
                            html += `<tr>`;
                            if(item.Itm_Portion > 4){
                                html += `<td>${item.ItemNm} ${CustItemDesc} ${portionss} ${ta} </td>`;
                            }else{
                                html += `<td>${item.ItemNm} ${CustItemDesc} ${ta} </td>`;
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
                            html += `<td>${item.ItemNm} ${item.CustItemDesc} ${portionss} ${ta}</td>`;
                            html += `<td class="text-center">${convertToUnicodeNo(item.Qty)}</td>`;
                            html += `<td class="text-center">${convertToUnicodeNo(item.OrigRate)}</td>`;
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
                    for (let index = 0; index < response.TaxData[initil_value].length; index++) {
                        const element = response.TaxData[initil_value][index];
                        
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

                            sub_total_temp = sub_total;

                    }
                    grand_total = grand_total + sub_total;
                    html += `<tr style="border-top: 1px solid white;border-bottom: 3px solid white;">`;
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
                        html_body +=`   <th><?= $this->lang->line('serviceCharge'); ?> @ `+convertToUnicodeNo(ServChrg)+` %</th>`;
                        html_body +=`   <td class="text-right">`+convertToUnicodeNo(serv.toFixed(2))+`</td>`;
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
                        html_body +=`   <th class="text-right" style="font-style: italic" id="totalDiscount">`+convertToUnicodeNo(disc.toFixed(2))+`</th>`;
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

                    // var itemGrossAmt = (grand_total - serv - pck_charge).toFixed(2);
                    var itemGrossAmt = (grand_total).toFixed(2);

                    html_body +=`<div class="main-ammount">`;
                    html_body +=`<table style="width:100%;">`;
                    html_body +=`<tr class="">`;
                    html_body +=`   <th><?= $this->lang->line('grandTotal'); ?></th>`;
                    html_body +=`   <td class="text-right"><strong>`+convertToUnicodeNo(grand_total)+`</strong></td>`;
                    html_body +=`</tr>`;
                    html_body +=`</table>`;
                    html_body +=`</div>`;

                    var calc = 0;
                    if(discountDT.length != ''){

                       calc = (grand_total * discountDT.pcent) / 100;
                       regular_discount = calc.toFixed(2);
                        html_body +=`<div class="main-ammount">`;
                        html_body +=`<table style="width:100%;">`;
                        html_body +=`<tr class="">`;
                        html_body +=`   <th>${discountDT.name} Discount @ ${discountDT.pcent} % </th>`;
                        html_body +=`   <td class="text-right"><strong>${calc.toFixed(2)}</strong><input type="hidden" id="regular_discount" value="${calc.toFixed(2)}" /></td>`;
                        html_body +=`</tr>`;
                        html_body +=`</table>`;
                        html_body +=`</div>`;

                        grand_total  = (grand_total - calc).toFixed(2);
                    }

                    var tt = '<?php echo $Tips?>';
                    if(tt == 1){
                        html_body +=`<div class="main-ammount">`;
                        html_body +=`<table style="width:100%;">`;
                        html_body +=`<tr class="<?= ($Tips == 0 ? 'hideDiv' : ''); ?>">`;
                        html_body +=`   <th><?= $this->lang->line('tips'); ?></th>`;
                        html_body +=`   <td><input id="tips" type="text" class="" value="0" onchange="change_tip()"></td>`;
                        html_body +=`</tr>`;
                        html_body +=`</table>`;
                        html_body +=`</div>`;
                    }

                    $('.bill_box').html(html_body);
                    grand_total = Math.round(grand_total);
                    $("#payable").text(convertToUnicodeNo(grand_total));
                    $("#payableAmt").val(grand_total);
                    // $("#totalAmt").val(itemGrossAmt);
                    $("#totalAmt").val(itemAmount);
                    // getDiscountOffer();
                }else{
                    alert('Nothing Pending!! ');
                    window.location = "<?php echo base_url('customer/cart'); ?>";
                }

            },
            error: (xhr, status, error) => {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }

    function getDiscountOffer(){

        var payable = parseFloat($('#payableAmt').val());

        $.post('<?= base_url('customer/getDiscounts') ?>',function(res){
            if(res.status == 'success'){
                var data = res.response;
                var calc = payable - (payable * data.pcent) / 100;
                alert(calc.toFixed(2));
            }else{
              alert(res.response); 
            }
        });        
    }    

    $("#tips").focusout(function() {
        
        var serveCharge = $("#serve-charge").val();
        var orderAmount = $("#order-amount").val();
      
        var tips = $(this).val();
        var string = $("#total-amount_loader").text();
        var res = string.split(":");
        var totalTemp = parseInt(tips) + parseFloat(res[1]) + (parseInt("<?= $ServChrg; ?>") * parseInt(orderAmount) / 100);
        $("#payable").val(totalTemp);
        $("#total-amount_loader").text('Cash : ' + totalTemp);
      
    });

    function change_tip(){
        var tips = $("#tips").val();
        // tips = convertToUnicodeNo(tips);
        var total =parseFloat( grand_total ) + parseInt(tips);

        $("#payable").text(convertToUnicodeNo(total));
        $("#payableAmt").val(total);
        $("#tips").val(convertToUnicodeNo(tips));
    }

    $(document).ready(function() {
        getBillAmount();

    });


    function payNow(){
        var payable = parseFloat($('#payableAmt').val());
        var tip = $('#tips').val();
      
        payable = parseFloat(payable) + parseFloat(regular_discount);
        
        var itemGrossAmt = $('#totalAmt').val();
        tip = convertDigitToEnglish(tip);

        $.post('<?= base_url('customer/checkout_pay') ?>',{payable:payable,itemGrossAmt:itemGrossAmt,tip:tip},function(res){
            if(res.status == 'success'){
              var BillId = res.response.BillId;  
              var MCNo = res.response.MCNo;  
              window.location = '<?= base_url();?>customer/pay/'+BillId+'/'+MCNo;     
            }else{
              alert(res.response); 
            }
        });
    }

</script>

</html>