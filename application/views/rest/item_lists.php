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

            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        <div class="text-right mb-2">
                            <?php if(empty($counter)){ ?>
                            <a href="<?= base_url('restaurant/menu_item_rates'); ?>" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i></a>&nbsp;&nbsp;

                            <a href="<?= base_url('restaurant'); ?>" class="btn btn-sm btn-primary"><i class="fas fa-arrow-right"></i></a>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" action="<?php echo base_url('restaurant/item_list'); ?>">
                                            <div class="row">
                                                <div class="col-md-2 col-5">
                                                    <div class="form-group">
                                                        <label for="">
                                                            <?= $this->lang->line('cuisine'); ?>
                                                        </label>
                                                    <select class="form-control form-control-sm" name="cuisine" id="cuisine" onchange="getCategory()">
                                                        <option value=""><?= $this->lang->line('all'); ?></option>
                                                        <?php foreach($cuisine as $key){?>
                                                        <option value="<?= $key['CID']?>" <?php if($key['CID'] == $CID){ echo 'selected';}?>><?= $key['Name']?></option>
                                                    <?php }?>
                                                    </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label for="">
                                                            <?= $this->lang->line('menuCategory'); ?>
                                                        </label>
                                                    <select class="form-control select2 custom-select" name="menucat" id="menucat" style="width: 100%;">
                                                        <option value=""><?= $this->lang->line('all'); ?></option>
                                                        <?php foreach($menucat as $key){?>
                                                            <option value="<?= $key['MCatgId']?>" <?php if($key['MCatgId'] == $catid){ echo 'selected';}?>><?= $key['MCatgNm']?></option>
                                                        <?php }?>
                                                    </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-5">
                                                    <div class="form-group">
                                                        <label for="">
                                                            <?= $this->lang->line('section'); ?>
                                                        </label>
                                                    <select class="form-control form-control-sm" name="SecId" id="SecId" style="width: 100%;">
                                                        <option value=""><?= $this->lang->line('all'); ?></option>
                                                        <?php  
                                                        foreach ($sections as $key) { ?>
                                                        <option value="<?= $key['SecId']; ?>" <?php if($key['SecId'] == $SecId){ echo 'selected';} ?> ><?= $key['Name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label for="">
                                                            <?= $this->lang->line('list'); ?>
                                                        </label>
                                                    <select class="form-control form-control-sm" name="filter" id="filter" style="width: 100%;">
                                                        <option value=""><?= $this->lang->line('all'); ?></option>
                                                        <option value="draft" <?php if($filter == 'draft'){ echo 'selected';}?>>Draft</option>
                                                        <option value="extras" <?php if($filter == 'extras'){ echo 'selected';}?> >Extras</option>
                                                        <option value="enabled" <?php if($filter == 'enabled'){ echo 'selected';}?> >Enabled</option>
                                                        <option value="disabled" <?php if($filter == 'disabled'){ echo 'selected';}?> >Disabled</option>
                                                    </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-2">
                                                    <div class="form-group">
                                                        <label for="">&nbsp;</label><br>
                                                    <input type="submit" class="btn btn-info btn-sm" value="<?= $this->lang->line('search'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-5">
                                                    <label for=""><?= $this->lang->line('action'); ?></label>&nbsp; 
                                                    <input type="checkbox" name="selectAll" id="selectAll">
                                                    <label for=""><?= $this->lang->line('select'); ?> <?= $this->lang->line('all'); ?></label> |
                                                    <button type="button" class="btn btn-success btn-sm btnc" onclick="updateData('live');"><?= $this->lang->line('live'); ?></button>
                                                    <button type="button" class="btn btn-primary btn-sm btnc" onclick="updateData('enabled');"><?= $this->lang->line('enabled'); ?></button>
                                                    <button type="button" class="btn btn-danger btn-sm btnc" onclick="updateData('disabled');"><?= $this->lang->line('disabled'); ?></button>
                                                    
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="item_lists" class="table table-bordered">
                                                <thead>
                                                <tr style="background: #cbc6c6;">
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('item'); ?></th>
                                                    <th><?= $this->lang->line('section'); ?></th>
                                                    <th><?= $this->lang->line('portion'); ?></th>
                                                    <th><?= $this->lang->line('rate'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($menuItemData)){
                                                        $i=1;
                                                        foreach ($menuItemData as $key) {
                                                     ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="itemId[]" class="selectItemId" value="<?= $key['ItemId']; ?>" irno="<?= $key['IRNo']; ?>">
                                                    </td>
                                                    <td>
                                                        <?php if($key['Stat'] == 5){ ?>
                                                            <a href="<?= base_url('restaurant/edit_toppings/'.$key['ItemId'])?>">
                                                        <?php echo $key['ItemNm']; ?>
                                                        </a>    
                                                        <?php } else{ ?>
                                                        <a href="<?= base_url('restaurant/edit_item/'.$key['ItemId'])?>">
                                                        <?php echo $key['ItemNm']; ?>
                                                        </a>
                                                    <?php } ?>
                                                    </td>
                                                    <td><?php echo $key['Sections']; ?></td>
                                                    <td><?php echo $key['Portions']; ?></td>
                                                    <td>
                                                        <span class="badge badge-boxed  badge-success" style="font-size: 90%;"><?php echo $key['OrigRate']; ?></span>
                                                        <?php echo $key['ItmRate']; ?>
                                                    </td>
                                                </tr>
                                                <?php }
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

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.0/css/buttons.dataTables.css">

<script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.print.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#menucat').select2();
        // $('#item_lists').DataTable(
        //                 {"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
        //                 });

        $('#item_lists').DataTable(
        {
            responsive: true,
  
            "lengthMenu": [[10, 25, 50, 100,-1], [10, 25, 50, 100, "All"]],     // page length options
            // "scrollY": 200,
            // "scrollX": true,  
            "dom": 'Bfrtip',
            "buttons": ['copy', 'csv', 'excel', 'pdf', 'print','pageLength'],    // adds the excel button
          }   );

    });

    function validate(){
        var cui = $('#cuisine').val();
        var cat = $('#menucat').val();
        if(cui == '' && cat == ''){
            alert("Please select atleast one out of Cuisine or Catgeory");
            return false;
        }
        return true;
    }

    function getCategory(){
        var cui = $('#cuisine').val();
        
        $.ajax({
            url: "<?php echo base_url('restaurant/item_list_get_category'); ?>",
            type: "post",
            data:{'CID': cui},
            success: function(data){
                data = JSON.parse(data);
                var b = '<option value = "">ALL</option>';
                for(i = 0;i<data.length;i++){
                    b = b+'<option value="'+data[i].MCatgId+'">'+data[i].MCatgNm+'</option>';
                }
                $('#menucat').html(b);
            }
        });
    }

    $(document).ready(function(){
        $('#selectAll').on('click',function(){
            if(this.checked){
                $('.selectItemId').each(function(){
                    this.checked = true;
                });
            }else{
                 $('.selectItemId').each(function(){
                    this.checked = false;
                });
            }
        });
        
        $('.selectItemId').on('click',function(){
            if($('.selectItemId:checked').length == $('.selectItemId').length){
                $('#selectAll').prop('checked',true);
            }else{
                $('#selectAll').prop('checked',false);
                
            }
        });
    });

    function updateData(para){
        var itemId = [];
        var irno = [];

        $(".selectItemId").each(function(index, el) {
            if ($(this).prop('checked')==true){ 
                itemId.push($(this).val());
                irno.push($(this).attr("irno"));    
            }    
        });

        if(itemId.length > 0){
            $.post('<?= base_url('restaurant/updateDataItem') ?>',{type:para, itemId:itemId,irno:irno},function(res){
                if(res.status == 'success'){
                  alert(res.response);
                }else{
                  alert(res.response);
                }
                  location.reload();
            });
        }else{
            alert('Please select atleat one item!')
        }
    }

</script>