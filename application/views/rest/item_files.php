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
                                        <p>Note : <span class="text-danger">ddd</span></p>
                                    <h5 class="card-title mb-3"><?= $this->lang->line('menu'); ?> <?= $this->lang->line('item'); ?></h5>
                                        <form method="post" enctype="multipart/form-data" id="menu_form">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="menu_file[]" class="form-control" required="" accept="image/jpg, image/jpeg" id="file" multiple>
                                                        <small class="text-danger"><?= $this->lang->line('uploadOnlyJPGFile'); ?> </small>
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

$('#menu_form').on('submit', function(e){
    e.preventDefault();

    var formData = new FormData(document.getElementById("menu_form"));
    callAjax(formData);
});

function callAjax(formData){
   $.ajax({
           url : '<?= base_url('restaurant/item_files_upload') ?>',
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