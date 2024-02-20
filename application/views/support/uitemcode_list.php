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
                                        <form method="post" id="uItemForm">
                                            <input type="hidden" id="UItmCd" name="UItmCd" value="0">
                                            <div class="row">
                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('name'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="ItemName" placeholder="<?= $this->lang->line('name'); ?>" required="" id="ItemName" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-4">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('cuisine'); ?></label>
                                                        <select name="CID" id="CID" class="form-control form-control-sm" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                            foreach ($cuisine as $cui){
                                                            ?>
                                                            <option value="<?= $cui['CID']; ?>"><?= $cui['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-3">
                                                    <div class="form-group">
                                                        <label for="">&nbsp;</label>
                                                        <br>
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>" id="saveBtn">
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('update'); ?>" id="updateBtn" style="display: none;">
                                                    </div>
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
                                            <table id="tableData" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>UItmCd</th>
                                                    <th><?= $this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($lists)){
                                                        foreach ($lists as $row) {
                                                         ?>
                                                    <tr>
                                                        <td><?= $row['UItmCd']; ?></td>
                                                        <td><?= $row['ItemName']; ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['UItmCd'] ?>, '<?= $row['ItemName'] ?>', <?= $row['CID'] ?>, <?= $row['CTyp'] ?>, <?= $row['FID'] ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php  }  } ?>
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
        $('#tableData').DataTable();
    });

    $('#uItemForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('support/uitemcd_list') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
            }else{
              $('#msgText').html(res.response);
            }
            location.reload();
        });

    });

    function editData(UItmCd, ItemName, CID, CTyp, FID){
        
        $('#UItmCd').val(UItmCd);
        $('#ItemName').val(ItemName);
        $('#CID').val(CID);
          

        $('#saveBtn').hide();
        $('#updateBtn').show();
    }
</script>