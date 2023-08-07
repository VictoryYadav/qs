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
                    <button class="btn btn btn-sm btn-success" onclick="addRow()"><i class="fa fa-plus"></i> Add Cell Number</button>
                </div>

                <div class="col-md-2">
                    <label for="">Split Type : </label>
                    <select name="splitType" id="splitType" onchange="splitChange()">
                        <option value="0">Choose Split Type</option>
                        <option value="1">Food & Bar Separate</option>
                        <option value="2">Equal Split</option>
                        <option value="3">Manual Percent</option>
                        <option value="4">Manual Amount</option>
                    </select>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table" id="splitTable">
                    <thead>
                        <tr>
                            <th>Mobile</th>
                            <th>Messaging Channel</th>
                            <th></th>
                            <th>%</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="addBody">
                        <?php 
                            $count = 0;
                            foreach ($mobile as $key) {
                                $count++;
                            ?>
                        <tr>
                            <td><input type="text" value="<?= $key; ?>" placeholder="Mobile" class="form-control" required></td>
                            <td>
                                <select name="" id="" class="form-control">
                                    <option value="">Messaging Channel</option>
                                    <option value="">WhatsApp</option>
                                    <option value="">Email</option>
                                </select>
                            </td>
                            <!-- gross amt + tips  percentage -->
                            <td>
                                <input type="text" class="form-control grossAmtRow" disabled="" name="totItemAmt" id="grossAmtRow_<?php echo $count; ?>">
                            </td>
                            <td>
                                <input type="number" value="" placeholder="Percent" class="form-control percentRow" id="percentRow_<?php echo $count; ?>" onchange="calcPerAmt(<?php echo $count; ?>)">
                            </td>
                            <td>
                                <input type="number" value="" placeholder="Amount" class="form-control amountRow" id="amountRow_<?php echo $count; ?>" onchange="calcAmt(<?php echo $count; ?>)">
                            </td>
                            <td>
                                <button class="btn btn btn-sm btn-danger removeRow"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

                
                <br><br><br>
                <button class="btn btn-sm btn-success">Split</button>
            
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
   $(document).ready(function() {
        
    });

   var rowCount = $('#splitTable tr').length - 1;
   // add row
   function addRow(){
    rowCount++;
    var row = '<tr>\
                            <td><input type="text" value="" placeholder="Mobile" class="form-control" required></td>\
                            <td>\
                                <select name="" id="" class="form-control">\
                                    <option value="">Choose MSG Type</option>\
                                    <option value="">WhatsApp</option>\
                                    <option value="">Email</option>\
                                    <option value="">Message</option>\
                                </select>\
                            </td>\
                            <td>\
                                <input type="text" class="form-control grossAmtRow" disabled="" name="totItemAmt" id="grossAmtRow_'+rowCount+'">\
                            </td>\
                            <td>\
                                <input type="number" value="" placeholder="Percent" class="form-control percentRow" id="percentRow_'+rowCount+'" onchange="calcPerAmt('+rowCount+')">\
                            </td>\
                            <td>\
                                <input type="number" value="" placeholder="Amount" class="form-control amountRow" id="amountRow_'+rowCount+'" onchange="calcAmt('+rowCount+')">\
                            </td>\
                            <td>\
                                <button class="btn btn btn-sm btn-danger removeRow"><i class="fa fa-trash"></i></button>\
                            </td>\
                        </tr>';
    $('#addBody').append(row) 
    $('#splitType').val(0);

    $('.percentRow').removeAttr("disabled");
    $('.amountRow').removeAttr("disabled");
    $('.percentRow').val('');
    $('.amountRow').val('');
    $('.grossAmtRow').val(0);
    
   }

   // remove row
   $("#addBody").on('click','.removeRow',function(){
    rowCount--;

        $(this).parent().parent().remove();
        $('#splitType').val(0);

        $('.percentRow').removeAttr("disabled");
        $('.amountRow').removeAttr("disabled");
        $('.percentRow').val('');
        $('.amountRow').val('');
        $('.grossAmtRow').val(0);
    });

   function splitChange(){
        var totalAmt = $('#payable').text();
        var grossAmt = $('#grossAmt').text();
        var tipAmt = $('#tipAmt').text();

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

                $('.percentRow').attr("disabled", "disabled");
                $('.amountRow').attr("disabled", "disabled");

                $('.percentRow').val(per.toFixed(2));
                $('.amountRow').val(amt.toFixed(2));
                $('.grossAmtRow').val(grsAmt.toFixed(2));
            }else if(val == 3){
                $('.percentRow').removeAttr("disabled");
                $('.amountRow').attr("disabled", "disabled"); 

                $('.grossAmtRow').val(0);
                $('.percentRow').val(0);
                $('.amountRow').val(0);
            }else if(val == 4){
                $('.amountRow').removeAttr("disabled");
                $('.percentRow').attr("disabled", "disabled");

                $('.grossAmtRow').val(0);
                $('.percentRow').val(0);
                $('.amountRow').val(0);
            }else{
                $('.percentRow').removeAttr("disabled");
                $('.amountRow').removeAttr("disabled");
            }
        }else{
            alert('Choose Split Type');
        }

   }

   function calcPerAmt(rowCount){
    var totalAmt = $('#payable').text();
    var grossAmt = $('#grossAmt').text();
    var tipAmt = $('#tipAmt').text();

    var val = $('#percentRow_'+rowCount).val();
    console.log(rowCount+' v '+val);

    itemVal = parseInt(grossAmt) + parseInt(tipAmt);
    var grossVal = (parseInt(itemVal) * parseInt(val)) / 100;
    $('#grossAmtRow_'+rowCount).val(grossVal.toFixed(2));
    // amountRow_1
    var amt = (parseInt(totalAmt) * parseInt(val)) / 100;
    $('#amountRow_'+rowCount).val(amt.toFixed(2));
   }

   function calcAmt(rowCount){
    var totalAmt = $('#payable').text();
    var grossAmt = $('#grossAmt').text();
    var tipAmt = $('#tipAmt').text();

    var val = $('#amountRow_'+rowCount).val();
    console.log(rowCount+' v '+val);

    var per =  parseFloat(val) / parseInt(totalAmt) * 100;
    $('#percentRow_'+rowCount).val(per.toFixed(2));

    itemVal = parseInt(grossAmt) + parseInt(tipAmt);
    var grossVal = (parseInt(itemVal) * parseInt(per.toFixed(2))) / 100;
    $('#grossAmtRow_'+rowCount).val(grossVal.toFixed(2))
   }


</script>

</html>