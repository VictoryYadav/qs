<?php $this->load->view('layouts/customer/head'); ?>
<style>

</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <section class="common-section p-2 dashboard-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form action="">
                        <div class="table-responsive">
                          <table class="table table-striped">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>CellNo</th>
                                    <th>Order Amt</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php 
                                if(!empty($orders)){
                                    $total = 0;
                                    foreach ($orders as $ord) {
                                        $total = $total + $ord['OrdAmt'];
                                ?>
                                <tr onclick="getOrderDetails(<?= $ord['CNo']; ?>)">
                                    <td>
                                        <input type="checkbox" name="" checked="" onchange="calcValue(<?= $ord['CNo']; ?>,<?= $ord['OrdAmt']; ?>)" id="item_<?= $ord['CNo']; ?>">
                                    </td>
                                    <td>
                                        <?= $ord['CellNo']; ?> <small>(<?= $ord['FName'].' '.$ord['LName']; ?>)</small>
                                    </td>
                                    <td>
                                        <?= $ord['OrdAmt']; ?>
                                    </td>
                                </tr>
                            <?php } } ?>
                            <tr>
                               <td></td> <td>Grand Total</td><td id="grandTotal" style="font-weight: bold;"><?= $total; ?></td>
                            </tr>
                            </tbody>

                          </table>
                        </div>
                    </form>

                </div>
            </div>
            <!-- checkout -->
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
                        <input type="text" name="totalAmt" id="totalAmt">
                        <p class="bill-ord-amt text-right" id="payable" style="    margin-bottom: 7px;"></p>
                    </div>
                </div>

                <div style="border-bottom: 1px solid;margin-bottom:5px;color: #fff;"></div>

                <div class="row" style="margin: 0px;">
                    <div class="col-6 text-right">
                        
                    </div>
                    
                   
                        <?php if ($Cash == 1) : ?>
                            
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
                    
                </div>
            </div>

            <div id="myOverlay"></div>

            <div id="loadingGIF">
                <p id="total-amount_loader" style="text-align: center;position: absolute;color: white;margin-top: -33px;/* width: 159%; */font-size: 15px;font-weight: 900;"> </p>
                <img src="<?= base_url();?>assets/img/loading.gif" style="height: 100px; width: 100px;">
                <p style="text-align: center; position: absolute; color: white">Please Pay To Cashier</p>
            </div> 
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
                <div class="modal-header">
                    <p class="modal-title offers-txt">Offers</p>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa fa-times text-danger" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">
                      <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody id="orderBody">
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
   $(document).ready(function() {
        merge_checkout();
    });

   function merge_checkout() {
    var MergeNo = '<?= $MergeNo; ?>';
        $.ajax({
            url: "<?= base_url('customer/merge_checkout'); ?>",
            type: 'POST',
            dataType: 'json',
            data: {
                MergeNo: MergeNo,
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
                
                var disc = parseInt(response.kitcheData[0]['totBillDiscAmt']) + parseInt(response.kitcheData[0]['TotItemDisc']) + parseFloat(response.kitcheData[0]['RtngDiscAmt']);
                
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
                    html_body +=`   <th>Service Charge @ `+ServChrg+` %</th>`;
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

                var itemGrossAmt = (grand_total - serv).toFixed(2);

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
                $("#totalAmt").val(itemGrossAmt);

            },
            error: (xhr, status, error) => {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }

   function getOrderDetails(CNo){
    console.log('ff '+CNo);
        $.post('<?= base_url('customer/get_merge_order') ?>',{CNo:CNo},function(res){
            if(res.status == 'success'){
              // alert(res.response);
              var data = res.response;
              console.log(data);
              var temp = '';
              var total = 0;
              for (var i = 0; i < data.length; i++) {
                total = parseInt(total) + parseInt(data[i].Qty * data[i].ItmRate);
                  temp += '<tr><td>'+data[i].ItemNm+'</td><td>'+data[i].Qty+'</td><td>'+(data[i].Qty * data[i].ItmRate)+'</td></tr>';
              }
              temp +='<tr><td></td><td>Total</td><td><b>'+total+'</b></td></tr>';
              $('#orderBody').html(temp);
              $('#orderDetails').modal('show');
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
   }

</script>

</html>