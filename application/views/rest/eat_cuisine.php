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
                                    <form method="post" id="cuisineForm" >
                                       <div class="row">
                                        
                                            <input type="hidden" name="ECID" id="ECID" value="0">
                                           <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <label for=""><?= $this->lang->line('cuisine'); ?> <?= $this->lang->line('name'); ?></label>
                                                    <input type="text" name="cuisineName1" class="form-control form-control-sm" id="cuisineName1" required="" autocomplete="off">
                                                </div>
                                           </div>

                                           <?php
                                            for ($i = 1; $i < sizeof($languages); $i++) { ?>
                                               <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('cuisine'); ?> <?= $languages[$i]['LngName']; ?></label>
                                                        <input type="text" id="cuisineName<?= $languages[$i]['LCd']; ?>" name="cuisineName<?= $languages[$i]['LCd']; ?>" class="form-control form-control-sm"  />
                                                    </div>
                                               </div>
                                           <?php } ?>

                                           <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('mode'); ?></label>
                                                    <select name="Stat" id="Stat" class="form-control form-control-sm" required="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>

                                                        <option value="0"><?= $this->lang->line('active'); ?></option>
                                                        <option value="1"><?= $this->lang->line('inactive'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                       </div>

                                       <div class="text-center">
                                            <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>" id="saveBtn">
                                            <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('update'); ?>" id="updateBtn" style="display: none;">
                                        </div>
                                    </form> 
                                        
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="tblData" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('cuisine'); ?> <?= $this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('cuisine'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($eatCuisine)){
                                                        $i = 1;
                                                        foreach ($eatCuisine as $row) { ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['ecuisineName']; ?></td>
                                                        <td><?= $row['cuisineName']; ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['ECID'] ?>,'<?= $row['ecuisineName'] ?>',<?= $row['Stat'] ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php    }
                                                    } 
                                                    ?>
                                                </tbody>
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

$(document).ready(function () {
    $('#tblData').DataTable();
    $('#CID').select2();
});

editData = (ECID, name1, Stat) => {
    
    $('#cuisineName1').val(name1);

    $("#ECID").val(ECID);
    
    $("#Stat").val(Stat);

    $('#saveBtn').hide();
    $('#updateBtn').show();
}

$('#cuisineForm').on('submit', function(e){
    e.preventDefault();

    var data = $(this).serializeArray();
    $.post('<?= base_url('restaurant/eat_cuisine') ?>',data,function(res){
        if(res.status == 'success'){
          alert(res.response);
        }else{
          alert(res.response);
        }
          location.reload();
    });
});

</script>