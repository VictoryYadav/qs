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
                                        <form method="post" id="tableForm">
                                            <input type="hidden" id="TId" name="TId" value="0">
                                            <input type="hidden" name="TblTyp" id="TblTyp" value="7" >
                                            <div class="row">
                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('tableNo'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="TableNo" required="" id="TableNo" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('capacity'); ?></label>
                                                        <input type="number" class="form-control form-control-sm" name="Capacity" required="" id="Capacity" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('section'); ?></label>
                                                        <select class="form-control form-control-sm" name="SecId" required="" id="SecId" autocomplete="off">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                            foreach ($sections as $key) {
                                                            ?>
                                                            <option value="<?= $key['SecId'];?>"><?= $key['Name'];?></option>
                                                        <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('cashier'); ?></label>
                                                        <select class="form-control form-control-sm" name="CCd" required="" id="CCd" autocomplete="off">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                            foreach ($casherList as $key) {
                                                            ?>
                                                            <option value="<?= $key['CCd'];?>"><?= $key['Name'];?></option>
                                                        <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

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

                                            <div class="">
                                                <div class="form-group">
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>" id="saveBtn">
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('update'); ?>" id="updateBtn" style="display: none;">
                                                </div>
                                                <?php if($counter != 0){ ?>
                                                <a href="<?= base_url('restaurant/dispense_outlet'); ?>" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i></a>&nbsp;&nbsp;
                                                <?php 
                                                if($this->session->userdata('MultiLingual') > 1){
                                                ?>
                                                <a href="<?= base_url('restaurant/language_access'); ?>" class="btn btn-sm btn-primary"><i class="fas fa-arrow-right"></i></a>
                                                <?php }else{  ?>
                                                    <a href="<?= base_url('restaurant/data_upload'); ?>" class="btn btn-sm btn-primary"><i class="fas fa-arrow-right"></i>
                                                <?php } } ?>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="tableList" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('tableNo'); ?></th>
                                                    <th><?= $this->lang->line('capacity'); ?></th>
                                                    <th><?= $this->lang->line('section'); ?></th>
                                                    <th><?= $this->lang->line('cashier'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($tables)){
                                                        $i = 1;
                                                        foreach ($tables as $row) { ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['TableNo']; ?></td>
                                                        <td><?= $row['Capacity']; ?></td>
                                                        <td><?= $row['sectionName']; ?></td>
                                                        <td><?= $row['cashierName']; ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['TId'] ?>, <?= $row['TableNo'] ?>,<?= $row['MergeNo'] ?>,<?= $row['TblTyp'] ?>,<?= $row['Capacity'] ?>,<?= $row['SecId'] ?>,<?= $row['CCd'] ?>, <?= $row['Stat'] ?>)">
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
        $('#tableList').DataTable();
    });

    $('#tableForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/table_list') ?>',data,function(res){
            if(res.status == 'success'){
              alert(res.response);
            }else{
              alert(res.response);
            }
            location.reload();
        });

    });

    function editData(tid, tableNo, mergeNo, TblTyp, capacity, SecId, CCd, stat){
        
        $('#TId').val(tid);
        $('#TableNo').val(tableNo);
        // $('#TblTyp').val(TblTyp);
        $('#Capacity').val(capacity);
        $('#SecId').val(SecId);
        $('#CCd').val(CCd);
        $('#Stat').val(stat);   

        $('#saveBtn').hide();
        $('#updateBtn').show();
    }
</script>