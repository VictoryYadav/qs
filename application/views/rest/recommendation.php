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
                                        <form method="post" id="recomForm">
                                            <input type="hidden" id="RecNo" name="RecNo" value="0">
                                            <div class="row">
                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('item'); ?></label>
                                                        <select name="ItemId" id="ItemId" class="form-control form-control-sm select2 custom-select" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>

                                                            <?php foreach ($itemList as $item) { ?>
                                                            <option value="<?= $item['ItemId']; ?>"><?= $item['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('recommendation'); ?></label>
                                                        <select name="RcItemId" id="RcItemId" class="form-control form-control-sm select2 custom-select" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>

                                                            <?php foreach ($itemList as $item) { ?>
                                                            <option value="<?= $item['ItemId']; ?>"><?= $item['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('remarks'); ?></label>
                                                        <input type="text" name="Remarks" id="Remarks" class="form-control form-control-sm" />
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-4">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('mode'); ?></label>
                                                        <select name="Stat" id="Stat" class="form-control form-control-sm" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>

                                                            <option value="0"><?= $this->lang->line('active'); ?></option>
                                                            <option value="1"><?= $this->lang->line('inactive'); ?></option>
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
                                            <table id="itemTbl" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('item'); ?></th>
                                                    <th><?= $this->lang->line('recommendation'); ?></th>
                                                    <th><?= $this->lang->line('mode'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($recList)){
                                                        $i = 1;
                                                        foreach ($recList as $row) {
                                                            $sts = ($row['Stat'] == 0)? $this->lang->line('active'):$this->lang->line('inactive');

                                                            $clr = ($row['Stat'] == 0)?'success':'danger';
                                                         ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['ItemNm']; ?></td>
                                                        <td><?= $row['recName']; ?></td>
                                                        <td>
                                                            <span class="badge badge-boxed  badge-<?= $clr; ?>"><?= $sts; ?></span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['RecNo'] ?>,<?= $row['ItemId'] ?>,<?= $row['RcItemId'] ?>, <?= $row['Stat'] ?>, <?= $row['Remarks'] ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php  }
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
        $('#itemTbl').DataTable();
        $('#ItemId').select2();
        $('#RcItemId').select2();
    });

    $('#recomForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/recommendation') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
            }else{
              $('#msgText').html(res.response);
            }
            location.reload();
        });

    });

    function editData(RecNo,itemid, RcItemId, stat, Remarks){
        
        $('#RecNo').val(RecNo);
        $('#ItemId').val(itemid).trigger('change');
        $('#RcItemId').val(RcItemId).trigger('change');
        $('#Stat').val(stat);
        $('#Remarks').val(Remarks);   

        $('#saveBtn').hide();
        $('#updateBtn').show();
    }
</script>