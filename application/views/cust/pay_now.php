<?php $this->load->view('layouts/customer/head'); ?>
<style>
body{
    font-size: 13px;
}
</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <section class="common-section p-2 dashboard-container">
        <div class="container">

            <div class="row">
                <div class="col-md-2 col-6">
                    <label for="">Payble: </label>
                    <b id="payable"><?= round($payable); ?></b>
                </div>
                <!-- <div class="col-md-2">
                    <label for="">Gross Amount : </label>
                    <b id="grossAmt">0</b>
                </div>

                <div class="col-md-2">
                    <label for="">Tip Amount : </label>
                    <b id="tipAmt">0</b>
                </div> -->
                
                <div class="col-md-2 col-6">
                    <button class="btn btn btn-sm btn-success" id="addrow"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            
            <div class="row mt-1">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table order-list" id="splitTable">
                            <thead>
                                <tr>
                                    <th style="width: 150px;">Amount</th>
                                    <th>Pymt.Mode</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="addBody">
                                <?php
                                if(!empty($splitBills)){
                                    $count = 0;
                                    $sum = 0;
                                    foreach ($splitBills as $bill) {
                                        $count++;
                                        if($bill['Stat'] == 1){
                                                $sum = $sum + $bill['PaidAmt'];
                                        }
                                 ?>
                                <tr>
                                    <td>
                                        <input type="number" class="form-control" name="amount" id="amount" readonly="" value="<?= $bill['PaidAmt']; ?>">
                                    </td>
                                    <td>
                                        <select name="mode" id="mode" class="form-control" disabled="">
                                            <option value="">Choose Mode</option>
                                            <?php
                                            foreach ($modes as $key) {
                                            ?>
                                            <option value="<?= $key['PymtMode'];?>" <?php if($key['PymtMode'] == $bill['PaymtMode']){ echo 'selected'; } ?>><?= $key['Name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    
                                    <td>
                                        <button class="btn btn-sm btn-success" id="go" onclick="goPay(0)" disabled="true">Pay</button>
                                    </td>
                                    <td>
                                        <span id="payStatus"><i class="fa fa-check" style="color:green;"></i></span>
                                    </td>
                                    <td>
                                        <!-- <button class="btn btn btn-sm btn-danger deleteRow"><i class="fa fa-trash" id="delBtn1"></i></button> -->
                                    </td>
                                </tr>
                            <?php  } ?>
                                    <input type="hidden" id="sum" value="<?= $sum; ?>">
                                <?php } else{ ?>
                                <tr>
                                    <td>
                                        <input type="number" placeholder="Amount" class="form-control" required name="amount" id="amount1" value="<?= round($payable); ?>">
                                    </td>
                                    <td>
                                        <select name="mode" id="mode1" class="form-control" required>
                                            <option value="">Choose Mode</option>
                                            <?php
                                            foreach ($modes as $key) {
                                            ?>
                                            <option value="<?= $key['PymtMode'];?>" data-mode="<?= $key['CodePage1'];?>"><?= $key['Name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    
                                    <td>
                                        <button class="btn btn-sm btn-success" id="go1" onclick="goPay(1)">Go</button>
                                    </td>
                                    <td>
                                        <span id="payStatus1"><i class="fa fa-spinner" style="color:orange;"></i></span>
                                    </td>
                                    <td>
                                        <button class="btn btn btn-sm btn-danger deleteRow"><i class="fa fa-trash" id="delBtn1"></i></button>
                                    </td>
                                </tr>
                            <?php  } ?>
                            </tbody>

                            <tfoot>

                            <tr>
                                <td colspan="1">
                                    Total: <span id="grandtotal"></span>
                                </td>
                                <td colspan="2">
                                    Balance: <b><span id="balance"></span></b>
                                </td>
                            </tr>
                        </tfoot>
                        </table>
                    </div>
                </div>
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

<script type="text/javascript">

    var BillId = '<?= $BillId; ?>';
    var totalPayable ='<?= round($payable); ?>';

    $(document).ready(function () {
        
        goToBill();

    var counter = 1;

    $("#addrow").on("click", function () {
        counter++;

        var newRow = '<tr>\
                        <td>\
                            <input type="number" placeholder="Amount" class="form-control" required name="amount'+counter+'" id="amount'+counter+'">\
                        </td>\
                        <td>\
                            <select name="mode'+counter+'" id="mode'+counter+'" class="form-control" required>\
                                <option value="">Choose Mode</option>\
                                <?php
                                    foreach ($modes as $key) {
                                    ?>
                                    <option value="<?= $key['PymtMode'];?>" data-mode="<?= $key['CodePage1'];?>"><?= $key['Name'];?></option>\
                                <?php } ?>
                            </select>\
                        </td>\
                        <td>\
                            <button class="btn btn-sm btn-success" id="go'+counter+'" onclick="goPay('+counter+')">Go</button>\
                        </td>\
                        <td>\
                            <span id="payStatus'+counter+'"><i class="fa fa-spinner" style="color:orange;"></i></span>\
                        </td>\
                        <td>\
                            <button class="btn btn btn-sm btn-danger deleteRow" id="delBtn'+counter+'"><i class="fa fa-trash"></i></button>\
                        </td>\
                    </tr>';

        $("table.order-list").append(newRow);
    });

    $("table.order-list").on("change", 'input[name^="amount"]', function (event) {
        calculateRow($(this).closest("tr"));
        calculateGrandTotal();
    });

    $("table.order-list").on("click", "button.deleteRow", function (event) {
        $(this).closest("tr").remove();
        calculateGrandTotal();
    });
});

function calculateRow(row) {
    var price = +row.find('input[name^="amount"]').val();
    row.find('input[name^="amount"]').val(price);
}

function calculateGrandTotal() {
    var payable = $('#payable').text();
    var balance = 0;
    var grandTotal = 0;
    $("table.order-list").find('input[name^="amount"]').each(function () {
        grandTotal += +$(this).val();
    });
    $("#grandtotal").text(grandTotal);
    balance = parseFloat(payable) - parseFloat(grandTotal);
    $("#balance").text(balance);
}

function goPay(val){
    var amount = $('#amount'+val).val();
    var mode = $('#mode'+val).val();

    var element = $('#mode'+val).find('option:selected'); 
    var payUrl  = element.attr("data-mode"); 

    var BillId = '<?= $BillId; ?>';
    var MCNo = '<?= $MCNo; ?>';
    var payable = $('#payable').text();
    console.log('val ='+val+' ,am = '+amount+' ,mode '+mode+' ,pble '+payable);

    if(amount < 0 || amount == ''){
        alert('Please enter amount');
        return false;
    }

    if(mode < 0 || mode == ''){
        alert('Please select mode');
        return false;
    }

    var payNo = 0;

    console.log('call to ajax');
    // for cash
    if(mode == 1){
        $.post('<?= base_url('customer/multi_payment') ?>',{amount:amount,mode:mode, BillId:BillId,MCNo:MCNo,payable:payable},function(res){
            if(res.status == 'success'){
              // alert(res.response);
              payNo = res.response;

              setInterval(function() {
                    checkStatus(BillId,payNo, val);
                }, 20000);

            }else{
              alert(res.response);
            }
        });
    }

    if(mode == 5){
        var totAmt = 0;
        var tips = 0;
        // var dd = '<?= base_url();?>'+payUrl+'&payable='+btoa(amount)+'&totAmt='+btoa(totAmt)+'&tips='+btoa(tips);
        // alert(dd);
        window.location = '<?= base_url();?>'+payUrl+'&payable='+btoa(amount)+'&totAmt='+btoa(totAmt)+'&tips='+btoa(tips)+'&billId='+btoa(BillId);
    }

    // phoenpe = 46
    if(mode == 46){

        $.post('<?= base_url('phonepe/pay') ?>',{billId:BillId,MCNo:MCNo,amount:amount,mode:mode},function(res){
            if(res.status == 'success'){
                window.location = res.response;  
            }else{ 
              alert(res.response);  
            }
        });
    }
}

function checkStatus(billId,payNo, serialNo){
    $.post('<?= base_url('customer/check_payment_status') ?>',{billId:billId,payNo:payNo},function(res){
        if(res.status == 'success'){
          // alert(res.response);
          $('#payStatus'+serialNo).html('<i class="fa fa-check" style="color:green;"></i>');    
          $('#go'+serialNo).attr("disabled",true);  
          $('#delBtn'+serialNo).attr("disabled",true); 
          location.reload();     
        }else{
          $('#payStatus'+serialNo).html('<i class="fa fa-spinner" style="color:orange;">'); 
          // $('#payStatus'+serialNo).html(res.response);  
        }
    });
}

// goto bill page
function goToBill(){
    console.log('hi');
    var BillId = '<?= $BillId; ?>';
    var payable = $('#payable').text();
    var total = $('#sum').val();

    if(payable == total){
     window.location = '<?= base_url();?>customer/bill/'+BillId;   
    }

    // setInterval(function(){ goToBill(); }, 3000);
}

</script>

</html>