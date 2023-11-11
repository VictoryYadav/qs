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
                                        <?php if($this->session->flashdata('success')): ?>
                                            <div class="alert alert-success" role="alert"><?= $this->session->flashdata('success') ?></div>
                                        <?php endif; ?>
                                        <form id="add_item_form" method="post" enctype="multipart/form-data">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label for="item_name">Item Name</label>
                                                    <input type="text" class="form-control form-control-sm" placeholder="Enter item name" name="ItemNm" required="" id="item_name">
                                                    <div class="select_option"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="row add_item_div">
                                                <div class="col-md-12" style="margin-top: 15px;">
                                                    <div class="form-group">
                                                        <label for="">Upload Item Image</label>
                                                        <input type="file" class="form-control" id="item_file" name="item_file" required="" accept="image/jpg,image/jpeg">
                                                    </div>
                                                    <?php if($this->session->flashdata('error')): ?>
                                                        <small class="text-danger" role="alert"><?= $this->session->flashdata('error') ?></small>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="item_desc">Item Description</label>
                                                        <textarea class="form-control form-control-sm" required="" rows="3" name="ItmDesc">-</textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="item_ingredients">Item Ingredients</label>
                                                        <textarea class="form-control form-control-sm" name="Ingeredients" rows="3">-</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label for="menu_category">Menu Category</label>
                                                            <select class="form-control form-control-sm" required="" name="MCatgId">
                                                                <?php
                                                                    foreach($MCatgIds as $row){
                                                                        echo "<option value='".$row['MCatgId']."'>".$row['MCatgNm']."</option>";
                                                                    }
                                                                ?>
                                                            </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <?php
                                                            $ctyp = array('0' => 'Standard',
                                                                          '1' => 'Bar',
                                                                          '2' => 'Beverages',
                                                                          '3' => 'Dessert',
                                                                          '75' => 'Custom'
                                                                      ); 
                                                        ?>
                                                        <label for="category_type">Cuisine</label>
                                                        <select class="form-control form-control-sm" required="" name="CTyp">
                                                            <?php
                                                            foreach ($ctyp as $key => $value) {
                                                             ?>
                                                            <option value="<?= $key; ?>" ><?= $value; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label for="cid_input">CID</label>
                                                        <select class="form-control form-control-sm" required="" name="CID">
                                                            <?php
                                                                foreach($CuisineList as $row){
                                                                    echo "<option value='".$row['CID']."'>".$row['Name']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label for="fid">FID</label>
                                                        <select class="form-control form-control-sm" required="" name="FID">
                                                            <?php
                                                                foreach($FoodType as $row){
                                                                    echo "<option value='".$row['FID']."'>".$row['Opt']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <?php
                                                            $Prepration = array('5' => '5 Min',
                                                                          '10' => '10 Min',
                                                                          '15' => '15 Min',
                                                                          '20' => '20 Min',
                                                                          '25' => '25 Min'
                                                                      ); 
                                                        ?>
                                                        <label for="PrepTime">Prepration Time</label>
                                                        <select class="form-control form-control-sm" required="" name="PrepTime">
                                                            <?php
                                                            foreach ($Prepration as $key => $value) {
                                                             ?>
                                                            <option value="<?= $key; ?>"><?= $value; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <?php
                                                            $Dayno = array('0' => 'All Days',
                                                                          '1' => 'Sunday',
                                                                          '2' => 'Monday',
                                                                          '3' => 'Tuesday',
                                                                          '4' => 'Wednesday',
                                                                          '5' => 'Thursday',
                                                                          '6' => 'Friday',
                                                                          '7' => 'Saturday'
                                                                      ); 
                                                        ?>
                                                        <label for="DayNo">Day No</label>
                                                        <select class="form-control form-control-sm" required="" name="DayNo">
                                                            <?php
                                                            foreach ($Dayno as $key => $value) {
                                                             ?>
                                                            <option value="<?= $key; ?>"><?= $value; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label for="FrmTime">From Time</label>
                                                        <input type="time" class="form-control form-control-sm" required="" name="FrmTime" placeholder="Enter From Time" value="06:00">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label for="ToTime">To Time</label>
                                                        <input type="time" class="form-control form-control-sm item_form" required="" aria-describedby="itemHelp" placeholder="Enter To Time" name="ToTime" value="23:59">
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label for="FrmTime1">From Time 1</label>
                                                        <input type="time" class="form-control form-control-sm" required="" placeholder="Enter From Time 1" name="AltFrmTime" value="06:00">
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label for="FrmTime2">To Time 2</label>
                                                        <input type="time" class="form-control form-control-sm" required="" placeholder="Enter From Time 2" name="AltToTime" value="23:59">
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <?php
                                                            $Attribute = array('0' => 'Standard',
                                                                          '1' => 'Mild',
                                                                          '2' => 'Spicy',
                                                                          '3' => 'Very Spicy',
                                                                          '4' => 'Hot'
                                                                      ); 
                                                        ?>
                                                        <label for="item_attribute">Item Attribute</label>
                                                        <select class="form-control form-control-sm" name="ItemAttrib" required="">
                                                            <?php
                                                            foreach ($Attribute as $key => $value) {
                                                             ?>
                                                            <option value="<?= $key; ?>"><?= $value; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <?php
                                                            $item_type = array('0' => 'NA',
                                                                          '1' => 'Pizzas',
                                                                          '2' => 'Subs',
                                                                          '3' => 'Salad',
                                                                          '4' => 'Item based customization'
                                                                      ); 
                                                        ?>
                                                        <label for="item_type">Item Type</label>
                                                        <select class="form-control form-control-sm" required="" name="ItemTyp">
                                                            <?php
                                                            foreach ($item_type as $key => $value) {
                                                             ?>
                                                            <option value="<?= $key; ?>"><?= $value; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            
                                                 <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <?php
                                                            $item_sale = array('0' => 'NA',
                                                                          '1' => 'Must Try',
                                                                          '2' => 'Fast Selling'
                                                                      ); 
                                                        ?>
                                                        <label for="item_sale">Item Sale</label>
                                                        <select class="form-control form-control-sm" required="" name="ItemSale">
                                                            <?php
                                                            foreach ($item_sale as $key => $value) {
                                                             ?>
                                                            <option value="<?= $key; ?>"><?= $value; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <?php
                                                            $item_tag = array('0' => 'NA',
                                                                          '1' => 'Must Try',
                                                                          '2' => 'Fast Selling'
                                                                      ); 
                                                        ?>
                                                        <label for="item_tag">Item Tag</label>
                                                        <select class="form-control form-control-sm" required="" name="ItemTag">
                                                            <?php
                                                            foreach ($item_tag as $key => $value) {
                                                             ?>
                                                            <option value="<?= $key; ?>"><?= $value; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label for="packaging_charges">Packaging Charges</label>
                                                        <input type="number" class="form-control form-control-sm" required="" name="PckCharge" placeholder="Enter Packaging Charges" value="0">
                                                    </div>
                                                </div>

                                                 <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label for="kitcd">Kitchen</label>
                                                        <select class="form-control form-control-sm" required="" name="KitCd">
                                                            <?php
                                                                foreach($Eat_Kit as $row){
                                                                    echo "<option value='".$row['KitCd']."'>".$row['KitName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label for="max_quantity">Max Quantity</label>
                                                        <input type="number" class="form-control form-control-sm"required="" placeholder="Enter Max Quantity" value = 0 name="MaxQty" >
                                                    </div>
                                                </div>

                                                <!-- <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label for="sale_period">Sale Period</label>
                                                        <select class="form-control form-control-sm item_form" id="sale_period" name="sale_period">
                                                            <option value="0"> NA </option>
                                                            <option value="1"> Daily </option>
                                                            <option value="2"> Weekly </option>
                                                            <option value="3"> Monthly </option>
                                                            <option value="5"> Total (all purchase-all sales) </option>
                                                        </select>
                                                    </div>
                                                </div> -->
                                                
                                            </div>
                                            
                                        <input type="Submit" class="btn btn-sm btn-success" value="Save">
                                        
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

</script>