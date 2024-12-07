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
                      <div class="text-right mb-2">
                        <?php if(empty($counter)){ ?>
                            <a href="<?= base_url('restaurant/data_upload'); ?>" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i></a>&nbsp;&nbsp;
                            <a href="<?= base_url('restaurant/payment_mode_access'); ?>" class="btn btn-sm btn-primary"><i class="fas fa-arrow-right"></i></a>
                          <?php } ?>
                      </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p>Notes : <span class="text-danger"><br>
                                          1) Only JPG files allowed<br>
                                        2) Each file less than 1MB<br>
                                      3) Maximum 100 files at a time</span></p>
                                    <h5 class="card-title mb-3"><?= $this->lang->line('menu'); ?> <?= $this->lang->line('item'); ?></h5>
                                        <form method="post" enctype="multipart/form-data" id="menu_form">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="files[]" class="form-control" required="" accept="image/jpg" id="file" multiple>
                                                        <small class="text-danger"><?= $this->lang->line('uploadOnlyJPGFile'); ?> </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('upload'); ?>">
                                            </div>
                                        </form>
                                        <div id="processBlock" style="display: none;">
                                          <p class="text-info">Processing...</p>
                                        </div>
                                        <div id="tableBlock" style="display: none;">
                                            <p>Files are Not uploaded in (more than 1
                                            MB).</p>
                                           <table class="table ">
                                               <tr>
                                                   <td>#</td>
                                                   <td>Image(s) Not Uploaded</td>
                                                   <td>File Size</td>
                                               </tr>
                                               <tbody id="notUpload"></tbody>
                                           </table>
                                        </div>
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
  $(`#processBlock`).show();
  $(`#tableBlock`).hide();
   $.ajax({
           url : '<?= base_url('restaurant/item_files_upload') ?>',
           type : 'POST',
           data : formData,
           processData: false,  
           contentType: false,  
           success : function(data) {
               // alert(data.response);
               if(data.status == 'pending'){
                    var data = data.response;
                    var temp = ``;
                    var count = 0;
                    data.forEach((item, index) => {
                        count++;
                        temp += `<tr>
                                    <td>${count}</td>
                                    <td>${item.files}</td>
                                    <td>${item.fileSize}</td>
                                 </tr>`;
                    });
                    $('#notUpload').html(temp);
                    $(`#processBlock`).hide();
                    $(`#tableBlock`).show();
               }else{
                alert(data.response);
                $(`#processBlock`).hide();
                $(`#tableBlock`).hide();
               }
               // location.reload();
           }
    }); 
}
</script>