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
                                                        <input type="text" class="form-control form-control-sm" name="fdate" value="<?= date('d-M-Y', strtotime($fdate)); ?>" id="fromDt">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('toDate'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="tdate" value="<?= date('d-M-Y', strtotime($tdate)); ?>" id="toDt">
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
                                            <?php 
                                            if(!empty($data)){
                                                // Prepare the structure
                                                $transformedData = [];
                                                $paymentTypes = []; // To keep track of all dynamic payment types

                                                foreach ($data as $row) {
                                                    $date = date('Y-m-d', strtotime($row['PymtDate'])); // Extract date only
                                                    $paymentName = $row['paymentName'];
                                                    $amount = $row['Amount'];
                                                    
                                                    if (!isset($transformedData[$date])) {
                                                        $transformedData[$date] = [];
                                                    }
                                                    
                                                    // Ensure this payment type is tracked
                                                    if (!in_array($paymentName, $paymentTypes)) {
                                                        $paymentTypes[] = $paymentName;
                                                    }
                                                    
                                                    // Assign amount to the payment name for the specific date
                                                    $transformedData[$date][$paymentName] = $amount;
                                                }

                                                // Ensure all dates have all payment types (fill missing with 0)
                                                foreach ($transformedData as $date => &$payments) {
                                                    foreach ($paymentTypes as $type) {
                                                        if (!isset($payments[$type])) {
                                                            $payments[$type] = 0;
                                                        }
                                                    }
                                                }
                                                unset($payments); // Break reference

                                    // Display the table
                                    echo "<table id='paymentTBL' class='table table-bordered topics'>";
                                    echo "<thead><tr><th>PymtDate</th>";

                                    // Add dynamic headers
                                    foreach ($paymentTypes as $type) {
                                        echo "<th>$type</th>";
                                    }
                                    echo "</tr></thead><tbody>";

                                    foreach ($transformedData as $date => $payments) {
                                        echo "<tr>";
                                        echo "<td>$date</td>";
                                        foreach ($paymentTypes as $type) {
                                            echo "<td>{$payments[$type]}</td>";
                                        }
                                        echo "</tr>";
                                    }
                                    echo "</tbody></table>";
                                            }
                                            ?>
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