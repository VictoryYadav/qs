<?php $this->load->view('layouts/admin/head');

$CheckOTP = $this->session->userdata('DeliveryOTP');
$EID = authuser()->EID;
$EType = $this->session->userdata('EType');
$RestName = authuser()->RestName;
 ?>
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
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18"><?php echo $title; ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-right mt-2">
                                            <a target="_blank" href="<?php echo base_url('restaurant/stock_consumption'); ?>" class="btn btn-info btn-sm">Consumption</a>
                                            <a target="_blank" href="<?php echo base_url('restaurant/stock_report'); ?>" class="btn btn-warning btn-sm">Report</a>
                                            <a href="<?php echo base_url('restaurant/add_stock'); ?>" class="btn btn-primary btn-sm">Add Stock</a>
                                        </div>
                                        <form method="post" action="<?php echo base_url('restaurant/stock_list'); ?>">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label>Transaction ID</label>
                                                    <input type="number" name="trans_id" id="trans_id" class="form-control" value="<?php if($trans_id){echo $trans_id;}?>" />
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Transaction Type</label>
                                                    <select class="form-control" name="trans_type">
                                                        <option value="">Transaction Type</option>
                                                        <?php foreach($trans_type as $key => $value){?>
                                                            <option value="<?= $key?>" <?php if($key == $trans_type_id){echo 'checked';}?>><?= $value?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>From Date</label>
                                                    <input type="date" name="from_date" class="form-control" value="<?php if($from_date){echo $from_date;}?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>To Date</label>
                                                    <input type="date" name="to_date" class="form-control" value="<?php if($to_date){echo $to_date;}?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>&nbsp;</label><br>
                                                    <input type="submit" class="btn btn-success" value="Filter">
                                                </div>
                                                <div class="col-md-2"></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        
                                        <div class="table-responsive">
                                            <table id="stock_list_table" class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Trans Num</th>
                                                    <th>Transaction Type</th>
                                                    <th>From</th>
                                                    <th>To</th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                  <?php 
                                                  if(!empty($stock)){
                                                    $i=1;
                                                    foreach($stock as $key){
                                                  ?>
                                                  <tr onclick="edit(<?= $key['TransId']?>)">
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $key['TransId']?></td>
                                                   <td><?= getTransType($key['TransType']) ?></td>
                                                    <td>
                                                        <?php 
                                                            if($key['FromEID']){
                                                                echo $key['FromEID'];
                                                            }elseif($key['FromSupp']){
                                                                echo $key['FromSupp'];
                                                            }elseif($key['FromKit']) {
                                                                echo $key['FromKit'];
                                                            }elseif($key['FromMain']) {
                                                                echo $key['FromMain'];
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            if($key['ToEID']){
                                                                echo $key['ToEID'];
                                                            }elseif($key['ToSupp']){
                                                                echo $key['ToSupp'];
                                                            }elseif($key['ToKit']) {
                                                                echo $key['ToKit'];
                                                            }elseif($key['ToMain']) {
                                                                echo $key['ToMain'];
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>

                                                  <?php } } ?>
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
        $('#stock_list_table').DataTable();
    });


</script>

<script type="text/javascript">
    function edit(id){
        window.location.href="<?php echo base_url();?>restaurant/edit_stock?TransId="+id;
    }
</script>