<?php $this->load->view('layouts/admin/head'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
</script>

<script src="<?= base_url() ?>assets_admin/js/customer_dashboard.js"></script>
<script src="<?= base_url() ?>assets_admin/js/food_dashboard.js"></script>
<script src="<?= base_url() ?>assets_admin/js/rest_dashboard.js"></script>
    
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
                                        <div class="container">
                                            <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
                                            <!-- header -->

                                            <nav class="navbar navbar-expand-lg navbar-light">

                                                <div class="col-md-12 create_graph_button_div">

                                                    <button type="button" class="btn btn-warning btn-sm" href="#" id="customer">Customer</button>

                                                    <button type="button" class="btn btn-success btn-sm" href="#" id="food">Food</button>

                                                    <button type="button" class="btn btn-danger btn-sm" href="#" id="restaurant">Restaurant</button>

                                                </div>

                                            </nav>

                                            <!-- /header -->

                                            <!-- body -->

                                            <div id="customer-parent-div">

                                                <div class="row">

                                                    <div class="col-lg-6 col-md-6 graph-div" data-toggle="modal" data-target="#cust01_popup"

                                                        style="cursor: pointer;">

                                                        <canvas id="cust01" width="400" height="300" style="background: white; padding: 25px;"></canvas>

                                                    </div>



                                                    <div class="col-lg-6 col-md-6 graph-div" data-toggle="modal" data-target="#cust02_popup"

                                                        style="cursor: pointer;">

                                                        <canvas id="cust02" width="400" height="300" style="background: white; padding: 25px;"></canvas>

                                                    </div>



                                                    <div class="col-lg-6 col-md-6 graph-div" data-toggle="modal" data-target="#cust03_popup"

                                                        style="cursor: pointer;">

                                                        <canvas id="cust03" width="400" height="300" style="background: white; padding: 25px;"></canvas>

                                                    </div>

                                                </div>

                                            </div>



                                            <div id="food-parent-div" style="display:none">

                                                <div class="row">

                                                    <div class="col-lg-6 col-md-6 graph-div" data-toggle="modal" data-target="#food01_popup"

                                                        style="cursor: pointer;">

                                                        <canvas id="food01" width="400" height="300" style="background: white; padding: 25px;"></canvas>

                                                    </div>



                                                    <div class="col-lg-6 col-md-6 graph-div" data-toggle="modal" data-target="#food02_popup"

                                                        style="cursor: pointer;">

                                                        <canvas id="food02" width="400" height="300" style="background: white; padding: 25px;"></canvas>

                                                    </div>



                                                    <div class="col-lg-6 col-md-6 graph-div" data-toggle="modal" data-target="#food03_popup"

                                                        style="cursor: pointer;">

                                                        <canvas id="food03" width="400" height="300" style="background: white; padding: 25px;"></canvas>

                                                    </div>

                                                </div>

                                            </div>



                                            <div id="restaurant-parent-div" style="display:none">

                                                <div class="row">

                                                    <div class="col-lg-6 col-md-6 graph-div" data-toggle="modal" data-target="#rest01_popup"

                                                        style="cursor: pointer;">

                                                        <canvas id="rest01" width="400" height="300" style="background: white; padding: 25px;"></canvas>

                                                    </div>



                                                    <div class="col-lg-6 col-md-6 graph-div" data-toggle="modal" data-target="#rest02_popup"

                                                        style="cursor: pointer;">

                                                        <canvas id="rest02" width="400" height="300" style="background: white; padding: 25px;"></canvas>

                                                    </div>
                                                    <div class="col-lg-6 col-md-6 graph-div" data-toggle="modal" data-target="#rest03_popup"

                                                        style="cursor: pointer;" >
                                                        <div id="rest03" width="400" height="300" style="background: white; padding: 25px;"></div>
                                                        

                                                    </div>
                                                    <div class="col-lg-6 col-md-6 graph-div" data-toggle="modal" data-target="#rest04_popup"

                                                        style="cursor: pointer;" >
                                                        <div id="rest04" width="400" height="300" style="background: white; padding: 25px;"></div>
                                                        

                                                    </div>
                                                    <div class="col-lg-6 col-md-6 graph-div" data-toggle="modal" data-target="#rest05_popup"

                                                        style="cursor: pointer;" >
                                                        <div id="rest05" width="400" height="300" style="background: white; padding: 25px;"></div>
                                                        

                                                    </div>
                                                    <div class="col-lg-6 col-md-6 graph-div" data-toggle="modal" data-target="#rest06_popup"

                                                        style="cursor: pointer;" >
                                                        <div id="rest06" width="400" height="300" style="background: white; padding: 25px;"></div>
                                                        

                                                    </div>
                                                    <div class="col-lg-6 col-md-6 graph-div" data-toggle="modal" data-target="#rest07_popup"

                                                        style="cursor: pointer;" >
                                                        <div id="rest07" width="400" height="300" style="background: white; padding: 25px;"></div>
                                                        

                                                    </div>
                                                    <div class="col-lg-6 col-md-6 graph-div" data-toggle="modal" data-target="#rest08_popup"

                                                        style="cursor: pointer;" >
                                                        <div id="rest08" width="400" height="300" style="background: white; padding: 25px;"></div>
                                                        

                                                    </div>
                                                    <div class="col-lg-6 col-md-6 graph-div" data-toggle="modal" data-target="#rest09_popup"

                                                        style="cursor: pointer;" >
                                                        <div id="rest09" width="400" height="300" style="background: white; padding: 25px;"></div>
                                                        

                                                    </div>



                                                    <!-- <div class="col-lg-6 col-md-6 graph-div">

                                                        <canvas id="rest03" width="400" height="300" style="background: white; padding: 25px;"></canvas>

                                                    </div> -->

                                                </div>

                                            </div>

                                            <!-- /body -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                                                                                             
                        </div><!--end row-->

                        
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


</script>