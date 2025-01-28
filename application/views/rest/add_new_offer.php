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
                                                        <input type="text" id="schnm" name="SchNm1" class="form-control form-control-sm" required="" placeholder="English" />
                                                    </div>
                                                </div>

                                                <?php
                                                for ($i = 1; $i < sizeof($languages); $i++) { ?>
                                                   <div class="col-md-4 col-6">
                                                        <div class="form-group">
                                                            <label for=""><?= $languages[$i]['LngName']; ?></label>
                                                            <input type="text" id="schnm<?= $languages[$i]['LCd']; ?>" name="SchNm<?= $languages[$i]['LCd']; ?>" class="form-control form-control-sm"  />
                                                        </div>
                                                   </div>
                                               <?php } ?>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('schemeType');?></label>
                                                        <select class="form-control form-control-sm" id="sch_typ" name="SchTyp" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="1">Bill Based</option>
                                                            <option value="2">Item Based</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('schemeCategory');?></label>
                                                        <select class="form-control form-control-sm" id="schcatg" name="SchCatg" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('offers');?> <?= $this->lang->line('type');?></label>
                                                        <select name="offerType" class="form-control form-control-sm">
                                                            <option value=""><?= $this->lang->line('select');?></option>
                                                            <option value="1"><?= $this->lang->line('only').' '.$this->lang->line('food'); ?></option>
                                                            <option value="2"><?= $this->lang->line('food').' & '.$this->lang->line('bar'); ?></option>
                                                            <option value="3"><?= $this->lang->line('only').' '.$this->lang->line('bar'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-5 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('day');?></label>
                                                        <select class="form-control form-control-sm select2 mb-3 select2-multiple" id="days" name="days[]" required="" style="width: 100%;" multiple="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 

                                                                foreach ($weekDay as $key) {?>
                                                                    <option value="<?= $key['DayNo']; ?>" <?php if(in_array($key['DayNo'], array(1,2,3,4,5,6,7))){ echo 'selected'; } ?> ><?= $key['Name'];?></option>
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

                                                <!-- <div class="col-md-3 col-6">
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
                                                </div> -->

                                                <!-- <div class="col-md-3 col-6">
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
                                                </div> -->

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
    $('#days').select2();

    var num_desc = 0;
    function add_description(){
        $('#description'+num_desc).show();
        $('.form-group').show();
        $('#add_desc').hide();
        $('#add_more').show();
    }

    $('#sch_typ').on('change', function(e){
        e.preventDefault();

        var SchTyp = $(this).val();
        if(SchTyp > 0){
            $('.billBased').hide();
            $(`.forBill`).show();
            if(SchTyp == 1){
                $('.billBased').show();
                $(`.forBill`).hide();
            }

            $.post('<?= base_url('restaurant/get_schemes') ?>',{SchTyp:SchTyp},function(res){
                if(res.status == 'success'){
                  if(res.response.length > 0){
                    var opt = `<option value="">Select</option>`;
                    res.response.forEach((item, index) =>{
                        opt += `<option value="${item.SchCatg}">${item.Name}</option>`;
                    })
                    $(`#schcatg`).html(opt);
                  }
                }else{
                  alert(res.response);
                }
            });
        }
    });

    $(`#schcatg`).on('change', function(e){
        var schTyp = $(`#sch_typ`).val();
        var schCat = $(this).val();

        $('#add_desc').show();

        if(schTyp > 0){

            $('.itemType').prop("disabled", false);
            $('.itemSale').prop("disabled", false);
            $('.cuisine').prop("disabled", false);
            $('.menuCategory').prop("disabled", false);
            $('.items').prop("disabled", false);
            $('.itemPortion').prop("disabled", false);
            
            $('.disccuisine').prop("disabled", false);
            $('.discmenuCategory').prop("disabled", false);
            $('.discountItems').prop("disabled", false);
            $('.discitemType').prop("disabled", false);

            // bill based
            if(schTyp == 1){
                $('#add_desc').hide();

                $('.minbillamt').prop("disabled", false);
                $('.discmaxamt').prop("disabled", false);
                $('.discpercent').prop("disabled", false);
                $('.discamount').prop("disabled", false);

                switch (schCat) {
                    // bill discount amount
                    case '1':
                        $('.discmaxamt').prop("disabled", true);
                        $('.discpercent').prop("disabled", true);

                        $('.itemType').prop("disabled", true);
                        $('.itemSale').prop("disabled", true);
                        $('.cuisine').prop("disabled", true);
                        $('.menuCategory').prop("disabled", true);
                        $('.items').prop("disabled", true);

                        $('.disccuisine').prop("disabled", true);
                        $('.discmenuCategory').prop("disabled", true);
                        $('.discountItems').prop("disabled", true);
                        $('.itemPortion').prop("disabled", true);
                        $('.discitemType').prop("disabled", true);

                    break;
                    // discount %
                    case '2':
                        $('.discmaxamt').prop("disabled", true);
                        $('.discamount').prop("disabled", true);

                        $('.itemType').prop("disabled", true);
                        $('.itemSale').prop("disabled", true);
                        $('.cuisine').prop("disabled", true);
                        $('.disccuisine').prop("disabled", true);
                        $('.menuCategory').prop("disabled", true);
                        $('.discmenuCategory').prop("disabled", true);
                        $('.items').prop("disabled", true);
                        $('.discountItems').prop("disabled", true);
                        $('.itemPortion').prop("disabled", true);
                        $('.discitemType').prop("disabled", true);
                    break;
                    // discount % with max amount
                    case '3':
                        $('.discamount').prop("disabled", true);

                        $('.itemType').prop("disabled", true);
                        $('.itemSale').prop("disabled", true);
                        $('.cuisine').prop("disabled", true);
                        $('.disccuisine').prop("disabled", true);
                        $('.menuCategory').prop("disabled", true);
                        $('.discmenuCategory').prop("disabled", true);
                        $('.items').prop("disabled", true);
                        $('.discountItems').prop("disabled", true);
                        $('.discitemType').prop("disabled", true);
                        $('.itemPortion').prop("disabled", true);
                    break;
                    // discount itemtype with max price
                    case '4':
                        $('.discpercent').prop("disabled", true);
                        $('.discamount').prop("disabled", true);

                        $('.itemSale').prop("disabled", true);
                        $('.cuisine').prop("disabled", true);
                        $('.menuCategory').prop("disabled", true);
                        $('.items').prop("disabled", true);

                    break;
                    // discount item %  with portion, from cuisine
                  case '5':
                        // $('.discmaxamt').prop("disabled", true);
                        // $('.discpercent').prop("disabled", true);
                        // $('.discamount').prop("disabled", true);

                        $('.itemType').prop("disabled", true);
                        $('.itemSale').prop("disabled", true);
                        $('.menuCategory').prop("disabled", true);
                        $('.items').prop("disabled", true);
                    break;
                    // discount item with max price from cuisine
                  case '6':

                        $('.itemType').prop("disabled", true);
                        $('.itemSale').prop("disabled", true);
                        $('.menuCategory').prop("disabled", true);
                        $('.items').prop("disabled", true);

                        // $('.discmenuCategory').prop("disabled", true);
                        // $('.discountItems').prop("disabled", true);
                        // $('.discitemType').prop("disabled", true);
                        // $('.itemPortion').prop("disabled", true);
                    break;
                    // discount item %  with portion, from category
                  case '7':

                        $('.itemType').prop("disabled", true);
                        $('.itemSale').prop("disabled", true);
                        $('.cuisine').prop("disabled", true);
                        $('.items').prop("disabled", true);

                        // $('.disccuisine').prop("disabled", true);
                        // $('.discountItems').prop("disabled", true);
                        $('.discitemType').prop("disabled", true);
                        
                    break;
                   // discount item with max price from category
                  case '8':

                        $('.itemType').prop("disabled", true);
                        $('.itemSale').prop("disabled", true);
                        $('.cuisine').prop("disabled", true);
                        $('.items').prop("disabled", true);

                        // $('.disccuisine').prop("disabled", true);
                        // $('.discountItems').prop("disabled", true);
                        // $('.discitemType').prop("disabled", true);
                        // $('.itemPortion').prop("disabled", true);
                    break;
                     // discount itemtyp %  with portion
                  case '9':
                        // $('.discmaxamt').prop("disabled", true);
                        // $('.discamount').prop("disabled", true);

                        // $('.itemType').prop("disabled", true);
                        $('.itemSale').prop("disabled", true);
                        $('.cuisine').prop("disabled", true);
                        $('.menuCategory').prop("disabled", true);
                        $('.items').prop("disabled", true);

                        // $('.disccuisine').prop("disabled", true);
                        // $('.discmenuCategory').prop("disabled", true);
                        // $('.discountItems').prop("disabled", true);

                    break;
                    // discount item with portion
                  case '10':
                        $('.discmaxamt').prop("disabled", true);
                        $('.discpercent').prop("disabled", true);
                        $('.discamount').prop("disabled", true);

                        $('.itemType').prop("disabled", true);
                        $('.itemSale').prop("disabled", true);
                        $('.cuisine').prop("disabled", true);
                        $('.menuCategory').prop("disabled", true);
                        $('.items').prop("disabled", true);

                        // $('.disccuisine').prop("disabled", true);
                        // $('.discmenuCategory').prop("disabled", true);
                    break;
                }
            }else{
                $('.cuisine').prop("disabled", false);
                $('.itemType').prop("disabled", false);
                $('.itemSale').prop("disabled", false);
                $('.menuCategory').prop("disabled", false);
                $('.items').prop("disabled", false);
                $('.itemPortion').prop("disabled", false);

                //2 item based
                switch (schCat) {
                    // menu Item based
                  case '21':
                        $('.itemType').prop("disabled", true);
                        $('.itemSale').prop("disabled", true);
                        $('.menuCategory').prop("disabled", true);
                        // $('.itemPortion').prop("disabled", true);
                        $('.cuisine').prop("disabled", true);
                     break;
                    // menu Item, portion based
                  case '22':
                        $('.itemType').prop("disabled", true);
                        $('.itemSale').prop("disabled", true);
                        $('.menuCategory').prop("disabled", true);
                        $('.cuisine').prop("disabled", true);
                    break;
                    // item type based

                  case '25':
                        $('.itemType').prop("disabled", true);
                        $('.menuCategory').prop("disabled", true);
                        $('.cuisine').prop("disabled", true);
                    break;

                  case '23':
                  case '75':
                        $('.itemSale').prop("disabled", true);
                        $('.cuisine').prop("disabled", true);
                        $('.menuCategory').prop("disabled", true);
                        $('.items').prop("disabled", true);
                        // $('.itemPortion').prop("disabled", true);
                    break;
                }
            }
        }
        
    });

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
                                <input type="text" name="SchDesc1[]" class="form-control form-control-sm" id="description'+num_desc+'_description" maxlength="100" />\
                        </div>\
                        <?php for ($i = 1; $i < sizeof($languages); $i++) { ?>
                            <div class="form-group col-md-3 col-6">\
                            <label for="description'+num_desc+'_description"><?= $this->lang->line('description');?> <?= $languages[$i]['LngName']; ?></label>\
                                <input type="text" name="SchDesc<?= $languages[$i]['LCd']; ?>[]" class="form-control form-control-sm" id="description'+num_desc+'_description<?= $languages[$i]['LCd']; ?>" maxlength="100" />\
                        </div>\
                        <?php } ?>
                        <div class="form-group col-md-3 col-6">\
                            <label for="description'+num_desc+'_image"><?= $this->lang->line('image');?></label>\
                            <input type="file" name="description_image[]" class="form-control form-control-sm" id="description'+num_desc+'_image" />\
                        </div>\
                        <div class="form-group col-md-3 col-6 forBill" id="description'+num_desc+'_itemtyp_div" style="display: block;">\
                            <label for="description'+num_desc+'_itemtyp"><?= $this->lang->line('item');?> <?= $this->lang->line('type');?></label>\
                                <select class="form-control form-control-sm itemType" id="description'+num_desc+'_itemtyp" name="description_itemtyp[]" onchange="getPortionByItmTyp(this, '+num_desc+')">\
                                    <option value=""><?= $this->lang->line('select');?></option>\
                                    <?php foreach($menuTags as $key){
                                        if($key['TagTyp'] == 2){  ?>
                                        <option value="<?= $key['TagId']?>"><?= $key['TDesc']?></option>\
                                    <?php } } ?>
                                </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6 forBill" id="description'+num_desc+'_itemsales" style="display: block;">\
                            <label for="description'+num_desc+'_itemsales"><?= $this->lang->line('itemSale');?></label>\
                            <select class="form-control form-control-sm itemSale" id="description'+num_desc+'_itemsale" name="description_itemsales[]" onchange="getItemListByItmSale(this, '+num_desc+')">\
                            <option value=""><?= $this->lang->line('select');?></option>\
                                <?php foreach($menuTags as $key){
                                    if($key['TagTyp'] == 3){  ?>
                                        <option value="<?= $key['TagId']?>"><?= $key['TDesc']?></option>\
                                <?php } } ?>
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6 forBill">\
                            <label for="description'+num_desc+'_cid"><?= $this->lang->line('cuisine');?></label>\
                            <select class="form-control form-control-sm cuisine" id="description'+num_desc+'_cid" name="description_cid[]" onchange="getCategory(this,'+num_desc+')">\
                                <option value=""><?= $this->lang->line('select');?></option>\
                                <?php foreach($cuisines as $key){?>
                                        <option value="<?= $key['CID']?>"><?= $key['Name']?></option>\
                                <?php } ?>
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6 forBill" id="description'+num_desc+'_mcatgid_div" style="display: block;">\
                            <label for="description'+num_desc+'_mcatgid"><?= $this->lang->line('menuCategory');?></label>\
                            <select class="form-control form-control-sm menuCategory select2 custom-select" id="description'+num_desc+'_mcatgid" name="description_mcatgid[]" onchange="getItems(this, '+num_desc+')">\
                            <option value=""><?= $this->lang->line('select');?></option>\
                            <?php foreach($menucat as $key){?>
                                        <option value="<?= $key['MCatgId']?>"><?= $key['MCatgNm']?></option>\
                                <?php } ?>
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6 forBill" id="description'+num_desc+'_item_div" style="display: block;">\
                            <label for="description'+num_desc+'_item"><?= $this->lang->line('item');?></label>\
                            <select class="form-control form-control-sm items select2 custom-select" id="description'+num_desc+'_item" name="description_item[]" onchange="getItemPortion(this, '+num_desc+')">\
                            <option value=""><?= $this->lang->line('select');?></option>\
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6 forBill" id="description'+num_desc+'_itemportion_div" style="display: block;">\
                            <label for="description'+num_desc+'_itemportion"><?= $this->lang->line('itemPortion');?></label>\
                            <select class="form-control form-control-sm itemPortion select2 custom-select" id="description'+num_desc+'_itemportion" name="description_itemportion[]">\
                                <option value=""><?= $this->lang->line('select');?></option>\
                                <?php foreach($portions as $key){?>
                                        <option value="<?= $key['IPCd']?>"><?= $key['Name']?></option>\
                                <?php } ?>
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6 forBill" id="description'+num_desc+'_quantity_div" style="display: block;">\
                            <label for="description'+num_desc+'_quantity"><?= $this->lang->line('quantity');?></label>\
                            <input type="number" class="form-control form-control-sm Qty" id="description'+num_desc+'_quantity" name="description_quantity[]" value="1">\
                        </div>\
                        <div class="form-group col-md-3 col-6">\
                            <label for="description'+num_desc+'_cid"><?= $this->lang->line('discount');?> <?= $this->lang->line('cuisine');?></label>\
                            <select class="form-control form-control-sm disccuisine" id="description'+num_desc+'_disccid" name="description_disc_cid[]" onchange="getCategoryDisc(this,'+num_desc+')">\
                                <option value=""><?= $this->lang->line('select');?></option>\
                                <?php foreach($cuisines as $key){?>
                                        <option value="<?= $key['CID']?>"><?= $key['Name']?></option>\
                                <?php } ?>
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_mcatgid_div" style="display: block;">\
                            <label for="description'+num_desc+'_mcatgid"><?= $this->lang->line('discount');?> <?= $this->lang->line('menuCategory');?></label>\
                            <select class="form-control form-control-sm discmenuCategory select2 custom-select" id="description'+num_desc+'_disc_mcatgid" name="description_disc_mcatgid[]" onchange="getItemsDisc(this, '+num_desc+')">\
                            <option value=""><?= $this->lang->line('select');?></option>\
                            <?php foreach($menucat as $key){?>
                                        <option value="<?= $key['MCatgId']?>"><?= $key['MCatgNm']?></option>\
                                <?php } ?>
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description1_discountitem_div" style="display: block;">\
                            <label for="description'+num_desc+'_discountitem"><?= $this->lang->line('discountItem');?></label>\
                            <select class="form-control form-control-sm select2 custom-select discountItems" id="description'+num_desc+'_discountitem" name="description_discountitem[]" onchange="getDiscItemPortion(this, '+num_desc+')">\
                                <option value=""><?= $this->lang->line('select');?></option>\
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_itemtyp_div" style="display: block;">\
                            <label for="description'+num_desc+'_itemtyp"><?= $this->lang->line('discount');?> <?= $this->lang->line('item');?> <?= $this->lang->line('type');?></label>\
                                <select class="form-control form-control-sm discitemType" id="description'+num_desc+'_discitemtyp" name="description_discitemtyp[]" onchange="getDiscPortionByItmTyp(this, '+num_desc+')">\
                                    <option value=""><?= $this->lang->line('select');?></option>\
                                    <?php foreach($menuTags as $key){
                                        if($key['TagTyp'] == 2){  ?>
                                        <option value="<?= $key['TagId']?>"><?= $key['TDesc']?></option>\
                                    <?php } } ?>
                                </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_discountitemportion_div" style="display: block;">\
                            <label for="description'+num_desc+'_discountitemportion"><?= $this->lang->line('discountItemPortion');?></label>\
                            <select class="form-control form-control-sm discountItemPortion select2 custom-select" id="description'+num_desc+'_discountitemportion" name="description_discountitemportion[]">\
                                <option value="0"><?= $this->lang->line('select');?></option>\
                                <?php foreach($portions as $key){?>
                                        <option value="<?= $key['IPCd']?>"><?= $key['Name']?></option>\
                                <?php } ?>
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_discountquantity_div" style="display: block;">\
                            <label for="description'+num_desc+'_discountquantity"><?= $this->lang->line('discountItemQuantity');?></label>\
                            <input type="number" class="form-control form-control-sm discQty" id="description'+num_desc+'_discountquantity" name="description_discountquantity[]" value="0">\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_discountitempercentage_div" style="display: block;">\
                            <label for="description'+num_desc+'discountitempercentage"><?= $this->lang->line('discountItemPercentage');?></label>\
                            <input type="number" class="form-control form-control-sm" id="description'+num_desc+'_discountitempercentage" name="description_discountitempercentage[]" value="0">\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_discountitempercentage_div" style="display: block;">\
                            <label for="description'+num_desc+'discountitempercentage"><?= $this->lang->line('discount');?> <?= $this->lang->line('maximum');?> <?= $this->lang->line('amount');?></label>\
                            <input type="number" class="form-control form-control-sm discmaxamt" id="description'+num_desc+'_disc_max_amt" name="description_disc_max_amt[]" value="0">\
                        </div>\
                        <div class="col-md-3 col-6 billBased" style="display: none;">\
                            <div class="form-group">\
                                <label><?= $this->lang->line('minimumBillAmount');?></label>\
                                <input type="number" class="form-control form-control-sm minbillamt" id="description'+num_desc+'_minbillamount" name="description_minbillamount[]" value="0">\
                            </div>\
                        </div>\
                        <div class="col-md-3 col-6 billBased" style="display: none;">\
                            <div class="form-group">\
                                <label><?= $this->lang->line('discountPercentage');?></label>\
                                <input type="number" class="form-control form-control-sm discpercent" id="description'+num_desc+'_discountpercent" name="description_discountpercent[]" value="0">\
                            </div>\
                        </div>\
                        <div class="col-md-3 col-6 billBased"  style="display: none;">\
                            <div class="form-group">\
                                <label for="description'+num_desc+'_discountamount"><?= $this->lang->line('discountAmount');?></label>\
                                <input type="number" class="form-control form-control-sm discamount" id="description'+num_desc+'_discountamount" name="description_discountamount[]" value="0">\
                            </div>\
                        </div>\
                    </div>\
                </div>';
        $('#offer_descriptions').append(v);

        $('.discountItems, .menuCategory, .discmenuCategory, .items, .itemPortion, .discountItemPortion').select2();
        getDiscountItems(num_desc);

    }
    
    function getCategory(el, n){
        var cid = el.value;
        if(cid > 0){
            $('.itemType').val('');
            $('.itemSale').val('');
            $('.menuCategory').val('').trigger('change');
            $('.items').val('').trigger('change');
            $('.itemPortion').val('').trigger('change');
            $(`.Qty`).val(1);

            portionCommon(ItemTyp=0 , cid, MCatgId=0, item_id=0, n, 'cid');
        }
    }

    function getCategoryDisc(el, n){
        var CID = el.value;

        if(CID > 0){
            $('.discmenuCategory').val('').trigger('change');
            $('.discountItems').val('').trigger('change');
            $('.discitemType').val("");
            $('.discQty').val(1);
            $(`.discountItemPortion`).prop('required', true);
            $(`.discQty`).prop('required', true);
            
            portionForCommon(ItemTyp = 0 ,CID, MCatgId=0, ItemId=0, n, 'cid');
        }
    }

    function getItems(el, n){
        var MCatgId = $('#description'+n+'_mcatgid').val();
        if(MCatgId > 0){
            $('.itemType').val('');
            $('.itemSale').val('');
            $('.cuisine').val('');
            $('.items').val('').trigger('change');
            $('.itemPortion').val('').trigger('change');
            $(`.Qty`).val(1);
            portionCommon(ItemTyp=0 ,CID=0, MCatgId, item_id=0, n, 'cat');
        }
    }

    function getItemListByItmSale(el, n){
        var item_sale =  el.value;
        if(item_sale > 0){
            $('.itemType').val('');
            $('.cuisine').val('');
            $('.itemPortion').val('').trigger('change');
            $(`.Qty`).val(1);
        }

        $.ajax({
            url: '<?php echo base_url('restaurant/offer_ajax'); ?>',
            type: 'post',
            data: {
                getItemsBySale: 1,
                ItemSale: item_sale
            },
            success: function(response) {
                var res = JSON.parse(response);
                var b = '<option value="0"><?= $this->lang->line('all'); ?></option>';
                for(i= 0;i<res.length;i++){
                    b += '<option value="'+res[i].ItemId+'">'+res[i].ItemNm+'</option>';
                }
                $('#description'+n+'_item').html(b);
            }
        })
    }

    function getPortionByItmTyp(el, n){
        var schcatg = $(`#schcatg`).val();
        if(schcatg > 0){
            $('.itemSale').val('');
            $('.cuisine').val('');
            $('.menuCategory').val('').trigger('change');
            $('.items').val('').trigger('change');
            $('.itemPortion').val('').trigger('change');
            $(`.Qty`).val(1);

            var ItemTyp = $(`#description${n}_itemtyp`).val();
            if(ItemTyp > 0){
                portionCommon(ItemTyp ,CID=0, MCatgId=0, item_id=0, n, 'itemtype');
            }
        }
    }

    function getDiscPortionByItmTyp(el, n){
        var ItemTyp = $(`#description${n}_discitemtyp`).val();
        if(ItemTyp > 0){
            $('.disccuisine').val('').trigger('change');
            $('.discmenuCategory').val('').trigger('change');
            $('.discountItems').val('').trigger('change');
            $('.discQty').val(1);
            $(`.discountItemPortion`).prop('required', true);
            $(`.discQty`).prop('required', true);
            
            portionForCommon(ItemTyp ,CID=0, MCatgId=0, ItemId=0, n, 'itemtype');
        }
    }

    function getItemsDisc(el, n){
        var MCatgId = $('#description'+n+'_disc_mcatgid').val();
        if(MCatgId > 0){
            $('.disccuisine').val('').trigger('change');
            $('.discountItems').val('').trigger('change');
            $('.discitemType').val("");
            $('.discQty').val(1);
            $(`.discountItemPortion`).prop('required', true);
            $(`.discQty`).prop('required', true);
            
            portionForCommon(ItemTyp = 0 ,CID=0, MCatgId, ItemId=0, n, 'mcat');
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
                
                var b1 = '<option value=""><?= $this->lang->line('select'); ?></option>';
                for(i= 0;i<res.length;i++){
                    b1 += '<option value="'+res[i].ItemId+'">'+res[i].Name+'</option>';
                }

                $('#description'+n+'_discountitem').html(b1);
                $('#description'+n+'_item').html(b1);
            }
        })
    }

    function getItemPortion(el, n){
        var item_id = el.value;

        if(item_id > 0){
            $('.itemType').val('');
            $('.itemSale').val('');
            $('.cuisine').val('');
            $('.menuCategory').val('').trigger('change');
            $('.itemPortion').val('').trigger('change');
            $(`.Qty`).val(1);
            $(`.Qty`).prop('required', true);
            portionCommon(ItemTyp=0 ,CID=0, MCatgId=0, item_id, n, 'itemid');
        }
    }

    function getDiscItemPortion(el, n){
        var ItemId = el.value;

        if(ItemId> 0){
            $('#description'+n+'_discountitemportion').attr('required', true);
            $('#description'+n+'_discountquantity').attr('required', true);

            $('.disccuisine').val('').trigger('change');
            $('.discmenuCategory').val('').trigger('change');
            $('.discitemType').val("");
            $('.discQty').val(1);
            $(`.discountItemPortion`).prop('required', true);
            $(`.discQty`).prop('required', true);
            
            portionForCommon(ItemTyp = 0 ,CID=0, MCatgId=0, ItemId, n, 'itemid');
        }else{
            $('#description'+n+'_discountitemportion').attr('required', false);
            $('#description'+n+'_discountquantity').attr('required', false);
        }

    }

    function portionCommon(ItemTyp ,CID, MCatgId, ItemId, n, type){
        $.post('<?= base_url('restaurant/get_portion_itemtype') ?>',{ItemTyp:ItemTyp, CID:CID, MCatgId:MCatgId, ItemId:ItemId, type:type},function(res){
            if(res.status == 'success'){
                var opt = `<option value=""><?= $this->lang->line('select'); ?></option>`;
              res.response.forEach((item, index) => {
                opt += `<option value="${item.IPCd}">${item.Portions}</option>`;
              })
              $(`#description${n}_itemportion`).html(opt);
            }else{
              alert(res.response);
            }
        });   
    }

    function portionForCommon(ItemTyp ,CID, MCatgId, ItemId, n, type){
        $.post('<?= base_url('restaurant/get_portion_itemtype') ?>',{ItemTyp:ItemTyp, CID:CID, MCatgId:MCatgId, ItemId:ItemId, type:type},function(res){
            if(res.status == 'success'){
                var opt = `<option value=""><?= $this->lang->line('select'); ?></option>`;
              res.response.forEach((item, index) => {
                opt += `<option value="${item.IPCd}">${item.Portions}</option>`;
              })
              $(`#description${n}_discountitemportion`).html(opt);
            }else{
              alert(res.response);
            }
        });   
    }

</script>