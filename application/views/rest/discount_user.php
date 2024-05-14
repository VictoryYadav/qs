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
                                        <?php if($this->session->flashdata('success')): ?>
                                            <div class="alert alert-success" role="alert" id="alertBlock"><?= $this->session->flashdata('success') ?></div>
                                            <?php endif; ?>
                                            
                                        <form method="post" id="discountForm">
                                            <input type="hidden" name="uId" id="uId">
                                            <div class="row">
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('country'); ?></label>
                                                        <select name="countryCd" id="countryCd" class="form-control form-control-sm select2 custom-select" required="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php 
                                                    foreach ($country as $key) { ?>
                                                        <option value="<?= $key['phone_code']; ?>" ><?= $key['country_name']; ?></option>
                                                    <?php } ?>  
                                                    </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('mobile'); ?></label>
                                                        <input type="text" name="MobileNo" class="form-control form-control-sm" required="" maxlength="10" minlength="10" id="MobileNo" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('name'); ?></label>
                                                        <input type="text" name="FName" class="form-control form-control-sm" required="" id="FName">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label>Discount Group</label>
                                                        <select name="discId" id="discId" class="form-control form-control-sm" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                            foreach ($discounts as $key) {
                                                            ?>
                                                            <option value="<?= $key['discId']; ?>"><?= $key['name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>" id="saveBtn">
                                            </div>
                                        </form>
                                    </div>
                                </div>
    
                            </div>
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="usersTBL" class="table table-bordered topics">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('userName'); ?></th>
                                                    <th><?= $this->lang->line('mobile'); ?></th>
                                                    <th><?= $this->lang->line('discount'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($users)){
                                                        $i=1;
                                                        foreach ($users as $key) {
                                                     ?>
                                                    
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo $key['FName'].' '.$key['LName']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $key['MobileNo']; ?> 
                                                    </td>
                                                    <td><?php echo $key['name'].'('.$key['pcent'].')%'; ?>
                                                    </td>
                                                    <td>
                                                        
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
    $('#usersTBL').DataTable();
    $('#countryCd').select2();
});

$('#MobileNo').on('change', function(e){

    var phone = $('#MobileNo').val();
    if(phone.length == 10){
        $.post('<?= base_url('restaurant/get_cust_details') ?>',{phone:phone},function(response){

        if(response.status == 'success') {
                var data = response.response;
                $('#FName').val(data.FName+' '+data.LName);
                $('#uId').val(data.uId);
        }else {
            alert(response.response);
        }
    });
    }else{
        alert('Enter Valid Phone');
    }

});


$('#discountForm').on('submit', function(e){
    e.preventDefault();

    var data = $(this).serializeArray();
    $.post('<?= base_url('restaurant/discount_user') ?>',data,function(res){
        if(res.status == 'success'){
          alert(res.response);
          location.reload();
        }else{
          alert(res.response);
        }
    });

});


</script>