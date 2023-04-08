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
                            <div class="col-md-8 mx-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" action="<?php echo base_url('restorent/offer_ajax'); ?>" enctype="multipart/form-data">
                                            <input type="hidden" name="addOffer" value="1">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Scheme Name</label>
                                                        <input type="text" id="schnm" name="SchNm" class="form-control" placeholder="Enter scheme name" required="" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Scheme Type</label>
                                                        <select class="form-control" id="sch_typ" name="SchTyp" required="">
                                                            <option value="">Select Scheme Type</option>
                                                            <?php 
                                                                foreach ($sch_typ as $key => $value) {?>
                                                                    <option value="<?= $key?>"><?= $value;?></option>
                                                                
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Scheme Category</label>
                                                        <select class="form-control" id="schcatg" name="SchCatg" required="">
                                                            <option value="">Select Scheme Category</option>
                                                            <?php 
                                                                foreach ($sch_cat as $key => $value) {?>
                                                                    <option value="<?= $key?>"><?= $value;?></option>
                                                                
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>From Day</label>
                                                        <select class="form-control" id="from_day" name="FromDayNo" required="">
                                                            <option value="">Select From Day</option>
                                                            <?php 
                                                                foreach ($days as $key => $value) {?>
                                                                    <option value="<?= $key?>"><?= $value;?></option>
                                                                
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>To Day</label>
                                                        <select class="form-control" id="to_day" name="ToDayNo" required="">
                                                            <option value="">Select To Day</option>
                                                            <?php 
                                                                foreach ($days as $key => $value) {?>
                                                                    <option value="<?= $key?>"><?= $value;?></option>
                                                                
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>From Time</label>
                                                        <input type="time" name="FrmTime" class="form-control" id="from_time" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>To Time</label>
                                                        <input type="time" name="ToTime" class="form-control" id="to_time" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Alternate From Time</label>
                                                        <input type="time" name="AltFrmTime" class="form-control" id="alt_from_time" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Alternate To Time</label>
                                                        <input type="time" name="AltToTime" class="form-control" id="alt_to_time" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>From Date</label>
                                                        <input type="date" name="FrmDt" class="form-control" id="from_date" value="<?php echo date('Y-m-d'); ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>To Date</label>
                                                        <input type="date" name="ToDt" class="form-control" id="to_date" value="<?php echo date('Y-m-d'); ?>" />
                                                    </div>
                                                </div>

                                            </div>

                                            <button type="button" class="btn btn-primary" onclick="add_description()" id="add_desc">Add Descriptions</button>
                                            <div class="offer_descriptions" id="offer_descriptions">
                                                <div class="description1" id="description1" style="display: none;">
                                                    <hr>
                                                    <div class="text-center"><h3>Offer Description - 1</h3></div>
                                                    <div class="row">
                                                        <div class="form-group col-6">
                                                            <label for="description1_description">Description</label>
                                                            <input type="text" name="description[]" class="form-control" id="description1_description" maxlength="100" placeholder="Enter Scheme Description" />
                                                        </div>
                                                        <div class="form-group col-6">
                                                            <label for="description1_image">Image</label>
                                                            <input type="file" name="description_image" class="form-control" id="description1_image" />
                                                        </div>
                                                        <div class="form-group col-6">
                                                            <label for="description1_cid">CID</label>
                                                            <select class="form-control" id="description1_cid" name="description_cid[]" onchange="getCategory(this, 1)">
                                                                <option value="">Select Cuisine</option>
                                                                <?php 
                                                                    foreach($cuisines as $key){?>
                                                                        <option value="<?= $key['CID']?>"><?= $key['Name']?></option>
                                                                <?php }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-6" id="description1_mcatgid_div" style="display: none;">
                                                            <label for="description1_mcatgid">Menu Category</label>
                                                            <select class="form-control" id="description1_mcatgid" name="description_mcatgid[]">
                                                                <option value="">Select Menu Category</option>
                                                                
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-6" id="description1_itemtyp_div" style="display: none;">
                                                            <label for="description1_itemtyp">Item Type</label>
                                                            <select class="form-control" id="description1_itemtyp" name="description_itemtyp[]" onchange="getItems(this, 1)">
                                                                <option value="">Select Item Type</option>
                                                                <?php foreach($item_types as $key){?>
                                                                    <option value="<?= $key['ItmTyp']?>"><?= $key['Name']?></option>
                                                                <?php }?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-6" id="description1_item_div" style="display: none;">
                                                            <label for="description1_item">Item</label>
                                                            <select class="form-control" id="description1_item" name="description_item[]" onchange="getItemPortion(this, 1)">
                                                                <option value="">Select Item</option>
                                                                
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-6" id="description1_itemportion_div" style="display: none;">
                                                            <label for="description1_itemportion">Item Portion</label>
                                                            <select class="form-control" id="description1_itemportion" name="description_itemportion[]">
                                                                <option value="">Select Item Portion</option>
                                                                
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-6" id="description1_quantity_div" style="display: none;">
                                                            <label for="description1_quantity">Quantity</label>
                                                            <input type="number" class="form-control" id="description1_quantity" name="description_quantity[]" value="0">
                                                        </div>
                                                        <div class="form-group col-6" id="description1_discountitem_div" style="display: none;">
                                                            <label for="description1_discountitem">Discount Item</label>
                                                            <select class="form-control" id="description1_discountitem" name="description_discountitem[]" onchange="getDiscItemPortion(this, 1)">
                                                                <option value="">Select Item</option>
                                                                
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="form-group col-6" id="description1_discountitemportion_div" style="display: none;">
                                                            <label for="description1_discountitemportion">Discount Item Portion</label>
                                                            <select class="form-control" id="description1_discountitemportion" name="description_discountitemportion[]">
                                                                <option value="">Select Discount Item Portion</option>
                                                                
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-6" id="description1_discountquantity_div" style="display: none;">
                                                            <label for="description1_discountquantity">Discount Item Quantity</label>
                                                            <input type="number" class="form-control" id="description1_discountquantity" name="description_discountquantity[]" value="0">
                                                        </div>
                                                        <div class="form-group col-6" id="description1_minbillamount_div" style="display: none;">
                                                            <label for="description1_minbillamount">Minimum Bill Amount</label>
                                                            <input type="number" class="form-control" id="description1_minbillamount" name="description_minbillamount[]" value="0">
                                                        </div>
                                                        <div class="form-group col-6" id="description1_discountpercent_div" style="display: none;">
                                                            <label for="description1_discountpercent">Discount Percentage</label>
                                                            <input type="number" class="form-control" id="description1_discountpercent" name="description_discountpercent[]" value="0">
                                                        </div>
                                                        <div class="form-group col-6" id="description1_discountamount_div" style="display: none;">
                                                            <label for="description1_discountamount">Discount Amount</label>
                                                            <input type="number" class="form-control" id="description1_discountamount" name="description_discountamount[]" value="0">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
            
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="Submit">
                                            </div>
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
    var num_desc = 1;
    function add_description(){
        $('#description'+num_desc).show();
        $('.form-group').show();
        $('#add_desc').hide();
        $('#add_more').show();
    }
    function ss(){
        $('form-group').show();
    }
    function add_more_description(){
        num_desc++;
        var v = '<div class="description'+num_desc+'" id="description'+num_desc+'"><hr><div class="text-center"><h3>Offer Description - '+num_desc+'</h3></div><div class="row"><div class="form-group col-6"> <label for="description'+num_desc+'_description">Description</label><input type="text" name="description[]" class="form-control" id="description'+num_desc+'_description" maxlength="100" /></div><div class="form-group col-6"> <label for="description'+num_desc+'_image">Image</label> <input type="file" name="description_image[]" class="form-control" id="description'+num_desc+'_image" /></div><div class="form-group col-6"> <label for="description'+num_desc+'_cid">CID</label> <select class="form-control" id="description'+num_desc+'_cid" name="description_cid[]" onchange="getCategory(this, '+num_desc+')"><option value="">Select Cuisine</option> <?php foreach($cuisines as $key){?><option value="<?= $key['CID']?>"><?= $key['Name']?></option> <?php } ?> </select></div><div class="form-group col-6" id="description'+num_desc+'_mcatgid_div" style="display: block;"> <label for="description'+num_desc+'_mcatgid">Menu Category</label> <select class="form-control" id="description'+num_desc+'_mcatgid" name="description_mcatgid[]"><option value="">Select Menu Category</option> </select></div><div class="form-group col-6" id="description'+num_desc+'_itemtyp_div" style="display: block;"> <label for="description'+num_desc+'_itemtyp">Item Type</label> <select class="form-control" id="description'+num_desc+'_itemtyp" name="description_itemtyp[]" onchange="getItems(this, '+num_desc+')"><option value="">Select Item Type</option><?php foreach($item_types as $key){?><option value="<?= $key['ItmTyp']?>"><?= $key['Name']?></option><?php }?> </select></div><div class="form-group col-6" id="description'+num_desc+'_item_div" style="display: block;"> <label for="description'+num_desc+'_item">Item</label> <select class="form-control" id="description'+num_desc+'_item" name="description_item[]" onchange="getItemPortion(this, '+num_desc+')"><option value="">Select Item</option> </select></div><div class="form-group col-6" id="description'+num_desc+'_itemportion_div" style="display: block;"> <label for="description'+num_desc+'_itemportion">Item Portion</label> <select class="form-control" id="description'+num_desc+'_itemportion" name="description_itemportion[]"><option value="">Select Item Portion</option> </select></div><div class="form-group col-6" id="description'+num_desc+'_quantity_div" style="display: block;"> <label for="description'+num_desc+'_quantity">Quantity</label> <input type="number" class="form-control" id="description'+num_desc+'_quantity" name="description_quantity[]" value="0"></div><div class="form-group col-6" id="description1_discountitem_div" style="display: block;"><label for="description'+num_desc+'_discountitem">Discount Item</label><select class="form-control" id="description'+num_desc+'_discountitem" name="description_discountitem[]" onchange="getDiscItemPortion(this, '+num_desc+')"><option value="0">Select Item</option></select></div><div class="form-group col-6" id="description'+num_desc+'_discountitemportion_div" style="display: block;"> <label for="description'+num_desc+'_discountitemportion">Discount Item Portion</label> <select class="form-control" id="description'+num_desc+'_discountitemportion" name="description_discountitemportion[]"><option value="0">Select Discount Item Portion</option> </select></div><div class="form-group col-6" id="description'+num_desc+'_discountquantity_div" style="display: block;"><label for="description'+num_desc+'_discountquantity">Discount Item Quantity</label><input type="number" class="form-control" id="description'+num_desc+'_discountquantity" name="description_discountquantity[]" value="0"></div><div class="form-group col-6" id="description'+num_desc+'_minbillamount_div" style="display: block;"> <label for="description'+num_desc+'_minbillamount">Minimum Bill Amount</label> <input type="number" class="form-control" id="description'+num_desc+'_minbillamount" name="description_minbillamount[]" value="0"></div><div class="form-group col-6" id="description'+num_desc+'_discountpercent_div" style="display: block;"> <label for="description'+num_desc+'_discountpercent">Discount Percentage</label> <input type="number" class="form-control" id="description'+num_desc+'_discountpercent" name="description_discountpercent[]" value="0"></div><div class="form-group col-6" id="description'+num_desc+'_discountamount_div" style="display: block;"> <label for="description'+num_desc+'_discountamount">Discount Amount</label> <input type="number" class="form-control" id="description'+num_desc+'_discountamount" name="description_discountamount[]" value="0"></div></div></div>';
        $('#offer_descriptions').append(v);

        // alert(v);
        // ss();
    }
    function getCategory(el, n){
        var cid = el.value;
        $.ajax({
            url: '<?php echo base_url('restorent/offer_ajax'); ?>',
            type: 'post',
            data: {
                getCategory: 1,
                cid: cid
            },
            success: function(response) {
                var res = JSON.parse(response);
                var b = '';
                for(i= 0;i<res.length;i++){
                    b += '<option value="'+res[i].MCatgId+'">'+res[i].MCatgNm+'</option>';
                }
                $('#description'+n+'_mcatgid').append(b);
            }
        })
    }
    function getItems(el, n){
        var cat = $('#description'+n+'_mcatgid').val();

        if(cat != ''){
            $.ajax({
                url: '<?php echo base_url('restorent/offer_ajax'); ?>',
                type: 'post',
                data: {
                    getItems: 1,
                    item_typ: el.value,
                    mcatgid: cat
                },
                success: function(response) {
                    var res = JSON.parse(response);
                    var b = '';
                    for(i= 0;i<res.length;i++){
                        b += '<option value="'+res[i].ItemId+'">'+res[i].ItemNm+'</option>';
                    }
                    $('#description'+n+'_item').append(b);
                    $('#description'+n+'_discountitem').append(b);
                }
            })
        }
    }
    function getItemPortion(el, n){
        var item_id = el.value;
        $.ajax({
            url: '<?php echo base_url('restorent/offer_ajax'); ?>',
            type: 'post',
            data: {
                getItemPortion: 1,
                item_id: item_id
            },
            success: function(response) {
                var res = JSON.parse(response);
                var b = '';
                for(i= 0;i<res.length;i++){
                    b += '<option value="'+res[i].IPCd+'">'+res[i].Name+'</option>';
                }
                $('#description'+n+'_itemportion').append(b);
            }
        })
    }
    function getDiscItemPortion(el, n){
        var item_id = el.value;
        $.ajax({
            url: '<?php echo base_url('restorent/offer_ajax'); ?>',
            type: 'post',
            data: {
                getItemPortion: 1,
                item_id: item_id
            },
            success: function(response) {
                var res = JSON.parse(response);
                var b = '';
                for(i= 0;i<res.length;i++){
                    b += '<option value="'+res[i].IPCd+'">'+res[i].Name+'</option>';
                }
                // alert(b);
                $('#description'+n+'_discountitemportion').append(b);
            }
        })
    }
</script>