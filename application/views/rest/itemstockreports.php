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
                                        <div class="text-center"><p>Item Name: <?= $storeName.' - '.$itemName; ?>, Opening Stock: <?= $op_stock;?> </p>
                                            <p>Date : <?= $fromDate .' to '. $toDate; ?></p></div>
                                        <div class="table-responsive">
                                            <table id="stock_report_table" class="table table-bordered">
                                                <thead>
                                                    <th><?= $this->lang->line('date'); ?></th>
                                                    <th><?= $this->lang->line('transactionType'); ?></th>
                                                    <th><?= $this->lang->line('uom'); ?></th>
                                                    <th><?= $this->lang->line('received'); ?></th>
                                                    <th><?= $this->lang->line('issued'); ?></th>
                                                    <th><?= $this->lang->line('available'); ?></th>
                                                </thead>
            
                                                <tbody>
                                                  <?php 
                                                  if(!empty($report)){
                                                    $i=1;
                                                    $count = $op_stock;
                                                    foreach($report as $key){
                                                        $iss = $rcv = '-';
                                                        if($key['TTyp'] == 1){
                                                            $iss = $key['issued'];
                                                            $count -= $key['issued'];
                                                        }else if($key['TTyp'] == 2){
                                                            $rcv = $key['rcvd'];
                                                            $count += $key['rcvd'];
                                                        }

                                                  ?>
                                                  <tr>
                                                        <td><?= date('d-M-Y',strtotime($key['TransDt']))?></td>
                                                        <td><?= getTransType($key['TransType']); ?></td>
                                                        <td><?= $key['UOM']?></td>
                                                        <td><?= $rcv?></td>
                                                        <td><?= $iss?></td>
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
