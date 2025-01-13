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
                                        <form method="post">
                                            <div class="row">
                                            
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('fromDate'); ?></label>
                                                        <input type="text" name="fromDate" id="fromDate" class="form-control form-control-sm" required  value="<?= $fromDate; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('toDate'); ?></label>
                                                        <input type="text" name="toDate" id="toDate" class="form-control form-control-sm" required value="<?= $toDate; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">&nbsp;</label><br>
                                                        <input type="submit" class="btn btn-sm btn-success" />
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="abcTBL" class="table table-bordered ">
                                                <thead>
                                                    <tr>
                                                        <th><?= $this->lang->line('date'); ?></th>
                                                        <th><?= $this->lang->line('day'); ?></th>
                                                        <th><?= $this->lang->line('invoices'); ?></th>
                                                        <?php if($this->session->userdata('ServChrg') > 0){ ?>
                                                        <th><?= $this->lang->line('serviceCharge'); ?></th>
                                                        <?php } ?> 
                                                        <th><?= $this->lang->line('discount'); ?></th>
                                                        <?php if($this->session->userdata('Tips') > 0){ ?>
                                                        <th><?= $this->lang->line('tips'); ?></th>
                                                        <?php } ?> 
                                                        <th><?= $this->lang->line('amount'); ?></th>
                                                    </tr>
                                                </thead>
            
                                                <tbody id="incomeBody">
                                                    <?php 
                                                    if(!empty($report)){
                                                        $tservice   = 0;
                                                        $ttip       = 0;
                                                        $tdiscount  = 0;
                                                        $tamount    = 0;
                                                        foreach ($report as $key) {
                                                            $tservice  = $tservice + $key['serviceCharge'];
                                                            $ttip      = $ttip + $key['tips'];
                                                            $tdiscount = $tdiscount + $key['totalDiscount'];
                                                            $tamount   = $tamount + $key['totalAmount'];
                                                         ?>
                                                            <tr>
                                                                <td><?= $key['Date']; ?></td>
                                                                <td><?= $key['dayName']; ?></td>
                                                                <td><?= $key['totalInvoice']; ?></td>
                                                                <?php if($this->session->userdata('ServChrg') > 0){ ?>
                                                                <td><?= $key['serviceCharge']; ?></td>
                                                                <?php } ?>
                                                                <td><?= $key['totalDiscount']; ?></td>
                                                                <?php if($this->session->userdata('Tips') > 0){ ?>
                                                                <td><?= $key['tips']; ?></td>
                                                                <?php } ?>
                                                                <td><?= $key['totalAmount']; ?></td>
                                                             </tr>
                                                    <?php } ?>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <?php if($this->session->userdata('ServChrg') > 0){ ?>
                                                                <td><b><?= $tservice; ?></b></td>
                                                                <?php } ?>
                                                                <td><b><?= $tdiscount; ?></b></td>
                                                                <?php if($this->session->userdata('Tips') > 0){ ?>
                                                                <td><b><?= $ttip; ?></b></td>
                                                                <?php } ?>
                                                                <td><b><?= $tamount; ?></b></td>
                                                             </tr>
                                                    <?php }  ?>
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

    $('#abcTBL').DataTable({
        destroy: true, // Allows reinitialization
        order: [[0, "desc"]],
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        dom: 'lBfrtip',
    });

    $("#fromDate").datepicker({  
        dateFormat: "dd-M-yy",
        defaultDate: new Date() 
    });

    $("#toDate").datepicker({  
        dateFormat: "dd-M-yy",
        defaultDate: new Date() 
    });

}); 


</script>