<?php $this->load->view('layouts/admin/head');

$CheckOTP = $this->session->userdata('DeliveryOTP');
$EID = authuser()->EID;
$EType = $this->session->userdata('EType');
$RestName = authuser()->RestName;
 ?>
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

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18"><?php echo $title; ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-md-12">
                                <form method="post" action="<?php echo base_url('restaurant/add_stock'); ?>">

                                <div class="card">
                                    <div class="card-body">
                                        <input type="hidden" name="edit_stock" value="1">
                                        <input type="hidden" name="trans_id" value="<?= $TransId?>">
                                            <div class="row">
                                                <?php $label = 'From';?>
                                                <div class="col-2">
                                                    <label>Num</label><br>
                                                    <b><?= $TransId?></b>
                                                </div>
                                                <div class="col-2">
                                                    <label>Type</label><br>
                                                    <b><?= getTransType($stock[0]['TransType']) ?></b>
                                                    <input type="hidden" id="trans_type" value="<?= $stock[0]['TransType']?>">
                                                    
                                                </div>
                                                <div class="col-2">
                                                    <label><span id="tr_date_label"></span>Date</label><br>
                                                    <b><?= date('d-m-Y', strtotime($stock[0]['TransDt']))?></b>
                                                </div>
                                                <div class="transtype_details col-2" id="from_store_div" style="display: none;">
                                                    <label>From</label><br>
                                                    <?php $fr = '';?>
                                                    <select class="form-control d-none" id="from_store" name="from_store">
                                                        <option value="" disabled="">Select Store</option>
                                                        <option value="1" <?php if($stock[0]['FrmStoreId'] == 1){$fr = 'Main Store';$label = 'To';;echo 'selected';}else{echo 'disabled';}?>>Main Store</option>
                                                    </select>
                                                    <b><?= $fr;?></b>
                                                </div>
                                                <div class=" transtype_details col-2" id="eatary_div" style="display: none;">
                                                    <label><span id="eid_label"></span><?= $label?></label><br>
                                                    <?php $ea = '';?>
                                                    <select class="form-control d-none" id="eatary" name="eatary">
                                                        <option value="" disabled="">Select EID</option>
                                                        <?php foreach($eatary as $key){?>
                                                            <option value="<?= $key['EID']?>" <?php if($key['EID'] == $stock[0]['FrmEID'] || $key['EID'] == $stock[0]['ToEID']){echo 'selected';$ea = $key['Name'];}else{echo 'disabled';}?>><?= $key['Name']?></option>
                                                        <?php }?>
                                                    </select>
                                                    <b><?= $ea;?></b>
                                                </div>
                                                <div class=" transtype_details col-2" id="kit_div" style="display: none;">
                                                    <label><span id="kit_label"></span><?= $label?></label><br>
                                                    <?php $ki = '';?>
                                                    <select class="form-control d-none" id="kit" name="kit">
                                                        <option value="" disabled="">Select KIT</option>
                                                        <?php foreach($kit as $key){?>
                                                            <option value="<?= $key['KitCd']?>" <?php if($key['KitCd'] == $stock[0]['FrmKitCd'] || $key['KitCd'] == $stock[0]['ToKitCd']){echo 'selected';$ki = $key['KitName'];}else{echo 'disabled';}?>><?= $key['KitName']?></option>
                                                        <?php }?>
                                                    </select>
                                                    <b><?= $ki;?></b>
                                                </div>
                                                <div class=" transtype_details col-2" id="suppliers_div" style="display: none;">
                                                    <label><span id="supp_label"></span><?= $label?></label><br>
                                                    <?php $su = '';?>
                                                    <select class="form-control d-none" id="suppliers" name="supplier">
                                                        <option value="" disabled="">Select Supplier</option>
                                                        <?php foreach($suppliers as $key){?>
                                                            <option value="<?= $key['SuppCd']?>" <?php if($key['SuppCd'] == $stock[0]['FrmSuppCd'] || $key['SuppCd'] == $stock[0]['ToSuppCd']){echo 'selected';$su = $key['SuppName'];}else{echo 'disabled';}?>><?= $key['SuppName']?></option>
                                                        <?php }?>
                                                    </select>
                                                    <b><?= $su?></b>
                                                </div>
                                                <div class="transtype_details col-2" id="to_store_div" style="display: none;">
                                                    <label>To</label><br>
                                                    <?php $to = '';?>
                                                    <select class="form-control d-none" id="to_store" name="to_store">
                                                        <option value="" disabled="">Select Store</option>
                                                        <option value="1" <?php if($stock[0]['ToStoreId'] == 1){echo 'selected';$to = 'Main Store';}else{echo 'disabled';}?>>Main Store</option>
                                                    </select>
                                                    <b><?= $to;?></b>
                                                </div>
                                                <div class="col-2">
                                                    <i class="fa fa-trash" style="color: red;" onclick="delete_trans(<?= $TransId?>)"></i>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                            <div class="container pt-3">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Item Name</th>
                                                                <th>UOM</th>
                                                                <th>Rate</th>
                                                                <th>Qty</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="stock_list" id="stock_list">
                                                            <?php $n=1;foreach($stock_details as $sd){?>
                                                                <input type="hidden" name="RMDetId[]" value="<?= $sd['RMDetId']?>">
                                                                <tr>
                                                                    <td>
                                                                        <select name="ItemId[]" class="items form-control" id="items<?= $n?>" onchange="getUOM(this, <?= $n?>)">
                                                                            <option value="">SELECT ITEM</option>
                                                                            <?php foreach($items as $key){?>
                                                                                <option value="<?= $key['RMCd']?>" <?php if($key['RMCd'] == $sd['RMCd']){echo 'selected';}?>><?= $key['RMName']?></option>
                                                                            <?php }?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="hidden" id="uomcd<?= $n?>" value="<?= $sd['UOMCd']?>">
                                                                        <select name="UOM[]" class="uom form-control" id="uom<?= $n?>">
                                                                            <option value="">SELECT UOM</option>
                                                                            
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="number" name="Rate[]" value="<?= $sd['Rate']?>" class="form-control"></td>
                                                                    <td><input type="number" name="Qty[]" value="<?= $sd['Qty']?>" class="form-control"></td>
                                                                    <td><i class="fa fa-trash" onclick="delete_details(<?= $sd['RMDetId']?>)" style="color: red;"></i></td>
                                                                </tr>
                                                            <?php $n++;}?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-primary btn-rounded" onclick="add_row()">+</button>
                                            </div>
                                            <div class="text-center p-2"><button class="btn btn-primary btn-sm" type="submit">Update</button></div>
                                        
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
var cntr = <?= sizeof($stock_details)?>;    
function getUOM(el, n){
    var item_id = el.value;
    $.ajax({
        url: "<?php echo base_url('restaurant/rm_ajax'); ?>",
        type: "post",
        data: {'getUOM':1, 'RMCd':item_id},
        success: response => {
            // console.log(response);
            var data = JSON.parse(response);
            var b = '<option value="">SELECT UOM</option>';
            // alert(data);
            // alert(data.length);
            if (data != '') {
                for(i = 0;i<data.length;i++){
                    // alert(data[i].UOMCd);
                    b+='<option value="'+data[i].UOMCd+'">'+data[i].Name+'</option>';
                }
                // alert(b);
                $('#uom'+n).html(b);
            } else {
                alert(response);
            }
        },
        error: (xhr, status, error) => {
            
        }
    });
}
function delete_details(id){
    if(confirm("Are you sure want to continue?")){
        $.ajax({
            url: "<?php echo base_url('restaurant/add_stock'); ?>",
            type: "post",
            data: {'delete_details':1, 'RMDetId':id},
            success: response => {
                // console.log(response);
                location.reload();
            },
            error: (xhr, status, error) => {
                
            }
        });
    }
}
function delete_trans(id){
    if(confirm("Are you sure want to continue?")){
        $.ajax({
            url: "<?php echo base_url('restaurant/add_stock'); ?>",
            type: "post",
            data: {'delete_trans':1, 'TransId':id},
            success: response => {
                // console.log(response);
                window.location.href = 'stock_list.php';
            },
            error: (xhr, status, error) => {
                
            }
        });
    }
}
function add_row(){
    cntr++;
    var b = '<tr><input type="hidden" name="RMDetId[]" value=""><td><select name="ItemId[]" class="items form-control" id="items1" onchange="getUOM(this, '+cntr+')"><option value="">SELECT ITEM</option><?php foreach($items as $key){?><option value="<?= $key['RMCd']?>"><?= $key['RMName']?></option><?php }?></select></td><td><select name="UOM[]" class="uom form-control" id="uom'+cntr+'"><option value="">SELECT UOM</option></select></td><td><input type="number" name="Rate[]" class="form-control"></td><td><input type="number" name="Qty[]" class="form-control"></td></tr>';
    $('#stock_list').append(b);
}

