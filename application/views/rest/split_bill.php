<?php $this->load->view('layouts/admin/head'); ?>
        <?php $this->load->view('layouts/admin/top'); ?>
            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <?php $this->load->view('layouts/admin/sidebar'); ?>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <form action="<?= base_url('restaurant/splitBill/'.$MCNo.'/'.$MergeNo.'/'.$tableFilter); ?>" method="post">
                                    <input type="hidden" name="payableAmount" value="<?= round($payable); ?>">
                                    <input type="hidden" name="grossAmount" value="<?= round($grossItemAmt); ?>">
                                    <input type="hidden" name="MergeNo" value="<?= $MergeNo; ?>">
                                    <input type="hidden" name="MCNo" value="<?= round($MCNo); ?>">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-2 col-4">
                                                    <label for=""><?= $this->lang->line('payable'); ?>: </label><br>
                                                    <b id="payable"><?= round($payable); ?></b>
                                                </div>
                                                <div class="col-md-2 col-5">
                                                    <label for=""><?= $this->lang->line('orderAmount'); ?>: </label><br>
                                                    <b id="grossAmt"><?= round($grossItemAmt); ?></b>
                                                </div>

                                                <div class="col-md-2 col-3">
                                                    <label for=""><?= $this->lang->line('tips'); ?>: </label><br>
                                                    <b id="tipAmt"><?= round($tip); ?></b>
                                                </div>
                                                
                                                <div class="col-md-2 col-3">
                                                    <label for="">&nbsp;</label><br>
                                                    <button class="btn btn btn-sm btn-success" onclick="addRow()" id="btnADD"><i class="fa fa-plus"></i></button>
                                                </div>

                                                <div class="col-md-4 col-9">
                                                    <label for=""><?= $this->lang->line('splitType'); ?> : </label>
                                                    <select name="splitType" id="splitType" onchange="splitChange()" required="" class="form-control form-control-sm">
                                                        <option value=""><?= $this->lang->line('chooseSplitType'); ?></option>
                                                        <option value="1"><?= $this->lang->line('foodBarSeparate'); ?></option>
                                                        <option value="2"><?= $this->lang->line('equalSplit'); ?></option>
                                                        <option value="3"><?= $this->lang->line('manualPercent'); ?></option>
                                                        <option value="4"><?= $this->lang->line('manualAmount'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table" id="splitTable">
                                                        <thead>
                                                            <tr>
                                                                <th width="155px;"><?= $this->lang->line('country'); ?></th>
                                                                <th width="155px;"><?= $this->lang->line('mobile'); ?></th>
                                                                <th width="100px;"></th>
                                                                <th width="100px;">%</th>
                                                                <th width="100px;"><?= $this->lang->line('amount'); ?></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="addBody">
                                                           
                                                            <tr>
                                                                <td>
                                                                    <select name="CountryCd[]" class="form-control form-control-sm CountryCd select2 custom-select" required="" >
                                                                        <?= $this->lang->line('select'); ?>
                                                                        <?php 
                                                                        foreach ($country as $key) { ?>
                                                                            <option value="<?= $key['phone_code']; ?>" <?php if($CountryCd == $key['phone_code']){ echo 'selected'; } ?>><?= $key['country_name']; ?></option>
                                                                        <?php } ?> 
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" value="<?= $CellNo; ?>" placeholder="Mobile" class="form-control form-control-sm" required name="mobile[]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control grossAmtRow form-control-sm" name="totItemAmt[]" id="grossAmtRow_1" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="text" placeholder="Percent" class="form-control percentRow form-control-sm" id="percentRow_1" onchange="calcPerAmt(1)" name="percent[]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" placeholder="Amount" class="form-control amountRow form-control-sm" id="amountRow_1" onchange="calcAmt(1)" required name="amount[]">
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn btn-sm btn-danger removeRow"><i class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="text-right">
                                                    <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('splitbill'); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <?php $this->load->view('layouts/admin/footer'); ?>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <?php $this->load->view('layouts/admin/color'); ?>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        
        <?php $this->load->view('layouts/admin/script'); ?>


<script type="text/javascript">

    $(document).ready(function() {
        $('.CountryCd').select2();
    });

    var totalAmt = "<?= round($payable); ?>";
    var grossAmt = "<?= round($grossItemAmt); ?>";
    var tipAmt   = "<?= round($tip); ?>";

   var rowCount = $('#splitTable tr').length - 1;
   // add row
   function addRow(){
        rowCount++;
        var row = `<tr>
                        <td><select name="CountryCd[]" class="form-control form-control CountryCd select2 custom-select" required="">
                        <option value=""><?= $this->lang->line('select'); ?></option>
                        <?php 
                        foreach ($country as $key) { ?>
                            <option value="<?= $key['phone_code']; ?>" <?php if($CountryCd == $key['phone_code']){ echo 'selected'; } ?>><?= $key['country_name']; ?></option>
                        <?php } ?>                   
                    </select></td>
                        <td><input type="text" placeholder="Mobile" class="form-control form-control-sm" required name="mobile[]"></td>
                        <td>
                            <input type="text" class="form-control grossAmtRow form-control-sm" readonly="" name="totItemAmt[]" id="grossAmtRow_${rowCount}">
                        </td>
                        <td>
                            <input type="number" name="percent[]" placeholder="Percent" class="form-control percentRow form-control-sm" id="percentRow_${rowCount}" onchange="calcPerAmt(${rowCount})">
                        </td>
                        <td>
                            <input type="number" name="amount[]" placeholder="Amount" class="form-control amountRow form-control-sm" id="amountRow_${rowCount}" onchange="calcAmt(${rowCount})" required>
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
        $('#splitType').val(0);

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
        // console.log(val+' ,row '+rowCount+', amt= '+totalAmt);
        if(val > 0){
            if(val == 1){
                if(rowCount == 1){
                    // alert('Single Contact Required');
                    $('.percentRow').val(100);
                    $('.amountRow').val(totalAmt);
                    $('.grossAmtRow').val(grossAmt);
                    // $('#btnADD')
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

                $('.percentRow').val(per.toFixed(2));
                $('.amountRow').val(amt.toFixed(2));
                $('.grossAmtRow').val(grsAmt.toFixed(2));
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
        $('#grossAmtRow_'+rowCount).val(grossVal.toFixed(2));
        // amountRow_1
        var amt = (parseInt(totalAmt) * parseInt(val)) / 100;
        $('#amountRow_'+rowCount).val(amt.toFixed(2));

        $('#percentRow_'+rowCount).val(val);
   }

   function calcAmt(rowCount){
    
        var val = $('#amountRow_'+rowCount).val();
        console.log(rowCount+' v '+val);

        var per =  parseFloat(val) / parseInt(totalAmt) * 100;
        $('#percentRow_'+rowCount).val(per.toFixed(2));

        itemVal = parseInt(grossAmt) + parseInt(tipAmt);
        var grossVal = (parseInt(itemVal) * parseInt(per.toFixed(2))) / 100;
        $('#grossAmtRow_'+rowCount).val(grossVal.toFixed(2));

        $('#amountRow_'+rowCount).val(val);
   }


</script>