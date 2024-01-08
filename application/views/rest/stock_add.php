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

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" action="<?php echo base_url('restaurant/add_stock'); ?>">
                                            <input type="hidden" name="add_stock" value="1">
                                            <div class="row">
                                                <div class="col-md-6 col-6">
                                                    <label><?= $this->lang->line('transactionType'); ?></label>
                                                    <select class="form-control form-control-sm" name="trans_type" onchange="trans_typee()" id="trans_type" required="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($trans_type as $key){?>
                                                            <option value="<?= $key['TagId']; ?>"><?= $key['TDesc']; ?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 col-6">
                                                    <label><span id="tr_date_label"></span><?= $this->lang->line('transactionDate'); ?></label>
                                                    <input type="date" name="TransDt" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>" required="">
                                                </div>

                                                <div class="transtype_details col-6" id="from_store_div" style="display: none;">
                                                    <label><?= $this->lang->line('from'); ?></label>
                                                    <select class="form-control form-control-sm" id="from_store" name="from_store">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($kit as $key){?>
                                                            <option value="<?= $key['KitCd']?>"><?= $key['KitName']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="transtype_details col-6" id="from_adjust_store_div" style="display: none;">
                                                    <label><?= $this->lang->line('from'); ?></label>
                                                    <select class="form-control form-control-sm" id="from_adjust_store" name="store_adjust">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($kit as $key){?>
                                                            <option value="<?= $key['KitCd']?>"><?= $key['KitName']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class=" transtype_details col-6" id="eatary_div" style="display: none;">
                                                    <label><span id="eid_label"></span><!-- Outlet --></label>
                                                    <select class="form-control form-control-sm" id="eatary" name="eatary">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($eatary as $key){?>
                                                            <option value="<?= $key['EID']?>"><?= $key['Name']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class=" transtype_details col-6" id="kit_div" style="display: none;">
                                                    <label><span id="kit_label"></span></label>
                                                    <select class="form-control form-control-sm" id="kit" name="kit">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($kit as $key){?>
                                                            <option value="<?= $key['KitCd']?>"><?= $key['KitName']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class=" transtype_details col-6" id="suppliers_div" style="display: none;">
                                                    <label><span id="supp_label"></span><!-- Supplier --></label>
                                                    <select class="form-control form-control-sm" id="suppliers" name="supplier">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($suppliers as $key){?>
                                                            <option value="<?= $key['SuppCd']?>"><?= $key['SuppName']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="transtype_details col-6" id="to_store_div" style="display: none;">
                                                    <label><?= $this->lang->line('to'); ?></label>
                                                    <select class="form-control form-control-sm" id="to_store" name="to_store">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($kit as $key){?>
                                                            <option value="<?= $key['KitCd']?>"><?= $key['KitName']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th><?= $this->lang->line('item'); ?></th>
                                                                <th><?= $this->lang->line('uom'); ?></th>
                                                                <th><?= $this->lang->line('rate'); ?></th>
                                                                <th><?= $this->lang->line('quantity'); ?></th>
                                                                <th><?= $this->lang->line('remarks'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="stock_list" id="stock_list">
                                                                <tr>
                                                                    <td>
                                                                        <select name="ItemId[]" class="items form-control form-control-sm" id="items1" onchange="getUOM(this, 1)" >
                                                                            <option value="">SELECT ITEM</option>
                                                                            <?php foreach($items as $key){?>
                                                                                <option value="<?= $key['RMCd']?>"><?= $key['RMName']?></option>
                                                                            <?php }?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="UOM[]" class="uom form-control form-control-sm" id="uom1" >
                                                                            <option value="">SELECT UOM</option>
                                                                            
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="number" name="Rate[]" class="rate form-control form-control-sm" ></td>
                                                                    <td><input type="number" name="Qty[]" class="form-control form-control-sm"></td>
                                                                    <td><input type="text" name="Remarks[]" class="form-control form-control-sm"></td>
                                                                </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-rounded btn-primary" onclick="add_row()">+</button>
                                                </div>
                                            </div>
                                            <div class="text-center p-2"><button class="btn btn-primary btn-sm" type="submit"><?= $this->lang->line('submit'); ?></button></div>
                                        </form>
                                    </div>
                                </div>
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
var cntr = 1;   
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
function add_row(){
    cntr++;
    var b = '<tr>\
                <td>\
                    <select name="ItemId[]" class="items form-control form-control-sm" id="items1" onchange="getUOM(this, '+cntr+')">\
                        <option value=""><?= $this->lang->line('select'); ?></option>\
                        <?php foreach($items as $key){?>
                            <option value="<?= $key['RMCd']?>"><?= $key['RMName']?></option>\
                            <?php }?>
                    </select>\
                </td>\
                <td>\
                    <select name="UOM[]" class="uom form-control form-control-sm" id="uom'+cntr+'">\
                        <option value=""><?= $this->lang->line('select'); ?></option>\
                    </select>\
                </td>\
                <td>\
                    <input class="rate form-control form-control-sm" type="number" name="Rate[]" >\
                </td>\
                <td>\
                    <input type="number" name="Qty[]" class="form-control form-control-sm">\
                </td>\
                <td>\
                    <input type="text" name="Remarks[]" class="form-control form-control-sm">\
                </td>\
            </tr>';

    $('#stock_list').append(b);
    var v = $('#trans_type').val();
    if(v == 20){
        // alert("sss");
        $('.rate').attr('readonly', "")
    }
}


function trans_typee(){
    var v = $('#trans_type').val();
    // alert(v);
    $('.transtype_details').hide();
    if(v == 3){
        // Transfer To EID
        $('#eatary_div').show();
        $('#eid_label').html('To');
        $('#from_store_div').show();
        $('.rate').attr('readonly', "")
    }else if(v == 1){
        // Purchase Return
        $('#suppliers_div').show();
        $('#supp_label').html('To');
        $('#from_store_div').show();
        $('.rate').attr('readonly', "")
    }else if(v == 2){
        // Issue to Kit
        $('#kit_div').show();
        $('#kit_label').html('To');
        $('#from_store_div').show();
        $('.rate').attr('readonly', "")
    }else if(v == 11){
        // Return From EID
        $('#eatary_div').show();
        $('#eid_label').html('From');
        $('#to_store_div').show();
        $('.rate').attr('readonly', "")
    }else if(v == 10){
        // Purchase
        $('#suppliers_div').show();
        $('#supp_label').html('From');
        $('#to_store_div').show();
        $('.rate').attr('readonly', false);
    }else if(v == 12){
        // Return from Kit
        $('#kit_div').show();
        $('#kit_label').html('From');
        $('#to_store_div').show();
        $('.rate').attr('readonly', "")
    }else if(v == 13){
        // Inward Adjust
        // $('#eatary_div').show();
        $('.rate').attr('readonly', "")
    }else if(v == 20){
        // Stock Adjust
        // alert("sss");
        $('.rate').attr('readonly', "")
        $('#from_adjust_store_div').show();
    }
}
</script>