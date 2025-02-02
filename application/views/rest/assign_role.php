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
                            <div class="col-md-7 mx-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <select name="RUserId" id="RUserId" onchange="roleAssign()" class="form-control select2 custom-select" style="width: 100%;">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php
                                                        foreach ($usersRestData as $key) {
                                                         ?>
                                                        <option value="<?php echo $key['RUserId']; ?>"><?php echo $key['MobileNo'].' ('.$key['FName'].' '.$key['LName'].'-'.$key['UTypName'].')'; ?></option>
                                                    <?php } ?>
                                                    </select>
                                                <small class="text-danger" id="msgText"></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="formData"></div>
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

<script>
    $(document).ready(function () {
        $('#RUserId').select2();
    });

    function  roleAssign() {
        var RUserId = $('#RUserId').val();
        if(RUserId){
            
            $('#msgText').hide();

            $.ajax({
                type: "POST",
                url: "<?php echo base_url()?>restaurant/getRoleData",
                data: {RUserId:RUserId},
                    success: function(res) {
                        if(res.status == 'success'){
                            var createForm = res.response.createForm;
                            $('#formData').html(createForm);
                        }else{
                            alert(res.status);
                        }
                }
            });
        }else{
            $('#msgText').show();
            $('#msgText').html('Please select Mobile No.');
        }
        
    }

    function submitData(){
        var data = $('roleAssignForm').serializeArray();
        $.post('<?= base_url('resaurant/role_assign') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
            }else{
              $('#msgText').html(res.response);
            }
            location.reload();
        });
    }

    
</script>
