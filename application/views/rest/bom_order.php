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

            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" id="bomForm">
                                            <input type="hidden" name="type" value="insert">
                                            <div class="row">
                                                <div class="col-md-2 col-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('kitchen'); ?></label>
                                                        <select name="KitCd" id="KitCd" class="form-control form-control-sm" required="" >
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                            foreach ($kitchen as $key) { ?>
                                                            <option value="<?= $key['MCd']; ?>"><?= $key['Name']; ?></option>
                                                        <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

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
                                                                <option value="<?= $key['ItemId']; ?>" ipcd="<?= $key['IPCd']; ?>" bomno="<?= $key['BOMNo']; ?>"><?= $key['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <input type="hidden" name="itembomno" id="itembomno">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6 mitemBlock" style="display: none;">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('item'); ?></label>
                                                        <select name="BItemId" id="BItemId" class="form-control form-control-sm select2 custom-select" onchange="getPortionByBOM();">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                            foreach ($intermediate as $key) { ?>
                                                                <option value="<?= $key['BOMNo']; ?>" ipcd="<?= $key['IPCd']; ?>"><?= $key['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
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
                                                        <input type="text" name="Qty" id="Qty" class="form-control form-control-sm" required="" value="1" />
                                                    </div>
                                                </div>

                                            </div>
                                            
                                            <div class="">
                                                <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">          
                                          <table class="table table-bordered" id="bomOrder">
                                            <thead>
                                              <tr>
                                                <th><?= $this->lang->line('item'); ?></th>
                                                <th><?= $this->lang->line('kitchen'); ?></th>
                                                <th><?= $this->lang->line('type'); ?></th>
                                                <th><?= $this->lang->line('quantity'); ?></th>
                                                <th><?= $this->lang->line('mode'); ?></th>
                                              </tr>
                                            </thead>
                                            <tbody id="box_body">
                                                <?php 
                                                if(!empty($orders)){
                                                    foreach ($orders as $key) {
                                                    $type = ($key['BOMDishTyp']==1)?'Finish Goods':'Intermediate';        
                                                    $chk = ($key['Stat'] == 0)?'checked':'';                                             
                                                ?>
                                                <tr>
                                                    <td><?= $key['itemname']." (<small>".$key['portions']."</small>)"; ?></td>
                                                    <td><?= $key['kitchen']; ?></td>
                                                    <td><?= $type; ?></td>
                                                    <td><?= $key['Qty']; ?></td>
                                                    <td>
                                                        <?php if($key['Stat'] < 1){ ?>
                                                        <input type="checkbox" id="switch4" switch="danger" onchange="changeStatus(<?= $key['BOrdNo']; ?>, <?= $key['Stat']; ?>)" <?= $chk; ?>>
                                                        <label for="switch4" data-on-label="P" data-off-label="A" style="cursor: pointer;"></label>
                                                    <?php } ?>

                                                    </td>
                                                </tr>
                                            <?php } } ?>
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

        $('#bomOrder').DataTable();
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
        var bomno = $('option:selected', $(`#ItemId`)).attr('bomno');
        $(`#itembomno`).val(bomno);
        if(ItemId > 0){

            $.post('<?= base_url('restaurant/get_item_portion_by_itemId') ?>',{ItemId:ItemId},function(res){
                if(res.status == 'success'){
                    var temp = `<option value=""><?= $this->lang->line('select'); ?></option>`;
                    res.response.forEach((item) => {
                        temp +=`<option value="${item.Itm_Portion}">${item.Portions}</option>`;
                    });
                    $('#IPCd').html(temp);
                }else{
                  alert(res.response);
                }
            });
        }
    }

    function getPortionByBOM(){
        var BItemId = $('#BItemId').val();
        var ipcd = $('option:selected', $(`#BItemId`)).attr('ipcd');
        
        if(BItemId > 0){

            $.post('<?= base_url('restaurant/get_item_portion_by_bom') ?>',{BItemId:BItemId, ipcd:ipcd},function(res){
                if(res.status == 'success'){
                    var temp = `<option value=""><?= $this->lang->line('select'); ?></option>`;
                    res.response.forEach((item) => {
                        temp +=`<option value="${item.IPCd}">${item.Portions}</option>`;
                    });
                    $('#IPCd').html(temp);
                }else{
                  alert(res.response);
                }
            });
        }
    }

    $('#bomForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/bom_order') ?>',data,function(res){
            if(res.status == 'success'){
              alert(res.response);
            location.reload();
            }else{
              alert(res.response);
            }
        });

    });

    function changeStatus(BOrdNo, stat){
        $.post('<?= base_url('restaurant/bom_order') ?>',{type:'update', BOrdNo:BOrdNo, stat:stat},function(res){
            if(res.status == 'success'){
                alert(res.response);
                location.reload();
            }else{
              alert(res.response);
            }
        });
    }
  
  
</script>