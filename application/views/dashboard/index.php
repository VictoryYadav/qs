<?php $this->load->view('layouts/admin/head'); ?>
<style>
    .graph-div{
        margin-top: 4px;
    }
</style>

    
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
                            <div class="col-md-6">
                                <select name="changeFilter" id="changeFilter" class="form-control-sm" onchange="changeFilter()">
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="half_yearly">Half-Yearly</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
                                <input type="hidden" id="lunch" value="<?= $this->lang->line('lunch'); ?>">
                                <input type="hidden" id="dinner" value="<?= $this->lang->line('dinner'); ?>">
                                <input type="hidden" id="customerFootfalls" value="<?= $this->lang->line('customerFootfallsForLunchDinner'); ?>">
                                <input type="hidden" id="customerOrder" value="<?= $this->lang->line('customerOrderValuesForLunchDinner'); ?>">
                                <input type="hidden" id="paymentModes" value="<?= $this->lang->line('paymentModesUsedByCustomer'); ?>">

                                <input type="hidden" id="revenueAndDiscounts" value="<?= $this->lang->line('revenueAndDiscounts'); ?>">
                                <input type="hidden" id="ordersByHour" value="<?= $this->lang->line('ordersByHour'); ?>">
                                <input type="hidden" id="billsAndRatings" value="<?= $this->lang->line('billsAndRatings'); ?>">

                                <input type="hidden" id="offers" value="<?= $this->lang->line('offers'); ?>">
                                <input type="hidden" id="takeAway" value="<?= $this->lang->line('takeAway'); ?>">

                                    <nav class="navbar navbar-expand-lg navbar-light">
                                        <div class="col-md-12 create_graph_button_div">
                                            <button type="button" class="btn btn-warning btn-sm" href="#" id="customer"><?= $this->lang->line('customer'); ?></button>

                                            <button type="button" class="btn btn-secondary btn-sm" href="#" id="food"><?= $this->lang->line('food'); ?></button>

                                            <button type="button" class="btn btn-danger btn-sm" href="#" id="restaurant"><?= $this->lang->line('restaurant'); ?></button>
                                        </div>
                                    </nav>
                                </div>

                                <div class="col-md-12">
                                    <div id="customer-parent-div">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 graph-div">
                                            <div class="card">
                                                <div class="card-body">
                                                    <canvas id="cust01" width="400" height="300"></canvas>
                                                    <div class="text-center">
                                                        <button class='btn btn-sm btn-success' onclick="showModal('footfalls')">Report</button>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 graph-div">
                                                <div class="card">
                                                <div class="card-body">
                                                    <canvas id="cust02" width="400" height="300"></canvas>
                                                    <div class="text-center">
                                                        <button class='btn btn-sm btn-success' onclick="showModal('orderValue')">Report</button>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 graph-div">
                                                <div class="card">
                                                <div class="card-body">
                                                    <canvas id="cust03" width="400" height="300"></canvas>
                                                    <div class="text-center">
                                                        <button class='btn btn-sm btn-success' onclick="showModal('paymentMode')">Report</button>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="food-parent-div" style="display:none">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 graph-div">
                                                <div class="card">
                                                <div class="card-body">
                                                    <canvas id="food01" width="400" height="300"></canvas>
                                                    <div class="text-center">
                                                        <button class='btn btn-sm btn-success' onclick="showModal('revenueDiscount')">Report</button>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 graph-div">
                                                <div class="card">
                                                <div class="card-body">
                                                    <canvas id="food02" width="400" height="300"></canvas>
                                                    <div class="text-center">
                                                        <button class='btn btn-sm btn-success' onclick="showModal('orderByHour')">Report</button>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 graph-div">
                                                <div class="card">
                                                <div class="card-body">
                                                    <canvas id="food03" width="400" height="300"></canvas>
                                                    <div class="text-center">
                                                        <button class='btn btn-sm btn-success' onclick="showModal('billsAndRating')">Report</button>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="restaurant-parent-div" style="display:none">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-6 graph-div">
                                                <div class="card">
                                                <div class="card-body">
                                                    <canvas id="rest01" width="400" height="300"></canvas>
                                                    <div class="text-center">
                                                        <button class='btn btn-sm btn-success' onclick="showModal('takeAway')">Report</button>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-6 graph-div">
                                                <div class="card">
                                                <div class="card-body">
                                                    <canvas id="rest02" width="400" height="300"></canvas>
                                                    <div class="text-center">
                                                        <button class='btn btn-sm btn-success' onclick="showModal('offers')">Report</button>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6 col-md-6 col-6 graph-div">
                                                <div class="card">
                                                <div class="card-body">
                                                    <div id="rest04" width="400" height="300"></div>
                                                    <div class="text-center">
                                                        <button class='btn btn-sm btn-success' onclick="showModal('tableWiseOccupencyLunch')">Report</button>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6 col-md-6 col-6 graph-div">
                                                <div class="card">
                                                <div class="card-body">
                                                    <div id="rest07" width="400" height="300"></div>
                                                    <div class="text-center">
                                                        <button class='btn btn-sm btn-success' onclick="showModal('KitchenOrders')">Report</button>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-12 col-md-12 col-12 graph-div">
                                                <div class="card">
                                                <div class="card-body">
                                                    <div id="rest09" width="400" height="300"></div>
                                                    <div class="text-center">
                                                        <button class='btn btn-sm btn-success' onclick="showModal('Ratings')">Report</button>
                                                    </div>
                                                </div>
                                                </div>
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

        <!-- offers modal -->
    <div class="modal fade bs-example-modal-center reportModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="reportForm" action="<?= base_url('DashboardController/csv_report') ?>">
                        <input type="hidden" id="reportType" name="reportType">                        
                        <div class="form-group">
                            <label for=""><?= $this->lang->line('fromDate'); ?></label>
                            <input type="date" name="from" class="form-control form-control-sm" required="" value="<?= date('Y-m-d'); ?>">
                        </div>

                        <div class="form-group">
                            <label for=""><?= $this->lang->line('toDate'); ?></label>
                            <input type="date" name="to" class="form-control form-control-sm" required="" value="<?= date('Y-m-d'); ?>">
                        </div>

                        <input type="submit" class="btn btn-sm btn-success" value="Submit">
                    </form>

                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger" role="alert"><?= $this->session->flashdata('error') ?></div>
                    <?php endif; ?>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
        
        <?php $this->load->view('layouts/admin/script'); ?>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});

    cust_cdn();
    food_cdn();
    rest_cdn();

    changeFilter = () => {
        var filter = $('#changeFilter').val();
        cust_cdn();
        food_cdn();
        rest_cdn();
    }

    function cust_cdn(){
        var url = '<?= base_url() ?>assets_admin/js/customer_dashboard.js';

        loadCDN(url, function() {
            console.log('s');
            });
    }

    function food_cdn(){
        var url = '<?= base_url() ?>assets_admin/js/food_dashboard.js';

        loadCDN(url, function() {
            console.log('s');
            });
    }

    function rest_cdn(){
        var url = '<?= base_url() ?>assets_admin/js/rest_dashboard.js';

        loadCDN(url, function() {
            console.log('s');
            });
    }
     // load cdn
    function loadCDN(url, callback) {
        var script = document.createElement('script');
        script.src = url
        script.onload = callback;
        document.head.appendChild(script);
    }

    $('document').ready(function() {
  
});

    showModal = (type) => {
        $('#exampleModalLabel').html(type);
        $('#reportType').val(type);

        $('.reportModal').modal('show');
    }

    // $('#reportForm').on('submit', function(e){
    //     e.preventDefault();
    //     var data = $(this).serializeArray();
    //     $.post('<?= base_url('DashboardController/csv_report') ?>',data,function(res){
    //         if(res.status == 'success'){
    //           alert(res.response);
    //         }else{
    //           alert(res.response);
    //         }
    //     });
    // });

    // end load data
</script>

<!-- <script src="<?= base_url() ?>assets_admin/js/customer_dashboard.js"></script> -->
<!-- <script src="<?= base_url() ?>assets_admin/js/food_dashboard.js"></script> -->
<!-- <script src="<?= base_url() ?>assets_admin/js/rest_dashboard.js"></script> -->