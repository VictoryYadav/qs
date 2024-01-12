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
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3">Restaurant</h5>
                                        <form method="post" enctype="multipart/form-data" id="eatary_form">
                                            <input type="hidden" name="type" value="eatary">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>File Upload</label>
                                                        <input type="file" name="eatary_file" class="form-control" required="" accept=".csv" id="file">
                                                        <small class="text-danger">File upload only CSV file</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="Upload">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3">Menu Categories</h5>
                                        <form method="post" enctype="multipart/form-data" id="menucatg_form">
                                            <input type="hidden" name="type" value="menucatg">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>File Upload</label>
                                                        <input type="file" name="mcatg_file" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger">File upload only CSV file</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="Upload">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3">Item Details</h5>
                                        <form method="post" enctype="multipart/form-data" id="menuitem_form">
                                            <input type="hidden" name="type" value="menuitem">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>File Upload</label>
                                                        <input type="file" name="mitem_file" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger">File upload only CSV file</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="Upload">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3">Item Pricing</h5>
                                        <form method="post" enctype="multipart/form-data" id="itemRates_form">
                                            <input type="hidden" name="type" value="itemrates">
                                            <div class="row">
                                                
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>File Upload</label>
                                                        <input type="file" name="mitem_rates" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger">File upload only CSV file</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="Upload">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3">Item Recommendations</h5>
                                        <form method="post" enctype="multipart/form-data" id="itemRecos_form">
                                            <input type="hidden" name="type" value="itemRecom">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>File Upload</label>
                                                        <input type="file" name="mitem_recos" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger">File upload only CSV file</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="Upload">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3">Kitchen</h5>
                                        
                                        <form method="post" enctype="multipart/form-data" id="kitchen_form">
                                            <input type="hidden" name="type" value="kitchen">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>File Upload</label>
                                                        <input type="file" name="kitchen_file" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger">File upload only CSV file</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="Upload">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3">Dispense Outlet</h5>
                                        
                                        <form method="post" enctype="multipart/form-data" id="dispenseOutlet_form">
                                            <input type="hidden" name="type" value="dispenseOutlet">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>File Upload</label>
                                                        <input type="file" name="dispense_file" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger">File upload only CSV file</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="Upload">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title mb-3">Table</h5>
                                        
                                        <form method="post" enctype="multipart/form-data" id="table_form">
                                            <input type="hidden" name="type" value="table">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>File Upload</label>
                                                        <input type="file" name="table_file" class="form-control" required="" accept=".csv">
                                                        <small class="text-danger">File upload only CSV file</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="Upload">
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

function callAjax(formData){
   $.ajax({
           url : '<?= base_url('restaurant/csv_file') ?>',
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
</script>