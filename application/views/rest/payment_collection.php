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
                                        <form method="post" id="filterForm">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="forn-group">
                                                        <label for=""><?= $this->lang->line('name'); ?></label>
                                                        <input type="text" name="fullname" class="form-control form-control-sm" id="fullname">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="forn-group">
                                                        <label for=""><?= $this->lang->line('mobile'); ?></label>
                                                        <input type="number" name="mobile" class="form-control form-control-sm" id="mobile">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="forn-group">
                                                        <label for="">&nbsp;</label><br>
                                                        <button type="button" class="btn btn-sm btn-success" onclick="searchData()"><?= $this->lang->line('search'); ?></button>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="item_lists" class="table table-bordered">
                                                <thead>
                                                <tr style="background: #cbc6c6;">
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('mobile'); ?></th>
                                                    <th><?= $this->lang->line('amount'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead >
            
                                                <tbody id="tblBody">
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


        <div class="modal fade bs-example-modal-center billingModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel"><?= $this->lang->line('bill'); ?> <?= $this->lang->line('details'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="detailTbl" class="table table-bordered">
                                    <thead>
                                    <tr style="background: #cbc6c6;">
                                        <th><?= $this->lang->line('bilNo'); ?></th>
                                        <th><?= $this->lang->line('mergeNo'); ?></th>
                                        <th>Bill Amt</th>
                                        <th>Bill Payable</th>
                                        <th><?= $this->lang->line('date'); ?></th>
                                    </tr>
                                    </thead >

                                    <tbody id="detailBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="col-md-12 mt-4">
                            <form method="post" id="collectionForm">
                                <input type="hidden" id="CustId" name="CustId">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span>Total Payable : <b><span id="outstanding">0</span></b></span>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control form-control-sm" name="amount" required="" placeholder="Bill Amount">
                                    </div>
                                    <div class="col-md-3">
                                        <select name="PaymtMode" id="PaymtMode" class="form-control form-control-sm" required="">
                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($pymtModes as $key) { ?>
                                                <option value="<?= $key['PymtMode']; ?>"><?= $key['Name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('pay'); ?>">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
        
        <?php $this->load->view('layouts/admin/script'); ?>


<script type="text/javascript">
    $(document).ready(function () {
        $('#item_lists').DataTable(
                        {"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
                        });
        $('#detailTbl').DataTable(
                        {"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
                        });
    });

    var mobile = '';
    var fullname = '';

    getData(mobile, fullname);

    function searchData(){
        mobile = $('#mobile').val();
        fullname = $('#fullname').val();
        getData(mobile, fullname);
    }

    function getData(mobile, fullname){
        
        $.post('<?= base_url('restaurant/payment_collection_settled_bill') ?>',{mobile:mobile, fullname:fullname},function(res){   
            if(res.status == 'success'){
                var temp = ``;
                var counter = 0;
                res.response.forEach((item, index) => {
                    counter++;
                    temp += `<tr>
                                <td>${counter}</td>
                                <td>${item.Fullname}</td>
                                <td>${item.CellNo}</td>
                                <td>${item.TotalAmt}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="getDetail(${item.CustId})">Detail</button>
                                </td>
                            <tr>`;
                });
               $('#tblBody').html(temp);
            }else{
              alert(res.response);
            }
        });
    }

    getDetail = (CustId) =>{
        $.post('<?= base_url('restaurant/getBillByCustId') ?>',{CustId:CustId},function(res){  
            if(res.status == 'success'){
                var temp = ``;
                outstanding = 0;
                res.response.forEach((item, index) => {
                    outstanding = parseFloat(outstanding) + parseFloat(item.totalBillPaidAmt);
                    temp += `<tr>
                                <td>${item.BillId}</td>
                                <td>${item.MergeNo}</td>
                                <td>${item.PaidAmt}</td>
                                <td>${item.totalBillPaidAmt}</td>
                                <td>${item.billTime}</td>
                            <tr>`;
                });
                $('#outstanding').html(outstanding);
                $('#CustId').val(CustId);
                $('#detailBody').html(temp);
                $('.billingModal').modal('show');
            }else{
                alert(res.response);
            }
        });
    }

    $('#collectionForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        
        if(data[1].value > 0){
            $.post('<?= base_url('restaurant/save_group_payment') ?>', data ,function(res){
                if(res.status == 'success'){
                    alert(res.response);
                    location.reload();
                }else{
                    alert(res.response);
                }
            });
        }else{
            alert('Please fill up required field!');
        }
    });
</script>
