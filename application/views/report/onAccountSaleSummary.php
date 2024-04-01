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
                                        <div class="table-responsive">
                                            <table id="abcTBL" class="table table-bordered ">
                                                <thead>
                                                <tr>
                                                    <!-- <th>#</th> -->
                                                    <th><?= $this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('mobileNo'); ?></th>
                                                    <th>Bill Amount</th>
                                                    <th>Outstanding</th>
                                                    <th>Billed To</th>
                                                    <th>Detail</th>
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
        $('#menucat').select2();
        $('#itemId').select2();
        onaccountsData();
    }); 

    onaccountsData = () => {
        
        $.post('<?= base_url('restaurant/onaccount_sale_summary') ?>',function(res){
            if(res.status == 'success'){
              var data = res.response;
              var temp = ``;
              if(data.length > 0){
                data.forEach((item, index) => {
                        temp += `<tr>
                                    <td>${item.Fullname}</td>
                                    <td>${item.CellNo}</td>
                                    <td>${item.PaidAmt}</td>
                                    <td>${item.totalBillPaidAmt}</td>
                                    <td>${item.billTo}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></button>
                                    </td>
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