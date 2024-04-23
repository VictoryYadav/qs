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
                            <div class="col-md-12">

                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" id="ratesFileForm" enctype="multipart/form-data">
                                            <input type="hidden" name="type" value="rates_file">
                                            <div class="row">
                                                <div class="col-md-3 col-3">
                                                    <div class="form-group">
                                                        <label for="">&nbsp;</label>
                                                        <br>
                                                    <a href="<?= base_url('uploads/common/menu_item_rates.csv') ?>" class="btn btn-info btn-sm" download><?= $this->lang->line('download'); ?> <?= $this->lang->line('format'); ?></a>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?></label>
                                                        <input type="file" name="itmRates" class="form-control form-control-sm" required="" accept=".csv" id="file">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-3">
                                                    <div class="form-group">
                                                        <label for="">&nbsp;</label>
                                                        <br>
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('upload'); ?>">
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" id="ratesForm">
                                            <input type="hidden" name="type" value="update">
                                            <div class="row">
                                                <div class="col-md-5 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('item'); ?></label>
                                                        <select class="form-control form-control-sm select2 custom-select" name="ItemId" id="ItemId" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                            foreach ($itemList as $item) {
                                                            ?>
                                                            <option value="<?= $item['ItemId']; ?>"><?= $item['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="ratesTbl" class="table table-bordered">
                                                            <thead>
                                                            <tr >
                                                                <th><?= $this->lang->line('section'); ?></th>
                                                                <th><?= $this->lang->line('portion'); ?></th>
                                                                <th><?= $this->lang->line('rate'); ?></th>
                                                            </tr>
                                                            </thead>
                        
                                                            <tbody id="rateBody"></tbody>
                                                        </table>
                                                    </div>
                                                    <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('update'); ?>">
                                                </div>
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

    $(document).ready(function () {
        $('#ratesTbl').DataTable();
        $('#ItemId').select2();
    });

    $('#ratesFileForm').on('submit', function(e){
        e.preventDefault();

        var formData = new FormData(document.getElementById("ratesFileForm"));
        callAjax(formData);

    });

    function callAjax(formData){
       $.ajax({
               url : '<?= base_url('restaurant/menu_item_rates') ?>',
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

    $('#ItemId').on('change', function(e){
        e.preventDefault();
        var item_id = $(this).val();
        if(item_id > 0){
            $.post('<?= base_url('restaurant/menu_item_rates') ?>',{ItemId:item_id, type:'get'},function(res){
                if(res.status == 'success'){
                  var tbl = ``;
                  res.response.forEach((item, index) =>{
                    tbl += `<tr>
                                <td>${item.Section}</td>
                                <td>${item.Portion}</td>
                                <td>
                                <input type="hidden" name="SecId[]" value="${item.SecId}" required />
                                <input type="hidden" name="Itm_Portion[]" value="${item.Itm_Portion}" required />
                                    <input type="text" name="itemRate[]" value="${item.ItmRate}" required />
                                </td>
                            </tr>`;
                  });
                  $('#rateBody').html(tbl);
                }else{
                  alert(res.response);
                }
                
            });    
        }
    });

    $('#ratesForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/menu_item_rates') ?>',data,function(res){
            if(res.status == 'success'){
              alert(res.response);
            }else{
              alert(res.response);
            }
            location.reload();
        });

    });

</script>