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

            <!-- =======================================EntId======================= -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" id="groupForm">
                                            <input type="hidden" id="ItemGrpCd" name="ItemGrpCd" value="0">
                                            <div class="row">
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('item'); ?> <?= $this->lang->line('group'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="name" placeholder="<?= $this->lang->line('name'); ?>" required="" id="name" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('item'); ?> <?= $this->lang->line('type'); ?></label>
                                                        <select name="ItemTyp" id="ItemTyp" class="form-control form-control-sm" required="" onchange="changeItemType()">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($itemTyp as $key) {
                                                                if($key['TagTyp'] == 2){
                                                             ?>
                                                             <option value="<?= $key['TagId']; ?>"><?= $key['TDesc']; ?></option>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('item'); ?> <?= $this->lang->line('name'); ?></label>
                                                        <select name="ItemId" id="ItemId" class="form-control form-control-sm select2 custom-select" >
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($itemList as $item) {
                                                             ?>
                                                             <option value="<?= $item['ItemId']; ?>"><?= $item['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('group'); ?> <?= $this->lang->line('type'); ?></label>
                                                        <select name="GrpType" id="GrpType" class="form-control form-control-sm" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>

                                                            <option value="1"><?= $this->lang->line('single').' '.$this->lang->line('selection'); ?></option>
                                                            <option value="2"><?= $this->lang->line('multiple').' '.$this->lang->line('selection'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('required'); ?></label>
                                                        <select name="Reqd" id="Reqd" class="form-control form-control-sm" required="">
                                                            <option value="0"><?= $this->lang->line('no'); ?></option>
                                                            <option value="1"><?= $this->lang->line('yes'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('calculation'); ?> <?= $this->lang->line('type'); ?></label>
                                                        <select name="CalcType" id="CalcType" class="form-control form-control-sm" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="0"><?= $this->lang->line('increment').' '.$this->lang->line('price'); ?></option>
                                                            <option value="1"><?= $this->lang->line('ReplaceOriginalPriceWithItemPrice'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('rank'); ?></label>
                                                        <input type="number" name="Rank" id="Rank" class="form-control form-control-sm" required="" />
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
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
                                                <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>" id="saveBtn">
                                                <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('update'); ?>" id="updateBtn" style="display: none;">

                                                <div class="text-success" id="msgText"></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="TableData" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('group').' '.$this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('item').' '.$this->lang->line('type'); ?></th>
                                                    <th><?= $this->lang->line('item').' '.$this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('required'); ?></th>

                                                    <th><?= $this->lang->line('mode'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    
                                                    if(!empty($lists)){
                                                        $i = 1;
                                                        foreach ($lists as $row) {
                                                            $sts = ($row['Stat'] == 0)? $this->lang->line('active'):$this->lang->line('inactive');

                                                            $clr = ($row['Stat'] == 0)?'success':'danger';

                                                            $req = ($row['Reqd'] == 0)? $this->lang->line('no'):$this->lang->line('yes');
                                                     ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['Name']; ?>
                                                        </td>
                                                        <td><?= $row['itemTypeName']; ?></td>
                                                        <td><?= $row['ItemNm']; ?></td>
                                                        <td><span class="badge badge-boxed  badge-<?= $clr; ?>"><?= $req; ?></span>
                                                        </td>
                                                        <td><span class="badge badge-boxed  badge-<?= $clr; ?>"><?= $sts; ?></span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['ItemGrpCd'] ?>, '<?= $row['Name'] ?>', <?= $row['ItemTyp'] ?>, <?= $row['ItemId'] ?>, <?= $row['GrpType'] ?>, <?= $row['Reqd'] ?>, <?= $row['CalcType'] ?>, <?= $row['Rank'] ?>, <?= $row['Stat'] ?>)">
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
        $('#TableData').DataTable();
        $('#ItemId').select2();
    });

    $('#groupForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/item_type_group') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
            }else{
              $('#msgText').html(res.response);
            }
            location.reload();
        });

    });

    function editData(ItemGrpCd, name, ItemTyp, ItemId, GrpType, Reqd, CalcType, Rank, stat){
        
        $('#ItemGrpCd').val(ItemGrpCd);
        $('#name').val(name);
        $('#ItemTyp').val(ItemTyp);
         $("#ItemId").val(ItemId).trigger('change');
        $('#GrpType').val(GrpType);
        $('#Reqd').val(Reqd);
        $('#CalcType').val(CalcType);
        $('#Rank').val(Rank);
        $('#Stat').val(stat);   

        $('#saveBtn').hide();
        $('#updateBtn').show();
    }

    function changeItemType(){
        var ItemTyp = $(`#ItemTyp`).val();
        if(ItemTyp == 125){
            $('#ItemId').prop('required', true);
        }else{
            $('#ItemId').prop('required', false);
        }
    }
</script>