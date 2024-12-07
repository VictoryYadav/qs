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
                        <div class="text-right">
                            <?php if(empty($counter)){ ?>
                            <a href="<?= base_url('restaurant/table_list'); ?>" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i></a>&nbsp;&nbsp;
                            <a href="<?= base_url('restaurant/item_files_upload'); ?>" class="btn btn-sm btn-primary"><i class="fas fa-arrow-right"></i></a>
                           <?php } ?>
                        </div>
                        <div class="row" id="showBlock">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body">
                                     <a href="<?= base_url('uploads/common/menuItem_temp.csv') ?>" class="btn btn-sm btn-success" download>CSV <?= $this->lang->line('file'); ?> <?= $this->lang->line('format'); ?> for Menu Item Upload</a>
                                        <form method="post" enctype="multipart/form-data" id="items_form">
                                            <input type="hidden" name="type" value="items">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('restaurant'); ?></label>
                                                        <select name="EID" id="EID" class="form-control form-control-sm" required="" onchange="getTempData()">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                            if(!empty($rests)){
                                                                foreach ($rests as $rest) { ?>
                                                            <option value="<?= $rest['EID']; ?>"><?= $rest['Name']; ?></option>
                                                        <?php }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 uploadBlock" style="display: none;">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="items_file" class="form-control form-control-sm" required="" accept=".csv" id="file">
                                                        <small class="text-danger"><?= $this->lang->line('uploadOnlyCSVFile'); ?> </small>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 uploadBlock" style="display: none;">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label><br>
                                                        <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('upload'); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center btnBlock" style="display: none;">

                                                <button type="button" class="btn btn-sm btn-primary" onclick="insertData()">Setup Menu</button>
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

        <!-- loader -->
        <div class="container text-center" id="loadBlock" style="display: none;">
            <img src="<?= base_url('assets/images/loader.gif'); ?>" alt="Eat Out">
        </div>
        
        <?php $this->load->view('layouts/admin/script'); ?>


<script type="text/javascript">


function getTempData(){
    var EID = $(`#EID`).val();
    if(EID > 0){
        $.post('<?= base_url('restaurant/get_temp_menu_item') ?>',{EID:EID},function(res){
            if(res.status == 'success'){
              var dt = res.response;
              if(dt > 0){
                $(`.uploadBlock`).hide();
                $(`.btnBlock`).show();
              }else{
                $(`.btnBlock`).hide();
                $(`.uploadBlock`).show();
              }
            }else{
              alert(res.response);
            }
            
        });
    }
}

$('#items_form').on('submit', function(e){
    e.preventDefault();

    var formData = new FormData(document.getElementById("items_form"));
    callAjax(formData);

});

function callAjax(formData){
    $('#showBlock').hide();
    $('#loadBlock').show();
   $.ajax({
           url : '<?= base_url('restaurant/data_upload') ?>',
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

function insertData(){

    var EID = $('#EID').val();
    if(EID > 0){
        $('#showBlock').hide();
        $('#loadBlock').show();
        $.post('<?= base_url('restaurant/insert_temp_menu_item') ?>',{EID:EID},function(res){
            if(res.status == 'success'){
              alert(res.response);
            }else{
              alert(res.response);
            }
            location.reload();
        });
    }else{
        alert('Please select restaurant.');
    }
}
</script>