<?php $this->load->view('layouts/admin/head'); ?>
<style>
    .topics tr {
     line-height: 8px !important; 
     font-size: 11px;
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
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post">
                                            <div class="row">
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('fromDate'); ?></label>
                                                        <input type="date" class="form-control form-control-sm" name="fdate" value="<?= $fdate; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('toDate'); ?></label>
                                                        <input type="date" class="form-control form-control-sm" name="tdate" value="<?= $tdate; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('mode'); ?></label>
                                                        <select name="pmode" id="" class="form-control form-control-sm">
                                                            <option value=""><?= $this->lang->line('all'); ?></option>
                                                            <?php
                                                            foreach ($modes as $mode ) { ?>
                                                                <option value="<?= $mode['PymtMode']; ?>" <?php if($mode['PymtMode'] == $pmode){ echo 'selected'; } ?> ><?= $mode['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <label for="">&nbsp;</label><br>
                                                    <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('search'); ?>">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="paymentTBL" class="table table-bordered topics">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('billId'); ?></th>
                                                    <th><?= $this->lang->line('billNo'); ?></th>
                                                    <th><?= $this->lang->line('paidAmount'); ?></th>
                                                    <th><?= $this->lang->line('orderReference'); ?></th>
                                                    <th><?= $this->lang->line('mode'); ?></th>
                                                    <th><?= $this->lang->line('paymentDate'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($details)){
                                                        $i=1;
                                                        foreach ($details as $key) {
                                                     ?>
                                                    
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $key['BillId']; ?></td>
                                                    <td><?= $key['BillPrefix'].' '.$key['BillNo'].''.$key['BillSuffix']; ?></td>
                                                    <td><?= $key['PaidAmt']; ?></td>
                                                    <td><?= $key['OrderRef']; ?></td>
                                                    <td><?= payMode($key['PaymtMode']); ?></td>
                                                    <td><?= date('d-M-Y', strtotime($key['PymtDate'])); ?></td>
                                                </tr>
                                                <?php }
                                                 }
                                                ?>
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
        $('#paymentTBL').DataTable();
    });

</script>