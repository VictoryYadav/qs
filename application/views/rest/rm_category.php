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
                                        <form method="post" id="catForm">
                                            <input type="hidden" id="RMCatgCd" name="RMCatgCd">
                                            <div class="row">
                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('category'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="RMCatgName" placeholder="<?= $this->lang->line('category'); ?>" required="" id="RMCatgName">
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label><br>
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="text-success" id="msgText"></div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="rm_cat_list" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($catList)){
                                                        $i = 1;
                                                        foreach ($catList as $row) { ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['RMCatgName']; ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['RMCatgCd'] ?>, '<?= $row['RMCatgName'] ?>')">
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
        $('#rm_cat_list').DataTable();
    });

    $('#catForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/rmcat') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
            location.reload();
            }else{
              $('#msgText').html(res.response);
            }
        });

    });

    function editData(id, name){
        // console.log(id+' '+name);
        $('#RMCatgCd').val(id);
        $('#RMCatgName').val(name);
    }
</script>