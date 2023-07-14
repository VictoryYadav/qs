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
        /*background-color: #0a88ff;*/
        /*background-color: <?php echo isset($body_bg)?$body_bg:"#000"?> !important;
        color: <?php echo isset($body_text)?$body_text:"#000"?> !important;*/
        /*padding-top: 65px;*/
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
        /*color: <?php echo isset($body_text)?$body_text:"#000"?> !important;*/
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
        /*color: <?php echo isset($body_text)?$body_text:"#000"?> !important;*/
        padding: 0 15px;
    }

    .bill-ord-amt {
        /*color: <?php echo isset($body_text)?$body_text:"#000"?> !important;*/
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
                <div class="main-ammount discount_and_other_charges_section" style="display:none">
                    <div style="border-bottom: 1px solid;margin-top: 5px;margin-bottom: 5px;color: #fff;"></div>
                    <table style="width:100%;" style="display:none">
                        <tr class="TotItemDisc_sec">
                            <th style="font-weight: normal;" id="cgst-h"> Total Discount </th>
                            <td class="text-right" id="TotItemDisc"></td>
                        </tr>
                        <tr class="TotPckCharge_sec" style="display:none">
                            <th style="font-weight: normal;" id="sgst-h"> Packing Charge </th>
                            <td class="text-right" id="TotPckCharge"></td>
                        </tr>
                        <tr class="DelCharge_sec" style="display:none">
                            <th style="font-weight: normal;" id="sgst-h"> Delivery Charge </th>
                            <td class="text-right" id="DelCharge"></td>
                        </tr>
                    </table>
                </div>

                <div style="border-bottom: 1px solid;margin-top: 5px;margin-bottom: 5px;color: #fff;">
                </div>

                <div class="row paybl" style="margin-left: 0px;margin-right: 0px;">
                    <div class="col-8">
                        <label class="bill-ord-amt" style="margin-bottom: 7px;">Payable</label>
                    </div>
                    <div class="col-4">
                        <input type="hidden" name="payable" id="payableAmt">
                        <p class="bill-ord-amt text-right" id="payable" style="    margin-bottom: 7px;"></p>
                    </div>
                </div>

                <div style="border-bottom: 1px solid;margin-bottom:5px;color: #fff;"></div>

                <div class="row" style="margin: 0px;">
                    <div class="col-6 text-right">
                        
                    </div>
                    
                    <?php if ($COrgId > 0 && $PymtOpt == 1) : ?>
                        <div class="col-12 text-center">
                            <button id="confirm" class="btn btn-primary pay-bill">Confirm</button>
                        </div>
                    <?php else : ?>
                        <?php if ($Cash == 1) : ?>
                            <?php if ($EType == 5) { ?>
                                <div class="col-12 text-center" style="width: 50%;">
                                    <a href="meargebill.php"><button  class="btn btn-primary btn-sm " style="background: #fd6b03;color: #FFF; font-weight: 600;border: 1px solid #fff;border-radius: 20px;margin-top: 20px;"> Merge Orders </button></a>
                                </div>
                            <?php } ?>
                            <div class="row remove-row-margin" style="width: 100%;">
                                
                            </div>
                            
                        <?php else : ?>
                            <div class="row remove-row-margin" style="width: 100%;">
                                <div class="col-12 text-center">
                                    <button id="pay" class="btn btn-primary pay-bill btn-sm">Pay Online</button>
                                </div>
                                <?php if ($EType == 5) { ?>
                                    <div class="col-12 text-center" style="width: 50%;">
                                        <a href="meargebill.php"><button class="btn btn-primary pay-bill btn-sm" style="background: #fd6b03;color: #FFF; font-weight: 600; "> Merge Bills </button></a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
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
    <!-- end footer section -->


    <!-- Js Plugins -->
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

<script>
    // var objDiv = $(".order-list");
    // var h = objDiv.get(0).scrollHeight;
    // objDiv.animate({
    //  scrollTop: h
    // });

    function getBillAmount() {

        console.log("getBillAmount");
        $.ajax({
            url: "<?= base_url('customer/bill_ajax'); ?>",
            type: 'POST',
            dataType: 'json',
            data: {
                getBillAmount: 1,
            },
            success: response => {
                var template = ``;
                var sub_total = 0;
                var html = ``;
                var html_body = ``;
                var grand_total = 0;
                var initil_value = response.kitcheData[0]['TaxType'];
                var del_charge = response.kitcheData[0]['DelCharge'];
                var pck_charge = response.kitcheData[0]['TotPckCharge'];
                var ServChrg = response.kitcheData[0]['ServChrg'];
                
                var disc = parseInt(response.kitchen_main_data['BillDiscAmt']) + parseInt(response.kitcheData[0]['TotItemDisc']) + parseFloat(response.kitcheData[0]['RtngDiscAmt']);
                
                var hr_line = `<div style="border-bottom: 1px solid;margin-top: 5px; margin-bottom: 5px;color: #fff;"></div>`;

                response.kitcheData.forEach(item => {
                    
                    if(initil_value == item.TaxType){
                        html += `<tr>`;
                        
                        if(item.Itm_Portions > 4){

                            html += `<td>${item.ItemNm} ( ${item.Portion} ) </td>`;

                        }else{

                            html += `<td>${item.ItemNm} </td>`;

                        }
                        
                        html += `<td class="text-center">${item.Qty}</td>`;
                        html += `<td class="text-center">${item.OrigRate}</td>`;
                        html += `<td class="text-right">${item.OrdAmt}</td>`;
                        html += `</tr>`;

                        sub_total = sub_total +  parseInt(item.OrdAmt);
                        initil_value = item.TaxType;

                    }else{

                        html += `<tr style="border-top: 1px solid white;">`;
                        html += `<td><b>Item Total :</b> </td>`;
                        html += `<td class="text-center"></td>`;
                        html += `<td class="text-center"></td>`;
                        html += `<td class="text-right"><b>${sub_total}</b></td>`;
                        html += `</tr>`;

                        var sub_total_temp = sub_total;
                        
                        for (let index = 0; index < response.TaxData[initil_value].length; index++) {
                            const element = response.TaxData[initil_value][index];
                            var total_percent = parseFloat(sub_total_temp) * (parseFloat(element['TaxPcent'])/100);
                                if(element['Included'] != 0 ){

                                    html += `<tr>`;
                                    html += `<td>${element['ShortName']} ${element['TaxPcent']} % </td>`;
                                    html += `<td class="text-center"></td>`;
                                    html += `<td class="text-center"></td>`;
                                    html += `<td class="text-right">${parseFloat(element['SubAmtTax']).toFixed(2)}</td>`;

                                    html += `</tr>`;
                                    
                                }

                                if(element['Included'] >= 5){

                                    sub_total = parseFloat(sub_total_temp) + parseFloat(element['SubAmtTax']);

                                }
                            
                        }

                        grand_total = grand_total + sub_total;

                        html += `<tr style="border-top: 1px solid white;border-bottom: 3px solid white;">`;
                        html += `<td><b>Sub Total :</b> </td>`;
                        html += `<td class="text-center"></td>`;
                        html += `<td class="text-center"></td>`;
                        html += `<td class="text-right"><b style="color: orange;">${sub_total.toFixed(2)}</b></td>`;
                        html += `</tr> <tr style="height: 25px;"></tr>`;

                        initil_value = item.TaxType;

                        sub_total = 0;

                        html += `<tr>`;
                        html += `<td>${item.ItemNm}</td>`;
                        html += `<td class="text-center">${item.Qty}</td>`;
                        html += `<td class="text-center">${item.ItmRate}</td>`;
                        html += `<td class="text-right">${item.OrdAmt}</td>`;
                        html += `</tr>`;

                        sub_total = sub_total +  parseInt(item.OrdAmt);
                    }
                });

                html += `<tr style="border-top: 1px solid white;">`;
                html += `<td><b>Item Total :</b> </td>`;
                html += `<td class="text-center"></td>`;
                html += `<td class="text-center"></td>`;
                html += `<td class="text-right"><b>${sub_total}</b></td>`;
                html += `</tr>`;

                var sub_total_temp = sub_total;
                for (let index = 0; index < response.TaxData[initil_value].length; index++) {
                    const element = response.TaxData[initil_value][index];
                    
                        if(element['Included'] != 0 ){

                            html += `<tr>`;
                            html += `<td>${element['ShortName']} ${element['TaxPcent']} % </td>`;
                            html += `<td class="text-center"></td>`;
                            html += `<td class="text-center"></td>`;
                            html += `<td class="text-right">${parseFloat(element['SubAmtTax']).toFixed(2)}</td>`;
                            html += `</tr>`;

                        }

                        if(element['Included'] >= 5){
                            sub_total = parseFloat(sub_total_temp) + parseFloat(element['SubAmtTax']);
                        }

                        sub_total_temp = sub_total;

                }
                grand_total = grand_total + sub_total;
                html += `<tr style="border-top: 1px solid white;border-bottom: 3px solid white;">`;
                        html += `<td><b>Sub Total :</b> </td>`;
                        html += `<td class="text-center"></td>`;
                        html += `<td class="text-center"></td>`;
                        html += `<td class="text-right"><b style="color: orange;">${sub_total.toFixed(2)}</b></td>`;
                        html += `</tr>`;
                
                sub_total = 0;

                html_body +=`<div class="order-list col-12">`;
                html_body +=`<table class="fixed_headers" style="width:100%">`;
                html_body +=`<thead>`;
                html_body +=`<tr>`;
                html_body +=`<th> Menu Item </th>`;
                html_body +=`<th class="text-center">Qty</th>`;
                html_body +=`<th class="text-center">Rate</th>`;
                html_body +=`<th class="text-right">Amt</th>`;
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
                    html_body +=`   <th>Service Charge</th>`;
                    html_body +=`   <td class="text-right">`+serv.toFixed(2)+`</td>`;
                    html_body +=`</tr>`;
                    html_body +=`</table>`;
                    html_body +=`</div>`;
                    grand_total = grand_total + serv;
                }
                
                if(disc > 0){
                    html_body +=`<div class="main-ammount">`;
                    html_body +=`<table style="width:100%;">`;
                    html_body +=`<tr>`;
                    html_body +=`   <th style="font-style: italic">Total Discount / Savings</th>`;
                    html_body +=`   <th class="text-right" style="font-style: italic">`+disc+`</th>`;
                    html_body +=`</tr>`;
                    html_body +=`</table>`;
                    html_body +=`</div>`;
                    grand_total = grand_total - disc;
                }

                if(del_charge > 0){
                    html_body +=`<div class="main-ammount">`;
                    html_body +=`<table style="width:100%;">`;
                    html_body +=`<tr>`;
                    html_body +=`   <th style="font-style: italic">Delivery Charge</th>`;
                    html_body +=`   <th class="text-right" style="font-style: italic">`+del_charge+`</th>`;
                    html_body +=`</tr>`;
                    html_body +=`</table>`;
                    html_body +=`</div>`;
                    grand_total = grand_total + parseInt(del_charge);
                }

                if(pck_charge > 0){
                    html_body +=`<div class="main-ammount">`;
                    html_body +=`<table style="width:100%;">`;
                    html_body +=`<tr>`;
                    html_body +=`   <th style="font-style: italic">Packing Charge</th>`;
                    html_body +=`   <th class="text-right" style="font-style: italic">`+pck_charge+`</th>`;
                    html_body +=`</tr>`;
                    html_body +=`</table>`;
                    html_body +=`</div>`;
                    grand_total = grand_total + parseInt(pck_charge);
                }
                var tt = '<?php echo $Tips?>';
                if(tt == 1){
                    html_body +=`<div class="main-ammount">`;
                    html_body +=`<table style="width:100%;">`;
                    html_body +=`<tr class="<?= ($Tips == 0 ? 'hideDiv' : ''); ?>">`;
                    html_body +=`   <th>Tips</th>`;
                    html_body +=`   <td><input id="tips" type="number" class="" value="0" onchange="change_tip()"></td>`;
                    html_body +=`</tr>`;
                    html_body +=`</table>`;
                    html_body +=`</div>`;
                }

                $('.bill_box').html(html_body);

                $("#payable").text(grand_total);
                $("#payableAmt").val(grand_total);

            },
            error: (xhr, status, error) => {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }
    

    function getTaxValue(initil_value,total){
        $.ajax({
            url: 'ajax/bill_cons_ajax.php',
            type: 'POST',
            dataType: 'json',
            data: {
                getTax: 1,
                tax_type:initil_value
            },
            success: response => {
                var html ='';
                response.TaxData.forEach(item => {

                        var total_percent = parseFloat(total) * (parseFloat(item.TaxPcent)/100);
                        console.log(total_percent.toFixed(2), total);
                    
                        html += `<tr>`;
                        html += `<td>${item.ShortName} ${item.TaxPcent}</td>`;
                        html += `<td class="text-center"></td>`;
                        html += `<td class="text-center"></td>`;
                        html += `<td class="text-right">${total_percent}</td>`;
                        html += `</tr>`;

                        return html;

                });
            }
        });
    }

    function getPaymentModes(){

        $.ajax({
            url: "<?= base_url('customer/bill_ajax'); ?>",
            type: 'POST',
            dataType: 'json',
            data: {
                get_payment_modes: 1
            },
            success: response => {
                var t ='';
                var n = 1;
                response.payment_modes.forEach(item => {
                    co = 6;
                    if(response.payment_modes.length % 2 == 0){
                        co = 6;
                    }else{
                        if(response.payment_modes.length == n){
                            co = 12;
                        }
                    }
                    if(item.Name == 'Cash'){
                        t+='<div class="col-'+co+' text-center"><button id="cash" class="btn btn-primary pay-bill btn-sm" onclick="cash_payment()">Pay Cash</button></div>';
                    }else{

                        t+='<div class="col-'+co+' pay_online_section text-center"><button id="pay'+item.Name+'" class="btn btn-primary pay-bill btn-sm" onclick="start_payment(\''+item.CodePage+'\')">'+item.Name+'</button></div>';
                    }
                    n++;
                        

                });
                $('.remove-row-margin').append(t);
            }
        });
    }
    getPaymentModes();
    function start_payment(p){
        var a = parseFloat($('#payable').html());
        var t = $('#tips').val();
        // alert(t);
        // alert(a);
        if(t === undefined){
            t = 0;
        }
        // alert(t);
        // alert(btoa(t));
        window.location = '<?= base_url();?>'+p+'&payable='+btoa(a)+'&tips='+btoa(t);
    }
    function checkCasherConfirm(billId) {
        $.ajax({
            url: "<?= base_url('customer/bill_ajax'); ?>",
            type: "post",
            data: {
                checkCasherConfirm: 1,
                billId: billId
            },
            dataType: "json",
            success: response => {
                console.log(response);
                if (response.status == 1) {
                    window.location = `bill_rcpt.php?billId=${billId}`;
                } else {
                    return true;
                }
            },
            error: (xhr, status, error) => {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }
$("#tips").focusout(function() {
            // console.log('ok');
            // alert("kkk");
            // var totalAmount = $("#total-amount").text();
            var serveCharge = $("#serve-charge").val();
            var orderAmount = $("#order-amount").val();
            // var payAble = parseFloat(totalAmount) + (parseInt(<?= $ServChrg; ?>) * parseInt(orderAmount) / 100);
            var tips = $(this).val();
            var string = $("#total-amount_loader").text();
            var res = string.split(":");
            var totalTemp = parseInt(tips) + parseFloat(res[1]) + (parseInt("<?= $ServChrg; ?>") * parseInt(orderAmount) / 100);
            // var newTotal = parseFloat(payAble) + parseInt(tips);
            console.log(totalTemp);
            $("#payable").val(totalTemp);
            $("#total-amount_loader").text('Cash : ' + totalTemp);
            // totalAmount = newTotal;
        });

        function change_tip(){
            var tips = $("#tips").val();
            var payableAmt  = $('#payableAmt').val();

            var total =parseFloat( payableAmt ) + parseInt(tips);

            $("#payable").text(total);
            // $("#payableAmt").val(total);
        }

    $(document).ready(function() {
        getBillAmount();


        // For Payment Order
        $("#pay").click(function(event) {
            var tips = $("#tips").val();

            $.ajax({
                url: "<?= base_url('customer/bill_ajax'); ?>",
                type: "post",
                data: {
                    pay: 1,
                    tips: tips
                },
                dataType: "json",
                success: response => {
                    console.log(response);
                    if (response.status == 100) {
                        alert(response.msg);
                        window.location.reload();
                    } else if (response.status == 1) {
                        window.location = response.location;
                    } else {
                        location.reload();
                    }
                },
                error: (xhr, status, error) => {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        });

        // For confirm Order
        $("#confirm").click(function(event) {
            var tips = $("#tips").val();

            $.ajax({
                url: "<?= base_url('customer/bill_ajax'); ?>",
                type: "post",
                data: {
                    confirm: 1,
                    tips: tips
                },
                dataType: "json",
                success: response => {
                    console.log(response);
                    if (response.status == 100) {
                        alert(response.msg);
                        window.location.reload();
                    } else if (response.status == 1) {
                        window.location = `bill_rcpt.php?billId=${response.billId}`;
                    } else {
                        location.reload();
                    }
                },
                error: (xhr, status, error) => {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        });

        // For confirm Order

    });
    function cash_payment() {
            var tips = $("#tips").val();
            var payableAmt = $("#payableAmt").val();
            // alert("Cash");
            $.ajax({
                url: "<?= base_url('customer/bill_ajax'); ?>",
                type: "post",
                data: {
                    cash: 1,
                    tips: tips,
                    payableAmt:payableAmt
                },
                dataType: "json",
                success: response => {
                    console.log(response);
                    if (response.status == 100) {
                        alert(response.msg);
                        window.location.reload();
                    } else if (response.status == 1) {
                        $('.bill-body').hide();
                        $('#myOverlay').show();
                        $('#loadingGIF').show();
                        setInterval(function() {
                            checkCasherConfirm(response.billId);
                        }, 20000);
                        // window.location = `bill_rcpt.php?billId=${response.billId}`;
                    } else {
                        alert(response.msg);
                        location.reload();
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

</html>