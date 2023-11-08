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

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" action="<?php echo base_url('restaurant/stock_list'); ?>">
                                            <div class="row">
                                                <div class="col-md-2 col-6">
                                                    <label>Trans No</label>
                                                    <input type="number" name="trans_id" id="trans_id" class="form-control form-control-sm" value="<?php if($trans_id){echo $trans_id;}?>" />
                                                </div>
                                                <div class="col-md-2 col-6">
                                                    <label>Transaction Type</label>
                                                    <select class="form-control form-control-sm" name="trans_type">
                                                        <option value="">Transaction Type</option>
                                                        <?php foreach($trans_type as $key => $value){?>
                                                            <option value="<?= $key?>" <?php if($key == $trans_type_id){echo 'checked';}?>><?= $value?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 col-6">
                                                    <label>From Date</label>
                                                    <input type="date" name="from_date" class="form-control form-control-sm" value="<?php if($from_date){echo $from_date;}?>">
                                                </div>
                                                <div class="col-md-2 col-6">
                                                    <label>To Date</label>
                                                    <input type="date" name="to_date" class="form-control form-control-sm" value="<?php if($to_date){echo $to_date;}?>">
                                                </div>
                                                <div class="col-md-1 col-2">
                                                    <label>&nbsp;</label><br>
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </div>
                                                <div class="col-md-3 col-10">
                                                    <div class="d-none d-sm-block">
                                                    <label>&nbsp;</label><br>
                                                    <a target="_blank" href="<?php echo base_url('restaurant/stock_consumption'); ?>" class="btn btn-info btn-sm">Consumption</a>
                                                    <a target="_blank" href="<?php echo base_url('restaurant/stock_report'); ?>" class="btn btn-warning btn-sm">Report</a>
                                                    <a href="<?php echo base_url('restaurant/add_stock'); ?>" class="btn btn-success btn-sm">Add Stock</a>
                                                </div>
                                                    <div class="d-sm-block d-md-none text-right">
                                                        <label>&nbsp;</label><br>
                                                    <a target="_blank" href="<?php echo base_url('restaurant/stock_consumption'); ?>" class="btn btn-info btn-sm">Consumption</a>
                                                    <a target="_blank" href="<?php echo base_url('restaurant/stock_report'); ?>" class="btn btn-warning btn-sm">Report</a>
                                                    <a href="<?php echo base_url('restaurant/add_stock'); ?>" class="btn btn-primary btn-sm">Add Stock</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        
                                        <div class="table-responsive">
                                            <table id="stock_list_table" class="table table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Trans No</th>
                                                    <th>Trans Date</th>
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
                                                  <tr onclick="edit(<?= $key['TransId']?>)" >
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $key['TransId']?></td>
                                                    <td><?= date('d-M-Y',strtotime($key['TransDt']));?></td>
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