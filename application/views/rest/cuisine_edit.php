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
                                        <form method="post" id="cuisineForm">
                                            <div class="row">
                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label for="">
                                                            <?= $this->lang->line('cuisine');?>
                                                        </label>
                                                        <input type="text" class="form-control form-control-sm" name="cuisineName" id="cuisineName" required="">
                                                        <input type="hidden" name="CID" id="CID">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for="">&nbsp;
                                                        </label><br>
                                                        <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('update'); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="cuisineListTbl" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($cuisines)){
                                                        $i = 1;
                                                        foreach ($cuisines as $row) { ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['Name']; ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['CID'] ?>, '<?= $row['Name'] ?>')">
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
    $('#cuisineListTbl').DataTable();
});


editData = (cid, name) => {
    
    $('#cuisineName').val(name);
    $('#CID').val(cid);
}

$('#cuisineForm').on('submit', function(e){
    e.preventDefault();

    var data = $(this).serializeArray();
    $.post('<?= base_url('restaurant/edit_cuisine') ?>',data,function(res){
        if(res.status == 'success'){
          alert(res.response);
        }else{
          alert(res.response);
        }
          location.reload();
    });
});

</script>