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
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3">Multi Language Name</h5>
                                        <form method="post" enctype="multipart/form-data" id="common_form">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('type'); ?></label>
                                                        <select  name="type" class="form-control form-control-sm select2 custom-select" required="" id="type">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="Eat_Kit"><?= $this->lang->line('kitchen'); ?></option>
                                                            <option value="3POrders"><?= $this->lang->line('thirdParty'); ?></option>
                                                            <option value="ConfigPymt"><?= $this->lang->line('payment'); ?> <?= $this->lang->line('mode'); ?></option>
                                                            <option value="Cuisines"><?= $this->lang->line('cuisine'); ?></option>
                                                            <option value="EatCuisine">Eat<?= $this->lang->line('cuisine'); ?></option>
                                                            <option value="Eat_Casher"><?= $this->lang->line('cashier'); ?></option>
                                                            <option value="Tax"><?= $this->lang->line('tax'); ?></option>
                                                            <option value="UserRoles"><?= $this->lang->line('user'); ?> <?= $this->lang->line('role'); ?></option>
                                                            <option value="Eat_Lang"><?= $this->lang->line('language'); ?></option>
                                                            <option value="Eat_Sections"><?= $this->lang->line('section'); ?></option>
                                                            <option value="FoodType"><?= $this->lang->line('food'); ?> <?= $this->lang->line('type'); ?></option>
                                                            <option value="ItemPortions"><?= $this->lang->line('itemPortion'); ?></option>
                                                            <option value="MenuItem"><?= $this->lang->line('menu'); ?> <?= $this->lang->line('item'); ?></option>
                                                            <option value="ItemTypes"><?= $this->lang->line('item'); ?> <?= $this->lang->line('type'); ?></option>
                                                            <option value="MenuTags"><?= $this->lang->line('menu'); ?> <?= $this->lang->line('tag'); ?></option>
                                                            <option value="Months"><?= $this->lang->line('month'); ?></option>
                                                            <option value="ordeType"><?= $this->lang->line('order'); ?> <?= $this->lang->line('type'); ?></option>
                                                            <option value="RMCatg">RM<?= $this->lang->line('category'); ?></option>
                                                            <option value="RMItems">RM<?= $this->lang->line('item'); ?></option>
                                                            <option value="RMSuppliers">RM<?= $this->lang->line('supplier'); ?></option>
                                                            <option value="RMUOM">RM<?= $this->lang->line('uom'); ?></option>
                                                            <option value="WeekDays"><?= $this->lang->line('week'); ?> <?= $this->lang->line('day'); ?></option>
                                                        </select>
                                                       
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="common_file" class="form-control" required="" accept=".csv" id="file">
                                                        <small class="text-danger"><?= $this->lang->line('uploadOnlyCSVFile'); ?> </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('upload'); ?>">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3"><?= $this->lang->line('menu'); ?> <?= $this->lang->line('item'); ?></h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="<?= base_url('restaurant/download_menu_item') ?>" class="btn btn-sm btn-primary">Download CSV File Format</a>
                                        </div>
                                    </div>
                                        <form method="post" enctype="multipart/form-data" id="menu_form">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="common_file" class="form-control" required="" accept=".csv" id="file">
                                                        <small class="text-danger"><?= $this->lang->line('uploadOnlyCSVFile'); ?> </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('upload'); ?>">
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

$(document).ready(function() {
    $('#type').select2();
});

$('#common_form').on('submit', function(e){
    e.preventDefault();

    var formData = new FormData(document.getElementById("common_form"));
    callAjax(formData);

});

function callAjax(formData){
   $.ajax({
           url : '<?= base_url('restaurant/multi_lang_upload') ?>',
           type : 'POST',
           data : formData,
           processData: false,  
           contentType: false,  
           success : function(data) {
               alert(data.response);
               // location.reload();
           }
    }); 
}

$('#menu_form').on('submit', function(e){
    e.preventDefault();

    var formData = new FormData(document.getElementById("menu_form"));
    
    $.ajax({
           url : '<?= base_url('restaurant/menu_lang_upload') ?>',
           type : 'POST',
           data : formData,
           processData: false,  
           contentType: false,  
           success : function(data) {
               alert(data.response);
               // location.reload();
           }
    });

});

</script>