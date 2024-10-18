<?php $this->load->view('layouts/customer/head'); ?>
<style>
body{
    font-size: 12px;
}

.btn-success{
    background: <?php echo $this->session->userdata('successBtn'); ?>;
    color: <?php echo $this->session->userdata('successBtnClr'); ?>;
}

/*select2*/
.select2-container--default .select2-selection--single {
  background-color: #00000000;
  border: 1px solid #ced4da;
  border-radius: 2px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    /*color: #717070;*/
    line-height: 28px;
    font-size: 12px;
}
.select2-container .select2-selection--single {
  height: 29px;
}

</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <section class="common-section p-2 dashboard-container">
        <div class="container">
            <form method="post" id="billForm">
                <input type="hidden" name="payableAmount" value="<?= round($payable); ?>" >
                <input type="hidden" name="grossAmount" value="<?= round($grossItemAmt); ?>">
                <input type="hidden" name="tipAmount" value="<?= round($tip); ?>">
                <input type="hidden" name="MergeNo" value="<?= $MergeNo; ?>">
                <input type="hidden" name="MCNo" value="<?= $MCNo; ?>">
                <input type="hidden" name="tot_sgst" value="<?= round($tot_sgst); ?>">

                <div class="row">
                    <div class="col-md-2 col-6">
                        <label for=""><?= $this->lang->line('payable'); ?>: </label>
                        <b id="payable"><?= convertToUnicodeNumber(round($payable)); ?></b>
                    </div>
                    <div class="col-md-2 col-6">
                        <label for=""><?= $this->lang->line('orderAmount'); ?>: </label>
                        <b id="grossAmt"><?= convertToUnicodeNumber(round($grossItemAmt)); ?></b>
                    </div>

                    <div class="col-md-2 col-4">
                        <label for=""><?= $this->lang->line('tips'); ?>: </label>
                        <b id="tipAmt"><?= convertToUnicodeNumber(round($tip)); ?></b>
                    </div>
                    
                    <div class="col-md-2 col-8">
                        <button class="btn btn btn-sm btn-success" onclick="addRow()"><i class="fa fa-plus"></i></button>
                    </div>

                    <div class="col-md-2 col-4">
                        <label for=""><?= $this->lang->line('splitType'); ?> : </label>
                    </div>
                    <div class="col-md-2 col-8">
                        <select name="splitType" id="splitType" onchange="splitChange()" required="" class="form-control form-control-sm">
                            <option value=""><?= $this->lang->line('chooseSplitType'); ?></option>
                            <option value="1"><?= $this->lang->line('foodBarSeparate'); ?></option>
                            <option value="2"><?= $this->lang->line('equalSplit'); ?></option>
                            <option value="3"><?= $this->lang->line('manualPercent'); ?></option>
                            <option value="4"><?= $this->lang->line('manualAmount'); ?></option>
                        </select>
                    </div>
                </div>
                    
                <div class="table-responsive">
                    <table class="table table-sm" id="splitTable">
                        <thead>
                            <tr>
                                <th width="160px;"><?= $this->lang->line('country'); ?></th>
                                <th width="155px;"><?= $this->lang->line('mobile'); ?></th>
                                <th width="100px;"></th>
                                <th width="100px;">%</th>
                                <th width="100px;"><?= $this->lang->line('amount'); ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="addBody">
                            <?php 
                                $count = 0;
                                for ($i=0; $i < sizeof($mobile) ; $i++) {
                                    $count++;
                                    $cd = substr($mobile[$i], 0, -10);
                                    $mobile10 = substr($mobile[$i], -10);
                                ?>
                            <tr>
                                <td>
                                    <input type="text" value="<?= $cd; ?>" placeholder="Mobile" class="form-control" required name="CountryCd[]" readonly>
                                </td>
                                <td>
                                    <input type="text" value="<?= $mobile10; ?>" placeholder="Mobile" class="form-control" required name="mobile[]" readonly>
                                    <input type="hidden" value="<?= $cust_id[$i]; ?>" class="form-control" name="custid[]">
                                </td>
                                
                                <td>
                                    <input type="text" class="form-control grossAmtRow" name="totItemAmt[]" id="grossAmtRow_<?php echo $count; ?>" readonly>
                                </td>
                                <td>
                                    <input type="text" placeholder="Percent" class="form-control percentRow" id="percentRow_<?php echo $count; ?>" onchange="calcPerAmt(<?php echo $count; ?>)" name="percent[]">
                                </td>
                                <td>
                                    <input type="text" placeholder="Amount" class="form-control amountRow" id="amountRow_<?php echo $count; ?>" onchange="calcAmt(<?php echo $count; ?>)" required name="amount[]">
                                </td>
                                <td>
                                    <button class="btn btn btn-sm btn-danger removeRow"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('splitbill'); ?>">
            </form>
            
        </div>
    </section>

    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
    
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

<script type="text/javascript">

    var totalAmt = "<?= round($payable); ?>";
    var grossAmt = "<?= round($grossItemAmt); ?>";
    var tipAmt   = "<?= round($tip); ?>";

   var rowCount = $('#splitTable tr').length - 1;
   // add row
   function addRow(){
    rowCount++;
    var row = `<tr>
                    <td><select name="CountryCd[]" class="form-control CountryCd select2 custom-select" required="" id="CountryCd">
                        <option value=""><?= $this->lang->line('select'); ?></option>
                        <?php 
                        foreach ($country as $key) { ?>
                            <option value="<?= $key['phone_code']; ?>" <?php if($CountryCd == $key['phone_code']){ echo 'selected'; } ?>><?= $key['country_name']; ?></option>
                        <?php } ?>                   
                    </select></td>
                    <td><input type="text" placeholder="Mobile" class="form-control" required name="mobile[]"><input type="hidden" value="0" class="form-control" name="custid[]"></td>
                    <td>
                        <input type="text" class="form-control grossAmtRow" readonly="" name="totItemAmt[]" id="grossAmtRow_'+rowCount+'">
                    </td>
                    <td>
                        <input type="number" name="percent[]" placeholder="Percent" class="form-control percentRow" id="percentRow_'+rowCount+'" onchange="calcPerAmt('+rowCount+')">
                    </td>
                    <td>
                        <input type="number" name="amount[]" placeholder="Amount" class="form-control amountRow" id="amountRow_'+rowCount+'" onchange="calcAmt('+rowCount+')" required>
                    </td>
                    <td>
                        <button class="btn btn btn-sm btn-danger removeRow"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>`;
        $('#addBody').append(row); 
        $('#splitType').val(0);

        $('.percentRow').removeAttr("readonly");
        $('.amountRow').removeAttr("readonly");
        $('.percentRow').val('');
        $('.amountRow').val('');
        $('.grossAmtRow').val(0);
        $('.CountryCd').select2();
   }

   // remove row
   $("#addBody").on('click','.removeRow',function(){
    rowCount--;

        $(this).parent().parent().remove();
        $('#splitType').val(0);

        $('.percentRow').removeAttr("readonly");
        $('.amountRow').removeAttr("readonly");
        $('.percentRow').val('');
        $('.amountRow').val('');
        $('.grossAmtRow').val(0);
    });

   function splitChange(){

        var rowCount = $('#splitTable tr').length - 1;
        var val = $('#splitType').val();
        console.log(val+' ,row '+rowCount+', amt= '+totalAmt);
        if(val > 0){
            if(val == 1){
                    var CellNo = "<?php echo $this->session->userdata('CellNo'); ?>";
                    var CustId = "<?php echo $this->session->userdata('CustId'); ?>";
                    
                    var cd = CellNo.substring(0, 2);
                    var mobile10 = CellNo.substring(2,14);

                    var row = `<tr>
                                <td>
                                    <input type="text" value="${cd}" placeholder="Mobile" class="form-control" required name="CountryCd[]" readonly>
                                </td>
                                <td>
                                    <input type="text" value="${mobile10}" placeholder="Mobile" class="form-control" required name="mobile[]" readonly>
                                    <input type="hidden" value="${CustId}" class="form-control" name="custid[]">
                                </td>
                                
                                <td>
                                    <input type="text" class="form-control grossAmtRow" name="totItemAmt[]" id="grossAmtRow_1" readonly>
                                </td>
                                <td>
                                    <input type="text" placeholder="Percent" class="form-control percentRow" id="percentRow_1" onchange="calcPerAmt(1)" name="percent[]">
                                </td>
                                <td>
                                    <input type="text" placeholder="Amount" class="form-control amountRow" id="amountRow_1" onchange="calcAmt(1)" required name="amount[]">
                                </td>
                                <td>
                                    <button class="btn btn btn-sm btn-danger removeRow"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>`;
                    $('#addBody').html(row);

                    $('.percentRow').val(100);
                    $('.amountRow').val(totalAmt);
                    $('.grossAmtRow').val(grossAmt);
                
            }else if(val == 2){
                var per = 0;
                var amt = 0;
                per  = 100 / rowCount; 
                amt = totalAmt / rowCount;

                var grsAmt = 0;

                grsAmt = (parseInt(grossAmt) + parseInt(tipAmt)) / rowCount;

                $('.percentRow').attr("readonly", "");
                $('.amountRow').attr("readonly", "");

                $('.percentRow').val(convertToUnicodeNo(per.toFixed(2)));
                $('.amountRow').val(convertToUnicodeNo(amt.toFixed(2)));
                $('.grossAmtRow').val(convertToUnicodeNo(grsAmt.toFixed(2)));
            }else if(val == 3){
                $('.percentRow').removeAttr("readonly");
                $('.amountRow').attr("readonly", ""); 

                $('.grossAmtRow').val(0);
                $('.percentRow').val(0);
                $('.amountRow').val(0);
            }else if(val == 4){
                $('.amountRow').removeAttr("readonly");
                $('.percentRow').attr("readonly", "");

                $('.grossAmtRow').val(0);
                $('.percentRow').val(0);
                $('.amountRow').val(0);
            }else{
                $('.percentRow').removeAttr("readonly");
                $('.amountRow').removeAttr("readonly");
            }
        }else{
            alert('Choose Split Type');
        }
   }

   function calcPerAmt(rowCount){
    var val = $('#percentRow_'+rowCount).val();
    console.log(rowCount+' v '+val);

    itemVal = parseInt(grossAmt) + parseInt(tipAmt);
    var grossVal = (parseInt(itemVal) * parseInt(val)) / 100;
    $('#grossAmtRow_'+rowCount).val(convertToUnicodeNo(grossVal.toFixed(2)));
    // amountRow_1
    var amt = (parseInt(totalAmt) * parseInt(val)) / 100;
    $('#amountRow_'+rowCount).val(convertToUnicodeNo(amt.toFixed(2)));

    $('#percentRow_'+rowCount).val(convertToUnicodeNo(val));
   }

   function calcAmt(rowCount){
    var val = $('#amountRow_'+rowCount).val();
    console.log(rowCount+' v '+val);

    var per =  parseFloat(val) / parseInt(totalAmt) * 100;
    $('#percentRow_'+rowCount).val(convertToUnicodeNo(per.toFixed(2)));

    itemVal = parseInt(grossAmt) + parseInt(tipAmt);
    var grossVal = (parseInt(itemVal) * parseInt(per.toFixed(2))) / 100;
    $('#grossAmtRow_'+rowCount).val(convertToUnicodeNo(grossVal.toFixed(2)));

    $('#amountRow_'+rowCount).val(convertToUnicodeNo(val));
   }

   $('#billForm').on('submit', function(e){
        e.preventDefault();
        var data = $(this).serializeArray();
        var rowCount = $('#splitTable tr').length - 1;
        var val = $('#splitType').val();
        if(val > 0){
            if(val == 1){
                if(rowCount == 1){
                    $('.percentRow').val(100);
                    $('.amountRow').val(totalAmt);
                    $('.grossAmtRow').val(grossAmt);
                    callAjax(data, val);
                }else if(rowCount > 1){
                    alert('Bill for this option can be generated only for one Contact.');
                }
            }else{
                callAjax(data, val);
            }
        }else{
            alert('Select Split Type');
        }
   });

   function callAjax(data, splitType){
        $.post('<?= base_url('customer/splitBill') ?>',data,function(res){
            if(res.status == 'success'){
              window.location = `${res.response}`;
              return false;
            }else{
              alert(res.response);
            }
        });
   }

</script>

</html>