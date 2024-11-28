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
                                                    <select class="form-control form-control-sm" id="from_store" name="FrmStore">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($store as $key){?>
                                                            <option value="<?= $key['MCd']?>"><?= $key['Name']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>

                                                <div class=" transtype_details col-6" id="from_kitchen_div" style="display: none;">
                                                    <label><?= $this->lang->line('from'); ?></label>
                                                    <select class="form-control form-control-sm" id="from_kitchen" name="FrmKit">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($kit as $key){?>
                                                            <option value="<?= $key['EID']?>"><?= $key['Name']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>

                                                <div class=" transtype_details col-6" id="from_suppliers_div" style="display: none;">
                                                    <label><?= $this->lang->line('from'); ?></label>
                                                    <select class="form-control form-control-sm" id="from_suppliers" name="FrmSupp">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($suppliers as $key){?>
                                                            <option value="<?= $key['MCd']?>"><?= $key['Name']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>

                                                <div class=" transtype_details col-6" id="from_bom_div" style="display: none;">
                                                    <label><?= $this->lang->line('from'); ?></label>
                                                    <select class="form-control form-control-sm" id="from_bom" name="FrmBOM">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($bomStore as $key){?>
                                                            <option value="<?= $key['MCd']?>"><?= $key['Name']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>

                                                <div class=" transtype_details col-6" id="from_waste_div" style="display: none;">
                                                    <label><?= $this->lang->line('from'); ?></label>
                                                    <select class="form-control form-control-sm" id="from_waste" name="FrmWaste">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($wasteStore as $key){?>
                                                            <option value="<?= $key['MCd']?>"><?= $key['Name']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>

                                                <div class="transtype_details col-6" id="to_store_div" style="display: none;">
                                                    <label><?= $this->lang->line('to'); ?></label>
                                                    <select class="form-control form-control-sm" id="to_store" name="ToStore">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($store as $key){?>
                                                            <option value="<?= $key['MCd']?>"><?= $key['Name']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                
                                                <div class=" transtype_details col-6" id="to_kitchen_div" style="display: none;">
                                                    <label><?= $this->lang->line('to'); ?></label>
                                                    <select class="form-control form-control-sm" id="to_kitchen" name="ToKit">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($kit as $key){?>
                                                            <option value="<?= $key['MCd']?>"><?= $key['Name']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>

                                                <div class="transtype_details col-6" id="to_suppliers_div" style="display: none;">
                                                    <label><?= $this->lang->line('to'); ?></label>
                                                    <select class="form-control form-control-sm" id="from_suppliers" name="ToSupp">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($suppliers as $key){?>
                                                            <option value="<?= $key['MCd']?>"><?= $key['Name']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>

                                                <div class=" transtype_details col-6" id="to_bom_div" style="display: none;">
                                                    <label><?= $this->lang->line('to'); ?></label>
                                                    <select class="form-control form-control-sm" id="to_bom" name="ToBOM">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($bomStore as $key){?>
                                                            <option value="<?= $key['MCd']?>"><?= $key['Name']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>

                                                <div class=" transtype_details col-6" id="to_waste_div" style="display: none;">
                                                    <label><?= $this->lang->line('to'); ?></label>
                                                    <select class="form-control form-control-sm" id="to_waste" name="ToWaste">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach($wasteStore as $key){?>
                                                            <option value="<?= $key['MCd']?>"><?= $key['Name']?></option>
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
                                                                <th><?= $this->lang->line('type'); ?></th>
                                                                <th><?= $this->lang->line('item'); ?></th>
                                                                <th><?= $this->lang->line('uom'); ?></th>
                                                                <th><?= $this->lang->line('rate'); ?></th>
                                                                <th><?= $this->lang->line('quantity'); ?></th>
                                                                <th><?= $this->lang->line('remarks'); ?></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="stock_list" id="stock_list">
                                                                <tr>
                                                                    <td>
                                                                        <select name="itemtype[]" id="itemtype_1" class="form-control form-control-sm" required="" onchange="changeItemType(1)">
                                                                            <option value="1">RM</option>
                                                                            <option value="2">Finish Goods</option>
                                                                </select>
                                                                    </td>
                                                                    <td id="rmTD1">
                                                                        <select name="ItemId[]" class="items form-control form-control-sm select2 custom-select" id="items1" onchange="getUOM(this, 1)" >
                                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                                            <?php foreach($items as $key){?>
                                                                                <option value="<?= $key['RMCd']?>"><?= $key['RMName']?></option>
                                                                            <?php }?>
                                                                        </select>
                                                                    </td>
                                                                    <td id="menuTD1" style="display: none;">
                                                                        <select name="MItemId[]" class="items form-control form-control-sm select2 custom-select" id="mitems1" onchange="getPortionItem(this, 1)" >
                                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                                            <?php foreach($menuItems as $key){?>
                                                                                <option value="<?= $key['ItemId']?>"><?= $key['Name']?></option>
                                                                            <?php }?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="UOM[]" class="uom form-control form-control-sm" id="uom1" >
                                                                            <option value="">SELECT UOM</option>
                                                                            
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="number" name="Rate[]" class="rate form-control form-control-sm" ></td>
                                                                    <td><input type="number" name="Qty[]" class="form-control form-control-sm" onchange="checkQty(this,1);" id="qty_box1"></td>
                                                                    <td><input type="text" name="Remarks[]" class="form-control form-control-sm"></td>
                                                                    <td>
                                                                        <button class="btn btn btn-sm btn-danger removeRow"><i class="fa fa-trash" ></i></button>
                                                                    </td>
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
    $(document).ready(function() {
        $('.items').select2();
    });

var cntr = 1;

function changeItemType(counter){
    var type = $(`#itemtype_${counter}`).val();
    if(type==1){
        // RM
        $(`#menuTD${counter}`).hide();
        $(`#rmTD${counter}`).show();
    }else if(type == 2){
        // finish goods
        $(`#rmTD${counter}`).hide();
        $(`#menuTD${counter}`).show();
    }
}

function getUOM(el, n){
    var item_id = el.value;
    $.ajax({
            url: "<?php echo base_url('restaurant/rm_ajax'); ?>",
            type: "post",
            data: {'getUOM':1, 'RMCd':item_id},
            success: response => {
                
                var data = JSON.parse(response);
                var b = '<option value="">SELECT UOM</option>';
                
                if (data != '') {
                    for(i = 0;i<data.length;i++){
                        
                        b+='<option value="'+data[i].UOMCd+'">'+data[i].Name+'</option>';
                    }
                    
                    $('#uom'+n).html(b);
                } else {
                    alert(response);
                }
            },
            error: (xhr, status, error) => {
                
            }
        });
}

function getPortionItem(el, n){
    var ItemId = el.value;
    if(ItemId > 0){
        $.post('<?= base_url('restaurant/get_item_portion_by_itemId') ?>',{ItemId:ItemId},function(res){
            if(res.status == 'success'){
                var temp = `<option value=""><?= $this->lang->line('select'); ?></option>`;
                res.response.forEach((item) => {
                    temp +=`<option value="${item.Itm_Portion}">${item.Portions}</option>`;
                });
                $(`#uom${n}`).html(temp);
            }else{
              alert(res.response);
            }
        });
    }
}

function add_row(){
    cntr++;
    var b = '<tr>\
                <td>\
                    <select name="itemtype[]" id="itemtype_'+cntr+'" class="form-control form-control-sm" required="" onchange="changeItemType('+cntr+')">\
                                <option value="1">RM</option>\
                                <option value="2">Finish Goods</option>\
                    </select>\
                </td>\
                <td id="rmTD'+cntr+'">\
                    <select name="ItemId[]" class="items form-control form-control-sm select2 custom-select" id="items'+cntr+'" onchange="getUOM(this, '+cntr+')">\
                        <option value=""><?= $this->lang->line('select'); ?></option>\
                        <?php foreach($items as $key){?>
                            <option value="<?= $key['RMCd']?>"><?= $key['RMName']?></option>\
                            <?php }?>
                    </select>\
                </td>\
                <td id="menuTD'+cntr+'" style="display: none;">\
                    <select name="MItemId[]" class="items form-control form-control-sm select2 custom-select" id="mitems'+cntr+'" onchange="getPortionItem(this, '+cntr+')" >\
                        <option value=""><?= $this->lang->line('select'); ?></option>\
                        <?php foreach($menuItems as $key){?>
                            <option value="<?= $key['ItemId']?>"><?= $key['Name']?></option>\
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
                    <input type="number" name="Qty[]" class="form-control form-control-sm" id="qty_box'+cntr+'" onchange="checkQty(this, '+cntr+')">\
                </td>\
                <td>\
                    <input type="text" name="Remarks[]" class="form-control form-control-sm">\
                </td>\
                <td>\
                    <button class="btn btn btn-sm btn-danger removeRow"><i class="fa fa-trash"></i></button>\
                </td>\
            </tr>';

    $('#stock_list').append(b);
    $('.items').select2();
    var v = $('#trans_type').val();
    if(v == 20){
        // alert("sss");
        $('.rate').attr('readonly', "")
    }
}

// remove row
   $("#stock_list").on('click','.removeRow',function(){
    cntr--;
        $(this).parent().parent().remove();
    });

   function checkQty(e, inc){
    var qty = e.value;
    var trans_type = $(`#trans_type`).val();
    var uom = $(`#uom${inc}`).val();
    if(uom > 0){
        if(trans_type > 0){
            if(trans_type == 1 || trans_type == 2 || trans_type == 12){
                if(trans_type == 1){
                    var frmId = $(`#from_store`).val();
                    if(frmId > 0){
                        var item = $(`#items${inc}`).val();
                        var uom = $(`#uom${inc}`).val();
                        calltoAjax(trans_type, frmId, item, uom, qty, inc);
                    }else{
                        alert('Please select store');
                    }
                }

                if(trans_type == 2){
                    var frmId = $(`#from_store`).val();
                    if(frmId > 0){
                        var item = $(`#items${inc}`).val();
                        var uom = $(`#uom${inc}`).val();
                        calltoAjax(trans_type, frmId, item, uom, qty, inc);
                    }else{
                        alert('Please select store');
                    }
                }

                if(trans_type == 12){
                    var frmId = $(`#from_kitchen`).val();
                    if(frmId > 0){
                        var item = $(`#items${inc}`).val();
                        var uom = $(`#uom${inc}`).val();
                        calltoAjax(trans_type, frmId, item, uom, qty, inc);
                    }else{
                        alert('Please select kitchen');
                    }
                }
            }
        }else{
            alert('Please select Transaction Type');
        }
    }else{
        alert('Please select UOM!!');
    }
   }

   function calltoAjax(TransTyp, FrmId, Item, uom, Qty, inc){

        $.post('<?= base_url('restaurant/checkStock') ?>',{TransTyp:TransTyp, FrmId:FrmId, Item:Item, uom:uom, Qty:Qty},function(res){
            if(res.status == 'success'){
                if(res.response > 0){
                    alert('Insufficent Quantity');
                    $(`#qty_box${inc}`).val(0);
                }
              
            }else{
              alert(res.response);
            }
        });
   }

function trans_typee(){
    var v = $('#trans_type').val();
    // alert(v);
    $('.transtype_details').hide();
    if(v == 1){
        // Purchase Return
        $('#from_store_div').show();
        $('#to_suppliers_div').show();

        $('#from_suppliers_div').hide();
        $('#to_store_div').hide();

        $('#from_kitchen_div').hide()
        $('#to_kitchen_div').hide();

        $('.rate').attr('readonly', "")
    }else if(v == 10){
        // Purchase
        $('#from_suppliers_div').show();
        $('#to_store_div').show();

        $('#from_store_div').hide();
        $('#from_kitchen_div').hide();

        $('#to_suppliers_div').hide();
        $('#to_kitchen_div').hide();
        
        $('.rate').attr('readonly', false);
    }else if(v == 2){
        // Issue to Kit
        $('#from_store_div').show();
        $('#to_kitchen_div').show();

        $('#to_suppliers_div').hide();
        $('#from_suppliers_div').hide();
        $('#to_store_div').hide();
        $('#from_kitchen_div').hide()

        $('.rate').attr('readonly', "")
    }else if(v == 12){
        // Return from Kit
        $('#from_kitchen_div').show()
        $('#to_store_div').show();

        $('#from_store_div').hide();
        $('#to_kitchen_div').hide();

        $('#to_suppliers_div').hide();
        $('#from_suppliers_div').hide();

        $('.rate').attr('readonly', "")
    }else if(v == 4){
        // outword Adjust
        $('#from_store_div').show();

        $('#to_store_div').hide();
        $('#to_kitchen_div').hide();

        $('#to_suppliers_div').hide();
        $('#from_suppliers_div').hide();
        $('#from_kitchen_div').hide()

        $('.rate').attr('readonly', "")
    }else if(v == 13){
        // Inward Adjust
        $('#to_store_div').show();
        
        $('#from_store_div').hide();
        $('#to_kitchen_div').hide();

        $('#to_suppliers_div').hide();
        $('#from_suppliers_div').hide();
        $('#from_kitchen_div').hide()

        $('.rate').attr('readonly', "")
    }else if(v == 26){
        // kitchen to bom
        $('#from_kitchen_div').show()
        $('#to_bom_div').show();

        $('#to_store_div').hide();
        $('#from_store_div').hide();
        $('#to_kitchen_div').hide();

        $('#to_suppliers_div').hide();
        $('#from_suppliers_div').hide();

        $('#from_bom_div').hide();
        $('#from_waste_div').hide();
        $('#to_waste_div').hide();

        $('.rate').attr('readonly', "")
    }else if(v == 27){
        // bom to kitchen
        $('#from_bom_div').show();
        $('#to_kitchen_div').show();

        $('#from_kitchen_div').hide()
        $('#to_bom_div').hide();
        $('#to_store_div').hide();
        $('#from_store_div').hide();
        $('#to_suppliers_div').hide();
        $('#from_suppliers_div').hide();
        $('#from_waste_div').hide();
        $('#to_waste_div').hide();

        $('.rate').attr('readonly', "")
    }else if(v == 28){
        // kitchen to waste
        $('#from_kitchen_div').show()
        $('#to_waste_div').show();

        $('#from_bom_div').hide();
        $('#to_kitchen_div').hide();
        $('#to_bom_div').hide();
        $('#to_store_div').hide();
        $('#from_store_div').hide();
        $('#to_suppliers_div').hide();
        $('#from_suppliers_div').hide();
        $('#from_waste_div').hide();

        $('.rate').attr('readonly', "")
    }else if(v == 29){
        // bom to waste
        $('#from_bom_div').show();
        $('#to_waste_div').show();

        $('#from_kitchen_div').hide()
        $('#to_kitchen_div').hide();
        $('#to_bom_div').hide();
        $('#to_store_div').hide();
        $('#from_store_div').hide();
        $('#to_suppliers_div').hide();
        $('#from_suppliers_div').hide();
        $('#from_waste_div').hide();

        $('.rate').attr('readonly', "")
    }
}
</script>