trans_typee();
function trans_typee(){
    var v = $('#trans_type').val();
    // alert(v);
    $('.transtype_details').hide();
    if(v == 1){
        $('#eatary_div').show();
        $('#from_store_div').show();
    }else if(v == 6){
        $('#suppliers_div').show();
        $('#from_store_div').show();
    }else if(v == 9){
        $('#kit_div').show();
        $('#from_store_div').show();
    }else if(v == 11){
        $('#eatary_div').show();
        $('#to_store_div').show();
    }else if(v == 16){
        $('#suppliers_div').show();
        $('#to_store_div').show();
    }else if(v == 19){
        $('#kit_div').show();
        $('#to_store_div').show();
    }else if(v == 25){
        // $('#eatary_div').show();
    }else if(v == 26){
        
    }else if(v == 27){
        
    }
}
var temp =1;
function set_uom(){
    for(k = 1;k<=cntr;k++){
        var item_id = $('#items'+temp).val();
        var uom = $('#uomcd'+temp).val();
        $.ajax({
            url: "<?php echo base_url('restaurant/rm_ajax'); ?>",
            type: "post",
            data: {'getUOM':1, 'RMCd':item_id},
            success: response => {
                // console.log(response);
                var data = JSON.parse(response);
                var b = '<option value="">SELECT UOM</option>';
                // alert(data);
                // alert(data.length);
                if (data != '') {
                    for(i = 0;i<data.length;i++){
                        var c = '';
                        // alert(uom);
                        if(data[i].UOMCd == uom){
                            c='selected';
                            // alert(c);
                        }
                        // alert(data[i].UOMCd);
                        b+='<option value="'+data[i].UOMCd+'" '+c+' >'+data[i].Name+'</option>';
                    }
                    // alert(b);
                    // alert(k);
                    // alert(temp);
                    $('#uom'+temp).html(b);
                    temp++;
                } else {
                    alert(response);
                }
            },
            error: (xhr, status, error) => {
                
            }
        });
    }
}
set_uom();
</script>