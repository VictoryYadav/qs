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
                                        <form method="post" id="userRoleForm">
                                            
                                            <div class="row">
                                                <div class="col-md-3 col-12">
                                                    <div class="form-group">
                                                        <label for="">EType</label>
                                                        <select name="EType" id="" class="form-control form-control-sm" required="">
                                                            <option value="1" <?php if($detail['EType'] == 1){ echo 'selected'; } ?>>QSR</option>
                                                            <option value="5" <?php if($detail['EType'] == 5){ echo 'selected'; } ?> >Sit In</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-12">
                                                    <div class="form-group">
                                                        <label for="">Category</label>
                                                        <select name="CatgID" id="" class="form-control form-control-sm" required="">
                                                <?php foreach ($Category as $key) { ?> 
                                                    <option value="<?= $key['CatgID']; ?>" <?php if($detail['CatgID'] == $key['CatgID']){ echo 'selected'; } ?> ><?= $key['CatgNm']; ?></option>
                                                <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-12">
                                                    <div class="form-group">
                                                        <label for="">E Category</label>
                                                        <select name="ECatg" id="" class="form-control form-control-sm" required="">
                                                        <?php foreach ($ECategory as $key) { ?> 
                                                        <option value="<?= $key['CatgID']; ?>" <?php if($detail['ECatg'] == $key['CatgID']){ echo 'selected'; } ?> ><?= $key['CatgNm']; ?></option>
                                                    <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <hr>
                                            <div class="row">
                                                <?php foreach ($roles as $role) { ?>
                                                <div class="col-md-2">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck<?= $role['RoleId']; ?>" data-parsley-multiple="groups" data-parsley-mincheck="2" name="role[<?= $role['RoleId']; ?>]"  <?php if($role['Stat'] == 0){ echo 'checked'; } ?> value="0">
                                                            <label class="custom-control-label" for="customCheck<?= $role['RoleId']; ?>"><?= $role['Name']; ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            </div>

                                            <div >
                                                    <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>" id="saveBtn">

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
        
    });

    $('#userRoleForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restsupp/user_roles') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
            }else{
              $('#msgText').html(res.response);
            }
            location.reload();
        });

    });

</script>