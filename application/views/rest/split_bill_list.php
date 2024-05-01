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
                                            <table class="table table-bordered" id="billViewTbl">
                                                <thead>
                                                    <tr>
                                                        <th><?= $this->lang->line('bilNo'); ?></th>
                                                        <th><?= $this->lang->line('billDate'); ?></th>
                                                        <th><?= $this->lang->line('billAmount'); ?></th>
                                                        <th><?= $this->lang->line('mobile'); ?></th>
                                                        <th><?= $this->lang->line('action'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if(!empty($bills)){
                                                        foreach ($bills as $key) {
                                                     ?>
                                                    <tr>
                                                        <td>
                                                            <a href="<?php echo base_url('restaurant/bill/'.$key['BillId']); ?>" target="_blank" class='tippy-btn' title="Billing" data-tippy-placement="top"><?= convertToUnicodeNumber($key['BillNo']); ?>
                                                            </a>
                                                        </td>
                                                        <td><?= date('d-M-Y',strtotime($key['billTime'])); ?></td>

                                                        <td><?= convertToUnicodeNumber($key['PaidAmt']); ?></td>   
                                                        <td ><?= convertToUnicodeNumber($key['CellNo']); ?></td>
                                                        <td>
                                                            <a href="<?php echo base_url('restaurant/kot_print/'.$key['CNo'].'/'.$key['MergeNo'].'/'.$key['FKOTNo'].'/'.$key['KOTNo']); ?>" class='btn btn-primary btn-sm tippy-btn' title="KOT Print" data-tippy-placement="top">
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                            <a href="<?php echo base_url('restaurant/print/'.$key['BillId']); ?>" class='btn btn-warning btn-sm tippy-btn' title=" Print" data-tippy-placement="top">
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                            <button class="btn btn-sm btn-info tippy-btn" title="Cash Collect" data-tippy-placement="top" id="btnCash" onclick="cashCollect(<?= $key['BillId']; ?>,<?= $key['OType']; ?>,<?= $key['TableNo']; ?>,<?= $key['MergeNo']; ?>,'<?= $key['CellNo']; ?>',<?= $key['PaidAmt']; ?>,<?= $key['CNo']; ?>,<?= $key['EID']; ?>)"><i class="fas fa-money-check"></i>
                                                </button>
                                                <?php if($this->session->userdata('AutoSettle') == 0){ ?>

                                                <button class="btn btn-sm btn-success tippy-btn" title="Bill Settle" data-tippy-placement="top" onclick="setPaidAmount(<?= $key['BillId']; ?>,<?= $key['CNo']; ?>,<?= $key['MergeNo']; ?>,<?= $key['CustId']; ?>,<?= $key['BillNo']; ?>,<?= $key['PaidAmt']; ?>)">
                                            <i class="fas fa-check-double"></i>
                                        </button>
                                            <?php } ?>
                                                        </td>
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

        <!-- cach collect -->
    <div class="modal" id="cashCollectModel">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">
                    <h6><?= $this->lang->line('cashCollect'); ?></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 500px;overflow: auto;">
                    <form method="post" id="cashForm">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?= $this->lang->line('mode'); ?></th>
                                    <th><?= $this->lang->line('amount'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cashBody">
                                <tr>
                                    <td>
                                        <select name="" id="" class="form-control form-control-sm">
                                        <?php
                                        foreach ($payModes as $key) {
                                         ?>
                                         <option value="<?= $key['Name']; ?>"><?= $key['Name']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </td>
                                    <td id="cashBodyTd">
                                    </td>
                                    <td>
                                        <button type="button" onclick="cashCollectData()" class="btn btn-sm btn-success">
                                        <i class="fas fa-save"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
        
        <?php $this->load->view('layouts/admin/script'); ?>


<script>
    $(document).ready(function () {
        $('#billViewTbl').DataTable();
    });

    function cashCollect(billId, oType, tableNo, mergeNo, cellNo, paidAmt, MCNo,EID){
        var tbl = `<input type="hidden" name="BillId" value="${billId}"/><input type="hidden" name="oType" value="${oType}"/><input type="hidden" name="TableNo" value="${tableNo}"/><input type="hidden" name="MCNo" value="${MCNo}"/><input type="hidden" name="EID" value="${EID}"/><input type="hidden" name="MergeNo" value="${mergeNo}"/><input type="hidden" name="CellNo" value="${cellNo}"/><input type="hidden" name="TotBillAmt" value="${paidAmt}"/><input type="text" name="PaidAmt" value="${convertToUnicodeNo(paidAmt)}" required class="form-control form-control-sm" onblur="changeValue(this)" />`;

        
        $('#cashBodyTd').html(tbl);

        $('#cashCollectModel').modal('show');
    }

    function cashCollectData(){
        var data = $('#cashForm').serializeArray();
      
        var TotBillAmt = data[7].value;
        var PaidAmt = data[8].value;

        PaidAmt = convertDigitToEnglish(PaidAmt);
        data[8].value = PaidAmt;

      // console.log(PaidAmt+' , '+TotBillAmt);
      if(parseFloat(PaidAmt) >= parseFloat(TotBillAmt)){
        $.post('<?= base_url('restaurant/collect_payment') ?>',data,function(res){
            if(res.status == 'success'){
              alert(res.response);
            }else{
              alert(res.response);
            }
            location.reload();
        });
      }else{
        alert('Amount has to be greater than or equal to Bill Amount.');
      }
    }

    // settle payments
    function setPaidAmount(billId , CNo , MergeNo , CustId, billNo, billAmt) {

        $.post('<?= base_url('restaurant/bill_settle') ?>',{billId:billId,CNo:CNo,MergeNo:MergeNo,CustId:CustId,billNo:billNo,billAmt:billAmt},function(response){

            if(response.status == 'success') {
                    alert("Successfully Settled");
            }else {
                alert("Not Settled");
            }
            location.reload();
        });
    }

</script>
