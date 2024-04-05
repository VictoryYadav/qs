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
                                        
                                        <form method="post" action="<?php echo base_url('restaurant/offer_ajax'); ?>" enctype="multipart/form-data">
                                            <input type="hidden" name="updateOffer" value="1">
                                            
                                            <input type="hidden" name="SchCd" value="<?= $SchCd?>">
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label for="schnm"><?= $this->lang->line('schemeName');?></label>
                                                    <input type="text" id="schnm" name="SchNm" class="form-control form-control-sm" placeholder="Enter scheme name" required="" value="<?= $scheme[0]['SchNm']?>" />
                                                </div>
                                                <div class="form-group col-md-4 col-6">
                                                    <label for="sch_typ"><?= $this->lang->line('schemeType');?></label>
                                                    <select class="form-control form-control-sm" id="sch_typ" name="SchTyp" required="" onchange="getCategoryl()">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <option value="1" <?php if($scheme[0]['SchTyp']==1){ echo 'selected'; } ?>>Bill Based</option>
                                                        <option value="2" <?php if($scheme[0]['SchTyp']==2){ echo 'selected'; } ?> >Item Based</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4 col-6">
                                                    <label for="schcatg"><?= $this->lang->line('schemeCategory');?></label>
                                                    <select class="form-control form-control-sm" id="schcatg" name="SchCatg" required="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-3 col-6">
                                                    <label for="from_date"><?= $this->lang->line('fromDate');?></label>
                                                    <input type="date" name="FrmDt" class="form-control form-control-sm" id="from_date" <?php if(!empty($scheme[0]['FrmDt'])){?> value="<?= $scheme[0]['FrmDt']?>" <?php }?> />
                                                </div>
                                                <div class="form-group col-md-3 col-6">
                                                    <label for="to_date"><?= $this->lang->line('toDate');?></label>
                                                    <input type="date" name="ToDt" class="form-control form-control-sm" id="to_date" <?php if(!empty($scheme[0]['ToDt'])){?> value="<?= $scheme[0]['ToDt']?>" <?php }?> />
                                                </div>
                                                
                                                <div class="form-group col-md-3 col-6">
                                                    <label for="from_day"><?= $this->lang->line('fromDay');?></label>
                                                    <select class="form-control form-control-sm" id="from_day" name="FromDayNo" required="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php 
                                                            foreach ($weekDay as $key) {?>
                                                                <option value="<?= $key['DayNo']; ?>" <?php if($scheme[0]['FrmDayNo'] == $key['DayNo']){echo 'selected';} ?>><?= $key['Name']; ?></option>
                                                            
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3 col-6">
                                                    <label for="to_day"><?= $this->lang->line('toDay');?></label>
                                                    <select class="form-control form-control-sm" id="to_day" name="ToDayNo" required="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php 
                                                            foreach ($weekDay as $key) {?>
                                                                <option value="<?= $key['DayNo']; ?>" <?php if($scheme[0]['ToDayNo'] == $key['DayNo']){echo 'selected';} ?>><?= $key['Name']; ?></option>
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3 col-6">
                                                    <label for="from_time"><?= $this->lang->line('fromTime');?></label>
                                                    <input type="time" name="FrmTime" class="form-control form-control-sm" id="from_time" <?php if(!empty($scheme[0]['FrmTime'])){?> value="<?= $scheme[0]['FrmTime']?>" <?php }?> />
                                                </div>
                                                <div class="form-group col-md-3 col-6">
                                                    <label for="to_time"><?= $this->lang->line('toTime');?></label>
                                                    <input type="time" name="ToTime" class="form-control form-control-sm" id="to_time" <?php if(!empty($scheme[0]['ToTime'])){?> value="<?= $scheme[0]['ToTime']?>" <?php }?> />
                                                </div>
                                                <div class="form-group col-md-3 col-6">
                                                    <label for="alt_from_time"><?= $this->lang->line('alternateFromTime');?></label>
                                                    <input type="time" name="AltFrmTime" class="form-control form-control-sm" id="alt_from_time" <?php if(!empty($scheme[0]['AltFrmTime'])){?> value="<?= $scheme[0]['AltFrmTime']?>" <?php }?> />
                                                </div>
                                                <div class="form-group col-md-3 col-6">
                                                    <label for="alt_to_time"><?= $this->lang->line('alternateToTime');?></label>
                                                    <input type="time" name="AltToTime" class="form-control form-control-sm" id="alt_to_time" <?php if(!empty($scheme[0]['ToTime'])){?> value="<?= $scheme[0]['ToTime']?>" <?php }?> />
                                                </div>
                                            </div>
                                            <div class="offer_descriptions" id="offer_descriptions">
                                                <?php $n = 1;foreach($descriptions as $key){?>

                                                    <div class="description1" id="description1" style="display: block;">
                                                        <hr>
                                                        <input type="hidden" name="SDetCd[]" value="<?= $key['SDetCd']?>">
                                                        <div class="text-center"><h5><?= $this->lang->line('offerDetails');?> - <?= convertToUnicodeNumber($n); ?> <span  onclick="delete_offer_description(<?= $key['SDetCd']?>)" style="cursor: pointer;"><i class="fa fa-trash" style="color: red;"></i></span></h5></div>
                                                        <div class="row">
                                                            <div class="form-group col-md-3 col-6">
                                                                <label for="description1_description"><?= $this->lang->line('description');?></label>
                                                                <input type="text" name="description[]" class="form-control form-control-sm" id="description<?= $n?>_description" maxlength="100" placeholder="Enter Scheme Description" value="<?= $key['SchDesc']?>" />
                                                            </div>
                                                            <div class="form-group col-md-3 col-6">
                                                                <label for="description1_image"><?= $this->lang->line('image');?></label>
                                                                <input type="file" name="description_image[]" class="form-control form-control-sm" id="description<?= $n?>_image" />
                                                            </div>
                                                            <div class="form-group col-md-3 col-6">
                                                                <label for="description1_cid"><?= $this->lang->line('cuisine');?></label>
                                                                <select class="form-control form-control-sm" id="description<?= $n?>_cid" name="description_cid[]" onchange="getCategory(<?= $n?>)">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    <?php 
                                                                        foreach($cuisines as $c){?>
                                                                            <option value="<?= $c['CID']?>" <?php if($key['CID'] == $c['CID']){echo 'selected';}?>><?= $c['Name']?></option>
                                                                    <?php }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_mcatgid_div" style="display: block;">
                                                                <label for="description<?= $n?>_mcatgid"><?= $this->lang->line('menuCategory');?></label>
                                                                <input type="hidden" name="" id="mcatgid<?= $n?>" value="<?= $key['MCatgId']?>">
                                                                <select class="form-control form-control-sm" id="description<?= $n?>_mcatgid" name="description_mcatgid[]" onchange="getItems(<?= $n?>)">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_itemtyp_div" style="display: block;">
                                                                <label for="description<?= $n?>_itemtyp"><?= $this->lang->line('foodType');?></label>
                                                                <select class="form-control form-control-sm" id="description<?= $n?>_itemtyp" name="description_itemtyp[]" onchange="getItems(<?= $n?>)">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    <?php foreach($foodType as $it){?>
                                                                        <option value="<?= $it['CTyp']?>" <?php if($it['CTyp'] == $key['ItemTyp']){echo 'selected';}?>><?= $it['Usedfor']?></option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_item_div" style="display: block;">
                                                                <label for="description<?= $n?>_item"><?= $this->lang->line('item');?></label>
                                                                <input type="hidden" name="" id="item_id<?= $n?>" value="<?= $key['ItemId']?>">
                                                                <select class="form-control form-control-sm" id="description<?= $n?>_item" name="description_item[]" onchange="getItemPortion(<?= $n?>)">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_itemportion_div" style="display: block;">
                                                                <label for="description<?= $n?>_itemportion"><?= $this->lang->line('itemPortion');?></label>
                                                                <input type="hidden" name="" id="ipcd<?= $n?>" value="<?= $key['IPCd']?>">
                                                                <select class="form-control form-control-sm" id="description<?= $n?>_itemportion" name="description_itemportion[]">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_quantity_div" style="display: block;">
                                                                <label for="description<?= $n?>_quantity"><?= $this->lang->line('quantity');?></label>
                                                                <input type="number" class="form-control form-control-sm" id="description<?= $n?>_quantity" name="description_quantity[]" value="<?= $key['Qty']?>">
                                                            </div>
                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_discountitem_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountitem"><?= $this->lang->line('discountItem');?></label>
                                                                <input type="hidden" name="" id="disc_item_id<?= $n?>" value="<?= $key['Disc_ItemId']?>">
                                                                <select class="form-control form-control-sm select2 custom-select discountItems" id="description<?= $n?>_discountitem" name="description_discountitem[]" onchange="getDiscItemPortion(<?= $n?>)">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_discountitemportion_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountitemportion"><?= $this->lang->line('discountItemPortion');?></label>
                                                                <input type="hidden" name="" id="disc_ipcd<?= $n?>" value="<?= $key['Disc_IPCd']?>">
                                                                <select class="form-control form-control-sm" id="description<?= $n?>_discountitemportion" name="description_discountitemportion[]">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_discountquantity_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountquantity"><?= $this->lang->line('discountItemQuantity');?></label>
                                                                <input type="number" class="form-control form-control-sm" id="description<?= $n?>_discountquantity" name="description_discountquantity[]" value="<?= $key['Disc_Qty']?>">
                                                            </div>

                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_discountitempercentage_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountitempercentage"><?= $this->lang->line('discountItemPercentage');?></label>
                                                                <input type="number" class="form-control form-control-sm" id="description<?= $n?>_discountitempercentage" name="description_discountitempercentage[]" value="<?= $key['DiscItemPcent']?>">
                                                            </div>

                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_minbillamount_div" style="display: block;">
                                                                <label for="description<?= $n?>_minbillamount"><?= $this->lang->line('minimumBillAmount');?></label>
                                                                <input type="number" class="form-control form-control-sm" id="description<?= $n?>_minbillamount" name="description_minbillamount[]" value="<?= $key['MinBillAmt']?>">
                                                            </div>
                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_discountpercent_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountpercent"><?= $this->lang->line('discountPercentage');?></label>
                                                                <input type="number" class="form-control form-control-sm" id="description<?= $n?>_discountpercent" name="description_discountpercent[]" value="<?= $key['Disc_pcent']?>">
                                                            </div>
                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_discountamount_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountamount"><?= $this->lang->line('discountAmount');?> </label>
                                                                <input type="number" class="form-control form-control-sm" id="description<?= $n?>_discountamount" name="description_discountamount[]" value="<?= $key['Disc_Amt']?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php $n++;}?>
                                            </div>
                                            <div class="text-center"><button type="button" class="btn btn-primary btn-sm" onclick="add_more_description()" id="add_more" style="display: block;"><?= $this->lang->line('addMoreOfferDetails');?></button>&nbsp;&nbsp;<button type="submit" class="btn btn-primary btn-sm" ><?= $this->lang->line('update');?></button></div>
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

    $(document).ready(function () {
        $('.discountItems').select2();
    });

    getCategoryl();
    function getCategoryl(){
      var SchCatg = "<?php echo $scheme[0]['SchCatg']; ?>";

        var SchTyp = $('#sch_typ').val();
        if(SchTyp > 0){
            $.post('<?= base_url('restaurant/get_schemes') ?>',{SchTyp:SchTyp},function(res){
                if(res.status == 'success'){
                    var dt = res.response;
                  if(dt.length > 0){
                    var opt = `<option value="">Select</option>`;
                    for(var i = 0; i <dt.length; i++){
                        var selct = ``;
                        if(dt[i].SchCatg == SchCatg){
                            selct = `selected`;
                        }
                        opt += `<option value="${dt[i].SchCatg}" ${selct}>${dt[i].Name}</option>`;
                    }
                    
                    $(`#schcatg`).html(opt);
                  }
                }else{
                  alert(res.response);
                }
            });
        }
    }

    var num_desc = <?= sizeof($descriptions)?>;
    for(i=1;i<=num_desc;i++){
        getCategory(i);
        getDiscountItems(i);
    }
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
                            <select class="form-control form-control-sm" id="description'+num_desc+'_cid" name="description_cid[]" onchange="getCategory2(this,'+num_desc+')">\
                                <option value=""><?= $this->lang->line('select');?></option>\
                                <?php foreach($cuisines as $key){?>
                                        <option value="<?= $key['CID']?>"><?= $key['Name']?></option>\
                                <?php } ?>
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_mcatgid_div" style="display: block;">\
                            <label for="description'+num_desc+'_mcatgid"><?= $this->lang->line('menuCategory');?></label>\
                            <select class="form-control form-control-sm" id="description'+num_desc+'_mcatgid" name="description_mcatgid[]">\
                            <option value=""><?= $this->lang->line('select');?></option>\
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_itemtyp_div" style="display: block;">\
                            <label for="description'+num_desc+'_itemtyp"><?= $this->lang->line('foodType');?></label>\
                                <select class="form-control form-control-sm" id="description'+num_desc+'_itemtyp" name="description_itemtyp[]" onchange="getItems2(this, '+num_desc+')">\
                                    <option value=""><?= $this->lang->line('select');?></option>\
                                    <?php foreach($foodType as $key){?>
                                        <option value="<?= $key['CTyp']?>"><?= $key['Usedfor']?></option>\
                                    <?php } ?>
                                </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_item_div" style="display: block;">\
                            <label for="description'+num_desc+'_item"><?= $this->lang->line('item');?></label>\
                            <select class="form-control form-control-sm" id="description'+num_desc+'_item" name="description_item[]" onchange="getItemPortion2(this, '+num_desc+')">\
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
                            <select class="form-control form-control-sm select2 custom-select discountItems" id="description'+num_desc+'_discountitem" name="description_discountitem[]" onchange="getDiscItemPortion2(this, '+num_desc+')">\
                                <option value="0"><?= $this->lang->line('select'); ?></option>\
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
                            <label for="description'+num_desc+'_discountitempercentage"><?= $this->lang->line('discountItemPercentage');?></label>\
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
        getDiscountItems2(num_desc);
    }
    function getCategory(n){
        var cid = $('#description'+n+'_cid').val();
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
                var b = '<option value="0"><?= $this->lang->line('all'); ?></option>';
                for(i= 0;i<res.length;i++){
                    var ch = '';
                    if(mcatgid == res[i].MCatgId){
                        ch = 'selected';
                    }else{
                        ch = '';
                    }
                    // alert(ch);
                    b += '<option value="'+res[i].MCatgId+'" '+ch+'>'+res[i].MCatgNm+'</option>';
                }
                $('#description'+n+'_mcatgid').html(b);
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
                    var b = '<option value="0"><?= $this->lang->line('all'); ?></option>';
                    var b1 = '<option value="0"><?= $this->lang->line('all'); ?></option>';
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
                    // $('#description'+n+'_item').append(b);
                    $('#description'+n+'_item').html(b);
                    // $('#description'+n+'_discountitem').html(b1);
                    getItemPortion(n);
                    getDiscItemPortion(n);
                }
            })
        }
    }

    function getDiscountItems(n){
        // var cat = $('#description'+n+'_mcatgid').val();
        // var it = $('#description'+n+'_itemtyp').val();
        // var item = $('#item_id'+n).val();
        var item1 = $('#disc_item_id'+n).val();
        // alert(item);
        if(item1 != ''){
            $.ajax({
                url: '<?php echo base_url('restaurant/offer_ajax'); ?>',
                type: 'post',
                data: {
                    getAllItemsList: 1,
                },
                success: function(response) {
                    var res = JSON.parse(response);
                    
                    var b1 = '<option value="0"><?= $this->lang->line('all'); ?></option>';
                    for(i= 0;i<res.length;i++){
                       
                        var ch1 = '';
                        if(item1 == res[i].ItemId){
                            ch1 = 'selected';
                        }
                        b1 += '<option value="'+res[i].ItemId+'" '+ch1+'>'+res[i].Name+'</option>';
                    }
                    
                    $('#description'+n+'_discountitem').html(b1);
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
                var b = '<option value="0"><?= $this->lang->line('all'); ?></option>';
                for(i= 0;i<res.length;i++){
                    var ch = '';
                    if(ip == res[i].IPCd){
                        ch = 'selected';
                    }
                    b += '<option value="'+res[i].IPCd+'" '+ch+'>'+res[i].Name+'</option>';
                }
                $('#description'+n+'_itemportion').html(b);
                // getDiscItemPortion(1);
            }
        })
    }

    function getDiscItemPortion(n){
        var item_id = $('#description'+n+'_discountitem').val();
        var ip = $('#disc_ipcd'+n).val();
        
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
                    var ch = '';
                    
                    if(ip == res[i].IPCd){
                        ch = 'selected';
                    }
                    
                    b += '<option value="'+res[i].IPCd+'" '+ch+'>'+res[i].Name+'</option>';
                }
                
                $('#description'+n+'_discountitemportion').html(b);
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
                    // $('#description'+n+'_discountitem').append(b);
                }
            })
        }
    }

    function getDiscountItems2(n){
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
                    b1 += '<option value="'+res[i].ItemId+'">'+res[i].Name+'</option>';
                }
                
                $('#description'+n+'_discountitem').html(b1);
                getDiscItemPortion(n);
            }
        })
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
    

</script>