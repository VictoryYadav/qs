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
                                        <form method="post">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">From Date</label>
                                                        <input type="date" class="form-control form-control-sm" name="fdate" value="<?= $fdate; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">To Date</label>
                                                        <input type="date" class="form-control form-control-sm" name="tdate" value="<?= $tdate; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">PMode</label>
                                                        <select name="pmode" id="" class="form-control form-control-sm">
                                                            <option value="">Choose</option>
                                                            <?php
                                                            foreach ($modes as $mode ) { ?>
                                                                <option value="<?= $mode['PymtMode']; ?>" <?php if($mode['PymtMode'] == $pmode){ echo 'selected'; } ?> ><?= $mode['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="">&nbsp;</label><br>
                                                    <input type="submit" class="btn btn-sm btn-success" value="Search">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="paymentTBL" class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Bill ID</th>
                                                    <th>Paid Amt</th>
                                                    <th>Ord Ref</th>
                                                    <th>PMod</th>
                                                    <th>PymtDate</th>
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