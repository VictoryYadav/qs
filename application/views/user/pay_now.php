<?php $this->load->view('layouts/customer/head'); 
$EID = $this->session->userdata('EID');
$folder = 'e'.$EID; 
?>
<link href="<?= base_url() ?>assets_admin/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<style>
body{
    font-size: 13px;
}

</style>

</head>

<body>

    <!-- Header Section Begin -->
    <section class="header-section">
        <div class="container p-2">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-4">
                    <ul class="list-inline product-meta">
                        <li class="list-inline-item">
                            <a href="#">
                                <img src="<?= base_url() ?>theme/images/Eat-Out-Icon.png" alt="" style="width: 30px;height: 28px;">
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-8 col-sm-8 col-8 text-right">
                    <ul class="list-inline product-meta">

                        <li class="list-inline-item">
                            <img src="<?= base_url('uploads/'.$folder.'/'.$EID.'_logo.jpg') ?>" width="auto" height="28px;">
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- Header Section End -->

    <section class="common-section p-2 dashboard-container">
        <?php if($this->session->userdata('ratingShow') == 0 ){ ?> 
        <div class="container" id="mobileSection">
            <form method="post" id="loginForm">
                <div class="row">
                    <div class="col-md-6 mx-auto">

                        <div class="form-group">
                            <select name="countryCd" id="countryCd" class="form-control form-control-sm select2 custom-select" required="">
                                <option value="">Select Country</option>
                                <?php 
                            foreach ($country as $key) { ?>
                                <option value="<?= $key['phone_code']; ?>" ><?= $key['country_name']; ?></option>
                            <?php } ?>  
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="number" name="mobile" class="form-control" placeholder="Enter Mobile" required="" autocomplete="off" minlength="10" maxlength="10" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                            <small id="loginMsg" class="text-danger" style="font-size: 10px;"></small>
                        </div>

                        <input type="submit" class="btn btn-sm btn-success" value="Submit">
                    </div>
                    
                </div>
            </form>

            <form method="post" id="otpForm" style="display: none;">
                <div class="row">
                    <div class="col-md-6 mx-auto">
                        <div class="form-group">
                            <input type="number" name="otp" class="form-control" placeholder="Enter OTP" autocomplete="off" required="" id="otp">
                            <span class="text-danger" id="errorMsg" style="font-size: 9px;"></span>
                        </div>
                        <input type="submit" class="btn btn-sm btn-success" value="Verify OTP">
                        <button class="btn btn-sm btn-warning" type="button" onclick="resendOTP()">Resend OTP</button>
                    </div>
                </div>
            </form>
        </div>
        <?php }else { ?>
        <div class="container" id="showBlock">

            <div class="row">
                <div class="col-md-2 col-6">
                    <label for=""><?php echo  $this->lang->line('payable'); ?>: </label>
                    <b id="payable"><?= round($payable); ?></b>
                </div>
                
                <?php if($this->session->userdata('MultiPayment') > 0){ ?>
                <div class="col-md-2 col-6">
                    <button class="btn btn btn-sm btn-success" id="addrow"><i class="fa fa-plus"></i></button>
                </div>
            <?php } ?>
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
                                        <button class="btn btn-sm btn-success" id="go" onclick="goPay(0)" disabled="true"><?php echo  $this->lang->line('click'); ?></button>
                                    </td>
                                    <td>
                                        <span id="payStatus"><i class="fa fa-check" style="color:green;"></i></span>
                                    </td>
                                    <td>
                                        <!-- <button class="btn btn btn-sm btn-danger deleteRow"><i class="fa fa-trash" id="delBtn1"></i></button> -->
                                    </td>
                                </tr>
                            <?php  } ?>
                                    <input type="hidden" id="sum" value="<?= round($sum); ?>">
                                <?php } else{ ?>
                                <tr>
                                    <td>
                                        <input type="number" placeholder="Amount" class="form-control" required name="amount" id="amount1" value="<?= round($payable); ?>" <?php if($this->session->userdata('MultiPayment') == 0){ echo 'readonly'; } ?>>
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
                                        <button class="btn btn-sm btn-success btngo" id="go1" onclick="goPay(1)"><?php echo  $this->lang->line('click'); ?></button>
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
                                    <?php echo  $this->lang->line('total'); ?>: <span id="grandtotal"><?= round($payable); ?></span>
                                </td>
                                <td colspan="2">
                                    <?php echo  $this->lang->line('balance'); ?>: <b><span id="balance">0</span></b>
                                </td>
                            </tr>
                        </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="container text-center" id="loadBlock" style="display: none;">
            <img src="<?= base_url('assets/images/loader.gif'); ?>" alt="Eat Out">
        </div>
        <?php } ?>
    </section>

    <!-- Js Plugins -->
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

