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
                                        <form id="reportForm">
                                            <div class="row">
                                                <?php
                                                $yesterday = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d'))));
                                                ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('store'); ?></label>
                                                        <select name="MCd" id="MCd" class="form-control form-control-sm" onchange="stockData()">
                                                            <!-- <option value=""><?= $this->lang->line('select'); ?></option> -->
                                                            <?php 
                                                            if(!empty($stores)){
                                                                foreach ($stores as $key) { ?>
                                                            ?>
                                                            <option value="<?= $key['MCd']; ?>" msttype="<?= $key['MstTyp']; ?>"><?= $key['Name']; ?></option>
                                                        <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('toDate'); ?></label>
                                                        <input type="date" name="TransDt" id="TransDt" class="form-control form-control-sm" onchange="stockData()" value="<?= date('Y-m-d'); ?>"/>
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
                                            <table id="taxTBL" class="table table-bordered ">
                                                <thead id="incomeHeader">
                                                    <tr>
                                                        <th><?= $this->lang->line('item'); ?></th>
                                                        <th><?= $this->lang->line('opening'); ?></th>
                                                        <th><?= $this->lang->line('received'); ?></th>
                                                        <th><?= $this->lang->line('issued'); ?></th>
                                                        <th><?= $this->lang->line('consumed'); ?></th>
                                                        <th><?= $this->lang->line('current'); ?></th>
                                                    </tr>
                                                </thead>
            
                                                <tbody id="incomeBody"></tbody>
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
<!-- data for bttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.0/css/buttons.dataTables.css">

<!-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script> -->
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.print.min.js"></script>
<!-- end of data for bttons -->

<script type="text/javascript">
$(document).ready(function () {
    stockData();
}); 

    stockData = () => {
        var MCd = $(`#MCd`).val();
        var TransDt = $(`#TransDt`).val();
        var MstTyp = $('option:selected', $('#MCd')).attr('msttype');

        $.post('<?= base_url('restaurant/stock_statement') ?>',{MCd:MCd, MstTyp:MstTyp, TransDt:TransDt},function(res){
            if(res.status == 'success'){
              var report = res.response;
              
              var temp = ``;
              if(report.length > 0){
                for(var i=0; i<report.length; i++) {
                        temp += `<tr>
                                    <td>${report[i].ItemNm}</td>
                                    <td>${report[i].opening}</td>
                                    <td>${report[i].received}</td>
                                    <td>${report[i].issued}</td>
                                    <td>${report[i].consumed}</td>
                                    <td>${report[i].closed}</td>
                                 </tr>`;
                    };
              }else{
                // temp += `Data Not Found!!`;
              }
              
              $('#incomeBody').html(temp);

              if ( $.fn.dataTable.isDataTable( '#taxTBL' ) ) {
                        table = $('#taxTBL').DataTable();
                    }
                    else {

                        $('#taxTBL').DataTable(
                            {
                                lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                                  dom: 'lBfrtip',
                              }
                            );

                    }

            }else{
              alert(res.response);
            }
        });
    }

</script>