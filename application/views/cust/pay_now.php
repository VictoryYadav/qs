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
                <div class="col-md-2">
                    <label for="">Payble Amount : </label>
                    <b id="payable"><?= round($payable); ?></b>
                </div>
                <div class="col-md-2">
                    <label for="">Gross Amount : </label>
                    <b id="grossAmt"><?= round($grossItemAmt); ?></b>
                </div>

                <div class="col-md-2">
                    <label for="">Tip Amount : </label>
                    <b id="tipAmt"><?= round($tip); ?></b>
                </div>
                
                <div class="col-md-2">
                    <button class="btn btn btn-sm btn-success" id="addrow"><i class="fa fa-plus"></i> Add</button>
                </div>
            </div>
            
            <div class="row mt-1">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table order-list" id="splitTable">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Mode</th>
                                    <th>Action</th>
                                    <th>Result</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="addBody">
                                <?php
                                if(!empty($splitBills)){
                                    $count = 0;
                                    foreach ($splitBills as $bill) {
                                        $count++;
                                 ?>
                                <tr>
                                    <td>
                                        <input type="number" class="form-control" name="amount" id="amount" readonly="" value="<?= $bill['PaidAmt']; ?>">
                                    </td>
                                    <td>
                                        <select name="mode" id="mode" class="form-control" readonly>
                                            <option value="">Choose Mode</option>
                                            <?php
                                            foreach ($modes as $key) {
                                            ?>
                                            <option value="<?= $key['PymtMode'];?>"><?= $key['Name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    
                                    <td>
                                        <button class="btn btn-sm btn-success" id="go" onclick="goPay(0)" disabled="true">Go</button>
                                    </td>
                                    <td>
                                        <span id="payStatus">success</span>
                                    </td>
                                    <td>
                                        <!-- <button class="btn btn btn-sm btn-danger deleteRow"><i class="fa fa-trash" id="delBtn1"></i></button> -->
                                    </td>
                                </tr>
                            <?php  }
                                } else{ ?>
                                <tr>
                                    <td>
                                        <input type="number" placeholder="Amount" class="form-control" required name="amount" id="amount1">
                                    </td>
                                    <td>
                                        <select name="mode" id="mode1" class="form-control" required>
                                            <option value="">Choose Mode</option>
                                            <?php
                                            foreach ($modes as $key) {
                                            ?>
                                            <option value="<?= $key['PymtMode'];?>"><?= $key['Name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    
                                    <td>
                                        <button class="btn btn-sm btn-success" id="go1" onclick="goPay(1)">Go</button>
                                    </td>
                                    <td>
                                        <span id="payStatus1">pending</span>
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
                                    Grand Total: <span id="grandtotal"></span>
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

    $(document).ready(function () {
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
                                    <option value="<?= $key['PymtMode'];?>"><?= $key['Name'];?></option>\
                                <?php } ?>
                            </select>\
                        </td>\
                        <td>\
                            <button class="btn btn-sm btn-success" id="go'+counter+'" onclick="goPay('+counter+')">Go</button>\
                        </td>\
                        <td>\
                            <span id="payStatus'+counter+'">pending</span>\
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

function checkStatus(billId,payNo, serialNo){
    $.post('<?= base_url('customer/check_payment_status') ?>',{billId:billId,payNo:payNo},function(res){
        if(res.status == 'success'){
          // alert(res.response);
          $('#payStatus'+serialNo).html('success');    
          $('#go'+serialNo).attr("disabled",true);  
          $('#delBtn'+serialNo).attr("disabled",true);      
        }else{
          $('#payStatus'+serialNo).html(res.response);  
        }
    });
}

// p= 'razorpay/pay?checkout=automatic'
function start_payment(p){
    var a = $('#amount'+val).val();
    var mode = $('#mode'+val).val();
    var t = 0;
    var amt = $('#grossAmt').val();
    // alert(t);
    // alert(a);
    if(t === undefined){
        t = 0;
    }
    // alert(t);
    // alert(btoa(t));
    window.location = '<?= base_url();?>'+p+'&payable='+btoa(a)+'&totAmt='+btoa(amt)+'&tips='+btoa(t);
}

</script>

</html>