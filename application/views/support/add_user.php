<?php $this->load->view('layouts/support/head'); ?>
        <?php $this->load->view('layouts/support/top'); ?>
            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <?php $this->load->view('layouts/support/sidebar'); ?>
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

                        <div class="row showBlock" >
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                    <form method="post" id="signupForm">
                                        <div class="row">

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('country'); ?></label>
                                                    <select  name="countryCd" id="CountryCd" class="form-control form-control-sm select2 custom-select" required="">
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
                                                    <input type="text" class="form-control form-control-sm" placeholder="Phone" name="mobileNo" id="mobileNo" required="" autocomplete="off" minlength="10" maxlength="10" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" />
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('password'); ?></label>
                                                    <input type="password" class="form-control form-control-sm" placeholder="Password" name="pwd" required="" autocomplete="off" />
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('name'); ?></label>
                                                    <input type="text" class="form-control form-control-sm" placeholder="FullName" name="fullname" autocomplete="off" required=""/>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('email'); ?></label>
                                                    <input type="email" class="form-control form-control-sm" placeholder="Email" name="email" autocomplete="off" required="" />
                                                </div>
                                            </div>
                                            <?php
                                            $dateP = date('Y-m-d', strtotime("-20 years", strtotime(date('Y-m-d'))));
                                             ?>
                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('dob'); ?></label>
                                                    <input type="date" name="DOB" id="DOB" class="form-control form-control-sm" required="" value="<?= $dateP; ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('gender'); ?></label>
                                                    <select name="gender" id="gender" class="form-control form-control-sm" required="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <option value="1"><?= $this->lang->line('male'); ?></option>
                                                        <option value="2"><?= $this->lang->line('female'); ?></option>
                                                        <option value="3"><?= $this->lang->line('transgender'); ?></option> 
                                                    </select>

                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('role'); ?></label>
                                                    <select name="userType" id="userType" class="form-control form-control-sm" required="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <option value="1">Support Create</option>
                                                        <option value="2">Support Rest</option>
                                                        <option value="9">Support Admin</option>
                                                    </select>

                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div>
                                            <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('submit'); ?>">
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <?php $this->load->view('layouts/support/footer'); ?>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <?php $this->load->view('layouts/support/color'); ?>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        
        <?php $this->load->view('layouts/support/script'); ?>
<!-- loader -->
<div class="container text-center" id="loadBlock" style="display: none;">
    <img src="<?= base_url('assets/images/loader.gif'); ?>" alt="Eat Out">
</div>

<script type="text/javascript">

    $(document).ready(function () {
        $('#CountryCd').select2();
    });

    $('#signupForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        
        $.post('<?= base_url('support/new_user') ?>',data,function(res){
            if(res.status == 'success'){
               alert(res.response);
            }else{
              alert(res.response);
            }
            location.reload();
        });
    });
</script>