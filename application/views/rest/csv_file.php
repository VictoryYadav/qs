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
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3"><?= $this->lang->line('restaurant'); ?></h5>

                                        <a href="<?= base_url('uploads/common/Eatary.csv'); ?>" class="btn btn-sm btn-info" download><?= $this->lang->line('download'); ?> <?= $this->lang->line('format'); ?></a>
                                        <form method="post" enctype="multipart/form-data" id="eatary_form">
                                            <input type="hidden" name="type" value="eatary">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="eatary_file" class="form-control" required="" accept=".csv" id="file">
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

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3"><?= $this->lang->line('cuisine'); ?></h5> (Support)
                                    <a href="<?= base_url('uploads/common/Cuisines.csv'); ?>" class="btn btn-sm btn-info" download><?= $this->lang->line('download'); ?> <?= $this->lang->line('format'); ?></a>
                                        <form method="post" enctype="multipart/form-data" id="cuisine_form">
                                            <input type="hidden" name="type" value="cuisine">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="cuisine_file" class="form-control" required="" accept=".csv" id="file">
                                                        <small class="text-danger"><?= $this->lang->line('uploadOnlyCSVFile'); ?></small>
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

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3"><?= $this->lang->line('menuCategory'); ?></h5>
                                    <a href="<?= base_url('uploads/common/MenuCatg.csv'); ?>" class="btn btn-sm btn-info" download><?= $this->lang->line('download'); ?> <?= $this->lang->line('format'); ?></a>
                                        <form method="post" enctype="multipart/form-data" id="menucatg_form">
                                            <input type="hidden" name="type" value="menucatg">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="mcatg_file" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger"><?= $this->lang->line('uploadOnlyCSVFile'); ?></small>
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

                            <!-- <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3"><?= $this->lang->line('item'); ?> <?= $this->lang->line('type'); ?></h5>
                                    <a href="<?= base_url('uploads/common/aa.csv'); ?>" class="btn btn-sm btn-info"><?= $this->lang->line('download'); ?> <?= $this->lang->line('format'); ?></a>
                                        <form method="post" enctype="multipart/form-data" id="item_types_form">
                                            <input type="hidden" name="type" value="itemType">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="itemtype_file" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger"><?= $this->lang->line('uploadOnlyCSVFile'); ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('upload'); ?>">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div> -->

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3"><?= $this->lang->line('itemDetails'); ?></h5>
                                    <a href="<?= base_url('uploads/common/MenuItem.csv'); ?>" class="btn btn-sm btn-info" download><?= $this->lang->line('download'); ?> <?= $this->lang->line('format'); ?></a>
                                        <form method="post" enctype="multipart/form-data" id="menuitem_form">
                                            <input type="hidden" name="type" value="menuitem">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="mitem_file" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger"><?= $this->lang->line('uploadOnlyCSVFile'); ?></small>
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

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3"><?= $this->lang->line('item'); ?> <?= $this->lang->line('price'); ?></h5>
                                    <a href="<?= base_url('uploads/common/MenuItemRates.csv'); ?>" class="btn btn-sm btn-info" download><?= $this->lang->line('download'); ?> <?= $this->lang->line('format'); ?></a>
                                        <form method="post" enctype="multipart/form-data" id="itemRates_form">
                                            <input type="hidden" name="type" value="itemrates">
                                            <div class="row">
                                                
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="mitem_rates" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger"><?= $this->lang->line('uploadOnlyCSVFile'); ?></small>
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

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3"><?= $this->lang->line('item'); ?> <?= $this->lang->line('recommendation'); ?></h5>
                                    <a href="<?= base_url('uploads/common/itemRecos.csv'); ?>" class="btn btn-sm btn-info" download><?= $this->lang->line('download'); ?> <?= $this->lang->line('format'); ?></a>
                                        <form method="post" enctype="multipart/form-data" id="itemRecos_form">
                                            <input type="hidden" name="type" value="itemRecom">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="mitem_recos" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger"><?= $this->lang->line('uploadOnlyCSVFile'); ?></small>
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

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3"><?= $this->lang->line('kitchen'); ?></h5>
                                        <a href="<?= base_url('uploads/common/eatKit.csv'); ?>" class="btn btn-sm btn-info" download><?= $this->lang->line('download'); ?> <?= $this->lang->line('format'); ?></a>
                                        <form method="post" enctype="multipart/form-data" id="kitchen_form">
                                            <input type="hidden" name="type" value="kitchen">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="kitchen_file" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger"><?= $this->lang->line('uploadOnlyCSVFile'); ?></small>
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

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3"><?= $this->lang->line('dispense'); ?> <?= $this->lang->line('outlet'); ?></h5>
                                        <a href="<?= base_url('uploads/common/dispenseOutlet.csv'); ?>" class="btn btn-sm btn-info" download><?= $this->lang->line('download'); ?> <?= $this->lang->line('format'); ?></a>
                                        <form method="post" enctype="multipart/form-data" id="dispenseOutlet_form">
                                            <input type="hidden" name="type" value="dispenseOutlet">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="dispense_file" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger"><?= $this->lang->line('uploadOnlyCSVFile'); ?></small>
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

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3"><?= $this->lang->line('table'); ?></h5>
                                        <a href="<?= base_url('uploads/common/eatTables.csv'); ?>" class="btn btn-sm btn-info" download><?= $this->lang->line('download'); ?> <?= $this->lang->line('format'); ?></a>
                                        <form method="post" enctype="multipart/form-data" id="table_form">
                                            <input type="hidden" name="type" value="table">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="table_file" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger"><?= $this->lang->line('uploadOnlyCSVFile'); ?></small>
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

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3"><?= $this->lang->line('cashier'); ?></h5>
                                        <a href="<?= base_url('uploads/common/cashier.csv'); ?>" class="btn btn-sm btn-info" download><?= $this->lang->line('download'); ?> <?= $this->lang->line('format'); ?></a>
                                        <form method="post" enctype="multipart/form-data" id="cashier_form">
                                            <input type="hidden" name="type" value="cashier">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="cashier_file" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger"><?= $this->lang->line('uploadOnlyCSVFile'); ?></small>
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
                                    <h5 class="card-title mb-3"><?= $this->lang->line('inventory'); ?></h5>
                                        <a href="<?= base_url('uploads/common/rm_items.csv'); ?>" class="btn btn-sm btn-info" download><?= $this->lang->line('download'); ?> <?= $this->lang->line('format'); ?></a>
                                        <form method="post" enctype="multipart/form-data" id="rmitems_form">
                                            <input type="hidden" name="type" value="rmitems">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('restaurant'); ?></label>
                                                        <select name="EID" class="form-control" required="" >
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php foreach ($rests as $key) { ?>
                                                                <option value="<?= $key['EID'] ?>"><?= $key['Name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="rm_file" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger"><?= $this->lang->line('uploadOnlyCSVFile'); ?></small>
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

$('#eatary_form').on('submit', function(e){
    e.preventDefault();

    var formData = new FormData(document.getElementById("eatary_form"));
    callAjax(formData);

});

$('#menucatg_form').on('submit', function(e){
    e.preventDefault();

    var formData = new FormData(document.getElementById("menucatg_form"));
    callAjax(formData);
    
});

$('#menuitem_form').on('submit', function(e){
    e.preventDefault();

    var formData = new FormData(document.getElementById("menuitem_form"));
    callAjax(formData);
    
});

$('#itemRates_form').on('submit', function(e){
    e.preventDefault();

    var formData = new FormData(document.getElementById("itemRates_form"));
    callAjax(formData);
    
});

$('#itemRecos_form').on('submit', function(e){
    e.preventDefault();

    var formData = new FormData(document.getElementById("itemRecos_form"));
    callAjax(formData);
    
});

$('#kitchen_form').on('submit', function(e){
    e.preventDefault();

    var formData = new FormData(document.getElementById("kitchen_form"));
    callAjax(formData);
    
});

$('#dispenseOutlet_form').on('submit', function(e){
    e.preventDefault();

    var formData = new FormData(document.getElementById("dispenseOutlet_form"));
    callAjax(formData);
    
});

$('#table_form').on('submit', function(e){
    e.preventDefault();
    var formData = new FormData(document.getElementById("table_form"));
    callAjax(formData);
    
});

$('#cuisine_form').on('submit', function(e){
    e.preventDefault();
    var formData = new FormData(document.getElementById("cuisine_form"));
    callAjax(formData);
});

$('#item_types_form').on('submit', function(e){
    e.preventDefault();
    var formData = new FormData(document.getElementById("item_types_form"));
    callAjax(formData);
});

$('#cashier_form').on('submit', function(e){
    e.preventDefault();
    var formData = new FormData(document.getElementById("cashier_form"));
    callAjax(formData);
});

$('#rmitems_form').on('submit', function(e){
    e.preventDefault();
    var formData = new FormData(document.getElementById("rmitems_form"));
    callAjax(formData);
});

function callAjax(formData){
   $.ajax({
           url : '<?= base_url('restaurant/csv_file_upload') ?>',
           type : 'POST',
           data : formData,
           processData: false,  
           contentType: false,  
           success : function(data) {
               alert(data.response);
               location.reload();
           }
    }); 
}
</script>