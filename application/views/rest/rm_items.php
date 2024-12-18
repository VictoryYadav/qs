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
                                            <input type="hidden" id="RMCd" name="RMCd">
                                            <div class="row">
                                                <div class="col-md-2 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('type'); ?></label>
                                                        <select class="form-control form-control-sm" name="type" required="" id="type" onclick="changeType()">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="1">RM</option>
                                                            <option value="2">FG</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('name'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="RMName" placeholder="<?= $this->lang->line('name'); ?>" required="" id="RMName" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-4">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('category'); ?></label>
                                                        <select name="RMCatg" id="RMCatg" class="form-control form-control-sm" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                    if(!empty($catList)){
                                                        foreach ($catList as $row) { ?>
                                                            <option value="<?= $row['RMCatgCd'] ?>"><?= $row['RMCatgName']; ?></option>
                                                        <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('item'); ?></label>
                                                        <select name="ItemId" id="ItemId" class="form-control form-control-sm select2 custom-select" >
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($itemList as $key) {
                                                             ?>
                                                            <option value="<?= $key['ItemId']; ?>"><?= $key['Name']; ?></option>
                                                        <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('mode'); ?></label>
                                                        <select name="Stat" id="Stat" class="form-control form-control-sm" >
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="0"><?= $this->lang->line('active'); ?></option>
                                                            <option value="1"><?= $this->lang->line('inactive'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-3">
                                                    <div class="form-group">
                                                        <label for="">&nbsp;</label>
                                                        <br>
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>">
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
                                            <table id="rm_cat_list" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('RMName'); ?></th>
                                                    <th><?= $this->lang->line('RMCat'); ?></th>
                                                    <th><?= $this->lang->line('mode'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($rm_items)){
                                                        $i = 1;
                                                        foreach ($rm_items as $row) {
                                                            $sts = ($row['Stat'] == 0)? $this->lang->line('active'):$this->lang->line('inactive');

                                                            $clr = ($row['Stat'] == 0)?'success':'danger';
                                                         ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['RMName']; ?></td>
                                                        <td><?= $row['RMCatgName']; ?></td>
                                                        <td><span class="badge badge-boxed  badge-<?= $clr; ?>"><?= $sts; ?></span></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['RMCd'] ?>,<?= $row['RMCatg'] ?>, '<?= $row['RMName'] ?>', <?= $row['ItemId'] ?>, <?= $row['Stat'] ?>)">
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
        $('#ItemId').select2();
    });

    function changeType() {
        var type = $(`#type`).val();
        if(type > 0){
            if(type == 1){
                $(`#ItemId`).val('').trigger('change')
                $(`#ItemId`).prop('disabled', true);
            }else{
                $(`#ItemId`).prop('disabled', false);
            }
        }
    }

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

    function editData(itemid,catid, rmname, ItemId, Stat){
        $('#RMCd').val(itemid);
        $('#RMCatg').val(catid);
        $('#RMName').val(rmname);   
        $("#ItemId").val(ItemId).trigger('change');
        $(`#Stat`).val(Stat);

        $(`#type`).val(1);
        $(`#ItemId`).prop('disabled', true);
        if(ItemId > 0){
            $(`#type`).val(2);
            $(`#ItemId`).prop('disabled', false);
        }
    }
</script>