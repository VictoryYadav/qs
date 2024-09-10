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
                                        <form method="post" id="tableForm">
                                            
                                            <div class="row">

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('table'); ?></label>
                                                        <select name="table" id="table" class="form-control form-control-sm" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>

                                                            <option value="3POrders">3POrders</option>
                                                            <option value="Category">Category</option>
                                                            <option value="ConfigPymt">ConfigPymt</option>
                                                            <option value="Cuisines">Cuisines</option>
                                                            <option value="CustOfferTypes">CustOfferTypes</option>
                                                            <option value="ECategory">ECategory</option>
                                                            <option value="FoodType">FoodType</option>
                                                            <option value="ItemPortions">ItemPortions</option>
                                                            <option value="ItemTypes">ItemTypes</option>
                                                            <option value="Languages">Languages</option>
                                                            <option value="Masts">Masts</option>
                                                            <option value="MenuTags">MenuTags</option>
                                                            <option value="orderType">orderType</option>
                                                            <option value="Sections">Sections</option>
                                                            <option value="Tax">Tax</option>
                                                            <option value="UserType">UserType</option>
                                                            <option value="Tax">Tax</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-6">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label><br>
                                                        <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('update'); ?>" id="saveBtn">
                                                    </div>
                                                </div>
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

    $('#tableForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/table_update') ?>',data,function(res){
            if(res.status == 'success'){
              alert(res.response);
            }else{
              alert(res.response);
            }
            // location.reload();
        });

    });

</script>