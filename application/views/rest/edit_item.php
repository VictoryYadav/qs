<?php $this->load->view('layouts/admin/head'); ?>
<style>
    .select_option{
        background: white;
        padding: 10px;
        display: none;
        max-height: 254px;
        overflow-y: scroll;
        position: absolute;
        width: 95%;
        z-index: 2;
        box-shadow: 2px 3px 6px #00000070;
    }

    .select_option a {
        color: black;
        width: 100%;
        display: block;
        text-decoration: none;
        margin-bottom: 5px;
        border-bottom: 1px solid #8080806b;
    }
</style>
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

            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <?php if($this->session->flashdata('success')): ?>
                                            <div class="alert alert-success" role="alert"><?= $this->session->flashdata('success') ?></div>
                                        <?php endif; ?>
                                        <form id="edit_item_form" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="ItemId" value="<?= $ItemId; ?>">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label for="item_name"><?= $this->lang->line('itemName'); ?></label>
                                                    <input type="text" class="form-control form-control-sm" placeholder="Enter item name" name="ItemNm" required="" id="item_name" value="<?= $detail['ItemNm']; ?>" readonly>
                                                    <div class="select_option"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="row add_item_div">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6 col-6">
                                                            <div class="form-group">
                                                                <label for=""><?= $this->lang->line('uploadImage'); ?></label>
                                                                <input type="file" class="form-control" id="item_file" name="item_file" accept="image/jpg,image/jpeg">
                                                            </div>
                                                            <?php if($this->session->flashdata('error')): ?>
                                                                <small class="text-danger" role="alert"><?= $this->session->flashdata('error') ?></small>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-md-6 col-6">
                                                            <?php 
                                                            $path = 'uploads/e'.authuser()->EID;
                                                            ?>
                                                            <a href="<?= base_url($path.'/'.$detail['ItemNm'].'.jpg') ?>" target="_blank">
                                                            <img src="<?= base_url($path.'/'.$detail['ItemNm'].'.jpg') ?>" alt="<?= $detail['ItemNm']; ?>" style="height: 60px;">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="item_desc"><?= $this->lang->line('description'); ?></label>

                                                        <textarea class="form-control form-control-sm" required="" rows="3" name="ItmDesc1"><?= $detail['ItmDesc1']; ?></textarea>
                                                    </div>
                                                </div>

                                                <?php
                                                for ($i = 1; $i < sizeof($languages); $i++) { ?>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="item_ingredients"><?= $this->lang->line('description'); ?> <?= $languages[$i]['LngName']; ?></label>
                                                            <textarea class="form-control form-control-sm" name="ItmDesc<?= $languages[$i]['LCd']; ?>" rows="3"><?= $detail['ItmDesc'.$languages[$i]['LCd']]; ?></textarea>
                                                        </div>
                                                    </div>
                                               <?php } ?>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="item_ingredients"><?= $this->lang->line('ingredients'); ?></label>
                                                        <textarea class="form-control form-control-sm" name="Ingeredients1" rows="3"><?= $detail['Ingeredients1']; ?></textarea>
                                                    </div>
                                                </div>

                                                <?php
                                                for ($i = 1; $i < sizeof($languages); $i++) { ?>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="item_ingredients"><?= $this->lang->line('ingredients'); ?> <?= $languages[$i]['LngName']; ?></label>
                                                            <textarea class="form-control form-control-sm" name="Ingeredients<?= $languages[$i]['LCd']; ?>" rows="3"><?= $detail['Ingeredients'.$languages[$i]['LCd']]; ?></textarea>
                                                        </div>
                                                    </div>
                                               <?php } ?>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="cid_input"><?= $this->lang->line('cuisine'); ?></label>
                                                        <select class="form-control form-control-sm" required="" name="CID">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                                foreach($CuisineList as $row){ ?>
                                                            <option value="<?= $row['CID']; ?>" <?php if($row['CID'] == $detail['CID']){ echo 'selected'; } ?> ><?= $row['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="menu_category"><?= $this->lang->line('menuCategory'); ?></label>
                                                            <select class="form-control form-control-sm" required="" name="MCatgId">
                                                                <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                                foreach($MCatgIds as $row){ ?>
                                                                    <option value="<?= $row['MCatgId']; ?>" <?php if($row['MCatgId'] == $detail['MCatgId']){ echo 'selected'; } ?> ><?= $row['MCatgNm']; ?></option>
                                                            <?php } ?>
                                                            </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="category_type"><?= $this->lang->line('cuisineType'); ?></label>
                                                        <select class="form-control form-control-sm" required="" name="CTyp" onchange="getFood()" id="CTyp">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($ctypList as $key) {
                                                             ?>
                                                            <option value="<?= $key['CTyp']; ?>" <?php if($key['CTyp'] == $detail['CTyp']){ echo 'selected'; } ?>><?= $key['Usedfor']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="fid"><?= $this->lang->line('foodType'); ?></label>
                                                        <select class="form-control form-control-sm" required="" name="FID" id="FID">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="FrmTime"><?= $this->lang->line('fromTime'); ?></label>
                                                        <input type="time" class="form-control form-control-sm" required="" name="FrmTime" placeholder="Enter From Time" value="<?= $detail['FrmTime']; ?>">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="ToTime"><?= $this->lang->line('toTime'); ?></label>
                                                        <input type="time" class="form-control form-control-sm item_form" required="" aria-describedby="itemHelp" placeholder="Enter To Time" name="ToTime" value="<?= $detail['ToTime']; ?>">
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="FrmTime1"><?= $this->lang->line('alternateFromTime'); ?></label>
                                                        <input type="time" class="form-control form-control-sm" required="" placeholder="Enter From Time 1" name="AltFrmTime" value="<?= $detail['AltFrmTime']; ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="FrmTime2"><?= $this->lang->line('alternateToTime'); ?></label>
                                                        <input type="time" class="form-control form-control-sm" required="" placeholder="Enter From Time 2" name="AltToTime" value="<?= $detail['AltToTime']; ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="item_type"><?= $this->lang->line('item'); ?> <?= $this->lang->line('type'); ?></label>
                                                        <select class="form-control form-control-sm" required="" name="ItemTyp">
                                                            <option value="0"><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($menuTags as $key) {
                                                                if($key['TagTyp'] == 2){
                                                             ?>
                                                            <option value="<?= $key['TagId']; ?>" <?php if($key['TagId'] == $detail['ItemTyp']){ echo 'selected'; } ?>><?= $key['TDesc']; ?></option>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="item_attribute"><?= $this->lang->line('itemAttribute'); ?></label>
                                                        <select class="form-control form-control-sm" name="ItemAttrib" >
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($menuTags as $key) {
                                                                if($key['TagTyp'] == 1){
                                                             ?>
                                                            <option value="<?= $key['TagId']; ?>" <?php if($key['TagId'] == $detail['ItemAttrib']){ echo 'selected'; } ?>><?= $key['TDesc']; ?></option>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            
                                                 <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="item_sale"><?= $this->lang->line('itemSale'); ?></label>
                                                        <select class="form-control form-control-sm" name="ItemSale">
                                                            <option value="0"><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($menuTags as $key) {
                                                                if($key['TagTyp'] == 3){
                                                             ?>
                                                            <option value="<?= $key['TagId']; ?>" <?php if($key['TagId'] == $detail['ItemSale']){ echo 'selected'; } ?>><?= $key['TDesc']; ?></option>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="item_tag"><?= $this->lang->line('itemTag'); ?></label>
                                                        <select class="form-control form-control-sm" name="ItemTag">
                                                            <option value="0"><?= $this->lang->line('select'); ?></option>
                                                            <!-- <?php
                                                            foreach ($menuTags as $key) {
                                                                if($key['TagTyp'] == 2){
                                                             ?>
                                                            <option value="<?= $key['TagId']; ?>" <?php if($key['TagId'] == $detail['ItemTag']){ echo 'selected'; } ?>><?= $key['TDesc']; ?></option>
                                                            <?php } } ?> -->
                                                        </select>
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="packaging_charges"><?= $this->lang->line('packingCharge'); ?></label>
                                                        <input type="number" class="form-control form-control-sm" required="" name="PckCharge" placeholder="Enter Packaging Charges" value="<?= $detail['PckCharge']; ?>">
                                                    </div>
                                                </div>

                                                 <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="kitcd"><?= $this->lang->line('kitchen'); ?></label>
                                                        <select class="form-control form-control-sm" required="" name="KitCd">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                                foreach($Eat_Kit as $row){ ?>
                                                                <option value="<?= $row['KitCd']; ?>" <?php if($row['KitCd'] == $detail['KitCd']){ echo 'selected'; } ?>><?= $row['KitName']; ?></option>
                                                             <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="PrepTime"><?= $this->lang->line('preparationTime'); ?> <?= $this->lang->line('inMinutes'); ?></label>
                                                        <input type="number" class="form-control form-control-sm" required="" name="PrepTime" value="<?= $detail['PrepTime']; ?>"/>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="DayNo"><?= $this->lang->line('day'); ?></label>
                                                        <select class="form-control form-control-sm" required="" name="DayNo">
                                                            <?php
                                                            foreach ($weekDay as $key) {
                                                             ?>
                                                            <option value="<?= $key['DayNo']; ?>" <?php if($key['DayNo'] == $detail['DayNo']){ echo 'selected'; } ?>><?= $key['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="MTyp"><?= $this->lang->line('uom'); ?></label>
                                                        <select class="form-control form-control-sm item_form" id="sale_period" name="MTyp">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                            if(!empty($uomList)){
                                                                foreach ($uomList as $key) {
                                                             ?>
                                                             <option value="<?= $key['UOMCd']; ?>" <?php if($key['UOMCd'] == $detail['MTyp']){ echo 'selected'; } ?>><?= $key['Name']; ?> </option>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="max_quantity"><?= $this->lang->line('maxQuantity'); ?></label>
                                                        <input type="number" class="form-control form-control-sm"required="" placeholder="Enter Max Quantity" name="MaxQty" value="<?= $detail['MaxQty']; ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="sale_period"><?= $this->lang->line('nutritionValue'); ?></label>
                                                        <input type="number" name="NV" required="" class="form-control form-control-sm" value="<?= $detail['NV']; ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="videoLInk"><?= $this->lang->line('videoLink'); ?></label>
                                                        <input type="text" name="videoLink" class="form-control form-control-sm" value="<?= $detail['videoLink']; ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="IMcCd"><?= $this->lang->line('machineCode'); ?></label>
                                                        <input type="number" name="IMcCd" class="form-control form-control-sm" value="<?= $detail['IMcCd']; ?>">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="table-responsive">
                                                      <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    <button type="button" class="btn btn-success btn-sm btn-rounded" id="addrow"><i class="fa fa-plus"></i></button>
                                                                </th>
                                                                <th><?= $this->lang->line('section'); ?></th>
                                                                <th><?= $this->lang->line('portion'); ?></th>
                                                                <th><?= $this->lang->line('rate'); ?></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tblBody">
                                                        <?php 
                                                        if(!empty($itmRates)){
                                                            foreach ($itmRates as $iRate) { ?>
                                                        <tr>
                                                            <td></td>
                                                            <td>
                                                                <select name="sections[]" id="" class="form-control form-control-sm" required="">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    <?php 
                                                                    foreach ($EatSections as $key) { ?>
                                                                    <option value="<?= $key['SecId']; ?>" <?php if($key['SecId'] == $iRate['SecId']){ echo 'selected'; } ?>><?= $key['Name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <select name="portions[]" id="" class="form-control form-control-sm" required="">
                                                                    <option value="0"><?= $this->lang->line('select'); ?></option>
                                                                    <?php 
                                                                    foreach ($ItemPortions as $key) { ?>
                                                                    <option value="<?= $key['IPCd']; ?>" <?php if($key['IPCd'] == $iRate['Itm_Portion']){ echo 'selected'; } ?>><?= $key['Name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input type="number" name="price[]" id="" class="form-control form-control-sm" required="" value="<?= $iRate['ItmRate'] ?>" />
                                                            </td>
                                                            <td>
                                                               
                                                            </td>
                                                        </tr>
                                                    <?php } } ?>
                                                        </tbody>
                                                      </table>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        <input type="Submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('submit'); ?>">
                                        
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

$("#item_name").keyup(function(){
    var $name = $("#item_name").val();

    $.post("<?= base_url('restaurant/get_item_name'); ?>", 
        {
        name: $name,
        },
        function(result){

            if (Object.keys(JSON.parse(result)).length == 0) {
                $('.select_option').css('display','none');
            }else{
                var status = JSON.parse(result);

                var html = '';

                for (const key in status) {
                    if (status.hasOwnProperty(key)) {
                        const element = status[key];
                        html +=  '<a href="<?= base_url('restaurant/edit_item/')?>'+element.ItemId+'"> '+element.ItemNm+' </a>';
                    }
                }
                $('.select_option').html(html);
                $('.select_option').css('display','block');
            }
            
    });
});

$("#addrow").on("click", function () {
    
    var newRow = '<tr>\
                        <td></td>\
                        <td>\
                            <select name="sections[]" id="" class="form-control form-control-sm" required="">\
                                <option value=""><?= $this->lang->line('select'); ?></option>\
                                <?php 
                                foreach ($EatSections as $key) { ?>
                                <option value="<?= $key['SecId']; ?>"><?= $key['Name']; ?></option>\
                                <?php } ?>
                            </select>\
                        </td>\
                        <td>\
                            <select name="portions[]" id="" class="form-control form-control-sm" required="">\
                                <option value=""><?= $this->lang->line('select'); ?></option>\
                                <?php 
                                foreach ($ItemPortions as $key) { ?>
                                <option value="<?= $key['IPCd']; ?>"><?= $key['Name']; ?></option>\
                                <?php } ?>
                            </select>\
                        </td>\
                        <td>\
                            <input type="number" name="price[]" id="" class="form-control form-control-sm" required="" />\
                        </td>\
                        <td>\
                            <button type="button" class="btn btn-danger btn-sm btn-rounded deleteRow"><i class="fa fa-trash"></i></button>\
                        </td>\
                    </tr>';

        $("#tblBody").append(newRow);
});

// remove row
$("#tblBody").on('click','.deleteRow',function(){
    $(this).parent().parent().remove();
});

getFood();
function getFood(){
    var CTyp = $('#CTyp').val();
    var fid = "<?= $detail['FID']; ?>";

    $.post('<?= base_url('restaurant/getfood_by_ctype') ?>',{CTyp:CTyp},function(res){
        if(res.status == 'success'){
            var temp = "<option value= ><?= $this->lang->line('select'); ?></option>";
            res.response.forEach((item, index) => {
                var selct = ``;
                if(item.FID == fid){
                    selct = `selected`;
                }
                temp += `<option value="${item.FID}" ${selct}>${item.Opt}</option>`;
            });

            $('#FID').html(temp);

        }else{
          alert(res.response);
        }
    });
}

</script>