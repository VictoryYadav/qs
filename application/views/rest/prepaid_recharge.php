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
                                        <form method="post" id="prepaidForm">
                                            <input type="hidden" name="type" value="pdata">
                                            <input type="hidden" id="acNo" name="acNo" value="0">
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('type'); ?></label>
                                                        <select class="form-control form-control-sm" name="Remarks" id="Remarks" required="" onchange="changeRemarks()">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="Added">Added</option>
                                                            <option value="Return">Return</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('mobile'); ?></label>
                                                        <select class="form-control form-control-sm select2 custom-select" name="CustId" id="CustId" required="" onchange="getAmt()">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php 
                                                            if(!empty($users)){
                                                                foreach ($users as $key) {
                                                            ?>
                                                            <option value="<?= $key['CustId']; ?>" prepaidamt="<?= $key['prePaidAmt']; ?>" ><?= $key['MobileNo'].' ('.$key['Fullname'].')'; ?></option>
                                                        <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 ">
                                                    <div class="form-group">
                                                        <label>Pre Paidamount</label>
                                                        <input type="number" class="form-control form-control-sm" name="prePaidAmt" placeholder="prePaidAmt" id="prePaidAmt" required="">
                                                    </div>
                                                </div>


                                            </div>

                                            <div>
                                                <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>" id="saveBtn">
                                                <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('update'); ?>" id="updateBtn" style="display: none;">
                                                
                                                <div class="text-success" id="msgText"></div>
                                            </div>
                                        </form>
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
        $('#CustId').select2();
    });

    $('#prepaidForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/prepaid_recharge') ?>',data,function(res){
            if(res.status == 'success'){
              alert(res.response);
            }else{
              alert(res.response);
            }
            location.reload();
        });

    });

    function getAmt(){
        var CustId = $(`#CustId`).val();
        var Remarks = $(`#Remarks`).val();
        $(`#prePaidAmt`).prop('readonly', false);
        if(Remarks.length > 0){
            if(CustId.length > 0){
                if(Remarks == 'Return'){
                    var prepaidamt = $('option:selected', $('#CustId')).attr('prepaidamt');
                    $(`#prePaidAmt`).val(prepaidamt);
                    $(`#prePaidAmt`).prop('readonly', true);
                }else{
                    $(`#prePaidAmt`).val(0);
                    $(`#prePaidAmt`).prop('readonly', false);
                }
            }else{
                alert('Please Select MobileNo');    
                $(`#prePaidAmt`).prop('readonly', false);
            }

        }else{
            alert('Please Select Type');
            $(`#prePaidAmt`).prop('readonly', false);
        }
    }

    function changeRemarks(){
        $(`#CustId`).val('').trigger('change');
        $(`#prePaidAmt`).val('');
    }

</script>