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
                                <div class="page-title-box align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18 text-center"><?php echo $title; ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" action="<?php echo base_url('restaurant/add_stock'); ?>">
                                            <input type="hidden" name="add_stock" value="1">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label>Transaction Type</label>
                                                    <select class="form-control" name="trans_type" onchange="trans_typee()" id="trans_type">
                                                        <?php foreach($trans_type as $key => $value){?>
                                                            <option value="<?= $key?>"><?= $value?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label><span id="tr_date_label"></span>Transaction Date</label>
                                                    <input type="date" name="TransDt" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                                </div>
                                                <div class="transtype_details col-6" id="from_store_div" style="display: none;">
                                                    <label>From <!-- Store --></label>
                                                    <select class="form-control" id="from_store" name="from_store">
                                                        <option value="">Select</option>
                                                        <option value="1">Main Store</option>
                                                    </select>
                                                </div>
                                                <div class="transtype_details col-6" id="from_adjust_store_div" style="display: none;">
                                                    <label>From <!-- Store --></label>
                                                    <select class="form-control" id="from_adjust_store" name="from_store">
                                                        <option value="">Select</option>
                                                        <option value="1">Main Store</option>
                                                        <?php foreach($kit as $key){?>
                                                            <option value="<?= $key['KitCd']?>"><?= $key['KitName']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class=" transtype_details col-6" id="eatary_div" style="display: none;">
                                                    <label><span id="eid_label"></span><!-- Outlet --></label>
                                                    <select class="form-control" id="eatary" name="eatary">
                                                        <option value="">Select</option>
                                                        <?php foreach($eatary as $key){?>
                                                            <option value="<?= $key['EID']?>"><?= $key['Name']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class=" transtype_details col-6" id="kit_div" style="display: none;">
                                                    <label><span id="kit_label"></span><!-- KIT --></label>
                                                    <select class="form-control" id="kit" name="kit">
                                                        <option value="">Select</option>
                                                        <?php foreach($kit as $key){?>
                                                            <option value="<?= $key['KitCd']?>"><?= $key['KitName']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class=" transtype_details col-6" id="suppliers_div" style="display: none;">
                                                    <label><span id="supp_label"></span><!-- Supplier --></label>
                                                    <select class="form-control" id="suppliers" name="supplier">
                                                        <option value="">Select</option>
                                                        <?php foreach($suppliers as $key){?>
                                                            <option value="<?= $key['SuppCd']?>"><?= $key['SuppName']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="transtype_details col-6" id="to_store_div" style="display: none;">
                                                    <label>To <!-- Store --></label>
                                                    <select class="form-control" id="to_store" name="to_store">
                                                        <option value="">Select</option>
                                                        <option value="1">Main Store</option>
                                                    </select>
                                                </div>
                                                <!-- <div class="transtype_details col-6" id="to_adjust_store_div" style="display: none;">
                                                    <label>From</label>
                                                    <select class="form-control" id="to_adjust_store" name="from_store">
                                                        <option value="">Select</option>
                                                        <option value="1">Main Store</option>
                                                        <option value="1">Kitchen</option>
                                                    </select>
                                                </div> -->
                                            </div>
                                            <div class="container pt-3">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Item Name</th>
                                                                <th>UOM</th>
                                                                <th>Rate</th>
                                                                <th>Qty</th>
                                                                <th>Remarks</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="stock_list" id="stock_list">
                                                                <tr>
                                                                    <td>
                                                                        <select name="ItemId[]" class="items form-control" id="items1" onchange="getUOM(this, 1)" >
                                                                            <option value="">SELECT ITEM</option>
                                                                            <?php foreach($items as $key){?>
                                                                                <option value="<?= $key['RMCd']?>"><?= $key['RMName']?></option>
                                                                            <?php }?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="UOM[]" class="uom form-control" id="uom1" >
                                                                            <option value="">SELECT UOM</option>
                                                                            
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="number" name="Rate[]" class="rate form-control" ></td>
                                                                    <td><input type="number" name="Qty[]" class="form-control"></td>
                                                                    <td><input type="text" name="Remarks[]" class="form-control"></td>
                                                                </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-rounded btn-primary" onclick="add_row()">+</button>
                                            </div>
                                            <div class="text-center p-2"><button class="btn btn-primary btn-sm" type="submit">Submit</button></div>
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
    var b = '<tr><td><select name="ItemId[]" class="items form-control" id="items1" onchange="getUOM(this, '+cntr+')"><option value="">SELECT ITEM</option><?php foreach($items as $key){?><option value="<?= $key['RMCd']?>"><?= $key['RMName']?></option><?php }?></select></td><td><select name="UOM[]" class="uom form-control" id="uom'+cntr+'"><option value="">SELECT UOM</option></select></td><td><input class="rate form-control" type="number" name="Rate[]" ></td><td><input type="number" name="Qty[]" class="form-control"></td><td><input type="text" name="Remarks[]" class="form-control"></td></tr>';
    $('#stock_list').append(b);
    var v = $('#trans_type').val();
    if(v == 27){
        // alert("sss");
        $('.rate').attr('readonly', "")
    }
}


function trans_typee(){
    var v = $('#trans_type').val();
    // alert(v);
    $('.transtype_details').hide();
    if(v == 1){
        $('#eatary_div').show();
        $('#eid_label').html('To');
        $('#from_store_div').show();
    }else if(v == 6){
        $('#suppliers_div').show();
        $('#supp_label').html('To');
        $('#from_store_div').show();
    }else if(v == 9){
        $('#kit_div').show();
        $('#kit_label').html('To');
        $('#from_store_div').show();
    }else if(v == 11){
        $('#eatary_div').show();
        $('#eid_label').html('From');
        $('#to_store_div').show();
    }else if(v == 16){
        $('#suppliers_div').show();
        $('#supp_label').html('From');
        $('#to_store_div').show();
    }else if(v == 19){
        $('#kit_div').show();
        $('#kit_label').html('From');
        $('#to_store_div').show();
    }else if(v == 25){
        // $('#eatary_div').show();
    }else if(v == 26){
        
    }else if(v == 27){
        // alert("sss");
        $('.rate').attr('readonly', "")
        $('#from_adjust_store_div').show();
    }
}
</script>