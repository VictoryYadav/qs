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

                                    <a class="btn btn-sm btn-primary float-right" href="<?php echo base_url('restaurant/new_offer'); ?>">
                                                <i class="fa fa-plus"></i> New Offer
                                            </a>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-right p-2"><button class="btn btn-danger btn-sm" onclick="delete_offer(<?= $SchCd?>)" style="cursor: pointer;">Delete Scheme</button></div>
                                        <form method="post" action="<?php echo base_url('restaurant/offer_ajax'); ?>" enctype="multipart/form-data">
                                            <input type="hidden" name="updateOffer" value="1">
                                            
                                            <input type="hidden" name="SchCd" value="<?= $SchCd?>">
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label for="schnm">Scheme Name</label>
                                                    <input type="text" id="schnm" name="SchNm" class="form-control" placeholder="Enter scheme name" required="" value="<?= $scheme[0]['SchNm']?>" />
                                                </div>
                                                <!-- <div class="form-group col-4">
                                                    <label for="schdesc">Scheme Description</label>
                                                    <textarea type="text" id="schdesc" name="SchDesc" class="form-control" placeholder="Enter scheme desription" required="" cols="10" ></textarea>
                                                </div> -->
                                                <div class="form-group col-md-4 col-6">
                                                    <label for="sch_typ">Scheme Type</label>
                                                    <select class="form-control" id="sch_typ" name="SchTyp" required="">
                                                        <option value="">Select Scheme Type</option>
                                                        <?php 
                                                            foreach ($sch_typ as $key => $value) {?>
                                                                <option value="<?= $key?>" <?php if($scheme[0]['SchTyp'] == $key){echo 'selected';} ?>><?= $value;?></option>
                                                            
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4 col-6">
                                                    <label for="schcatg">Scheme Category</label>
                                                    <select class="form-control" id="schcatg" name="SchCatg" required="">
                                                        <option value="">Select Scheme Category</option>
                                                        <?php 
                                                            foreach ($sch_cat as $key => $value) {?>
                                                                <option value="<?= $key?>" <?php if($scheme[0]['SchCatg'] == $key){echo 'selected';} ?>><?= $value;?></option>
                                                            
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4 col-6">
                                                    <label for="from_day">From Day</label>
                                                    <select class="form-control" id="from_day" name="FromDayNo" required="">
                                                        <option value="">Select From Day</option>
                                                        <?php 
                                                            foreach ($days as $key => $value) {?>
                                                                <option value="<?= $key?>" <?php if($scheme[0]['FrmDayNo'] == $key){echo 'selected';} ?>><?= $value;?></option>
                                                            
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4 col-6">
                                                    <label for="to_day">To Day</label>
                                                    <select class="form-control" id="to_day" name="ToDayNo" required="">
                                                        <option value="">Select To Day</option>
                                                        <?php 
                                                            foreach ($days as $key => $value) {?>
                                                                <option value="<?= $key?>" <?php if($scheme[0]['ToDayNo'] == $key){echo 'selected';} ?>><?= $value;?></option>
                                                            
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4 col-6">
                                                    <label for="from_time">From Time</label>
                                                    <input type="time" name="FrmTime" class="form-control" id="from_time" <?php if(!empty($scheme[0]['FrmTime'])){?> value="<?= $scheme[0]['FrmTime']?>" <?php }?> />
                                                </div>
                                                <div class="form-group col-md-4 col-6">
                                                    <label for="to_time">To Time</label>
                                                    <input type="time" name="ToTime" class="form-control" id="to_time" <?php if(!empty($scheme[0]['ToTime'])){?> value="<?= $scheme[0]['ToTime']?>" <?php }?> />
                                                </div>
                                                <div class="form-group col-md-4 col-6">
                                                    <label for="alt_from_time">Alternate From Time</label>
                                                    <input type="time" name="AltFrmTime" class="form-control" id="alt_from_time" <?php if(!empty($scheme[0]['AltFrmTime'])){?> value="<?= $scheme[0]['AltFrmTime']?>" <?php }?> />
                                                </div>
                                                <div class="form-group col-md-4 col-6">
                                                    <label for="alt_to_time">Alternate To Time</label>
                                                    <input type="time" name="AltToTime" class="form-control" id="alt_to_time" <?php if(!empty($scheme[0]['ToTime'])){?> value="<?= $scheme[0]['ToTime']?>" <?php }?> />
                                                </div>
                                                <div class="form-group col-md-4 col-6">
                                                    <label for="from_date">From Date</label>
                                                    <input type="date" name="FrmDt" class="form-control" id="from_date" <?php if(!empty($scheme[0]['FrmDt'])){?> value="<?= $scheme[0]['FrmDt']?>" <?php }?> />
                                                </div>
                                                <div class="form-group col-md-4 col-6">
                                                    <label for="to_date">To Date</label>
                                                    <input type="date" name="ToDt" class="form-control" id="to_date" <?php if(!empty($scheme[0]['ToDt'])){?> value="<?= $scheme[0]['ToDt']?>" <?php }?> />
                                                </div>
                                            </div>
                                            <div class="offer_descriptions" id="offer_descriptions">
                                                <?php $n = 1;foreach($descriptions as $key){?>

                                                    <div class="description1" id="description1" style="display: block;">
                                                        <hr>
                                                        <input type="hidden" name="SDetCd[]" value="<?= $key['SDetCd']?>">
                                                        <div class="text-center"><h3>Offer Description - <?= $n?> <span  onclick="delete_offer_description(<?= $key['SDetCd']?>)" style="cursor: pointer;"><i class="fa fa-trash" style="color: red;"></i></span></h3></div>
                                                        <div class="row">
                                                            <div class="form-group col-md-4 col-6">
                                                                <label for="description1_description">Description</label>
                                                                <input type="text" name="description[]" class="form-control" id="description<?= $n?>_description" maxlength="100" placeholder="Enter Scheme Description" value="<?= $key['SchDesc']?>" />
                                                            </div>
                                                            <div class="form-group col-md-4 col-6">
                                                                <label for="description1_image">Image</label>
                                                                <input type="file" name="description_image[]" class="form-control" id="description<?= $n?>_image" />
                                                            </div>
                                                            <div class="form-group col-md-4 col-6">
                                                                <label for="description1_cid">CID</label>
                                                                <select class="form-control" id="description<?= $n?>_cid" name="description_cid[]" onchange="getCategory(<?= $n?>)">
                                                                    <option value="">Select Cuisine</option>
                                                                    <?php 
                                                                        foreach($cuisines as $c){?>
                                                                            <option value="<?= $c['CID']?>" <?php if($key['CID'] == $c['CID']){echo 'selected';}?>><?= $c['Name']?></option>
                                                                    <?php }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-4 col-6" id="description<?= $n?>_mcatgid_div" style="display: block;">
                                                                <label for="description<?= $n?>_mcatgid">Menu Category</label>
                                                                <input type="hidden" name="" id="mcatgid<?= $n?>" value="<?= $key['MCatgId']?>">
                                                                <select class="form-control" id="description<?= $n?>_mcatgid" name="description_mcatgid[]">
                                                                    <option value="">Select Menu Category</option>
                                                                    
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-4 col-6" id="description<?= $n?>_itemtyp_div" style="display: block;">
                                                                <label for="description<?= $n?>_itemtyp">Item Type</label>
                                                                <select class="form-control" id="description<?= $n?>_itemtyp" name="description_itemtyp[]" onchange="getItems(<?= $n?>)">
                                                                    <option value="">Select Item Type</option>
                                                                    <?php foreach($item_types as $it){?>
                                                                        <option value="<?= $it['ItmTyp']?>" <?php if($it['ItmTyp'] == $key['ItemTyp']){echo 'selected';}?>><?= $it['Name']?></option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-4 col-6" id="description<?= $n?>_item_div" style="display: block;">
                                                                <label for="description<?= $n?>_item">Item</label>
                                                                <input type="hidden" name="" id="item_id<?= $n?>" value="<?= $key['ItemId']?>">
                                                                <select class="form-control" id="description<?= $n?>_item" name="description_item[]" onchange="getItemPortion(<?= $n?>)">
                                                                    <option value="">Select Item</option>
                                                                    
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-4 col-6" id="description<?= $n?>_itemportion_div" style="display: block;">
                                                                <label for="description<?= $n?>_itemportion">Item Portion</label>
                                                                <input type="hidden" name="" id="ipcd<?= $n?>" value="<?= $key['IPCd']?>">
                                                                <select class="form-control" id="description<?= $n?>_itemportion" name="description_itemportion[]">
                                                                    <option value="">Select Item Portion</option>
                                                                    
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-4 col-6" id="description<?= $n?>_quantity_div" style="display: block;">
                                                                <label for="description<?= $n?>_quantity">Quantity</label>
                                                                <input type="number" class="form-control" id="description<?= $n?>_quantity" name="description_quantity[]" value="<?= $key['Qty']?>">
                                                            </div>
                                                            <div class="form-group col-md-4 col-6" id="description<?= $n?>_discountitem_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountitem">Discount Item</label>
                                                                <input type="hidden" name="" id="disc_item_id<?= $n?>" value="<?= $key['Disc_ItemId']?>">
                                                                <select class="form-control" id="description<?= $n?>_discountitem" name="description_discountitem[]" onchange="getDiscItemPortion(<?= $n?>)">
                                                                    <option value="">Select Item</option>
                                                                    
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="form-group col-md-4 col-6" id="description<?= $n?>_discountitemportion_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountitemportion">Discount Item Portion</label>
                                                                <input type="hidden" name="" id="disc_ipcd<?= $n?>" value="<?= $key['Disc_IPCd']?>">
                                                                <select class="form-control" id="description<?= $n?>_discountitemportion" name="description_discountitemportion[]">
                                                                    <option value="">Select Discount Item Portion</option>
                                                                    
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-4 col-6" id="description<?= $n?>_discountquantity_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountquantity">Discount Item Quantity</label>
                                                                <input type="number" class="form-control" id="description<?= $n?>_discountquantity" name="description_discountquantity[]" value="<?= $key['Disc_Qty']?>">
                                                            </div>
                                                            <div class="form-group col-md-4 col-6" id="description<?= $n?>_minbillamount_div" style="display: block;">
                                                                <label for="description<?= $n?>_minbillamount">Minimum Bill Amount</label>
                                                                <input type="number" class="form-control" id="description<?= $n?>_minbillamount" name="description_minbillamount[]" value="<?= $key['MinBillAmt']?>">
                                                            </div>
                                                            <div class="form-group col-md-4 col-6" id="description<?= $n?>_discountpercent_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountpercent">Discount Percentage</label>
                                                                <input type="number" class="form-control" id="description<?= $n?>_discountpercent" name="description_discountpercent[]" value="<?= $key['Disc_pcent']?>">
                                                            </div>
                                                            <div class="form-group col-md-4 col-6" id="description<?= $n?>_discountamount_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountamount">Discount Amount</label>
                                                                <input type="number" class="form-control" id="description<?= $n?>_discountamount" name="description_discountamount[]" value="<?= $key['Disc_Amt']?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php $n++;}?>
                                            </div>
                                            <div class="text-center"><button type="button" class="btn btn-primary btn-sm" onclick="add_more_description()" id="add_more" style="display: block;">Add More Descriptions</button>&nbsp;&nbsp;<button type="submit" class="btn btn-primary btn-sm" >Submit</button></div>
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
    var num_desc = <?= sizeof($descriptions)?>;
    for(i=1;i<=num_desc;i++){
        getCategory(i);
    }
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
        var v = '<div class="description'+num_desc+'" id="description'+num_desc+'"><hr><input type="hidden" name="SDetCd[]" value=""><div class="text-center"><h3>Offer Description - '+num_desc+'</h3></div><div class="row"><div class="form-group col-6"> <label for="description'+num_desc+'_description">Description</label><input type="text" name="description[]" class="form-control" id="description'+num_desc+'_description" maxlength="100" /></div><div class="form-group col-6"> <label for="description'+num_desc+'_image">Image</label> <input type="file" name="description_image[]" class="form-control" id="description'+num_desc+'_image" /></div><div class="form-group col-6"> <label for="description'+num_desc+'_cid">CID</label> <select class="form-control" id="description'+num_desc+'_cid" name="description_cid[]" onchange="getCategory2(this, '+num_desc+')"><option value="">Select Cuisine</option> <?php foreach($cuisines as $key){?><option value="<?= $key['CID']?>"><?= $key['Name']?></option> <?php } ?> </select></div><div class="form-group col-6" id="description'+num_desc+'_mcatgid_div" style="display: block;"> <label for="description'+num_desc+'_mcatgid">Menu Category</label> <select class="form-control" id="description'+num_desc+'_mcatgid" name="description_mcatgid[]"><option value="">Select Menu Category</option> </select></div><div class="form-group col-6" id="description'+num_desc+'_itemtyp_div" style="display: block;"> <label for="description'+num_desc+'_itemtyp">Item Type</label> <select class="form-control" id="description'+num_desc+'_itemtyp" name="description_itemtyp[]" onchange="getItems2(this, '+num_desc+')"><option value="">Select Item Type</option><?php foreach($item_types as $key){?><option value="<?= $key['ItmTyp']?>"><?= $key['Name']?></option><?php }?> </select></div><div class="form-group col-6" id="description'+num_desc+'_item_div" style="display: block;"> <label for="description'+num_desc+'_item">Item</label> <select class="form-control" id="description'+num_desc+'_item" name="description_item[]" onchange="getItemPortion2(this, '+num_desc+')"><option value="">Select Item</option> </select></div><div class="form-group col-6" id="description'+num_desc+'_itemportion_div" style="display: block;"> <label for="description'+num_desc+'_itemportion">Item Portion</label> <select class="form-control" id="description'+num_desc+'_itemportion" name="description_itemportion[]"><option value="">Select Item Portion</option> </select></div><div class="form-group col-6" id="description'+num_desc+'_quantity_div" style="display: block;"> <label for="description'+num_desc+'_quantity">Quantity</label> <input type="number" class="form-control" id="description'+num_desc+'_quantity" name="description_quantity[]" value="0"></div><div class="form-group col-6" id="description1_discountitem_div" style="display: block;"><label for="description'+num_desc+'_discountitem">Discount Item</label><select class="form-control" id="description'+num_desc+'_discountitem" name="description_discountitem[]" onchange="getDiscItemPortion2(this, '+num_desc+')"><option value="0">Select Item</option></select></div><div class="form-group col-6" id="description'+num_desc+'_discountitemportion_div" style="display: block;"> <label for="description'+num_desc+'_discountitemportion">Discount Item Portion</label> <select class="form-control" id="description'+num_desc+'_discountitemportion" name="description_discountitemportion[]"><option value="0">Select Discount Item Portion</option> </select></div><div class="form-group col-6" id="description'+num_desc+'_discountquantity_div" style="display: block;"><label for="description'+num_desc+'_discountquantity">Discount Item Quantity</label><input type="number" class="form-control" id="description'+num_desc+'_discountquantity" name="description_discountquantity[]" value="0"></div><div class="form-group col-6" id="description'+num_desc+'_minbillamount_div" style="display: block;"> <label for="description'+num_desc+'_minbillamount">Minimum Bill Amount</label> <input type="number" class="form-control" id="description'+num_desc+'_minbillamount" name="description_minbillamount[]" value="0"></div><div class="form-group col-6" id="description'+num_desc+'_discountpercent_div" style="display: block;"> <label for="description'+num_desc+'_discountpercent">Discount Percentage</label> <input type="number" class="form-control" id="description'+num_desc+'_discountpercent" name="description_discountpercent[]" value="0"></div><div class="form-group col-6" id="description'+num_desc+'_discountamount_div" style="display: block;"> <label for="description'+num_desc+'_discountamount">Discount Amount</label> <input type="number" class="form-control" id="description'+num_desc+'_discountamount" name="description_discountamount[]" value="0"></div></div></div>';
        $('#offer_descriptions').append(v);

        // alert(v);
        // ss();
    }
    function getCategory(n){
        var cid = $('#description'+n+'_cid').val();
        // alert(cid);
        var mcatgid = $('#mcatgid'+n).val();
        $.ajax({
            url: '<?php echo base_url('restaurant/offer_ajax'); ?>',
            type: 'post',
            data: {
                getCategory: 1,
                cid: cid
            },
            success: function(response) {
                var res = JSON.parse(response);
                var b = '';
                for(i= 0;i<res.length;i++){
                    var ch = '';
                    if(mcatgid == res[i].MCatgId){
                        ch = 'selected';
                    }
                    // alert(ch);
                    b += '<option value="'+res[i].MCatgId+'" '+ch+'>'+res[i].MCatgNm+'</option>';
                }
                $('#description'+n+'_mcatgid').append(b);
                getItems(n);
            }
        })
    }
    function getItems(n){
        var cat = $('#description'+n+'_mcatgid').val();
        var it = $('#description'+n+'_itemtyp').val();
        var item = $('#item_id'+n).val();
        var item1 = $('#disc_item_id'+n).val();
        // alert(item);
        if(cat != ''){
            $.ajax({
                url: '<?php echo base_url('restaurant/offer_ajax'); ?>',
                type: 'post',
                data: {
                    getItems: 1,
                    item_typ: it,
                    mcatgid: cat
                },
                success: function(response) {
                    var res = JSON.parse(response);
                    var b = '';
                    var b1='';
                    for(i= 0;i<res.length;i++){
                        var ch = '';
                        if(item == res[i].ItemId){
                            ch = 'selected';
                        }
                        var ch1 = '';
                        if(item1 == res[i].ItemId){
                            ch1 = 'selected';
                        }
                        b += '<option value="'+res[i].ItemId+'" '+ch+'>'+res[i].ItemNm+'</option>';
                        b1 += '<option value="'+res[i].ItemId+'" '+ch1+'>'+res[i].ItemNm+'</option>';
                    }
                    $('#description'+n+'_item').append(b);
                    $('#description'+n+'_discountitem').append(b1);
                    getItemPortion(n);
                    getDiscItemPortion(n);
                }
            })
        }
    }
    function getItemPortion(n){
        var item_id = $('#description'+n+'_item').val();
        var ip = $('#ipcd'+n).val();
        // alert(item_id);
        $.ajax({
            url: '<?php echo base_url('restaurant/offer_ajax'); ?>',
            type: 'post',
            data: {
                getItemPortion: 1,
                item_id: item_id
            },
            success: function(response) {
                var res = JSON.parse(response);
                var b = '';
                for(i= 0;i<res.length;i++){
                    var ch = '';
                    if(ip == res[i].IPCd){
                        ch = 'selected';
                    }
                    b += '<option value="'+res[i].IPCd+'" '+ch+'>'+res[i].Name+'</option>';
                }
                $('#description'+n+'_itemportion').append(b);
                // getDiscItemPortion(1);
            }
        })
    }
    function getDiscItemPortion(n){
        var item_id = $('#description'+n+'_discountitem').val();
        var ip = $('#disc_ipcd'+n).val();
        // alert(item_id);
        $.ajax({
            url: '<?php echo base_url('restaurant/offer_ajax'); ?>',
            type: 'post',
            data: {
                getItemPortion: 1,
                item_id: item_id
            },
            success: function(response) {
                var res = JSON.parse(response);
                var b = '';
                for(i= 0;i<res.length;i++){
                    var ch = '';
                    // alert(ip);alert(res[i].IPCd);
                    if(ip == res[i].IPCd){
                        ch = 'selected';
                    }
                    // alert(ch);
                    b += '<option value="'+res[i].IPCd+'" '+ch+'>'+res[i].Name+'</option>';
                }
                // alert(b);
                $('#description'+n+'_discountitemportion').append(b);
            }
        })
    }
    function getCategory2(el, n){
        var cid = el.value;
        $.ajax({
            url: '<?php echo base_url('restaurant/offer_ajax'); ?>',
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
    function getItems2(el, n){
        var cat = $('#description'+n+'_mcatgid').val();
        if(cat != ''){
            $.ajax({
                url: '<?php echo base_url('restaurant/offer_ajax'); ?>',
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
    function getItemPortion2(el, n){
        var item_id = el.value;
        $.ajax({
            url: '<?php echo base_url('restaurant/offer_ajax'); ?>',
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
    function getDiscItemPortion2(el, n){
        var item_id = el.value;
        $.ajax({
            url: '<?php echo base_url('restaurant/offer_ajax'); ?>',
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
    function delete_offer_description(id){
        if(confirm("Are you sure want to delete the scheme description?")){
            $.ajax({
                url: '<?php echo base_url('restaurant/offer_ajax'); ?>',
                type: 'post',
                data: {
                    delete_offer_description: 1,
                    SDetCd: id
                },
                success: function(response) {
                    if(response == 1){
                        alert("Successfully deleted");
                        location.reload();
                    }else{
                        alert("Something went wrong");
                    }
                }
            });
        }
    }
    function delete_offer(id){
        if(confirm("Are you sure want to delete the scheme?")){
            $.ajax({
                url: "<?php echo base_url('restaurant/offer_ajax'); ?>",
                type: "post",
                data: {'SchCd':id, 'delete_offer':1},
                success: response => {
                    // console.log(response);
                    if (response == 1) {
                        alert("Scheme Successfully Deleted.");
                        window.location.href = "offers_list.php";
                    } else {
                        alert(response);
                    }
                },
                error: (xhr, status, error) => {
                    
                }
            });
        }
    }
</script>