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
                                                $fromDate = date('Y-m-d', strtotime("-7 day", strtotime(date('Y-m-d'))));
                                                ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('fromDate'); ?></label>
                                                        <input type="date" name="fromDate" id="fromDate" class="form-control form-control-sm" onchange="saleReport()" value="<?= $fromDate; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('toDate'); ?></label>
                                                        <input type="date" name="toDate" id="toDate" class="form-control form-control-sm" onchange="saleReport()" value="<?= date('Y-m-d'); ?>" />
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Order By</label>
                                                        <select name="orderBy" id="orderBy" class="form-control form-control-sm" onchange="saleReport()">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="qty">Quantity</option>
                                                            <option value="value">Value</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">List</label>
                                                        <select name="Listing" id="Listing" class="form-control form-control-sm" onchange="saleReport()">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="top">Top 10</option>
                                                            <option value="bottom">Bottom 10</option>
                                                        </select>
                                                    </div>
                                                </div> -->

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Mode</label>
                                                        <select name="modes" id="modes" class="form-control form-control-sm" onchange="saleReport()">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="full_menu">Full Menu</option>
                                                            <option value="traded_goods"> Traded Goods</option>
                                                        </select>
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
                                                    <!-- <th>#</th> -->
                                                    <th><?= $this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('quantity'); ?></th>
                                                    <th><?= $this->lang->line('amount'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody id="abcBody">
                                                    
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
        saleReport();
    }); 

    saleReport = () => {
        var data = $('#reportForm').serializeArray();
        $.post('<?= base_url('restaurant/item_sales_report') ?>',data,function(res){
            if(res.status == 'success'){
              var data = res.response;
              var temp = ``;
              if(data.length > 0){
                data.forEach((item, index) => {
                        temp += `<tr>
                                    <td>${item.menuItem}</td>
                                    <td>${item.Qty}</td>
                                    <td>${item.itemValue}</td>
                                 </tr>`;
                    });
              }else{
                // temp += `Data Not Found!!`;
              }
              $('#abcBody').html(temp);

              if ( $.fn.dataTable.isDataTable( '#abcTBL' ) ) {
                        table = $('#abcTBL').DataTable();
                    }
                    else {

                        $('#abcTBL').DataTable( {
                                    layout: {
                                        topStart: {
                                            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                                        }
                                    }
                                },{"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
                                    }
                                );

                        // table = $('#tableData').DataTable(    {"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
                        //             } );
                    }

              

            }else{
              alert(res.response);
            }
        });
    }

</script>