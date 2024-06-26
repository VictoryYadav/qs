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
                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <select name="cuisine" id="cuisine" class="form-control form-control-sm" required="" onchange="getCategory()">
                                                            <option value=""><?= $this->lang->line('selectCuisine'); ?></option>
                                                            <?php foreach($cuisine as $key){?>
                                                        <option value="<?= $key['CID']?>"><?= $key['Name']?></option>
                                                        <?php }?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <select name="menucat" id="menucat" class="form-control form-control-sm" required="" onchange="getItem()">
                                                            <option value=""><?= $this->lang->line('selectCategory'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <select name="ItemId" id="ItemId" class="form-control form-control-sm" required="" onchange="showBlock()">
                                                            <option value=""><?= $this->lang->line('selectDish'); ?></option>   
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="rowBlock" style="display:none;">
                                            <button class="btn btn-sm btn-success btn-rounded mb-1" onclick="addRow()"><i class="fa fa-plus"></i></button>

                                                <div class="table-responsive">          
                                                  <table class="table table-bordered">
                                                    <thead>
                                                      <tr>
                                                        <th><?= $this->lang->line('item'); ?></th>
                                                        <th><?= $this->lang->line('quantity'); ?></th>
                                                        <th><?= $this->lang->line('uom'); ?></th>
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
    });

    function getCategory(){
        var cui = $('#cuisine').val();
        // alert(cui);
        $.ajax({
            url: "<?php echo base_url('restaurant/item_list_get_category'); ?>",
            type: "post",
            data:{'CID': cui},
            success: function(data){
                // alert(data);
                data = JSON.parse(data);
                var All = "<?= $this->lang->line('all'); ?>";
                var b = '<option value = "">'+All+'</option>';
                for(i = 0;i<data.length;i++){
                    b = b+'<option value="'+data[i].MCatgId+'">'+data[i].MCatgNm+'</option>';
                }
                // alert(b);
                $('#menucat').html(b);
            }
        });
    }

    function getItem(){
        var mcatg = $('#menucat').val();
        $.ajax({
            url: "<?php echo base_url('restaurant/getMenuItemList'); ?>",
            type: "post",
            data:{'MCatgId': mcatg},
            success: function(data){
                // alert(data);
                data = JSON.parse(data);
                var selectDish = "<?= $this->lang->line('selectDish'); ?>";
                var b = '<option value = "">'+selectDish+'</option>';
                for(i = 0;i<data.length;i++){
                    b = b+'<option value="'+data[i].ItemId+'">'+data[i].ItemNm+'</option>';
                }
                // alert(b);
                $('#ItemId').html(b);
            }
        });
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

    function showBlock(){
        var item = $('#ItemId').val();
        if(item){

            $.post('<?= base_url('restaurant/get_bom_dish') ?>',{item:item},function(res){
                if(res.status == 'success'){
                    var data = res.response;
                    console.log(data);

                    var b = '';
                    
                    // // alert(b);
                    $('#box_body').html(res.response);

                  // $('#box_body').html(res.response);
                }else{
                  $('#box_body').html(res.response);
                }
            });

            $('#rowBlock').show();
        }
    }

    var count = 0;
    function addRow(){
        count++;
        console.log(count);
        var selectItem = "<?= $this->lang->line('selectItem'); ?>";
        var selectUOM = "<?= $this->lang->line('selectRUOM'); ?>";

        var template = '<tr>\
                            <td>\
                            <select name="RMCd[]" id="RMCd_'+count+'" class="form-control form-control-sm" required="" onchange="getRMItemsUOM('+count+')">\
                                <option value="">'+selectItem+'</option>\
                                <?php
                        if(!empty($rm_items)){
                            foreach ($rm_items as $row) { ?>
                                <option value="<?= $row['RMCd'] ?>"><?= $row['RMName']; ?></option>\
                            <?php } } ?>
                            </select>\
                            </td>\
                            <td>\
                            <input type="text" class="form-control form-control-sm" name="RMQty[]" required="" id="RMQty" onblur="changeValue(this)">\
                            </td>\
                            <td>\
                            <select name="RMUOM[]" id="RMUOM_'+count+'" class="form-control form-control-sm" required="">\
                                <option value="">'+selectUOM+'</option>\
                            </select>\
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


    $('#bomForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/bom_dish') ?>',data,function(res){
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