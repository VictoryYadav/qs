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
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18"><?php echo $title; ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" action="<?php echo base_url('restorent/item_list'); ?>">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <select class="form-control" name="cuisine" id="cuisine" onchange="getCategory()">
                                                        <option value="">All</option>
                                                        <?php foreach($cuisine as $key){?>
                                                        <option value="<?= $key['CID']?>" <?php if($key['CID'] == $CID){ echo 'selected';}?>><?= $key['Name']?></option>
                                                    <?php }?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control" name="menucat" id="menucat">
                                                        <option value="">ALL</option>
                                                        <?php foreach($menucat as $key){?>
                                                            <option value="<?= $key['MCatgId']?>" <?php if($key['MCatgId'] == $catid){ echo 'selected';}?>><?= $key['MCatgNm']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="submit" class="btn btn-info" value="GET">
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
                                                <tr>
                                                    <th>#</th>
                                                    <th>Item Name</th>
                                                    <th>Portion</th>
                                                    <th>Available</th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($menuItemData)){
                                                        $i=1;
                                                        foreach ($menuItemData as $key) {
                                                     ?>
                                                    
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo $key['ItemNm']; ?></td>
                                                    <td><?php echo $key['Portions']; ?></td>
                                                    <td>
                                                       <!--  <label class="switch">Disabled
                                                <input type="checkbox" onchange="enableDisable(<?= $key['ItemId'];?>, this);" <?= ($key['deactive'] != '' ? '' : 'checked');?> >
                                                <span class="slider round"></span>
                                            </label> -->

                                            <input type="checkbox" id="switch3" switch="bool" onchange="enableDisable(<?= $key['ItemId'];?>, this);" <?= ($key['deactive'] != '' ? '' : 'checked');?>>
                                            <label for="switch3" data-on-label="Yes" data-off-label="No"></label>
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


<script type="text/javascript">
    $(document).ready(function () {
        $('#item_lists').DataTable();
    });

</script>

<script>
function enableDisable(id, input) {
    console.log(id);
    if ($(input).prop('checked') == false) {
        console.log("Enter Data");
        $.ajax({
            url: "<?php echo base_url('restorent/rest_item_list'); ?>",
            type: "post",
            data:{
                insertMenuItemDisabled : 1,
                id : id
            },
            dataType: "json",
            success: response => {
                console.log(response.msg);
            },
            error: (xhr, status, error) => {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }else{
        console.log("Delete Data");
        $.ajax({
            url: "<?php echo base_url('restorent/rest_item_list'); ?>",
            type: "post",
            data:{
                deleteMenuItemDisabled : 1,
                id : id
            },
            dataType: "json",
            success: response => {
                console.log(response.msg);
            },
            error: (xhr, status, error) => {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }
}

$(document).ready(function() {
    $("#item-list").DataTable();
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
    // alert(cui);
    $.ajax({
        url: "<?php echo base_url('restorent/item_list_get_category'); ?>",
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
</script>