<script src="<?= base_url() ?>assets_admin/libs/select2/js/select2.min.js"></script>
<script type="text/javascript">

    var BillId = '<?= $BillId; ?>';
    var MCNo = '<?= $MCNo; ?>';
    var totalPayable ='<?= round($payable); ?>';

    $(document).ready(function () {
        $('#countryCd').select2();
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
                            <button class="btn btn-sm btn-success btngo" id="go'+counter+'" onclick="goPay('+counter+')">Go</button>\
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
    var countRow = 0;
    $("table.order-list").find('input[name^="amount"]').each(function () {
        grandTotal += +$(this).val();
    });
    
    if(grandTotal > payable){
        Swal.fire({
          text: 'Total amount has exceeded the payable amount.',
          confirmButtonText: 'OK',
          confirmButtonColor: "red",
        });
    }
    $("#grandtotal").text(grandTotal);
    balance = parseFloat(payable) - parseFloat(grandTotal);
    $("#balance").text(balance);
}

function goPay(val){
     $('.btngo').attr("disabled", "disabled");
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
    // for cash, card, online
    if(mode < 5){
        $.post('<?= base_url('users/multi_payment') ?>',{amount:amount,mode:mode, BillId:BillId,MCNo:MCNo,payable:payable},function(res){
            if(res.status == 'success'){
              // alert(res.response);
              payNo = res.response;
              // for loading
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
        // var dd = '<?= base_url();?>'+payUrl+'&payable='+btoa(amount)+'&totAmt='+btoa(totAmt)+'&tips='+btoa(tips);
        // alert(dd);
        
        amount = convertDigitToEnglish(amount);
        var pageurl = 'user';
        window.location = '<?= base_url();?>'+payUrl+'&payable='+btoa(amount)+'&billId='+btoa(BillId)+'&MCNo='+btoa(MCNo)+'&pageurl='+btoa(pageurl);
    }

    // phoenpe = 34
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

        $.post('<?= base_url('customer/send_payment_otp') ?>',{billId:BillId,MCNo:MCNo,amount:amount,mode:mode},function(res){
            if(res.status == 'success'){
                $('#paymentBillId').val(BillId);
                $('#paymentMCNo').val(MCNo);
                $('#paymentAmount').val(amount);
                $('#paymentMode').val(mode);
                $('#otpModal').modal('show');
                // window.location = res.response;  
            }else{ 
              alert(res.response);  
            }
        });
    }


}

function checkStatus(billId,payNo, serialNo){
    $.post('<?= base_url('users/check_payment_status') ?>',{billId:billId,payNo:payNo},function(res){
        if(res.status == 'success'){
          // alert(res.response);
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
        $.post('<?= base_url('users/updateCustPayment') ?>',{BillId:BillId, MCNo:MCNo},function(res){
        
        });
        
     window.location = '<?= base_url();?>users/bill/'+BillId;   
    }

    // setInterval(function(){ goToBill(); }, 3000);
}

  var mobile = ''; 
    $('#loginForm').on('submit', function(e){
        e.preventDefault();
        var data = $(this).serializeArray();
        // console.log(data[0].value);
        mobile = data[0].value;

        $.post('<?= base_url('users/send_otp') ?>',data,function(res){
            if(res.status == 'success'){
              $('#otpForm').show();
              $('#loginForm').hide();
            }else{
              $('#loginMsg').html(res.response);
              $('#loginForm').show();
              $('#otpForm').hide();
            }
        });
    });

   $('#otpForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('users/verifyOTP') ?>',data,function(res){
            if(res.status == 'success'){
                $('#mobileSection').hide();
                $('#ratingSection').show();
                location.reload();
            }else{
              $('#errorMsg').html(res.response);
            }
        });
    });

   function resendOTP(){
    $('#otp').val('');
    var page = 'Resend Rating';
      $.post('<?= base_url('users/resendOTP') ?>',{mobile:mobile,page:page},function(res){
            if(res.status == 'success'){
                $('#errorMsg').html(res.response);
            }else{
              $('#errorMsg').html(res.response);
            }
      });
   }


</script>

</html>