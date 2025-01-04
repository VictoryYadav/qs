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
            
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" id="filterForm" action="<?= base_url('restaurant/detailstockreport') ?>">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for=""><?= $this->lang->line('fromDate'); ?></label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control form-control-sm" value="<?= date('d-M-Y');?>" name="from_date" required id="fromDt">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for=""><?= $this->lang->line('toDate'); ?></label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control form-control-sm" value="<?= date('d-M-Y');?>" name="to_date" required id="toDt">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for=""><?= $this->lang->line('store'); ?></label>
                                                    <div class="form-group">
                                                        <select class="form-control form-control-sm" name="MCd" required>
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                            foreach ($stores as $key) { ?>
                                                                <option value="<?= $key['MCd']; ?>"><?= $key['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for=""><?= $this->lang->line('item'); ?></label>
                                                    <div class="form-group">
                                                        <select class="form-control form-control-sm select2 custom-select" id="rmcd" name="RMCd" required>
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                            if(!empty($items)){
                                                            foreach ($items as $key) { ?>
                                                                <option value="<?= $key['RMCd']; ?>"><?= $key['RMName']; ?></option>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('search'); ?>">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
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
        $('#rmcd').select2();
        $('#stock_report_table').DataTable();

        $("#fromDt").datepicker({  
            dateFormat: "dd-M-yy",
            defaultDate: new Date() 
        });

        $("#toDt").datepicker({  
            dateFormat: "dd-M-yy",
            defaultDate: new Date() 
        });
    });

</script>
