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
                                        
                                        <div class="table-responsive">
                                            <table id="stock_report_table" class="table table-bordered">
                                                <thead>
                                                <!-- <th>Sl no</th> -->
                                                <th>Item Cd</th>
                                                <th>Item Name</th>
                                                <th>Category</th>
                                                <!-- <th>Selled</th>
                                                <th>Recieved</th> -->
                                                <th>Diff (RCVD - ISSUED)</th>
                                                </thead>
            
                                                <tbody>
                                                  <?php 
                                                  if(!empty($report)){
                                                    $i=1;
                                                    foreach($report as $key){
                                                  ?>
                                                  <tr>
                                                    <!-- <td><?= $i++; ?></td> -->
                                                    <td><?= $key['RMCd']?></td>
                                                    <td><?= $key['RMName']?></td>
                                                    <td>
                                                        <?= $key['RMCatgName']?>
                                                    </td>
                                                    <!-- <td>
                                                        <?= $key['sell']?>
                                                    </td>
                                                    <td><?= $key['rcvd']?></td> -->
                                                    <td><span onclick="report_form(<?= $key['RMCd']?>)" style="color: blue;cursor: pointer;"><?= $key['rcvd'] - $key['sell']?></span></td>
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

        <!-- model -->

        <div class="modal" id="report_form">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="decline-title">Select Date</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <form method="post" action="<?php echo base_url('restorent/itemstockreport'); ?>">
                            <input type="hidden" name="RMCd" id="RMCdReport">
                            <div class="form-group">
                                <label>From Date</label>
                                <input class="form-control" type="date" name="from_date" id="from_date" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="form-group">
                                <label>To Date</label>
                                <input class="form-control" type="date" name="to_date" id="to_date" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="text-center"><button class="btn btn-primary btn-sm" type="submit">Submit</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <?php $this->load->view('layouts/admin/script'); ?>


<script type="text/javascript">
    $(document).ready(function () {
        $('#stock_report_table').DataTable();
    });

    function report_form(id){
        $('#RMCdReport').val(id);
        $('#report_form').modal('show');
    }
    

</script>
