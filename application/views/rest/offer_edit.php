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
                                                    <input type="text" id="schnm" name="SchNm1" class="form-control form-control-sm" placeholder="Enter scheme name" required="" value="<?= $scheme['SchNm'];?>" />
                                                </div>
                                                <?php
                                                for ($i = 1; $i < sizeof($languages); $i++) {
                                                    $descc = 'SchNm'.$languages[$i]['LCd'];
                                                 ?>
                                                   <div class="col-md-4 col-6">
                                                        <div class="form-group">
                                                            <label for=""><?= $languages[$i]['LngName']; ?></label>
                                                            <input type="text" id="schnm<?= $languages[$i]['LCd']; ?>" name="SchNm<?= $languages[$i]['LCd']; ?>" class="form-control form-control-sm"  value="<?= $scheme[$descc];?>" />
                                                        </div>
                                                   </div>
                                                <?php } ?>
                                                <div class="form-group col-md-4 col-6">
                                                    <label for="sch_typ"><?= $this->lang->line('schemeType');?></label>
                                                    <select class="form-control form-control-sm" id="sch_typ" name="SchTyp" required="" onchange="getCategoryl()" disabled="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <option value="1" <?php if($scheme['SchTyp']==1){ echo 'selected'; } ?>>Bill Based</option>
                                                        <option value="2" <?php if($scheme['SchTyp']==2){ echo 'selected'; } ?> >Item Based</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4 col-6">
                                                    <label for="schcatg"><?= $this->lang->line('schemeCategory');?></label>
                                                    <select class="form-control form-control-sm" id="schcatg" name="SchCatg" required="" disabled="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-3 col-6">
                                                    <label for="from_date"><?= $this->lang->line('fromDate');?></label>
                                                    <input type="date" name="FrmDt" class="form-control form-control-sm" id="from_date" <?php if(!empty($scheme['FrmDt'])){?> value="<?= $scheme['FrmDt']?>" <?php }?> />
                                                </div>
                                                <div class="form-group col-md-3 col-6">
                                                    <label for="to_date"><?= $this->lang->line('toDate');?></label>
                                                    <input type="date" name="ToDt" class="form-control form-control-sm" id="to_date" <?php if(!empty($scheme['ToDt'])){?> value="<?= $scheme['ToDt']?>" <?php }?> />
                                                </div>
                                                
                                                <div class="form-group col-md-3 col-6">
                                                    <label for="from_day"><?= $this->lang->line('fromDay');?></label>
                                                    <select class="form-control form-control-sm" id="from_day" name="FromDayNo" required="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php 
                                                            foreach ($weekDay as $key) {?>
                                                                <option value="<?= $key['DayNo']; ?>" <?php if($scheme['FrmDayNo'] == $key['DayNo']){echo 'selected';} ?>><?= $key['Name']; ?></option>
                                                            
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
                                                                <option value="<?= $key['DayNo']; ?>" <?php if($scheme['ToDayNo'] == $key['DayNo']){echo 'selected';} ?>><?= $key['Name']; ?></option>
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3 col-6">
                                                    <label for="from_time"><?= $this->lang->line('fromTime');?></label>
                                                    <input type="time" name="FrmTime" class="form-control form-control-sm" id="from_time" <?php if(!empty($scheme['FrmTime'])){?> value="<?= $scheme['FrmTime']?>" <?php }?> />
                                                </div>
                                                <div class="form-group col-md-3 col-6">
                                                    <label for="to_time"><?= $this->lang->line('toTime');?></label>
                                                    <input type="time" name="ToTime" class="form-control form-control-sm" id="to_time" <?php if(!empty($scheme['ToTime'])){?> value="<?= $scheme['ToTime']?>" <?php }?> />
                                                </div>
                                                <div class="form-group col-md-3 col-6">
                                                    <label for="alt_from_time"><?= $this->lang->line('alternateFromTime');?></label>
                                                    <input type="time" name="AltFrmTime" class="form-control form-control-sm" id="alt_from_time" <?php if(!empty($scheme['AltFrmTime'])){?> value="<?= $scheme['AltFrmTime']?>" <?php }?> />
                                                </div>
                                                <div class="form-group col-md-3 col-6">
                                                    <label for="alt_to_time"><?= $this->lang->line('alternateToTime');?></label>
                                                    <input type="time" name="AltToTime" class="form-control form-control-sm" id="alt_to_time" <?php if(!empty($scheme['ToTime'])){?> value="<?= $scheme['ToTime']?>" <?php }?> />
                                                </div>

                                                <div class="form-group col-md-3 col-6">
                                                    <label for=""><?= $this->lang->line('offers');?> <?= $this->lang->line('type');?></label>
                                                    <select name="offerType" class="form-control form-control-sm" id="offerType" required="">
                                                        <option value=""><?= $this->lang->line('select');?></option>
                                                        <option value="1" <?php if(($scheme['offerType'] == 1)){ echo 'selected'; } ?> >Only Food</option>
                                                        <option value="2" <?php if(($scheme['offerType'] == 2)){ echo 'selected'; } ?>>Food & Bar</option>
                                                        <option value="3" <?php if(($scheme['offerType'] == 3)){ echo 'selected'; } ?>>Only Bar</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-3 col-6">
                                                    <label for=""><?= $this->lang->line('mode');?></label>
                                                    <select name="Stat" class="form-control form-control-sm" id="Stat" required="">
                                                        <option value=""><?= $this->lang->line('select');?></option>
                                                        <option value="0" <?php if(($scheme['Stat'] == 0)){ echo 'selected'; } ?> ><?= $this->lang->line('active');?></option>
                                                        <option value="1" <?php if(($scheme['Stat'] == 1)){ echo 'selected'; } ?>><?= $this->lang->line('inactive');?></option>
                                                    </select>
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
                                                                <input type="text" name="SchDesc1[]" class="form-control form-control-sm" id="description<?= $n?>_description" maxlength="100" placeholder="Enter Scheme Description" value="<?= $key['SchDesc']?>" />
                                                            </div>

                                                            <?php
                                                            for ($i = 1; $i < sizeof($languages); $i++) {
                                                                $descc = 'SchDesc'.$languages[$i]['LCd'];
                                                             ?>
                                                               <div class="col-md-3 col-6">
                                                                    <div class="form-group">
                                                                        <label for=""><?= $this->lang->line('description');?> <?= $languages[$i]['LngName']; ?></label>
                                                                        <input type="text" id="description<?= $languages[$i]['LCd']; ?>" name="SchDesc<?= $languages[$i]['LCd']; ?>[]" class="form-control form-control-sm"  value="<?= $key[$descc];?>" />
                                                                    </div>
                                                               </div>
                                                            <?php } ?>

                                                            <div class="form-group col-md-3 col-6">
                                                                <label for="description1_image"><?= $this->lang->line('image');?></label>
                                                                <input type="file" name="description_image[]" class="form-control form-control-sm" id="description<?= $n?>_image" />
                                                            </div>

                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_itemtyp_div" style="display: block;">
                                                                <label for="description<?= $n?>_itemtyp"><?= $this->lang->line('item');?> <?= $this->lang->line('type');?></label>
                                                                <select class="form-control form-control-sm itemType" id="description<?= $n?>_itemtyp" name="description_itemtyp[]" onchange="getPortionByItmTyp_edit(<?= $n?>)">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    <?php foreach($menuTags as $it){
                                                                        if($it['TagTyp'] == 2){
                                                                        ?>
                                                                        <option value="<?= $it['TagId']?>" <?php if($it['TagId'] == $key['ItemTyp']){echo 'selected';}?>><?= $it['TDesc']?></option>
                                                                    <?php } } ?>
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_itemsales" style="display: block;">
                                                                <label for="description<?= $n?>_itemsales"><?= $this->lang->line('itemSale');?></label>
                                                                <select class="form-control form-control-sm itemSale" id="description<?= $n?>_itemsale" name="description_itemsales[]">
                                                                <option value=""><?= $this->lang->line('all');?></option>
                                                                <?php 
                                                                    foreach($menuTags as $c){
                                                                        if($c['TagTyp'] == 3){
                                                                        ?>
                                                                        <option value="<?= $c['TagId']?>" <?php if($key['ItemSale'] == $c['TagId']){echo 'selected';}?>><?= $c['TDesc']?></option>
                                                                <?php } } ?>
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-3 col-6">
                                                                <label for="description1_cid"><?= $this->lang->line('cuisine');?></label>
                                                                <select class="form-control form-control-sm cuisine" id="description<?= $n?>_cid" name="description_cid[]" onchange="getCategory(<?= $n?>)">
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
                                                                <select class="form-control form-control-sm menuCategory" id="description<?= $n?>_mcatgid" name="description_mcatgid[]" onchange="getItems(<?= $n?>)">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>

                                                                    <?php foreach($menucat as $mn){?>
                                                                            <option value="<?= $mn['MCatgId']?>" <?php if($key['MCatgId'] == $mn['MCatgId']){echo 'selected';}?>><?= $mn['MCatgNm']?></option>
                                                                    <?php } ?>
                                                                    
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_item_div" style="display: block;">
                                                                <label for="description<?= $n?>_item"><?= $this->lang->line('item');?></label>
                                                                <input type="hidden" name="" id="item_id<?= $n?>" value="<?= $key['ItemId']?>">
                                                                <select class="form-control form-control-sm items" id="description<?= $n?>_item" name="description_item[]" onchange="getItemPortion(<?= $n?>)">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    <?php foreach($itemList as $item){ ?>
                                                                            <option value="<?= $item['ItemId']; ?>" <?php if($item['ItemId']== $key['ItemId']) { echo 'selected'; } ?> ><?= $item['Name']?></option>
                                                                    <?php } ?>

                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_itemportion_div" style="display: block;">
                                                                <label for="description<?= $n?>_itemportion"><?= $this->lang->line('itemPortion');?></label>
                                                                <input type="hidden" name="" id="ipcd<?= $n?>" value="<?= $key['IPCd']?>">
                                                                <select class="form-control form-control-sm itemPortion" id="description<?= $n?>_itemportion" name="description_itemportion[]">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    <?php foreach($portions as $ip){?>
                                                                        <option value="<?= $ip['IPCd']?>" <?php if($key['IPCd'] == $ip['IPCd']){ echo 'selected'; } ?>><?= $ip['Name']?></option>\
                                                                <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_quantity_div" style="display: block;">
                                                                <label for="description<?= $n?>_quantity"><?= $this->lang->line('quantity');?></label>
                                                                <input type="number" class="form-control form-control-sm" id="description<?= $n?>_quantity" name="description_quantity[]" value="<?= $key['Qty']?>">
                                                            </div>

                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_disccid" style="display: block;">
                                                                <label><?= $this->lang->line('discount');?> <?= $this->lang->line('cuisine');?></label>
                                                                <select class="form-control form-control-sm disccuisine" id="description<?= $n; ?>_disccid" name="description_disc_cid[]">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    <?php foreach($cuisines as $c){ ?>
                                                                        <option value="<?= $c['CID']?>" <?php if($key['Disc_CID'] == $c['CID']){echo 'selected';}?>><?= $c['Name']?></option>
                                                                <?php } ?>
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_mcatgid_div" style="display: block;">
                                                                <label><?= $this->lang->line('discount');?> <?= $this->lang->line('menuCategory');?></label>
                                                                <select class="form-control form-control-sm discmenuCategory select2 custom-select" id="description<?= $n; ?>_disc_mcatgid" name="description_disc_mcatgid[]">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    <?php foreach($menucat as $mn){?>
                                                                            <option value="<?= $mn['MCatgId']?>" <?php if($key['Disc_MCatgId'] == $mn['MCatgId']){echo 'selected';}?>><?= $mn['MCatgNm']?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_discountitem_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountitem"><?= $this->lang->line('discountItem');?></label>
                                                                <input type="hidden" name="" id="disc_item_id<?= $n?>" value="<?= $key['Disc_ItemId']?>">
                                                                <select class="form-control form-control-sm select2 custom-select discountItems" id="description<?= $n?>_discountitem" name="description_discountitem[]" onchange="getDiscItemPortion(<?= $n?>)">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    <?php foreach($itemList as $item){ ?>
                                                                            <option value="<?= $item['ItemId']; ?>" <?php if($item['ItemId']== $key['Disc_ItemId']) { echo 'selected'; } ?> ><?= $item['Name']?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_itemtyp_div" style="display: block;">
                                                                <label for="description<?= $n?>_itemtyp"><?= $this->lang->line('discount');?> <?= $this->lang->line('item');?> <?= $this->lang->line('type');?></label>
                                                                    <select class="form-control form-control-sm discitemType" id="description<?= $n?>_discitemtyp" name="description_discitemtyp[]" onchange="getDiscPortionByItmTyp_edit(<?= $n?>)">
                                                                        <option value="0"><?= $this->lang->line('select');?></option>
                                                                        <?php foreach($menuTags as $it){
                                                                            if($it['TagTyp'] == 2){  ?>
                                                                            <option value="<?= $it['TagId']?>" <?php if($key['Disc_ItemTyp'] == $it['TagId']){echo 'selected';}?>><?= $it['TDesc']?></option>
                                                                        <?php } } ?>
                                                                    </select>
                                                            </div>

                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_discountitemportion_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountitemportion"><?= $this->lang->line('discountItemPortion');?></label>
                                                                <input type="hidden" name="" id="disc_ipcd<?= $n?>" value="<?= $key['Disc_IPCd']?>">
                                                                <select class="form-control form-control-sm discountItemPortion" id="description<?= $n?>_discountitemportion" name="description_discountitemportion[]">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    <?php 
                                                                    foreach ($portions as $po) {
                                                                    ?>
                                                                    <option value="<?= $po['IPCd']; ?>" <?php if($key['Disc_IPCd'] == $po['IPCd']){ echo 'selected'; } ?>><?= $po['Name']; ?></option>
                                                                <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_discountquantity_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountquantity"><?= $this->lang->line('discountItemQuantity');?></label>
                                                                <input type="number" class="form-control form-control-sm discQty" id="description<?= $n?>_discountquantity" name="description_discountquantity[]" value="<?= $key['Disc_Qty']?>">
                                                            </div>

                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_discountitempercentage_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountitempercentage"><?= $this->lang->line('discountItemPercentage');?></label>
                                                                <input type="number" class="form-control form-control-sm" id="description<?= $n?>_discountitempercentage" name="description_discountitempercentage[]" value="<?= $key['DiscItemPcent']?>">
                                                            </div>

                                                            <div class="form-group col-md-3 col-6" id="description<?= $n?>_discountmaxamt_div" style="display: block;">
                                                                <label for="description<?= $n?>discountmaxamt"><?= $this->lang->line('discount');?> <?= $this->lang->line('maximum');?> <?= $this->lang->line('amount');?></label>
                                                                <input type="number" class="form-control form-control-sm discmaxamt" id="description<?= $n?>_disc_max_amt" name="description_disc_max_amt[]" value="<?= $key['DiscMaxAmt']; ?>">
                                                            </div>

                                                            <div class="form-group col-md-3 col-6 billBased" id="description<?= $n?>_minbillamount_div" style="display: block;">
                                                                <label for="description<?= $n?>_minbillamount"><?= $this->lang->line('minimumBillAmount');?></label>
                                                                <input type="number" class="form-control form-control-sm minbillamt" id="description<?= $n?>_minbillamount" name="description_minbillamount[]" value="<?= $key['MinBillAmt']?>">
                                                            </div>
                                                            <div class="form-group col-md-3 col-6 billBased" id="description<?= $n?>_discountpercent_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountpercent"><?= $this->lang->line('discountPercentage');?></label>
                                                                <input type="number" class="form-control form-control-sm discpercent" id="description<?= $n?>_discountpercent" name="description_discountpercent[]" value="<?= $key['Disc_pcent']?>">
                                                            </div>
                                                            <div class="form-group col-md-3 col-6 billBased" id="description<?= $n?>_discountamount_div" style="display: block;">
                                                                <label for="description<?= $n?>_discountamount"><?= $this->lang->line('discountAmount');?> </label>
                                                                <input type="number" class="form-control form-control-sm discamount" id="description<?= $n?>_discountamount" name="description_discountamount[]" value="<?= $key['Disc_Amt']?>">
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
        // itemPortion
        $('.discountItems, .menuCategory, .discmenuCategory, .items, .itemPortion, .discountItemPortion').select2();
    });

    function changeSchemeCategory(){
        var schTyp = $(`#sch_typ`).val();
        var schCat = $('#schcatg').val();
        $('.cuisine').prop("disabled", false);
        $('.itemType').prop("disabled", false);
        $('.itemSale').prop("disabled", false);
        $('.menuCategory').prop("disabled", false);
        $('.items').prop("disabled", false);
        $('.itemPortion').prop("disabled", false);
        // $('.billBased').hide();
        $('#add_more').show();

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
                    // discount amount
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
    }

    getCategoryl();
    function getCategoryl(){
      var SchCatg = "<?php echo $scheme['SchCatg']; ?>";

        var SchTyp = $('#sch_typ').val();
        if(SchTyp > 0){

            $('.billBased').hide();
            if(SchTyp == 1){
                $('.billBased').show();
            }

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
                    changeSchemeCategory();
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
        var schcatg = "<?php echo $scheme['SchCatg']; ?>";
        if(schcatg !=4){
            getDiscountItems(i);
        }
        getPortionByItmTyp_edit(i);
        getDiscPortionByItmTyp_edit(i);
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
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_itemtyp_div" style="display: block;">\
                            <label for="description'+num_desc+'_itemtyp"><?= $this->lang->line('item');?> <?= $this->lang->line('type');?></label>\
                                <select class="form-control form-control-sm itemType" id="description'+num_desc+'_itemtyp" name="description_itemtyp[]" onchange="getPortionByItmTyp(this, '+num_desc+')">\
                                    <option value=""><?= $this->lang->line('select');?></option>\
                                    <?php foreach($menuTags as $key){
                                        if($key['TagTyp'] == 2){ ?>
                                        <option value="<?= $key['TagId']?>"><?= $key['TDesc']?></option>\
                                    <?php } } ?>
                                </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_itemsales" style="display: block;">\
                            <label for="description'+num_desc+'_itemsales"><?= $this->lang->line('itemSale');?></label>\
                            <select class="form-control form-control-sm itemSale" id="description'+num_desc+'_itemsale" name="description_itemsales[]">\
                            <option value=""><?= $this->lang->line('all');?></option>\
                                <?php foreach($menuTags as $key){
                                    if($key['TagTyp'] == 3){ ?>
                                        <option value="<?= $key['TagId']?>"><?= $key['TDesc']?></option>\
                                <?php } } ?>
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6">\
                            <label for="description'+num_desc+'_cid"><?= $this->lang->line('cuisine');?></label>\
                            <select class="form-control form-control-sm cuisine" id="description'+num_desc+'_cid" name="description_cid[]" onchange="getCategory2(this,'+num_desc+')">\
                                <option value=""><?= $this->lang->line('select');?></option>\
                                <?php foreach($cuisines as $key){?>
                                        <option value="<?= $key['CID']?>"><?= $key['Name']?></option>\
                                <?php } ?>
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_mcatgid_div" style="display: block;">\
                            <label for="description'+num_desc+'_mcatgid"><?= $this->lang->line('menuCategory');?></label>\
                            <select class="form-control form-control-sm menuCategory select2 custom-select" id="description'+num_desc+'_mcatgid" name="description_mcatgid[]">\
                            <option value=""><?= $this->lang->line('select');?></option>\
                            <?php foreach($menucat as $key){?>
                                        <option value="<?= $key['MCatgId']?>"><?= $key['MCatgNm']?></option>\
                            <?php } ?>
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_item_div" style="display: block;">\
                            <label for="description'+num_desc+'_item"><?= $this->lang->line('item');?></label>\
                            <select class="form-control form-control-sm items select2 custom-select" id="description'+num_desc+'_item" name="description_item[]" onchange="getItemPortion2(this, '+num_desc+')">\
                            <option value=""><?= $this->lang->line('select');?></option>\
                            <?php foreach($itemList as $key){?>
                                        <option value="<?= $key['ItemId']?>"><?= $key['Name']?></option>\
                                <?php } ?>
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_itemportion_div" style="display: block;">\
                            <label for="description'+num_desc+'_itemportion"><?= $this->lang->line('itemPortion');?></label>\
                            <select class="form-control form-control-sm itemPortion select2 custom-select" id="description'+num_desc+'_itemportion" name="description_itemportion[]">\
                                <option value=""><?= $this->lang->line('select');?></option>\
                                <?php foreach($portions as $key){?>
                                        <option value="<?= $key['IPCd']?>"><?= $key['Name']?></option>\
                                <?php } ?>
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_quantity_div" style="display: block;">\
                            <label for="description'+num_desc+'_quantity"><?= $this->lang->line('quantity');?></label>\
                            <input type="number" class="form-control form-control-sm" id="description'+num_desc+'_quantity" name="description_quantity[]" value="0">\
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
                            <select class="form-control form-control-sm select2 custom-select discountItems" id="description'+num_desc+'_discountitem" name="description_discountitem[]" onchange="getDiscItemPortion2(this, '+num_desc+')">\
                                <option value="0"><?= $this->lang->line('select'); ?></option>\
                            </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_itemtyp_div" style="display: block;">\
                            <label for="description'+num_desc+'_itemtyp"><?= $this->lang->line('discount');?> <?= $this->lang->line('item');?> <?= $this->lang->line('type');?></label>\
                                <select class="form-control form-control-sm discitemType" id="description'+num_desc+'_discitemtyp" name="description_discitemtyp[]" onchange="getDiscPortionByItmTyp(this, '+num_desc+')">\
                                    <option value="0"><?= $this->lang->line('select');?></option>\
                                    <?php foreach($menuTags as $key){
                                        if($key['TagTyp'] == 2){  ?>
                                        <option value="<?= $key['TagId']?>"><?= $key['TDesc']?></option>\
                                    <?php } } ?>
                                </select>\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_discountitemportion_div" style="display: block;">\
                            <label for="description'+num_desc+'_discountitemportion"><?= $this->lang->line('discountItemPortion');?></label>\
                            <select class="form-control form-control-sm" id="description'+num_desc+'_discountitemportion" name="description_discountitemportion[]">\
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
                            <label for="description'+num_desc+'_discountitempercentage"><?= $this->lang->line('discountItemPercentage');?></label>\
                            <input type="number" class="form-control form-control-sm discpercent" id="description'+num_desc+'_discountitempercentage" name="description_discountitempercentage[]" value="0">\
                        </div>\
                        <div class="form-group col-md-3 col-6" id="description'+num_desc+'_discountmaxamt_div" style="display: block;">\
                            <label for="description'+num_desc+'discountmaxamt"><?= $this->lang->line('discount');?> <?= $this->lang->line('maximum');?> <?= $this->lang->line('amount');?></label>\
                            <input type="number" class="form-control form-control-sm discmaxamt" id="description'+num_desc+'_disc_max_amt" name="description_disc_max_amt[]" value="0">\
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
                if(res.length > 0){
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
            }
        })
    }
    function getItems(n){
        var cat = $('#description'+n+'_mcatgid').val();
        var it = $('#description'+n+'_itemtyp').val();
        var item = $('#item_id'+n).val();
        var item1 = $('#disc_item_id'+n).val();
        // alert(item);
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
                var schCatg = "<?php echo $scheme['SchCatg']; ?>";
                if(schCatg !=4){
                    getItemPortion(n);  
                    getDiscItemPortion(n);
                }
            }
        })
        
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
        var ipcd = $('#ipcd'+n).val();
        if(item_id > 0){
            portionCommon(ItemTyp=0 ,CID=0, MCatgId=0, item_id, n, 'itemid', ipcd);
        }
    }

    function getDiscItemPortion(n){
        var ItemId = $('#description'+n+'_discountitem').val();
        var ip = $('#disc_ipcd'+n).val();

        if(ItemId> 0){
            $('#description'+n+'_discountitemportion').attr('required', true);
            $('#description'+n+'_discountquantity').attr('required', true);

            $('.disccuisine').val('').trigger('change');
            $('.discmenuCategory').val('').trigger('change');
            $('.discitemType').val("");
            // $('.discQty').val(1);
            $(`'discountItemPortion`).prop('required', true);
            $(`'discQty`).prop('required', true);

            portionForCommon(ItemTyp=0 ,CID=0, MCatgId=0, ItemId, n, 'itemid');

        }else{
            $('#description'+n+'_discountitemportion').attr('required', false);
            $('#description'+n+'_discountquantity').attr('required', false);
        }

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
        if(item_id > 0){
            portionCommon(ItemTyp=0 ,CID=0, MCatgId=0, item_id, n, 'itemid', 0);
        }
    }
    function getDiscItemPortion2(el, n){
        var item_id = el.value;

        if(item_id> 0){
            $('#description'+n+'_discountitemportion').attr('required', true);
            $('#description'+n+'_discountquantity').attr('required', true);

            portionForCommon(ItemTyp=0 ,CID=0, MCatgId=0, item_id, n, 'itemid');
        }else{
            $('#description'+n+'_discountitemportion').attr('required', false);
            $('#description'+n+'_discountquantity').attr('required', false);
        }
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

    function getPortionByItmTyp_edit(n){

        var schcatg = "<?php echo $scheme['SchCatg']; ?>";
        // item type based =4
        if(schcatg == 4){
            var itemTyp = $(`#description${n}_itemtyp`).val();
            var ip = $('#ipcd'+n).val();
            
            if(itemTyp > 0){
                portionCommon(ItemTyp ,CID=0, MCatgId=0, ItemId=0, n, 'itemtype', ip)
            }
        }
    }

    function getDiscPortionByItmTyp_edit(n){
        var schcatg = "<?php echo $scheme['SchCatg']; ?>";
        var ip = $('#disc_ipcd'+n).val();

        // item type based =4
        if(schcatg == 4 || schcatg == 75){
            var itemTyp = $(`#description${n}_discitemtyp`).val();
            if(itemTyp > 0){
                portionForCommon(itemTyp ,CID=0, MCatgId=0, ItemId=0, n, 'itemtype');
            }
        }
    }


    function getPortionByItmTyp(el, n){
        var schcatg = $(`#schcatg`).val();
        // item type based =4
        if(schcatg == 4){
            var itemTyp = $(`#description${n}_itemtyp`).val();
            if(itemTyp > 0){
                portionCommon(itemTyp ,CID=0, MCatgId=0, ItemId=0, n, 'itemtype', 0);
            }
        }
    }

    function getDiscPortionByItmTyp(el, n){
        var schcatg = $(`#schcatg`).val();
        var ItemTyp = $(`#description${n}_discitemtyp`).val();
        
        if(ItemTyp > 0){
            $('.disccuisine').val('').trigger('change');
            $('.discmenuCategory').val('').trigger('change');
            $('.discountItems').val('').trigger('change');
            // $('.discQty').val(1);
            $(`'discountItemPortion`).prop('required', true);
            $(`'discQty`).prop('required', true);
        }

        if(ItemTyp > 0){
            portionForCommon(ItemTyp ,CID=0, MCatgId=0, ItemId=0, n, 'itemtype');
        }
    }

    function getCategoryDisc(el, n){
        var CID = el.value;

        if(CID > 0){
            $('.discmenuCategory').val('').trigger('change');
            $('.discountItems').val('').trigger('change');
            $('.discitemType').val("");
            // $('.discQty').val(1);
            $(`'discountItemPortion`).prop('required', true);
            $(`'discQty`).prop('required', true);
            
            portionForCommon(ItemTyp=0 ,CID, MCatgId=0, ItemId=0, n, 'cid');
        }

    }

    function getItemsDisc(el, n){
        var MCatgId = $('#description'+n+'_disc_mcatgid').val();
        if(MCatgId > 0){
            $('.disccuisine').val('').trigger('change');
            $('.discountItems').val('').trigger('change');
            $('.discitemType').val("");
            // $('.discQty').val(1);
            $(`'discountItemPortion`).prop('required', true);
            $(`'discQty`).prop('required', true);

            portionForCommon(ItemTyp=0 ,CID=0, MCatgId, ItemId=0, n, 'cat');
        }

    }

    function portionCommon(ItemTyp ,CID, MCatgId, ItemId, n, type, ipcd){

        $.post('<?= base_url('restaurant/get_portion_itemtype') ?>',{ItemTyp:ItemTyp, CID:CID, MCatgId:MCatgId, ItemId:ItemId, type:type},function(res){
            if(res.status == 'success'){
                var opt = `<option value=""><?= $this->lang->line('select'); ?></option>`;
              res.response.forEach((item, index) => {
                if(ipcd > 0){
                var ch = '';
                    if(ipcd == item.IPCd){
                        ch = 'selected';
                    }
                }

                opt += `<option value="${item.IPCd}" ${ch}>${item.Portions}</option>`;
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