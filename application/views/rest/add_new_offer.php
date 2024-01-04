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
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('schemeName');?></label>
                                                        <input type="text" id="schnm" name="SchNm" class="form-control form-control-sm" required="" />
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('schemeType');?></label>
                                                        <select class="form-control form-control-sm" id="sch_typ" name="SchTyp" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                                foreach ($sch_typ as $key) {?>
                                                                    <option value="<?= $key['SchCatg']; ?>"><?= $key['Name']; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('schemeCategory');?></label>
                                                        <select class="form-control form-control-sm" id="schcatg" name="SchCatg" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                                foreach ($sch_cat as $key) {?>
                                                                    <option value="<?= $key['SchCatg']; ?>"><?= $key['Name']; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('fromDate');?></label>
                                                        <input type="date" name="FrmDt" class="form-control form-control-sm" id="from_date" value="<?php echo date('Y-m-d'); ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('toDate');?></label>
                                                        <input type="date" name="ToDt" class="form-control form-control-sm" id="to_date" value="<?php echo date('Y-m-d'); ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('fromDay');?></label>
                                                        <select class="form-control form-control-sm" id="from_day" name="FromDayNo" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                                foreach ($weekDay as $key) {?>
                                                                    <option value="<?= $key['DayNo']; ?>"><?= $key['Name'];?></option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('toDay');?></label>
                                                        <select class="form-control form-control-sm" id="to_day" name="ToDayNo" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                                foreach ($weekDay as $key) {?>
                                                                    <option value="<?= $key['DayNo']; ?>"><?= $key['Name'];?></option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('fromTime');?></label>
                                                        <input type="time" name="FrmTime" class="form-control form-control-sm" id="from_time" />
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('toTime');?></label>
                                                        <input type="time" name="ToTime" class="form-control form-control-sm" id="to_time" />
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('alternateFromTime');?></label>
                                                        <input type="time" name="AltFrmTime" class="form-control form-control-sm" id="alt_from_time" />
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('alternateToTime');?></label>
                                                        <input type="time" name="AltToTime" class="form-control form-control-sm" id="alt_to_time" />
                                                    </div>
                                                </div>

                                            </div>

                                            <button type="button" class="btn btn-primary btn-sm" onclick="add_more_description()" id="add_desc"><?= $this->lang->line('addMoreOfferDetails');?></button>
                                            <div class="offer_descriptions" id="offer_descriptions">
                                               
                                            </div>
            
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('submit');?>">
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

    var num_desc = 0;
    function add_description(){
        $('#description'+num_desc).show();
        $('.form-group').show();
        $('#add_desc').hide();
        $('#add_more').show();
    }

    function add_more_description(){
        num_desc++;
        var v = '<div class="description'+num_desc+'" id="description'+num_desc+'">\
                    <hr>\
                    <div class="text-center">\
                        <h3><?= $this->lang->line('offerDetails');?> - '+num_desc+'</h3>\
                    </div>\
                    <div class="row">\
                        <div class="form-group col-md-3 col-6">\
                            <label for="description'+num_desc+'_description"><?= $this->lang->line('description');?></label>\
                                <input type="text" name="description[]" class="form-control form-control-sm" id="description'+num_desc+'_description" maxlength="100" />\
                        </div>\
                        <div class="form-group col-md-3 col-6">\
                            <label for="description'+num_desc+'_image"><?= $this->lang->line('image');?></label>\
                            <input type="file" name="description_image[]" class="form-control form-control-sm" id="description'+num_desc+'_image" />\
                        </div>\
                        <div class="form-group col-md-3 col-6">\
                            <label for="description'+num_desc+'_cid"><?= $this->lang->line('cuisine');?></label>\
                            <select class="form-control form-control-sm" id="description'+num_desc+'_cid" name="description_cid[]" onchange="getCategory(this,'+num_desc+')">\
                                <option value=""><?= $this->lang->line('select');?></option>\
                                <?php foreach($cuisines as $key){?>
                                        <option value="<?= $key['CID']?>"><?= $key['Name']?></option>\
                                <?php } ?>
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_mcatgid_div" style="display: block;">\
                            <label for="description'+num_desc+'_mcatgid"><?= $this->lang->line('menuCategory');?></label>\
                            <select class="form-control form-control-sm" id="description'+num_desc+'_mcatgid" name="description_mcatgid[]" onchange="getItems(this, '+num_desc+')">\
                            <option value=""><?= $this->lang->line('select');?></option>\
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_itemtyp_div" style="display: block;">\
                            <label for="description'+num_desc+'_itemtyp"><?= $this->lang->line('foodType');?></label>\
                                <select class="form-control form-control-sm" id="description'+num_desc+'_itemtyp" name="description_itemtyp[]" onchange="getItems(this, '+num_desc+')">\
                                    <option value=""><?= $this->lang->line('select');?></option>\
                                    <?php foreach($foodType as $key){?>
                                        <option value="<?= $key['CTyp']?>"><?= $key['Usedfor']?></option>\
                                    <?php } ?>
                                </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_item_div" style="display: block;">\
                            <label for="description'+num_desc+'_item"><?= $this->lang->line('item');?></label>\
                            <select class="form-control form-control-sm" id="description'+num_desc+'_item" name="description_item[]" onchange="getItemPortion(this, '+num_desc+')">\
                            <option value=""><?= $this->lang->line('select');?></option>\
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_itemportion_div" style="display: block;">\
                            <label for="description'+num_desc+'_itemportion"><?= $this->lang->line('itemPortion');?></label>\
                            <select class="form-control form-control-sm" id="description'+num_desc+'_itemportion" name="description_itemportion[]">\
                                <option value=""><?= $this->lang->line('select');?></option>\
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_quantity_div" style="display: block;">\
                            <label for="description'+num_desc+'_quantity"><?= $this->lang->line('quantity');?></label>\
                            <input type="number" class="form-control form-control-sm" id="description'+num_desc+'_quantity" name="description_quantity[]" value="0">\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description1_discountitem_div" style="display: block;">\
                            <label for="description'+num_desc+'_discountitem"><?= $this->lang->line('discountItem');?></label>\
                            <select class="form-control form-control-sm select2 custom-select discountItems" id="description'+num_desc+'_discountitem" name="description_discountitem[]" onchange="getDiscItemPortion(this, '+num_desc+')">\
                                <option value="0">Select Item</option>\
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_discountitemportion_div" style="display: block;">\
                            <label for="description'+num_desc+'_discountitemportion"><?= $this->lang->line('discountItemPortion');?></label>\
                            <select class="form-control form-control-sm" id="description'+num_desc+'_discountitemportion" name="description_discountitemportion[]">\
                                <option value="0"><?= $this->lang->line('select');?></option>\
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_discountquantity_div" style="display: block;">\
                            <label for="description'+num_desc+'_discountquantity"><?= $this->lang->line('discountItemQuantity');?></label>\
                            <input type="number" class="form-control form-control-sm" id="description'+num_desc+'_discountquantity" name="description_discountquantity[]" value="0">\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_discountitempercentage_div" style="display: block;">\
                            <label for="description'+num_desc+'discountitempercentage"><?= $this->lang->line('discountItemPercentage');?></label>\
                            <input type="number" class="form-control form-control-sm" id="description'+num_desc+'_discountitempercentage" name="description_discountitempercentage[]" value="0">\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_minbillamount_div" style="display: block;">\
                            <label for="description'+num_desc+'_minbillamount"><?= $this->lang->line('minimumBillAmount');?></label>\
                            <input type="number" class="form-control form-control-sm" id="description'+num_desc+'_minbillamount" name="description_minbillamount[]" value="0">\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_discountpercent_div" style="display: block;">\
                            <label for="description'+num_desc+'_discountpercent"><?= $this->lang->line('discountPercentage');?></label>\
                            <input type="number" class="form-control form-control-sm" id="description'+num_desc+'_discountpercent" name="description_discountpercent[]" value="0">\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_discountamount_div" style="display: block;">\
                            <label for="description'+num_desc+'_discountamount"><?= $this->lang->line('discountAmount');?></label>\
                            <input type="number" class="form-control form-control-sm" id="description'+num_desc+'_discountamount" name="description_discountamount[]" value="0">\
                        </div>\
                    </div>\
                </div>';
        $('#offer_descriptions').append(v);

        $('.discountItems').select2();
        getDiscountItems(num_desc);

        // alert(v);
        // ss();
    }
    
    function getCategory(el, n){
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
                var b = '<option value="0"><?= $this->lang->line('all'); ?></option>';
                for(i= 0;i<res.length;i++){
                    b += '<option value="'+res[i].MCatgId+'">'+res[i].MCatgNm+'</option>';
                }
                $('#description'+n+'_mcatgid').html(b);
            }
        })
    }
    function getItems(el, n){
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
                    var b = '<option value="0"><?= $this->lang->line('all'); ?></option>';
                    for(i= 0;i<res.length;i++){
                        b += '<option value="'+res[i].ItemId+'">'+res[i].ItemNm+'</option>';
                    }
                    $('#description'+n+'_item').html(b);
                    // $('#description'+n+'_discountitem').append(b);
                }
            })
        }
    }

    function getDiscountItems(n){
        $.ajax({
            url: '<?php echo base_url('restaurant/offer_ajax'); ?>',
            type: 'post',
            data: {
                getAllItemsList: 1,
            },
            success: function(response) {
                var res = JSON.parse(response);
                
                var b1 = '<option value="0"><?= $this->lang->line('select'); ?></option>';
                for(i= 0;i<res.length;i++){
                    b1 += '<option value="'+res[i].ItemId+'">'+res[i].ItemNm+'</option>';
                }

                $('#description'+n+'_discountitem').html(b1);
            }
        })
    }

    function getItemPortion(el, n){
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
                var b = '<option value="0"><?= $this->lang->line('all'); ?></option>';
                for(i= 0;i<res.length;i++){
                    b += '<option value="'+res[i].IPCd+'">'+res[i].Name+'</option>';
                }
                $('#description'+n+'_itemportion').html(b);
            }
        })
    }

    function getDiscItemPortion(el, n){
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
                var b = '<option value="0"><?= $this->lang->line('all'); ?></option>';
                for(i= 0;i<res.length;i++){
                    b += '<option value="'+res[i].IPCd+'">'+res[i].Name+'</option>';
                }
                // alert(b);
                $('#description'+n+'_discountitemportion').html(b);
            }
        })
    }
</script>