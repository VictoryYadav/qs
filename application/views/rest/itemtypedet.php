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
                                            <input type="hidden" id="ItemOptCd" name="ItemOptCd" value="0">
                                            <div class="row">

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('type'); ?></label>
                                                        <select name="customType" id="type" class="form-control form-control-sm" required="" onchange="changeModes()">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="5"><?= $this->lang->line('custom'); ?></option>
                                                            <option value="0"><?= $this->lang->line('combo'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('menu'); ?> <?= $this->lang->line('name'); ?></label>
                                                        <select name="ItemId" id="ItemId" class="form-control form-control-sm select2 custom-select" required="" onchange="getProtions()">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6" id="SecIdBlock" style="display: none;">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('section'); ?></label>
                                                        <select name="SecId" id="SecId" class="form-control form-control-sm">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6" id="ipcdBlock" style="display: none;">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('itemPortion'); ?></label>
                                                        <select name="IPCd" id="IPCd" class="form-control form-control-sm">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('item'); ?> <?= $this->lang->line('group'); ?></label>
                                                        <select name="ItemGrpCd" id="ItemGrpCd" class="form-control form-control-sm select2 custom-select" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($itemGroup as $typ) {
                                                             ?>
                                                             <option value="<?= $typ['ItemGrpCd']; ?>" itemId="<?= $typ['ItemId']; ?>"><?= $typ['Name']; ?></option>
                                                            <?php } ?>
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
                                                    <th><?= $this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('group').' '.$this->lang->line('name'); ?></th>
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
                                                     ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['menuName']; ?>
                                                        </td>
                                                        <td><?= $row['groupName']; ?></td>
                                                        <td><span class="badge badge-boxed  badge-<?= $clr; ?>"><?= $sts; ?></span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['ItemOptCd'] ?>, <?= $row['ItemId'] ?>, <?= $row['ItemGrpCd'] ?>, <?= $row['Rank'] ?>, <?= $row['Stat'] ?>, <?= $row['IPCd'] ?>, <?= $row['customType'] ?>, <?= $row['SecId'] ?>)">
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
        $('#ItemGrpCd').select2();
        $('#ItemId').select2();
        // $('#IPCd').select2();
    });

    getPrice = () =>{
        var section  = $('#SecId').val();
        var portion  = $('#Itm_Portion').val();
        var itemId  = $('#').val();
        // ItemGrpCd
    }

    $('#groupForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/item_type_det') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
            }else{
              $('#msgText').html(res.response);
            }
            location.reload();
        });

    });

    var cur_item_id = 0;
    var cur_ipcd = 0;

    function editData(ItemOptCd, ItemId, ItemGrpCd, Rank, stat, IPCd, customType, SecId){
        
        $('#ItemOptCd').val(ItemOptCd);
        $('#ItemId').val(ItemId).trigger('change');
        $("#ItemGrpCd").val(ItemGrpCd).trigger('change');
        $("#IPCd").val(IPCd).trigger('change');
        $('#Rank').val(Rank);
        $('#Stat').val(stat);  
        $('#type').val(customType); 
        $('#SecId').val(SecId); 

        $('#saveBtn').hide();
        $('#updateBtn').show();

        cur_item_id = ItemId;
        cur_ipcd = IPCd;
        changeModes();
    }

    function getProtions(){
        var ItemId = $(`#ItemId`).val();
        $.post('<?= base_url('restaurant/get_item_portion_by_itemId') ?>',{ItemId:ItemId},function(res){
            if(res.status == 'success'){
                var temp = "<option value= ><?= $this->lang->line('select'); ?></option>";
                var sec = "<option value= ><?= $this->lang->line('select'); ?></option>";
                res.response.forEach((item, index) => {
                    var selc = '';
                    if(cur_ipcd == item.Itm_Portion){
                        selc = 'selected';
                    }
                    temp += `<option value="${item.Itm_Portion}" ${selc}>${item.Portions}</option>`;
                     selc = '';
                    if(cur_ipcd == item.SecId){
                        selc = 'selected';
                    }
                    sec += `<option value="${item.SecId}" ${selc}>${item.Section}</option>`;
                });

                $('#IPCd').html(temp);
                $('#SecId').html(sec);

            }else{
              alert(res.response);
            }
        });
    }

    function changeModes(){
        var type = $('#type').val();
        if(type == 0){
            $('#ipcdBlock').show();
            $('#SecIdBlock').show();
        }else{
            $('#ipcdBlock').hide();
            $('#SecIdBlock').hide();
        }
        $.post('<?= base_url('restaurant/get_menu_list') ?>',{Stat:type},function(res){
            if(res.status == 'success'){
                var temp = "<option value= ><?= $this->lang->line('select'); ?></option>";
                if(res.response.length > 0){
                    res.response.forEach((item, index) => {
                        var selc = '';
                        if(cur_item_id == item.ItemId){
                            selc = 'selected';
                        }
                        temp += `<option value="${item.ItemId}" ${selc}>${item.ItemName}</option>`;
                    });
                }
                
                $('#ItemId').html(temp);
                getProtions();
            }else{
              alert(res.response);
            }
        });
    }
</script>