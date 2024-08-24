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
                                        <form method="post" id="bomForm">
                                            <input type="hidden" name="BOMNo" value="<?= $boms['BOMNo']; ?>">
                                            <div class="row">
                                                <div class="col-md-2 col-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('type'); ?></label>
                                                        <select name="BOMDishTyp" id="bomType" class="form-control form-control-sm" required="" onchange="changeType()">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="1" <?php if($boms['BOMDishTyp'] == 1){ echo 'selected'; } ?> ><?= $this->lang->line('finish'); ?> <?= $this->lang->line('goods'); ?></option>
                                                            <option value="2" <?php if($boms['BOMDishTyp'] == 2){ echo 'selected'; } ?> ><?= $this->lang->line('intermediate'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6 itemBlock" >
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('item'); ?></label>
                                                        <select name="ItemId" id="ItemId" class="form-control form-control-sm select2 custom-select" onchange="getPortionByItem();">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                            foreach ($items as $key) { ?>
                                                                <option value="<?= $key['ItemId']; ?>" itemname="<?= $key['Name']; ?>" <?php if($boms['ItemId'] == $key['ItemId']){ echo 'selected'; } ?>><?= $key['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <input type="hidden" name="itemname" id="itemname">
                                                </div>

                                                <div class="col-md-3 col-6 mitemBlock" style="display: none;">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('item'); ?></label>
                                                        <input type="text" name="MItemId" id="MItemId" class="form-control form-control-sm" value="<?= $boms['Name']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('portion'); ?></label>
                                                        <select name="IPCd" id="IPCd" class="form-control form-control-sm select2 custom-select" required="" >
                                                            <option value=""><?= $this->lang->line('select'); ?></option> 
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('quantity'); ?></label>
                                                        <input type="text" name="Qty" id="Qty" class="form-control form-control-sm" required="" value="<?= $boms['Qty']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('costing'); ?></label>
                                                        <input type="text" name="Costing" id="Costing" class="form-control form-control-sm" required="" value="<?= $boms['Costing']; ?>" />
                                                    </div>
                                                </div>

                                            </div>
                                            <div id="rowBlock" >
                                            <button class="btn btn-sm btn-success btn-rounded mb-1" onclick="addRow()"><i class="fa fa-plus"></i></button>

                                                <div class="table-responsive">          
                                                  <table class="table table-bordered">
                                                    <thead>
                                                      <tr>
                                                        <th><?= $this->lang->line('type'); ?></th>
                                                        <th><?= $this->lang->line('item'); ?></th>
                                                        <th><?= $this->lang->line('quantity'); ?></th>
                                                        <th><?= $this->lang->line('uom'); ?></th>
                                                        <th style="width:100px;"><?= $this->lang->line('costing'); ?></th>
                                                        <th><?= $this->lang->line('action'); ?></th>
                                                      </tr>
                                                    </thead>
                                                    <tbody id="box_body">
                                                        <?php 
                                                        $count = 1;
                                                        foreach ($bomDet as $key) { 
                                                            $rmAr = array('1' => 'RM','2' => 'Intermediate', '3' => 'Finish Goods');
                                                         ?>
                                                            <tr>
                                                                <td>
                                                                <input type="hidden" name="BNo[]" value="<?= $key['BNo']; ?>">

                                                                <select name="itemtype[]" id="itemtype_<?= $count; ?>" class="form-control form-control-sm" required="" onchange="changeItemType(<?= $count; ?>)">
                                                                    <?php
                                                                    foreach ($rmAr as $keyr => $val) { ?>
                                                                    <option value="<?= $keyr; ?>" <?php if($keyr == $key['RMType']) { echo 'selected'; } ?> ><?= $val; ?></option>
                                                                <?php } ?>
                                                                </select></td>
                                                            <td>
                                                                <select name="RMCd[]" id="RMCd_<?= $count; ?>" class="form-control form-control-sm select2 custom-select" onchange="getRMItemsUOME(<?= $count; ?>)">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    <?php
                                                            if(!empty($rm_items)){
                                                                foreach ($rm_items as $row) { ?>
                                                                    <option value="<?= $row['RMCd'] ?>" <?php if($row['RMCd'] == $key['RMCd']){ echo 'selected'; } ?> rmuom="<?= $key['RMUOM']; ?>"><?= $row['RMName']; ?></option>
                                                                <?php } } ?>
                                                                </select>

                                                                <select name="BomNo[]" id="BomNo_<?= $count; ?>" class="form-control form-control-sm select2 custom-select"  onchange="getBomPortion(<?= $count; ?>)" style="display:none;">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    <?php
                                                            if(!empty($intermediate)){
                                                                foreach ($intermediate as $row) { ?>
                                                                    <option value="<?= $row['BOMNo'] ?>" ipcd="<?= $row['IPCd'] ?>" rmuom="<?= $key['RMUOM']; ?>" <?php if($row['BOMNo'] == $key['RMCd']){ echo 'selected'; } ?>><?= $row['Name']; ?></option>
                                                                <?php } } ?>
                                                                </select>

                                                                <select name="goods[]" id="goods_<?= $count; ?>" class="form-control form-control-sm select2 custom-select"  onchange="getGoodPortionE(<?= $count; ?>)" style="display:none;">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    <?php
                                                            if(!empty($items)){
                                                                foreach ($items as $row) { ?>
                                                                    <option value="<?= $row['ItemId'] ?>" rmuom="<?= $key['RMUOM']; ?>" <?php if($row['ItemId'] == $key['RMCd']){ echo 'selected'; } ?>><?= $row['Name']; ?></option>
                                                                <?php } } ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                            <input type="text" class="form-control form-control-sm" name="RMQty[]" required="" id="RMQty" onblur="changeValue(this)" value="<?= $key['RMQty']; ?>" readonly>
                                                            </td>
                                                            <td>
                                                            <select name="RMUOM[]" id="RMUOM_<?= $count; ?>" class="form-control form-control-sm">
                                                                <option value=""><?= $this->lang->line('select'); ?></option>
                                                            </select>
                                                            <input type="text" readonly name="bomRMUOM[]" id="bomRMUOM_<?= $count; ?>" class="form-control form-control-sm" style="display:none;" value="<?= $key['RMUOM']; ?>" />
                                                            </td>
                                                            <td>
                                                            <input type="text" name="itemCost[]" id="itemCost_<?= $count; ?>" class="form-control form-control-sm" style="width:100px;" value="<?= $key['Costing']; ?>" readonly />
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-sm btn-danger btn-rounded" onclick="deleteItemByBom(<?= $key['BNo'] ?>)">
                                                                    <i class="fa fa-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    <?php 
                                                    $count++;
                                                        } ?>
                                                    </tbody>
                                                  </table>
                                                  </div>

                                            </div>
                                            <div class="">
                                                <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('update'); ?>">
                                            
                                                <div class="text-success" id="msgText"></div>
                                            </div>
                                        </form>
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
        $('#IPCd').select2();

        changeType();
        getPortion();

        var num_desc = <?= sizeof($bomDet)?>;
        for(i=1;i<=num_desc;i++){
            changeItemType(i);
        }

    });

    function changeType(){
        var bomType = $('#bomType').val();
        if(bomType == 1){
            $(`.itemBlock`).show();
            $(`.mitemBlock`).hide();
        }else{
            $(`.itemBlock`).hide();
            $(`.mitemBlock`).show();
            
            getPortion();    
        }

    }

    function getPortion(){
        var bipcd = "<?= $boms['IPCd']; ?>"
        $.post('<?= base_url('restaurant/get_item_portionList') ?>',function(res){
            if(res.status == 'success'){
                var temp = `<option value=""><?= $this->lang->line('select'); ?></option>`;
                res.response.forEach((item) => {
                    var slct = '';
                    if(bipcd == item.IPCd){
                        slct = 'selected';
                    }
                    temp +=`<option value="${item.IPCd}" ${slct}>${item.Name}</option>`;
                });
                $('#IPCd').html(temp);
            }else{
              alert(res.response);
            }
        });
        
    }

    function getPortionByItem(){
        var ItemId = $('#ItemId').val();
        var itemname = $('option:selected', $(`#ItemId`)).attr('itemname');
        $(`#itemname`).val(itemname);
        if(ItemId > 0){

            $.post('<?= base_url('restaurant/get_item_portion_by_itemId') ?>',{ItemId:ItemId},function(res){
                if(res.status == 'success'){
                    var temp = `<option value=""><?= $this->lang->line('select'); ?></option>`;
                    res.response.forEach((item) => {
                        temp +=`<option value="${item.Itm_Portion}">${item.Portion}</option>`;
                    });
                    $('#IPCd').html(temp);
                }else{
                  alert(res.response);
                }
            });
        }
    }

    function getGoodPortion(counter){
        var ItemId = $(`#goods_${counter}`).val();
        if(ItemId > 0){

            $.post('<?= base_url('restaurant/get_item_portion_by_itemId') ?>',{ItemId:ItemId},function(res){
                if(res.status == 'success'){
                    var temp = `<option value=""><?= $this->lang->line('select'); ?></option>`;
                    res.response.forEach((item) => {
                        temp +=`<option value="${item.Itm_Portion}">${item.Portion}</option>`;
                    });
                    $(`#RMUOM_${counter}`).html(temp);
                }else{
                  alert(res.response);
                }
            });
        }
    }

    function getRMItemsUOM(count){
        var RMCd = $('#RMCd_'+count).val();
        $.ajax({
            url: "<?php echo base_url('restaurant/getRMItemsUOMList'); ?>",
            type: "post",
            data:{'RMCd': RMCd},
            success: function(data){
                
                data = JSON.parse(data);
                var selectUOM = "<?= $this->lang->line('selectRUOM'); ?>";
                var b = '<option value = "">'+selectUOM+'</option>';
                for(i = 0;i<data.length;i++){
                    b = b+'<option value="'+data[i].UOMCd+'">'+data[i].Name+'</option>';
                }
                
                $('#RMUOM_'+count).html(b);
            }
        });
    }

    function getGoodPortionE(counter){
        var ItemId = $(`#goods_${counter}`).val();
        var rmuom = $('option:selected', $(`#goods_${counter}`)).attr('rmuom');
        if(ItemId > 0){

            $.post('<?= base_url('restaurant/get_item_portion_by_itemId') ?>',{ItemId:ItemId},function(res){
                if(res.status == 'success'){
                    var temp = `<option value=""><?= $this->lang->line('select'); ?></option>`;
                    res.response.forEach((item) => {
                        var slct = '';
                        if(item.Itm_Portion == rmuom){
                            slct = 'selected';
                        }
                        temp +=`<option value="${item.Itm_Portion}" ${slct}>${item.Portion}</option>`;
                    });
                    $(`#RMUOM_${counter}`).html(temp);
                }else{
                  alert(res.response);
                }
            });
        }
    }

    function getRMItemsUOME(count){
        var RMCd = $('#RMCd_'+count).val();
        var rmuom = $('option:selected', $(`#RMCd_${count}`)).attr('rmuom');
        $.ajax({
            url: "<?php echo base_url('restaurant/getRMItemsUOMList'); ?>",
            type: "post",
            data:{'RMCd': RMCd},
            success: function(data){
                // alert(data);
                data = JSON.parse(data);
                var selectUOM = "<?= $this->lang->line('selectRUOM'); ?>";
                var b = '<option value = "">'+selectUOM+'</option>';
                for(i = 0;i<data.length;i++){
                    var slct = '';
                    if(data[i].UOMCd == rmuom){
                        slct = 'selected';
                    }
                    b = b+'<option value="'+data[i].UOMCd+'" '+slct+' >'+data[i].Name+'</option>';
                }
                // alert(b);
                $('#RMUOM_'+count).html(b);
            }
        });
    }

    var count = 0;
    function addRow(){
        count++;
        console.log(count);
        var selectItem = "<?= $this->lang->line('selectItem'); ?>";
        var selectUOM = "<?= $this->lang->line('selectRUOM'); ?>";

        var template = '<tr>\
                            <td>\
                            <input type="hidden" name="BNo[]" value="0">\
                            <select name="itemtype[]" id="itemtype_'+count+'" class="form-control form-control-sm" required="" onchange="changeItemType('+count+')">\
                                <option value="1">RM</option>\
                                <option value="2">Inter</option>\
                                <option value="3">Finish Goods</option>\
                            </select></td>\
                            <td>\
                            <select name="RMCd[]" id="RMCd_'+count+'" class="form-control form-control-sm select2 custom-select" onchange="getRMItemsUOM('+count+')">\
                                <option value="">'+selectItem+'</option>\
                                <?php
                        if(!empty($rm_items)){
                            foreach ($rm_items as $row) { ?>
                                <option value="<?= $row['RMCd'] ?>"><?= $row['RMName']; ?></option>\
                            <?php } } ?>
                            </select>\
                            <select name="BomNo[]" id="BomNo_'+count+'" class="form-control form-control-sm select2 custom-select"  onchange="getBomPortion('+count+')" style="display:none;">\
                                <option value="">'+selectItem+'</option>\
                                <?php
                        if(!empty($intermediate)){
                            foreach ($intermediate as $row) { ?>
                                <option value="<?= $row['BOMNo'] ?>" ipcd="<?= $row['IPCd'] ?>"><?= $row['Name']; ?></option>\
                            <?php } } ?>
                            </select>\
                            <select name="goods[]" id="goods_'+count+'" class="form-control form-control-sm select2 custom-select"  onchange="getGoodPortion('+count+')" style="display:none;">\
                                <option value="">'+selectItem+'</option>\
                                <?php
                        if(!empty($items)){
                            foreach ($items as $row) { ?>
                                <option value="<?= $row['ItemId'] ?>"><?= $row['Name']; ?></option>\
                            <?php } } ?>
                            </select>\
                            </td>\
                            <td>\
                            <input type="text" class="form-control form-control-sm" name="RMQty[]" required="" id="RMQty" onblur="changeValue(this)">\
                            </td>\
                            <td>\
                            <select name="RMUOM[]" id="RMUOM_'+count+'" class="form-control form-control-sm">\
                                <option value="">'+selectUOM+'</option>\
                            </select>\
                            <input type="text" readonly name="bomRMUOM[]" id="bomRMUOM_'+count+'" class="form-control form-control-sm" style="display:none;" />\
                            </td>\
                            <td>\
                            <input type="text" name="itemCost[]" id="itemCost_'+count+'" class="form-control form-control-sm" style="width:100px;" />\
                            </td>\
                            <td>\
                                <button class="btn btn-sm btn-danger btn-rounded" onclick="deleteItem(this)">\
                                    <i class="fa fa-trash"></i></button>\
                            </td>\
                        </tr>';

            $("#box_body").append(template);

    }
    // Delete item from table
    function deleteItem(event) {
        count--;
        $(event).parent().parent().remove();
    }

    function changeItemType(counter){
        getGoodPortionE(counter);
        getRMItemsUOME(counter);
        getBomPortion(counter);

        var type = $(`#itemtype_${counter}`).val();
        if(type==1){
            // RM
            $(`#BomNo_${counter}`).hide();
            $(`#goods_${counter}`).hide();
            $(`#RMCd_${counter}`).show();

            $(`#RMUOM_${counter}`).show();
            $(`#bomRMUOM_${counter}`).hide();
        }else if(type == 2){
            // intermediate
            $(`#RMCd_${counter}`).hide();
            $(`#goods_${counter}`).hide();
            $(`#BomNo_${counter}`).show();

            $(`#RMUOM_${counter}`).hide();
            $(`#bomRMUOM_${counter}`).show();
        }else if(type == 3){
            // finish goods
            $(`#goods_${counter}`).show();
            $(`#RMCd_${counter}`).hide();
            $(`#BomNo_${counter}`).hide();

            $(`#RMUOM_${counter}`).show();
            $(`#bomRMUOM_${counter}`).hide();
        }
    }

    function getBomPortion(counter){
        var ipcd = $('option:selected', $(`#BomNo_${counter}`)).attr('ipcd');
        $(`#bomRMUOM_${counter}`).val(ipcd);
    }

    $('#bomForm').on('submit', function(e){
        e.preventDefault();
        var BOMNo = "<?= $BOMNo; ?>";

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/edit_bom/') ?>'+BOMNo, data,function(res){
            if(res.status == 'success'){
              alert(res.response);
            location.reload();
            }else{
              $('#msgText').html(res.response);
            }
        });

    });

    changeValue = (input) => {
        var val = $(input).val();
        $(input).val(convertToUnicodeNo(val));
    }

    function deleteItemByBom(BNo){
        $.post('<?= base_url('restaurant/delete_bom_det') ?>',{BNo:BNo},function(res){
            if(res.status == 'success'){
              alert(res.response);
            location.reload();
            }else{
              alert(res.response);
            }
        });
    }

  
</script>