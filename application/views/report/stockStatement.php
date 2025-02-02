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
                                        <!-- id="reportForm" -->
                                        <form  method="post">
                                            <input type="hidden" name="MstTyp" id="MstTyp" value="<?= $MstTyp; ?>">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('store'); ?></label>
                                                        
                                                        <select name="MCd" id="MCd" class="form-control form-control-sm" onchange="stockData()">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                            if(!empty($stores)){
                                                                foreach ($stores as $key) { ?>
                                                            ?>
                                                            <option value="<?= $key['MCd']; ?>" msttype="<?= $key['MstTyp']; ?>" <?php if($key['MCd'] == $s_MCd){ echo 'selected'; } ?> ><?= $key['Name']; ?></option>
                                                        <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <!-- onchange="stockData()"  -->
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('date'); ?></label>
                                                        <input type="text" name="TransDt" id="TransDt" class="form-control form-control-sm" value="<?= $sTransDt; ?>"/>
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
                                            <table id="taxTBL" class="table table-bordered ">
                                                <thead id="incomeHeader">
                                                    <tr>
                                                        <th><?= $this->lang->line('item'); ?></th>
                                                        <th><?= $this->lang->line('uom'); ?></th>
                                                        <th><?= $this->lang->line('opening'); ?></th>
                                                        <th><?= $this->lang->line('received'); ?></th>
                                                        <th><?= $this->lang->line('issued'); ?></th>
                                                        <th><?= $this->lang->line('consumed'); ?></th>
                                                        <th><?= $this->lang->line('current'); ?></th>
                                                    </tr>
                                                </thead>
            
                                                <tbody id="incomeBody">
                                                    <?php
                                                    if(!empty($report)){
                                                        foreach ($report as $key) { ?>
                                                        <tr>
                                                            <td><?= $key['ItemNm']; ?></td>
                                                            <td><?= $key['uom_name']; ?></td>
                                                            <td><?= $key['opening']; ?></td>
                                                            <td><?= $key['received']; ?></td>
                                                            <td><?= $key['issued']; ?></td>
                                                            <td><?= $key['consumed']; ?></td>
                                                            <td><?= $key['closed']; ?></td>
                                                         </tr>
                                                <?php } }?>
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
    // stockData();
    $('#taxTBL').DataTable({
            destroy: true, // Allows reinitialization
            order: [[0, "desc"]],
            lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
            dom: 'lBfrtip',
        });

    $("#TransDt").datepicker({  
        dateFormat: "dd-M-yy",
        defaultDate: new Date() 
    });
}); 

    stockData = () => {
        var MCd = $(`#MCd`).val();
        var TransDt = $(`#TransDt`).val();
        var MstTyp = $('option:selected', $('#MCd')).attr('msttype');
        $(`#MstTyp`).val(MstTyp);

        return false;

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
              $('#taxTBL').DataTable();

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