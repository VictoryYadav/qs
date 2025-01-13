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
                                        <form id="add_item_form" method="post" enctype="multipart/form-data">
                                            
                                            <div class="row add_item_div">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label for="item_name"><?= $this->lang->line('itemName'); ?></label>
                                                    <input type="text" class="form-control form-control-sm" name="ItemNm" required="" id="item_name">
                                                    <div class="select_option"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label for=""><?= $this->lang->line('uploadImage'); ?></label>
                                                    <input type="file" class="form-control" id="item_file" name="item_file" accept="image/jpg,image/jpeg">
                                                </div>
                                                <?php if($this->session->flashdata('error')): ?>
                                                    <small class="text-danger" role="alert"><?= $this->session->flashdata('error') ?></small>
                                                <?php endif; ?>
                                            </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="item_desc"><?= $this->lang->line('description'); ?></label>
                                                        <textarea class="form-control form-control-sm" required="" rows="3" name="ItmDesc1">-</textarea>
                                                    </div>
                                                </div>

                                                <?php
                                                for ($i = 1; $i < sizeof($languages); $i++) { ?>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="item_ingredients"><?= $this->lang->line('description'); ?> <?= $languages[$i]['LngName']; ?></label>
                                                            <textarea class="form-control form-control-sm" name="ItmDesc<?= $languages[$i]['LCd']; ?>" rows="3">-</textarea>
                                                        </div>
                                                    </div>
                                               <?php } ?>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="item_ingredients"><?= $this->lang->line('ingredients'); ?></label>
                                                        <textarea class="form-control form-control-sm" name="Ingeredients1" rows="3">-</textarea>
                                                    </div>
                                                </div>

                                                <?php
                                                for ($i = 1; $i < sizeof($languages); $i++) { ?>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="item_ingredients"><?= $this->lang->line('ingredients'); ?> <?= $languages[$i]['LngName']; ?></label>
                                                            <textarea class="form-control form-control-sm" name="Ingeredients<?= $languages[$i]['LCd']; ?>" rows="3">-</textarea>
                                                        </div>
                                                    </div>
                                               <?php } ?>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="cid_input"><?= $this->lang->line('cuisine'); ?></label>
                                                        <select class="form-control form-control-sm" required="" name="CID" id="cuisine" onchange="getCategory()">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                                foreach($CuisineList as $row){
                                                                    echo "<option value='".$row['CID']."'>".$row['Name']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="menu_category"><?= $this->lang->line('menuCategory'); ?></label>
                                                            <select class="form-control form-control-sm" required="" name="MCatgId" id="menucat">
                                                                <option value=""><?= $this->lang->line('select'); ?></option>
                                                            </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="category_type"><?= $this->lang->line('cuisineType'); ?></label>
                                                        <select class="form-control form-control-sm" required="" name="CTyp" id="CTyp" onchange="getFood()">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($ctypList as $key) {
                                                             ?>
                                                            <option value="<?= $key['CTyp']; ?>" ><?= $key['Usedfor']; ?></option>
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
                                                        <input type="time" class="form-control form-control-sm" required="" name="FrmTime" placeholder="Enter From Time" value="06:00">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="ToTime"><?= $this->lang->line('toTime'); ?></label>
                                                        <input type="time" class="form-control form-control-sm item_form" required="" aria-describedby="itemHelp" placeholder="Enter To Time" name="ToTime" value="23:59">
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="FrmTime1"><?= $this->lang->line('alternateFromTime'); ?></label>
                                                        <input type="time" class="form-control form-control-sm" required="" placeholder="Enter From Time 1" name="AltFrmTime" value="06:00">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="FrmTime2"><?= $this->lang->line('alternateToTime'); ?></label>
                                                        <input type="time" class="form-control form-control-sm" required="" placeholder="Enter From Time 2" name="AltToTime" value="23:59">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="item_type"><?= $this->lang->line('item'); ?> <?= $this->lang->line('type'); ?></label>
                                                        <select class="form-control form-control-sm" name="ItemTyp">
                                                            <option value="0"><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($menuTags as $key) {
                                                                if($key['TagTyp'] == 2){
                                                             ?>
                                                            <option value="<?= $key['TagId']; ?>"><?= $key['TDesc']; ?></option>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="item_attribute"><?= $this->lang->line('itemAttribute'); ?></label>
                                                        <select class="form-control form-control-sm" name="ItemAttrib">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($menuTags as $key) {
                                                                if($key['TagTyp'] == 1){
                                                             ?>
                                                            <option value="<?= $key['TagId']; ?>"><?= $key['TDesc']; ?></option>
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
                                                            <option value="<?= $key['TagId']; ?>"><?= $key['TDesc']; ?></option>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="item_tag"><?= $this->lang->line('itemTag'); ?></label>
                                                        <select class="form-control form-control-sm" name="ItemTag">
                                                            <option value="0"><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($menuTags as $key) {
                                                                if($key['TagTyp'] == 4){
                                                             ?>
                                                            <option value="<?= $key['TagId']; ?>"><?= $key['TDesc']; ?></option>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="packaging_charges"><?= $this->lang->line('packingCharge'); ?></label>
                                                        <input type="number" class="form-control form-control-sm" required="" name="PckCharge" placeholder="Enter Packaging Charges" value="0">
                                                    </div>
                                                </div>

                                                 <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="kitcd"><?= $this->lang->line('kitchen'); ?></label>
                                                        <select class="form-control form-control-sm" required="" name="KitCd">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                                foreach($Eat_Kit as $row){
                                                                    echo "<option value='".$row['KitCd']."'>".$row['KitName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="PrepTime"><?= $this->lang->line('preparationTime'); ?> <?= $this->lang->line('inMinutes'); ?></label>
                                                        <input type="number" class="form-control form-control-sm" required="" name="PrepTime" value="5" />
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="DayNo"><?= $this->lang->line('day'); ?></label>
                                                        <select class="form-control form-control-sm" required="" name="DayNo">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($weekDay as $key) {
                                                             ?>
                                                            <option value="<?= $key['DayNo']; ?>" <?php if($key['DayNo'] == 8){ echo 'selected'; } ?> ><?= $key['Name']; ?></option>
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
                                                             <option value="<?= $key['UOMCd']; ?>"><?= $key['Name']; ?> </option>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="max_quantity"><?= $this->lang->line('maxQuantity'); ?></label>
                                                        <input type="number" class="form-control form-control-sm" required="" placeholder="Enter Max Quantity" value = 0 name="MaxQty" >
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="sale_period"><?= $this->lang->line('nutritionValue'); ?></label>
                                                        <input type="number" name="NV" required="" class="form-control form-control-sm" value="0">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="videoLInk"><?= $this->lang->line('videoLink'); ?></label>
                                                        <input type="text" name="videoLink" class="form-control form-control-sm" value="-">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="IMcCd"><?= $this->lang->line('shortCode'); ?></label>
                                                        <input type="number" name="IMcCd" class="form-control form-control-sm">
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
                                                        <tr>
                                                            <td></td>
                                                            <td>
                                                                <select name="sections[]" id="" class="form-control form-control-sm" required="">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    <?php 
                                                                    foreach ($EatSections as $key) { ?>
                                                                    <option value="<?= $key['SecId']; ?>"><?= $key['Name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <select name="portions[]" id="" class="form-control form-control-sm" required="">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    <?php 
                                                                    foreach ($ItemPortions as $key) { ?>
                                                                    <option value="<?= $key['IPCd']; ?>"><?= $key['Name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input type="number" name="price[]" id="" class="form-control form-control-sm" required="" />
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-sm btn-rounded deleteRow"><i class="fa fa-trash"></i></button>
                                                            </td>
                                                        </tr>
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

function getCategory(){
    var cui = $('#cuisine').val();
    
    $.ajax({
        url: "<?php echo base_url('restaurant/item_list_get_category'); ?>",
        type: "post",
        data:{'CID': cui},
        success: function(data){
            
            data = JSON.parse(data);
            var All = "<?= $this->lang->line('all'); ?>";
            var b = '<option value = "">'+All+'</option>';
            for(i = 0;i<data.length;i++){
                b = b+'<option value="'+data[i].MCatgId+'">'+data[i].MCatgNm+'</option>';
            }
            
            $('#menucat').html(b);
        }
    });
}

function getFood(){
    var CTyp = $('#CTyp').val();

    $.post('<?= base_url('restaurant/getfood_by_ctype') ?>',{CTyp:CTyp},function(res){
        if(res.status == 'success'){
            var temp = "<option value= ><?= $this->lang->line('select'); ?></option>";
            res.response.forEach((item, index) => {
                temp += `<option value="${item.FID}">${item.Opt}</option>`;
            });

            $('#FID').html(temp);

        }else{
          alert(res.response);
        }
    });
}
</script>