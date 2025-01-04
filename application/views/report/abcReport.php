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
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('cuisine'); ?></label>
                                                        <select name="cuisine" id="cuisine" class="form-control form-control-sm" onchange="getMenuCat()">
                                                            <option value=""><?= $this->lang->line('all'); ?></option>
                                                            <?php 
                                                            foreach ($cuisine as $key) {
                                                            ?>
                                                            <option value="<?= $key['CID']; ?>"><?= $key['Name']; ?></option>
                                                        <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('menuCategory'); ?></label>
                                                        <select name="menucat" id="menucat" class="form-control form-control-sm select2 custom-select" onchange="getItemList()">
                                                            <option value=""><?= $this->lang->line('all'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('item'); ?> <?= $this->lang->line('name'); ?></label>
                                                        <select name="itemId" id="itemId" class="form-control form-control-sm select2 custom-select" onchange="abcData()">
                                                            <option value=""><?= $this->lang->line('all'); ?></option>
                                                            <?php 
                                                            foreach ($menuItem as $key) {
                                                            ?>
                                                            <option value="<?= $key['ItemId']; ?>"><?= $key['Name']; ?></option>
                                                        <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php
                                                $fromDate = date('d-M-Y', strtotime("-7 day", strtotime(date('Y-m-d'))));
                                                ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('fromDate'); ?></label>
                                                        <input type="text" name="fromDate" id="fromDate" class="form-control form-control-sm" onchange="abcData()" value="<?= $fromDate; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('toDate'); ?></label>
                                                        <input type="text" name="toDate" id="toDate" class="form-control form-control-sm" onchange="abcData()" value="<?= date('d-M-Y'); ?>" />
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
                                                    <th><?= $this->lang->line('item'); ?></th>
                                                    <th><?= $this->lang->line('cuisine'); ?></th>
                                                    <th><?= $this->lang->line('menuCategory'); ?></th>
                                                    <th><?= $this->lang->line('item'); ?> <?= $this->lang->line('name'); ?></th>
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
    $('#menucat').select2();
    $('#itemId').select2();

    $("#fromDate").datepicker({  
        dateFormat: "dd-M-yy",
        defaultDate: new Date() 
    });

    $("#toDate").datepicker({  
        dateFormat: "dd-M-yy",
        defaultDate: new Date() 
    });

    abcData();
}); 

    getMenuCat = () =>{
        var CID = $('#cuisine').val();
        $.ajax({
            url: "<?php echo base_url('restaurant/item_list_get_category'); ?>",
            type: "post",
            data:{'CID': CID},
            success: function(data){
                data = JSON.parse(data);
                var b = '<option value = "">ALL</option>';
                for(i = 0;i<data.length;i++){
                    b = b+'<option value="'+data[i].MCatgId+'">'+data[i].MCatgNm+'</option>';
                }
                $('#menucat').html(b);
                abcData();
            }
        });
    }

    getItemList = () =>{
        var MCatgId = $('#menucat').val();
        $.post('<?= base_url('restaurant/get_itemList_by_mcatgId') ?>',{MCatgId:MCatgId},function(res){
            if(res.status == 'success'){
              var data = res.response;
              var temp = `<option value = "">ALL</option>`;
              data.forEach((item, index) => {
                temp += `<option value="${item.ItemId}" >${item.Name}</option>`;
              });
              $('#itemId').html(temp);
              abcData();
            }else{
              alert(res.response);
            }
        });
    }

    abcData = () => {
        var data = $('#reportForm').serializeArray();
        $.post('<?= base_url('restaurant/abc_report') ?>',data,function(res){
            if(res.status == 'success'){
              var data = res.response;
              var temp = ``;
              if(data.length > 0){
                data.forEach((item, index) => {
                        temp += `<tr>
                                    <td>${item.ItemId}</td>
                                    <td>${item.CuisineName}</td>
                                    <td>${item.menuCatName}</td>
                                    <td>${item.ItemName}</td>
                                    <td>${item.Quantity}</td>
                                    <td>${item.Amount}</td>
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