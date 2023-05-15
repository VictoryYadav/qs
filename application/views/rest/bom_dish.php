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

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18 text-center"><?php echo $title; ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" id="bomForm">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="cuisine" id="cuisine" class="form-control" required="" onchange="getCategory()">
                                                            <option value="">Select Cuisine</option>
                                                            <?php foreach($cuisine as $key){?>
                                                        <option value="<?= $key['CID']?>"><?= $key['Name']?></option>
                                                        <?php }?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="menucat" id="menucat" class="form-control" required="" onchange="getItem()">
                                                            <option value="">Select</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="ItemId" id="ItemId" class="form-control" required="">
                                                            <option value="">Select</option>
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="RMCd" id="RMCd" class="form-control" required="">
                                                            <option value="">Select</option>
                                                            <?php
                                                    if(!empty($rm_items)){
                                                        foreach ($rm_items as $row) { ?>
                                                            <option value="<?= $row['RMCd'] ?>"><?= $row['RMName']; ?></option>
                                                        <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" name="RMQty" placeholder="Quantity" required="" id="RMQty">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="RMUOM" id="RMUOM" class="form-control" required="">
                                                            <option value="">Select RMUOM</option>
                                                            <?php
                                                    if(!empty($RMUOM)){
                                                        foreach ($RMUOM as $row) { ?>
                                                            <option value="<?= $row['UOMCd'] ?>"><?= $row['Name']; ?></option>
                                                        <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">
                                                <input type="submit" class="btn btn-success" value="Submit">
                                            
                                                <div class="text-success" id="msgText"></div>
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
                                                    <th>Item Name</th>
                                                    <th>RMName</th>
                                                    <th>RMUOM</th>
                                                    <th>Quantity</th>
                                                    <!-- <th>Action</th> -->
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($bom_dish)){
                                                        $i = 1;
                                                        foreach ($bom_dish as $row) { ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $row['ItemNm']; ?></td>
                                                        <td><?= $row['RMName']; ?></td>
                                                        <td><?= $row['RMQty']; ?></td>
                                                        <td><?= $row['Name']; ?></td>
                                                        <!-- <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $row['RMCd'] ?>,<?= $row['RMCatg'] ?>, '<?= $row['RMName'] ?>')">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td> -->
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
                var b = '<option value = "">ALL</option>';
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
                var b = '<option value = "">Select</option>';
                for(i = 0;i<data.length;i++){
                    b = b+'<option value="'+data[i].ItemId+'">'+data[i].ItemNm+'</option>';
                }
                // alert(b);
                $('#ItemId').html(b);
            }
        });
    }


    $('#bomForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/bom_dish_list') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
            // location.reload();
            }else{
              $('#msgText').html(res.response);
            }
        });

    });

  
</script>