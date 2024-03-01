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
                                    <form method="post" id="customerForm">
                                        <div class="row">

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('restaurant'); ?></label>
                                                    <input type="text" name="Name" id="Name" class="form-control form-control-sm" required="">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label>Person Name</label>
                                                    <input type="text" name="ContactPerson" id="ContactPerson" class="form-control form-control-sm"  required="">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('mobile'); ?></label>
                                                    <input type="number" name="CellNo" id="CellNo" class="form-control form-control-sm" required="">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('email'); ?></label>
                                                    <input type="email" name="Email" id="Email" class="form-control form-control-sm" required="">
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
                                                    <label>E<?= $this->lang->line('category'); ?></label>
                                                    <input type="text" name="ECatg" id="ECatg" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('chainNo'); ?></label>
                                                    <input type="number" name="ChainId" id="ChainId" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('category'); ?></label>
                                                    <input type="text" name="CatgId" id="CatgId" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('GSTNo'); ?></label>
                                                    <input type="number" name="GSTNo" id="GSTNo" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('area'); ?></label>
                                                    <input type="text" name="Area" id="Area" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('suburb'); ?></label>
                                                    <input type="text" name="Suburb" id="Suburb" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('city'); ?></label>
                                                    <input type="text" name="City" id="City" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('pincode'); ?></label>
                                                    <input type="number" name="PIN" id="PIN" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('state'); ?></label>
                                                    <input type="text" name="State" id="State" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('address'); ?></label>
                                                    <input type="text" name="HOAddress" id="HOAddress" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('fromTime'); ?></label>
                                                    <input type="time" name="StTime" id="StTime" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('toTime'); ?></label>
                                                    <input type="time" name="EndTime" id="EndTime" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('latitude'); ?></label>
                                                    <input type="text" name="Lat" id="Lat" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('longitude'); ?></label>
                                                    <input type="text" name="Lng" id="Lng" class="form-control form-control-sm" >
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="text-right">
                                            <input type="submit" class="btn btn-sm btn-success" value="Submit">
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

    $('#customerForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('support/new_customer_create') ?>',data,function(res){
            if(res.status == 'success'){
              alert(res.response);
              // location.reload();
            }else{
              alert(res.response);
            }
        });
    });



    
</script>