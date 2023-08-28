<?php $this->load->view('layouts/customer/head'); ?>
<style>

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
                                <th>CellNo</th>
                                <th>Order Amt</th>
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

                            <tr onclick="getOrderDetails(<?= $ord['CNo']; ?>)">
                                <input type="hidden" name="mobile[]" value="<?= $ord['CellNo']; ?>">
                                <td>
                                    <input type="hidden" name="CNo[]" value="<?= $ord['CNo']; ?>">
                                     <input type="checkbox" name="chck_cno[]" checked="" onchange="calcValue(<?= $ord['CNo']; ?>,<?= $ord['OrdAmt']; ?>)" id="item_<?= $ord['CNo']; ?>" class="cnoClass" value="<?= $ord['CNo']; ?>">

                                </td>
                                <td>
                                    <?= $ord['CellNo']; ?> <small>(<?= $ord['FName'].' '.$ord['LName']; ?>)</small>
                                </td>
                                <td>
                                    <?= $ord['OrdAmt']; ?>
                                </td>
                            </tr>
                        <?php }
                        ?>
                        <input type="hidden" value="<?= implode(',', $cn_no); ?>" id="allCNo">
                        <?php }
                            $this->session->set_userdata('split_mobile', $split_mobile);
                         ?>
                        <tr>
                           <td></td> <td><b>Grand Total</b></td>
                           <td id="grandTotal" style="font-weight: bold;">
                            <input type="hidden" name="grossItemAmt" value="<?= $total; ?>">
                            <?= $total; ?>
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
                        <input type="hidden" id="payableAmt">
                        <input type="hidden" name="payable" id="payableAmount">
                        
                        <input type="hidden" name="totalAmt" id="totalAmt">
                        <p class="bill-ord-amt text-right" id="payable" style="    margin-bottom: 7px;margin-right: 15px;"></p>
                    </div>
                </div>

                <div style="border-bottom: 1px solid;margin-bottom:5px;color: #fff;"></div>

                <div class="row">
                    <div class="col-12 text-center">
                        
                        <button class="btn btn-sm btn-warning" type="submit" name="btnName" value="splitBill">
                        Split Bill
                        </button>
                         <button class="btn btn-sm btn-primary" type="submit" name="btnName" value="payNow">
                            Pay Now
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
                    var totSGST = 0;
                    for (let index = 0; index < response.TaxData[initil_value].length; index++) {
                        const element = response.TaxData[initil_value][index];
                        
                            if(element['Included'] != 0 ){
                                totSGST = totSGST + parseFloat(element['TaxPcent']);
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
                    html += `<input type="hidden" name="tot_sgst" value="`+totSGST+`" ><tr style="border-top: 1px solid white;border-bottom: 3px solid white;">`;
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
                        html_body +=`<th>Service Charge @ `+ServChrg+` %</th>`;
                        html_body +=`<td class="text-right">`+serv.toFixed(2)+`</td>`;
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
                        html_body +=`   <td><input type="hidden" name="MCNo" value="`+MCNo+`"><input id="tips" type="number" class="" value="0" onchange="change_tip()" name="tip"></td>`;
                        html_body +=`</tr>`;
                        html_body +=`</table>`;
                        html_body +=`</div>`;
                    }

                    $('.bill_box').html(html_body);
                    $("#payable").text(grand_total);
                    $("#payableAmt").val(grand_total);
                    $("#totalAmt").val(itemGrossAmt);
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

        var total =parseFloat( payableAmt ) + parseInt(tips);

        $("#payable").text(total);
        $("#payableAmount").val(total);
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
       CNo = itemId;
       merge_checkout(MergeNo, AllCNo, CNo);
   }

   function splitBill(){
        var mobile = $('#splitForm').serializeArray();
        var payable = $('#payableAmt').val();
        // console.log(mobile+' '+payable);
        $.post('<?= base_url('customer/splitBill') ?>',{mobile:mobile, payable:payable},function(res){
        }
        );
   }

</script>

</html>