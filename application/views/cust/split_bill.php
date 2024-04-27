<?php $this->load->view('layouts/customer/head'); ?>
<style>
body{
    font-size: 12px;
}

.btn-success{
    background: <?php echo $this->session->userdata('successBtn'); ?>;
    color: <?php echo $this->session->userdata('successBtnClr'); ?>;
}

</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <section class="common-section p-2 dashboard-container">
        <div class="container">
            <form action="<?= base_url('customer/splitBill/'.$MergeNo); ?>" method="post">
                <input type="hidden" name="payableAmount" value="<?= round($payable); ?>">
                <input type="hidden" name="grossAmount" value="<?= round($grossItemAmt); ?>">
                <input type="hidden" name="tipAmount" value="<?= round($tip); ?>">
                <input type="hidden" name="MergeNo" value="<?= round($MergeNo); ?>">
                <input type="hidden" name="MCNo" value="<?= round($MCNo); ?>">
                <input type="hidden" name="tot_sgst" value="<?= round($tot_sgst); ?>">

                <div class="row">
                    <div class="col-md-2 col-4">
                        <label for=""><?= $this->lang->line('payable'); ?>: </label>
                        <b id="payable"><?= convertToUnicodeNumber(round($payable)); ?></b>
                    </div>
                    <div class="col-md-2 col-5">
                        <label for=""><?= $this->lang->line('orderAmount'); ?>: </label>
                        <b id="grossAmt"><?= convertToUnicodeNumber(round($grossItemAmt)); ?></b>
                    </div>

                    <div class="col-md-2 col-3">
                        <label for=""><?= $this->lang->line('tips'); ?>: </label>
                        <b id="tipAmt"><?= convertToUnicodeNumber(round($tip)); ?></b>
                    </div>
                    
                    <div class="col-md-2 col-3">
                        <button class="btn btn btn-sm btn-success" onclick="addRow()"><i class="fa fa-plus"></i></button>
                    </div>

                    <div class="col-md-4 col-9">
                        <label for=""><?= $this->lang->line('splitType'); ?> : </label>
                        <select name="splitType" id="splitType" onchange="splitChange()" required="">
                            <option value=""><?= $this->lang->line('chooseSplitType'); ?></option>
                            <option value="1"><?= $this->lang->line('foodBarSeparate'); ?></option>
                            <option value="2"><?= $this->lang->line('equalSplit'); ?></option>
                            <option value="3"><?= $this->lang->line('manualPercent'); ?></option>
                            <option value="4"><?= $this->lang->line('manualAmount'); ?></option>
                        </select>
                    </div>
                </div>
                    
                <div class="table-responsive">
                    <table class="table" id="splitTable">
                        <thead>
                            <tr>
                                <th><?= $this->lang->line('country'); ?></th>
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
                                ?>
                            <tr>
                                <td>
                                    <input type="text" value="<?= $cd; ?>" placeholder="Mobile" class="form-control" required name="CountryCd[]" readonly>
                                </td>
                                <td>
                                    <input type="text" value="<?= $mobile[$i]; ?>" placeholder="Mobile" class="form-control" required name="mobile[]" readonly>
                                    <input type="hidden" value="<?= $cust_id[$i]; ?>" class="form-control" name="custid[]">
                                </td>
                                <!-- <td>
                                    <select name="msgFormat[]" id="" class="form-control" required>
                                        <option value="">Messaging Channel</option>
                                        <option value="W">WhatsApp</option>
                                        <option value="E">Email</option>
                                    </select>
                                </td> -->
                                <!-- gross amt + tips  percentage -->
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
    <!-- end footer section -->


    <!-- Js Plugins -->
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
                    <td><select name="CountryCd[]" class="form-control" required="" id="CountryCd">
                        <option value=""><?= $this->lang->line('select'); ?></option>
                        <?php 
                        foreach ($country as $key) { ?>
                            <option value="<?= $key['phone_code']; ?>" <?php if($CountryCd == $key['phone_code']){ echo 'selected'; } ?>><?= $key['country_name']; ?></option>
                        <?php } ?>                   
                    </select></td>
                    <td><input type="text" placeholder="Mobile" class="form-control" required name="mobile[]"></td>
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
    $('#addBody').append(row) 
    $('#splitType').val(0);

    $('.percentRow').removeAttr("readonly");
    $('.amountRow').removeAttr("readonly");
    $('.percentRow').val('');
    $('.amountRow').val('');
    $('.grossAmtRow').val(0);
    
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
                if(rowCount == 0){
                    alert('Single Contact Required');
                }else if(rowCount > 1){
                    alert('Bill for this option can be generated only for one Contact.');
                }
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


</script>

</html>