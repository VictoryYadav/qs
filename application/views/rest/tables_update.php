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
                                        <div class="checkbox my-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck101" data-parsley-multiple="groups" data-parsley-mincheck="2" name="allSelected" >
                                                <label class="custom-control-label" for="customCheck101">Select All</label>
                                            </div>
                                        </div> 
                                        <form method="post" id="tableForm">
                                            
                                            <div class="row">
                                                <div class="col-md-3 col-6">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input selectedCheckBox" id="customCheck01" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tables[]" value="3POrders">
                                                            <label class="custom-control-label" for="customCheck01">3POrders</label>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input selectedCheckBox" id="customCheck02" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tables[]" value="Category">
                                                            <label class="custom-control-label" for="customCheck02">Category</label>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input selectedCheckBox" id="customCheck03" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tables[]" value="ConfigPymt">
                                                            <label class="custom-control-label" for="customCheck03">ConfigPymt</label>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input selectedCheckBox" id="customCheck04" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tables[]" value="Cuisines">
                                                            <label class="custom-control-label" for="customCheck04">Cuisines</label>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input selectedCheckBox" id="customCheck05" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tables[]" value="CustOfferTypes">
                                                            <label class="custom-control-label" for="customCheck05">CustOfferTypes</label>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input selectedCheckBox" id="customCheck06" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tables[]" value="ECategory">
                                                            <label class="custom-control-label" for="customCheck06">ECategory</label>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input selectedCheckBox" id="customCheck07" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tables[]" value="FoodType">
                                                            <label class="custom-control-label" for="customCheck07">FoodType</label>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input selectedCheckBox" id="customCheck08" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tables[]" value="ItemPortions">
                                                            <label class="custom-control-label" for="customCheck08">ItemPortions</label>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input selectedCheckBox" id="customCheck09" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tables[]" value="Languages">
                                                            <label class="custom-control-label" for="customCheck09">Languages</label>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input selectedCheckBox" id="customCheck10" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tables[]" value="Masts">
                                                            <label class="custom-control-label" for="customCheck10">Masts</label>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input selectedCheckBox" id="customCheck11" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tables[]" value="MenuTags">
                                                            <label class="custom-control-label" for="customCheck11">MenuTags</label>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input selectedCheckBox" id="customCheck12" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tables[]" value="orderType">
                                                            <label class="custom-control-label" for="customCheck12">OrderType</label>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input selectedCheckBox" id="customCheck13" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tables[]" value="Sections">
                                                            <label class="custom-control-label" for="customCheck13">Sections</label>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input selectedCheckBox" id="customCheck14" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tables[]" value="Tax">
                                                            <label class="custom-control-label" for="customCheck14">Tax</label>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input selectedCheckBox" id="customCheck15" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tables[]" value="UserType">
                                                            <label class="custom-control-label" for="customCheck15">UserType</label>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="checkbox my-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input selectedCheckBox" id="customCheck16" data-parsley-multiple="groups" data-parsley-mincheck="2" name="tables[]" value="Currency">
                                                            <label class="custom-control-label" for="customCheck16">Currency</label>
                                                        </div>
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

    $(document).ready(function(){
        $('#customCheck101').on('click',function(){
            
            if(this.checked){
                $('.selectedCheckBox').each(function(){
                    this.checked = true;
                });
            }else{
                 $('.selectedCheckBox').each(function(){
                    this.checked = false;
                });
            }
        });
    });


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