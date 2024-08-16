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
                                            <div class="row">
                                                <div class="col-md-2 col-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('type'); ?></label>
                                                        <select name="BOMDishTyp" id="bomType" class="form-control form-control-sm" required="" onchange="changeType()">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="1"><?= $this->lang->line('finish'); ?> <?= $this->lang->line('goods'); ?></option>
                                                            <option value="2"><?= $this->lang->line('intermediate'); ?></option>
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
                                                                <option value="<?= $key['ItemId']; ?>" itemname="<?= $key['Name']; ?>"><?= $key['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <input type="hidden" name="itemname" id="itemname">
                                                </div>

                                                <div class="col-md-3 col-6 mitemBlock" style="display: none;">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('item'); ?></label>
                                                        <input type="text" name="MItemId" id="MItemId" class="form-control form-control-sm" />
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
                                                        <input type="text" name="Qty" id="Qty" class="form-control form-control-sm" required="" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('costing'); ?></label>
                                                        <input type="text" name="Costing" id="Costing" class="form-control form-control-sm" required="" />
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
                                                        
                                                    </tbody>
                                                  </table>
                                                  </div>

                                            </div>
                                            <div class="">
                                                <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>">
                                            
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

        getPortion();
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

        $.post('<?= base_url('restaurant/get_item_portionList') ?>',function(res){
            if(res.status == 'success'){
                var temp = `<option value=""><?= $this->lang->line('select'); ?></option>`;
                res.response.forEach((item) => {
                    temp +=`<option value="${item.IPCd}">${item.Name}</option>`;
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
                // alert(data);
                data = JSON.parse(data);
                var selectUOM = "<?= $this->lang->line('selectRUOM'); ?>";
                var b = '<option value = "">'+selectUOM+'</option>';
                for(i = 0;i<data.length;i++){
                    b = b+'<option value="'+data[i].UOMCd+'">'+data[i].Name+'</option>';
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
            // $(`#goods_${count}`).select2();
            // $(`#RMCd_${count}`).select2();
            // $(`#BomNo_${count}`).select2();

    }
    // Delete item from table
    function deleteItem(event) {
        count--;
        $(event).parent().parent().remove();
    }

    function changeItemType(counter){
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

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/add_bom_dish') ?>',data,function(res){
            if(res.status == 'success'){
              // $('#msgText').html(res.response);
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

  
</script>