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
                                        <div class=""><h3>Item Name: <?php echo !empty($report['RMName'])?$report['RMName']:''; ?>, Opening Stock: <?= $op_stock;?></h3></div>
                                        <div class="table-responsive">
                                            <table id="stock_report_table" class="table table-bordered">
                                                <thead>
                                                    <!-- <th>Sl no</th> -->
                                                    <!-- <th>Item Name</th> -->
                                                    <th>Date</th>
                                                    <th>Trans Type</th>
                                                    <th>UOM</th>
                                                    <th>Issued</th>
                                                    <th>Rcvd</th>
                                                    <th></th>
                                                </thead>
            
                                                <tbody>
                                                  <?php 
                                                  if(!empty($report)){
                                                    $i=1;
                                                    $count = $op_stock;
                                                    foreach($report as $key){
                                                        $iss = $rcv = '-';
                                                        if($key['TransType'] < 10){
                                                            $iss = $key['Qty'];
                                                            $count -= $key['Qty'];
                                                        }else{
                                                            $rcv = $key['Qty'];
                                                            $count += $key['Qty'];
                                                        }

                                                        if($key['Qty'] < 0){
                                                            $iss = -1*$key['Qty'];
                                                            $rcv = '-';
                                                        }
                                                  ?>
                                                  <tr>
                                                        <!-- <td><?= $key['RMName']?></td> -->
                                                        <td><?= $key['TransDt']?></td>
                                                        <!-- <td><?= $n?></td> -->
                                                        <td><?= getTransType($key['TransType']); ?></td>
                                                        <td><?= $key['UOM']?></td>
                                                        
                                                        <td>
                                                            <?= $iss?>
                                                        </td>
                                                        <td>
                                                            <?= $rcv?>
                                                        </td>
                                                        <th><?= $count?></th>
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
        $('#stock_report_table').DataTable();
    });

</script>
