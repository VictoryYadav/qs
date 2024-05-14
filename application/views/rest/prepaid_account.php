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
                                        <form method="post" enctype="multipart/form-data" id="prepaid_csv_form">
                                            <input type="hidden" name="type" value="file_data">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('file'); ?> <?= $this->lang->line('upload'); ?></label>
                                                        <input type="file" name="prepaid_files" class="form-control form-control-sm" required="" accept=".csv" id="file" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label><br>
                                                        <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('upload'); ?>">
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
                                        <form method="post" id="prepaidForm">
                                            <input type="hidden" name="type" value="pdata">
                                            <input type="hidden" id="acNo" name="acNo" value="0">
                                            <div class="row">
                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label for=""><?= $this->lang->line('country'); ?></label>
                                                        <select name="countryCd" id="countryCd" class="form-control form-control-sm select2 custom-select" required="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php 
                                                    foreach ($country as $key) { ?>
                                                        <option value="<?= $key['phone_code']; ?>" ><?= $key['country_name']; ?></option>
                                                    <?php } ?>  
                                                    </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-5">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('mobile'); ?></label>
                                                        <input type="text" class="form-control form-control-sm" name="MobileNo" placeholder="Mobile" minlength="10" maxlength="10" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" id="MobileNo" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-4">
                                                    <div class="form-group">
                                                        <label>Max Limit</label>
                                                        <input type="number" class="form-control form-control-sm" name="MaxLimit" placeholder="MaxLimit" id="MaxLimit" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-4">
                                                    <div class="form-group">
                                                        <label>Pre Paidamount</label>
                                                        <input type="number" class="form-control form-control-sm" name="prePaidAmt" placeholder="prePaidAmt" id="prePaidAmt" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-4">
                                                    <div class="form-group">
                                                        <label>Cust Type</label>
                                                        <select name="custType" class="form-control form-control-sm" id="custType" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="1">OnAccount</option>
                                                            <option value="2">Prepaid</option>
                                                            <option value="3">Corporate</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-4">
                                                    <div class="form-group">
                                                        <label>Mode</label>
                                                        <select name="Stat" class="form-control form-control-sm" id="Stat" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="0"><?= $this->lang->line('active'); ?></option>
                                                            <option value="1"><?= $this->lang->line('inactive'); ?></option>
                                                        </select>
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
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="prepaidTbl" class="table table-bordered">
                                                <thead>
                                                <tr >
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('mobile'); ?></th>
                                                    <th>Max Limit</th>
                                                    <th>Prepaid</th>
                                                    <th><?= $this->lang->line('type'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($prepaids)){
                                                        $i = 1;
                                                        foreach ($prepaids as $key) {
                                                            if($key['custType'] == 1){
                                                                $custType = 'OnAccount';
                                                            }
                                                            if($key['custType'] == 2){
                                                                $custType = 'Prepaid';
                                                            }
                                                            if($key['custType'] == 3){
                                                                $custType = 'Corporate';
                                                            }
                                                         ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $key['MobileNo']; ?></td>
                                                        <td><?= $key['MaxLimit']; ?></td>
                                                        <td><?= $key['prePaidAmt']; ?></td>
                                                        <td><?= $custType; ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $key['acNo'] ?>, '<?= $key['MobileNo']; ?>', <?= $key['MaxLimit']; ?>,<?= $key['prePaidAmt']; ?>,<?= $key['custType']; ?>,<?= $key['Stat']; ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
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
        
        <?php $this->load->view('layouts/admin/script'); ?>


<script type="text/javascript">

    $(document).ready(function () {
        $('#prepaidTbl').DataTable();
        $('#countryCd').select2();
    });

    $('#prepaidForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/prepaid_account') ?>',data,function(res){
            if(res.status == 'success'){
              $('#msgText').html(res.response);
            }else{
              $('#msgText').html(res.response);
            }
            location.reload();
        });

    });

    function editData(acNo, MobileNo, MaxLimit, prePaidAmt, custType, stat){
        var mobile =  MobileNo.substr(MobileNo.length - 10);
        var country = MobileNo.slice(0, -10);

        $('#acNo').val(acNo);
        $('#countryCd').val(country).trigger('change');;
        $('#MobileNo').val(mobile);
        $('#MaxLimit').val(MaxLimit);
        $('#prePaidAmt').val(prePaidAmt);
        $('#custType').val(custType);
        $('#Stat').val(stat);   
        $('#prePaidAmt').attr('readonly', true);

        $('#saveBtn').hide();
        $('#updateBtn').show();
    }

    $('#prepaid_csv_form').on('submit', function(e){
        e.preventDefault();

        var formData = new FormData(document.getElementById("prepaid_csv_form"));
        callAjax(formData);
    });

    function callAjax(formData){
       $.ajax({
               url : '<?= base_url('restaurant/prepaid_account') ?>',
               type : 'POST',
               data : formData,
               processData: false,  
               contentType: false,  
               success : function(data) {
                   alert(data.response);
                   location.reload();
               }
        }); 
    }
</script>