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
                                        <form method="post" id="topingForm">
                                            
                                            <div class="row">
                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('item'); ?> <?= $this->lang->line('name'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="itemName" placeholder="<?= $this->lang->line('name'); ?>" required="" id="itemName" autocomplete="off" value="<?= $toppings['ItemName']; ?>" readonly>

                                                        <input type="hidden" name="ItemId" value="<?= $toppings['ItemId']; ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('foodType'); ?></label>
                                                        <select class="form-control form-control-sm" name="FID" required="" id="FID" >
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                            foreach ($foodType as $key) { ?>
                                                                <option value="<?= $key['FID']; ?>" <?php if($key['FID']== $toppings['FID']){ echo 'selected'; } ?> ><?= $key['Opt']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-8">
                                                    <button type="button" class="btn btn-info btn-sm btn-rounded" id="addrow"><i class="fa fa-plus"></i></button>
                                                    <div class="table-responsive">
                                                      <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th><?= $this->lang->line('section'); ?></th>
                                                                <th><?= $this->lang->line('portion'); ?></th>
                                                                <th><?= $this->lang->line('rate'); ?></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tblBody">
                                                        <?php 
                                                        if(!empty($itmRates)){
                                                            foreach ($itmRates as $iRate) { ?>
                                                        <tr>
                                                            <td>
                                                                <select name="sections[]" id="" class="form-control form-control-sm" required="">
                                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                                    <?php 
                                                                    foreach ($EatSections as $key) { ?>
                                                                    <option value="<?= $key['SecId']; ?>" <?php if($key['SecId'] == $iRate['SecId']){ echo 'selected'; } ?>><?= $key['Name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <select name="portions[]" id="" class="form-control form-control-sm" required="">
                                                                    <option value="0"><?= $this->lang->line('select'); ?></option>
                                                                    <?php 
                                                                    foreach ($ItemPortions as $key) { ?>
                                                                    <option value="<?= $key['IPCd']; ?>" <?php if($key['IPCd'] == $iRate['Itm_Portion']){ echo 'selected'; } ?>><?= $key['Name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input type="number" name="price[]" id="" class="form-control form-control-sm" required="" value="<?= $iRate['ItmRate'] ?>" />

                                                                <input type="hidden" name="irno[]"  value="<?= $iRate['IRNo'] ?>" />
                                                            </td>
                                                            <td>
                                                                <!-- <button type="button" class="btn btn-danger btn-sm btn-rounded deleteRow"><i class="fa fa-trash"></i></button> -->
                                                            </td>
                                                        </tr>
                                                    <?php } } ?>
                                                        </tbody>
                                                      </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <p id="msgText"></p>
                                            <input type="Submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('update'); ?>">
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
        $('#kitchenTbl').DataTable();
    });

$("#addrow").on("click", function () {
    
    var newRow = '<tr>\
                        <td>\
                            <select name="sections[]" id="" class="form-control form-control-sm" required="">\
                                <option value=""><?= $this->lang->line('select'); ?></option>\
                                <?php 
                                foreach ($EatSections as $key) { ?>
                                <option value="<?= $key['SecId']; ?>"><?= $key['Name']; ?></option>\
                                <?php } ?>
                            </select>\
                        </td>\
                        <td>\
                            <select name="portions[]" id="" class="form-control form-control-sm" required="">\
                                <option value=""><?= $this->lang->line('select'); ?></option>\
                                <?php 
                                foreach ($ItemPortions as $key) { ?>
                                <option value="<?= $key['IPCd']; ?>"><?= $key['Name']; ?></option>\
                                <?php } ?>
                            </select>\
                        </td>\
                        <td>\
                            <input type="number" name="price[]" id="" class="form-control form-control-sm" required="" />\
                            <input type="hidden" name="irno[]"  value="0" />\
                        </td>\
                        <td>\
                            <button type="button" class="btn btn-danger btn-sm btn-rounded deleteRow"><i class="fa fa-trash"></i></button>\
                        </td>\
                    </tr>';

        $("#tblBody").append(newRow);        
});

// remove row
$("#tblBody").on('click','.deleteRow',function(){
    $(this).parent().parent().remove();
});

$('#topingForm').on('submit', function(e){
    e.preventDefault();
    var itemId = "<?= $ItemId; ?>";
    var data = $(this).serializeArray();
    $.post('<?= base_url('restaurant/edit_toppings/') ?>'+itemId,data,function(res){
        if(res.status == 'success'){
          $('#msgText').html(res.response);
        }else{
          $('#msgText').html(res.response);
        }
        // location.reload();
    });
});

</script>