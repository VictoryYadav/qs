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

            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post">
                                        <div class="row">
                                            <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-control-sm" name="from_date" required="" value="<?php echo date('d-M-Y', strtotime($from_date)); ?>" id="fromDt">
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-control-sm" name="to_date" required="" value="<?php echo date('d-M-Y', strtotime($to_date)); ?>" id="toDt">
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-4">
                                                <div class="form-group">
                                                    <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('search'); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        </form>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="billViewTbl">
                                                <thead>
                                                    <tr>
                                                        <th><?= $this->lang->line('billNo'); ?></th>
                                                        <th><?= $this->lang->line('billDate'); ?></th>
                                                        <th><?= $this->lang->line('mobile'); ?></th>
                                                        <th><?= $this->lang->line('billAmount'); ?></th>
                                                        <th><?= $this->lang->line('paidAmount'); ?></th>
                                                        <th><?= $this->lang->line('mode'); ?></th>
                                                        <th><?= $this->lang->line('paymentInstrument'); ?></th>
                                                        <th><?= $this->lang->line('action'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if(!empty($bills)){
                                                        foreach ($bills as $key) {
                                                     ?>
                                                    <tr>
                                                        <td>
                                                            <a href="<?php echo base_url('restaurant/bill/'.$key['BillId']); ?>" target="_blank"><?= convertToUnicodeNumber($key['BillNo']); ?>
                                                            </a>
                                                        </td>
                                                        <td><?= date('d-M-Y',strtotime($key['BillDate'])); ?></td>

                                                        <td><?= convertToUnicodeNumber($key['CellNo']); ?></td>   
                                                        <td><?= convertToUnicodeNumber($key['bPaidAmt']); ?></td>   
                                                        <td ><?= convertToUnicodeNumber($key['PaidAmt']); ?></td>
                                                        <td><?= $key['Company']; ?></td>
                                                        <td><?= $key['PymtType']; ?></td>
                                                        <td>
                                                            <a href="<?php echo base_url('restaurant/print/'.$key['BillId']); ?>" class='btn btn-warning btn-sm'>
                                                                <i class="fas fa-print"></i>
                                                            </a>
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

<script>
    $(document).ready(function () {
        $('#billViewTbl').DataTable();

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
