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

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18 text-center"><?php echo $title; ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" id="catForm">
                                            <input type="hidden" id="RMCd" name="RMCd">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <select name="RMCatg" id="RMCatg" class="form-control" required="">
                                                            <option value="">Select</option>
                                                            <?php
                                                    if(!empty($catList)){
                                                        foreach ($catList as $row) { ?>
                                                            <option value="<?= $row['RMCatgCd'] ?>"><?= $row['RMCatgName']; ?></option>
                                                        <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="RMName" placeholder="Category Name" required="" id="RMName">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="submit" class="btn btn-success" value="Submit">
                                                </div>
                                                <div class="col-md-3">
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
                                                    <th>RMName</th>
                                                    <th>RMCat</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($rm_items)){
                                                        $i = 1;
                                                        foreach ($rm_items as $row) { ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['RMName']; ?></td>
                                                        <td><?= $row['RMCatgName']; ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['RMCd'] ?>,<?= $row['RMCatg'] ?>, '<?= $row['RMName'] ?>')">
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
        $.post('<?= base_url('restaurant/rmitems_list') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
            location.reload();
            }else{
              $('#msgText').html(res.response);
            }
        });

    });

    function editData(itemid,catid, rmname){
        console.log(itemid+' '+catid+' '+rmname);
        $('#RMCd').val(itemid);
        $('#RMCatg').val(catid);
        $('#RMName').val(rmname);
    }
</script>