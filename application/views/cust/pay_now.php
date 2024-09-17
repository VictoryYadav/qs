<?php $this->load->view('layouts/customer/head'); ?>
<style>
body{
    font-size: 13px;
}

.selectMode{
    width: 100px;
}

.item-value{
    width: 95px;
}

.btn-success{
    background: <?php echo $this->session->userdata('successBtn'); ?>;
    color: <?php echo $this->session->userdata('successBtnClr'); ?>;
}

</style>

</head>

<body>

    <?php $this->load->view('layouts/customer/top'); ?>

    <section class="common-section p-2 dashboard-container">
        <div class="container" id="showBlock">

            <div class="row">
                <div class="col-md-4 col-10">
                    <span><?php echo  $this->lang->line('bilNo'); ?> : <b><?= convertToUnicodeNumber($BillId); ?></b> &nbsp;<?php echo  $this->lang->line('billDate'); ?> : <b><?= date('d-M-Y'); ?></b> </span><span></span><?php echo  $this->lang->line('payable'); ?>: <b id="payable"><?= convertToUnicodeNumber(round($payable)); ?></b>
                    </span>
                    
                    <input type="hidden" id="payableAmt" value="<?= round($payable); ?>">
                    <input type="hidden" id="MCNo" value="<?= $MCNo; ?>">
                    <input type="hidden" id="BillId" value="<?= $BillId; ?>">
                </div>
                
                <?php if($this->session->userdata('MultiPayment') > 0){ ?>
                <div class="col-md-2 col-2">
                    <button class="btn btn btn-sm btn-success" id="addrow"><i class="fa fa-plus"></i></button>
                </div>
            <?php } ?>
            </div>
            
            <div class="row mt-1">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-sm order-list" id="splitTable">
                            <thead>
                                <tr>
                                    <th style="width: 150px;"><?= $this->lang->line('amount'); ?></th>
                                    <th><?= $this->lang->line('mode'); ?></th>
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
                                        <input type="number" class="form-control item-value" name="amount" id="amount" readonly="" value="<?= convertToUnicodeNumber($bill['PaidAmt']); ?>">
                                    </td>
                                    <td>
                                        <select name="mode" id="mode" class="form-control selectMode" disabled="">
                                            <option value=""><?= $this->lang->line('chooseMode'); ?></option>
                                            <?php
                                            foreach ($modes as $key) {
                                            ?>
                                            <option value="<?= $key['PymtMode'];?>" <?php if($key['PymtMode'] == $bill['PaymtMode']){ echo 'selected'; } ?>><?= $key['Name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    
                                    <td>
                                        <button class="btn btn-sm btn-success" id="go" onclick="goPay(0)" disabled="true"><?php echo  $this->lang->line('pay'); ?></button>
                                    </td>
                                    <td>
                                        <span id="payStatus"><i class="fa fa-check" style="color:green;"></i></span>
                                    </td>
                                    <td>
                                        
                                    </td>
                                </tr>
                            <?php  } ?>
                                    <input type="hidden" id="sum" value="<?= round($sum); ?>">
                                <?php } else{ ?>
                                <tr>
                                    <td>
                                        <input type="text" placeholder="Amount" class="form-control item-value" required name="amount" id="amount1" value="<?= convertToUnicodeNumber(round($payable)); ?>" <?php if($this->session->userdata('MultiPayment') == 0){ echo 'readonly'; } ?> onblur="changeValue(this)">
                                    </td>
                                    <td>
                                        <select name="mode" id="mode1" class="form-control selectMode" required onchange="changeMode(1)">
                                            <option value=""><?= $this->lang->line('chooseMode'); ?></option>
                                            <?php
                                            foreach ($modes as $key) {
                                            ?>
                                            <option value="<?= $key['PymtMode'];?>" data-mode="<?= $key['CodePage1'];?>"><?= $key['Name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    
                                    <td>
                                        <button class="btn btn-sm btn-success btngo" id="go1" onclick="goPay(1)"><?php echo  $this->lang->line('pay'); ?></button>
                                    </td>
                                    <td>
                                        <span id="payStatus1"><i class="fa fa-spinner" fstyle="color:orange;"></i></span>
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
                                    <?php echo  $this->lang->line('total'); ?>: <span id="grandtotal"><?= convertToUnicodeNumber(round($payable)); ?></span>
                                </td>
                                <td colspan="2">
                                    <?php echo  $this->lang->line('balance'); ?>: <b><span id="balance"><?= convertToUnicodeNumber(0); ?></span></b>
                                </td>
                            </tr>
                        </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-sm" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th><?= $this->lang->line('bilNo'); ?></th>
                                    <th><?= $this->lang->line('mobile'); ?></th>
                                    <th><?= $this->lang->line('link'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(!empty($billLinks)){
                                    $count = 0;
                                    foreach ($billLinks as $key ) {
                                        $count++;
                                ?>
                                <tr>
                                    <td><?= $key['billId']; ?></td>
                                    <td><?= $key['mobileNo']; ?></td>
                                    <td>
                                        <input type="hidden" id="textToCopy<?= $count; ?>" value="<?= $key['link']; ?>">
                                        <!-- <?= $key['link']; ?> -->
                                        <span style="background: #000;color:#fff;padding: 3px;font-size: 11px;border-radius: 50px;cursor: pointer;" id="copyButton<?= $count; ?>" class="copyButtons" onclick="copyLink(<?= $count; ?>)">Bill Link</span>
                                            
                                        </td>
                                </tr>
                            <?php } } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="container text-center" id="loadBlock" style="display: none;">
            <img src="<?= base_url('assets/images/loader.gif'); ?>" alt="Eat Out">
        </div>
    </section>

    <!-- payment OTP Modal -->
    <div class="modal" id="otpModal">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title"><?= $this->lang->line('OTP'); ?></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form method="post" id="paymentForm">
                <input type="hidden" name="paymentMode" id="paymentMode">
                <input type="hidden" name="billId" id="paymentBillId">
                <input type="hidden" name="paymentMCNo" id="paymentMCNo">
                <input type="hidden" name="billAmount" id="paymentAmount">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for=""><?= $this->lang->line('OTP'); ?></label>
                            <input type="number" name="otp" class="form-control form-control-sm" required="" placeholder="OTP">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-sm btn-success" value="Verify">
                            <button type="button" class="btn btn-sm btn-danger" onclick="resendOTP()"><?= $this->lang->line('resendOTP'); ?></button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <small class="text-danger" id="paymentSMS"></small>
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Loyalty Modal -->
    <div class="modal" id="loyaltyModal">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title"><?= $this->lang->line('loyalty'); ?></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                        <table class="table order-list" id="splitTable">
                            <thead>
                                <tr>
                                    <th><?= $this->lang->line('name'); ?></th>
                                    <th><?= $this->lang->line('earned'); ?> <?= $this->lang->line('points'); ?></th>
                                    <th><?= $this->lang->line('used'); ?> <?= $this->lang->line('points'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="loyaltyBody">
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
          </div>
        </div>
      </div>
    </div>

    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
    
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

<script type="text/javascript">

    var BillId = $('#BillId').val();
    var MCNo = $('#MCNo').val();
    var totalPayable ='<?= round($payable); ?>';

    $(document).ready(function () {
        calculateGrandTotal();
        goToBill();

    var counter = 1;

    $("#addrow").on("click", function () {
        var balance = calculateGrandTotal();
        counter++;

        var newRow = '<tr>\
                        <td>\
                            <input type="text" placeholder="Amount" class="form-control item-value" required name="amount'+counter+'" id="amount'+counter+'" onblur="changeValue(this)" value="'+convertToUnicodeNo(balance)+'">\
                        </td>\
                        <td>\
                            <select name="mode'+counter+'" id="mode'+counter+'" class="form-control selectMode" required>\
                                <option value=""><?= $this->lang->line('chooseMode'); ?></option>\
                                <?php
                                    foreach ($modes as $key) {
                                    ?>
                                    <option value="<?= $key['PymtMode'];?>" data-mode="<?= $key['CodePage1'];?>"><?= $key['Name'];?></option>\
                                <?php } ?>
                            </select>\
                        </td>\
                        <td>\
                            <button class="btn btn-sm btn-success btngo" id="go'+counter+'" onclick="goPay('+counter+')"><?php echo  $this->lang->line('pay'); ?></button>\
                        </td>\
                        <td>\
                            <span id="payStatus'+counter+'"><i class="fa fa-spinner" style="color:orange;"></i></span>\
                        </td>\
                        <td>\
                            <button class="btn btn btn-sm btn-danger deleteRow" id="delBtn'+counter+'"><i class="fa fa-trash"></i></button>\
                        </td>\
                    </tr>';

        $("table.order-list").append(newRow);
        calculateGrandTotal();
    });
});

function changeMode(serialNo){
    var pMode = $(`#mode${serialNo}`).val();
    var billAmount = $('#payableAmt').val();
    // loyalty
    if(pMode == 31 || pMode == 32){

        $.post('<?= base_url('customer/get_loyalty_points') ?>',{EatOutLoyalty : pMode},function(res){
            if(res.status == 'success'){
                if(res.response.length > 0){
                    var temp = ``;
                    var usedPointsAmt = 0;
                    var billPerc = (billAmount * res.response[0].billUsagePerc) / 100;
                    billPerc = Math.round(billPerc);
                    if(parseInt(billPerc) > parseInt(res.response[0].MaxPointsUsage)){
                        usedPointsAmt = res.response[0].MaxPointsUsage;
                    }else{
                        usedPointsAmt = billPerc;
                    }
                    var availPoints = parseInt(res.response[0].EarnedPoints) - parseInt(res.response[0].UsedPoints);
                    if(parseInt(availPoints) < parseInt(usedPointsAmt)){
                        usedPointsAmt = availPoints;
                    }

                    $(`#amount${serialNo}`).val(usedPointsAmt);
                    res.response.forEach((item, index) => {
                        temp += `<tr>
                                    <td>${item.Name}</td>
                                    <td>${item.EarnedPoints}</td>
                                    <td>${item.UsedPoints}</td>
                                </tr>`;
                    });  
                }else{
                    temp += `<tr>
                                <td colspan="3">No Loyalty Points Found!!</td>
                            </tr>`;
                }
                $('#loyaltyBody').html(temp);
                $('#loyaltyModal').modal('show');
            }else{ 
              alert(res.response);  
            }
        });
    }
}

function goPay(val){
     $('.btngo').attr("disabled", "disabled");
    var amount = $('#amount'+val).val();
    var mode = $('#mode'+val).val();

    var element = $('#mode'+val).find('option:selected'); 
    var payUrl  = element.attr("data-mode"); 
    var payable = $('#payableAmt').val();

    if(amount < 0 || amount == ''){
        alert('Please enter amount');
        return false;
    }

    if(mode < 0 || mode == ''){
        alert('Please select mode');
        return false;
    }

    var payNo = 0;

    // for cash
    if(mode == 1){
        $.post('<?= base_url('customer/multi_payment') ?>',{amount:amount,mode:mode, BillId:BillId,MCNo:MCNo,payable:payable},function(res){
            if(res.status == 'success'){
              
              payNo = res.response;
              
                $('#showBlock').hide();
                $('#loadBlock').show();
              setInterval(function() {
                    checkStatus(BillId,payNo, val);
                }, 20000);

            }else{
              alert(res.response);
            }
        });
    }

    // loyalty
    if(mode == 31 || mode == 32){

        $.post('<?= base_url('customer/loyalty_pay') ?>',{amount:amount,mode:mode, BillId:BillId,MCNo:MCNo,payable:payable},function(res){
            if(res.status == 'success'){
                location.reload();
            }else{ 
              alert(res.response);  
            }
        });
    }
    // razorpay
    if(mode == 35){
        var totAmt = 0;
        var tips = 0;

        var pageurl = 'customer';
        amount = convertDigitToEnglish(amount);
        window.location = '<?= base_url();?>'+payUrl+'&payable='+btoa(amount)+'&billId='+btoa(BillId)+'&MCNo='+btoa(MCNo)+'&pageurl='+btoa(pageurl);
    }
    // payu
    if(mode == 51){
        var totAmt = 0;
        var tips = 0;
        var pageurl = 'customer';
        amount = convertDigitToEnglish(amount);
        window.location = '<?= base_url();?>'+payUrl+'?payable='+btoa(amount)+'&billId='+btoa(BillId)+'&MCNo='+btoa(MCNo)+'&pageurl='+btoa(pageurl);
    }
    // phoenpe
    if(mode == 34){

        $.post('<?= base_url('phonepe/pay') ?>',{billId:BillId,MCNo:MCNo,amount:amount,mode:mode},function(res){
            if(res.status == 'success'){
                window.location = res.response;  
            }else{ 
              alert(res.response);  
            }
        });
    }
    // onAccount, RoomNo, MembershipNo, EmployeeId
    if(mode >=20 && mode <= 30){
        // for onaccount
        $.post('<?= base_url('customer/check_onaccount_cust') ?>',{billId:BillId,MCNo:MCNo,amount:amount,mode:mode},function(res){
            if(res.status == 'success'){
                $('#paymentBillId').val(BillId);
                $('#paymentMCNo').val(MCNo);
                $('#paymentAmount').val(amount);
                $('#paymentMode').val(mode);
                $('#otpModal').modal('show');
                
            }else{ 
              alert(res.response);  
            }
        });
    }
}

function resendOTP(){
    $.post('<?= base_url('customer/resend_payment_OTP') ?>',function(res){
        if(res.status == 'success'){
            $('#paymentSMS').html(res.response)
        }else{ 
          alert(res.response);  
        }
    });
}

$('#paymentForm').on('submit', function(e){
    e.preventDefault();
    var data = $(this).serializeArray();
    
    var BillId = data[1]['value'];
    
    $.post('<?= base_url('customer/settle_bill_without_payment') ?>', data, function(res){
        if(res.status == 'success'){
            window.location = '<?= base_url();?>customer/bill/'+BillId;   
            return false;
        }else{ 
          alert(res.response);  
        }
    });
})

function checkStatus(billId,payNo, serialNo){
    $.post('<?= base_url('customer/check_payment_status') ?>',{billId:billId,payNo:payNo},function(res){
        if(res.status == 'success'){
          
          $('#payStatus'+serialNo).html('<i class="fa fa-check" style="color:green;"></i>');    
          $('#go'+serialNo).attr("disabled",true);  
          $('#delBtn'+serialNo).attr("disabled",true); 
          $('#loadBlock').hide();
          $('#showBlock').show();
          location.reload();     
        }else{
          $('#payStatus'+serialNo).html('<i class="fa fa-spinner" style="color:orange;">'); 
          $('#showBlock').hide();
          $('#loadBlock').show();
          
        }
    });
}

// goto bill page
function goToBill(){
    var payable = $('#payableAmt').val();
    var total = $('#sum').val();

    if(payable == total){

        $.post('<?= base_url('customer/updateCustPayment') ?>',{BillId:BillId, MCNo:MCNo, billAmount : payable},function(res){
        
            window.location = '<?= base_url();?>customer/bill/'+BillId;   
            return false;
        });
        
    }
    // setInterval(function(){ goToBill(); }, 3000);
}

function calculateGrandTotal() {

    var payable = $('#payableAmt').val();
    var balance = 0;
    var grandTotal = 0;
    var countRow = 0;

    $(".item-value").each(function(index, el) {
        val = $(this).val();
        val = convertDigitToEnglish(val);
        grandTotal += parseInt(val);
        
    });
    
    if(grandTotal > payable){
        Swal.fire({
          text: 'Total amount has exceeded the payable amount.',
          confirmButtonText: 'OK',
          confirmButtonColor: "red",
        });
    }
    $("#grandtotal").text(convertToUnicodeNo(grandTotal));
    balance = parseFloat(payable) - parseFloat(grandTotal);
    $("#balance").text(convertToUnicodeNo(balance));
    
    $('#addrow').prop("disabled", false);
    if(balance < 1){
        $('#addrow').attr("disabled", "disabled");
    }
    return balance;
}

function changeValue(input) {
    var val = $(input).val();
    $(input).val(convertToUnicodeNo(val));
    calculateGrandTotal();
}

function copyLink(counter){
    const textToCopy = $(`#textToCopy${counter}`).val();
    copyTextToClipboard(textToCopy, counter);
}

function copyTextToClipboard(text, counter) {

    navigator.clipboard.writeText(text).then(() => {
        $(`#copyButton${counter}`).html('Copied');
    }).catch(error => {
        console.error('Unable to copy text:', error);
    });
}

// end copy text 
function copied(){

    $('.copyButtons').html('Bill Link');
}
setInterval(copied, 3000);

</script>

</html>