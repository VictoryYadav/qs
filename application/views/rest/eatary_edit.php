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
                                        <?php if($this->session->flashdata('success')): ?>
                                            <div class="alert alert-success" role="alert" id="alertBlock"><?= $this->session->flashdata('success') ?></div>
                                            <?php endif; ?>
                                        <div class="col-md-4">
                                            <select name="EID" id="EID" class="form-control form-control-sm select2 custom-select" onchange="getEataryData()">
                                                <option value="">
                                                    <?= $this->lang->line('select'); ?>
                                                </option>
                                                <?php
                                                    foreach ($eatary as $key) {
                                                 ?>
                                                 <option value="<?= $key['EID']; ?>">
                                                    <?= $key['Name']; ?>
                                                </option>
                                             <?php } ?>
                                            </select>
                                        </div>
                                            <br>
                                        <form method="post" id="eataryForm" style="display: none;">
                                            <input type="hidden" name="type" value="update">
                                            <div class="row" id="eataryRow">
                                                
                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('update'); ?>">
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
    $('#EID').select2();
});

getEataryData = () =>{
    $('#eataryForm').hide();

    var EID = $('#EID').val();
    var type = 'search';

    $.post('<?= base_url('restaurant/eatary') ?>',{EID:EID, type:type},function(res){
        if(res.status == 'success'){
          var data = res.response;
          var temp = `<input type="hidden" name="EID" value="${data.EID}">
                        <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('chainNo'); ?></label>
                            <input type="text" name="ChainId" class="form-control form-control-sm" required="" value="${data.ChainId}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('ONo'); ?></label>
                            <input type="text" name="ONo" class="form-control form-control-sm" required="" value="${data.ONo}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('stall'); ?></label>
                            <input type="text" name="Stall" class="form-control form-control-sm" required="" value="${data.Stall}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('name'); ?></label>
                            <input type="text" name="Name" class="form-control form-control-sm" required="" value="${data.Name}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('category'); ?></label>
                            <input type="text" name="CatgId" class="form-control form-control-sm" required="" value="${data.CatgID}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('country'); ?> <?= $this->lang->line('code'); ?></label>
                            <input type="text" name="CountryCd" class="form-control form-control-sm" required="" value="${data.CountryCd}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('city'); ?> <?= $this->lang->line('code'); ?></label>
                            <input type="text" name="CityCd" class="form-control form-control-sm" required="" value="${data.CityCd}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('address'); ?></label>
                            <input type="text" name="Addr" class="form-control form-control-sm" required="" value="${data.Addr}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('suburb'); ?></label>
                            <input type="text" name="Suburb" class="form-control form-control-sm" required="" value="${data.Suburb}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('EWNS'); ?></label>
                            <input type="text" name="EWNS" class="form-control form-control-sm" required="" value="${data.EWNS}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('city'); ?></label>
                            <input type="text" name="City" class="form-control form-control-sm" required="" value="${data.City}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('pincode'); ?></label>
                            <input type="text" name="Pincode" class="form-control form-control-sm" required="" value="${data.Pincode}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('tagline'); ?></label>
                            <input type="text" name="Tagline" class="form-control form-control-sm" required="" value="${data.Tagline}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('remarks'); ?></label>
                            <input type="text" name="Remarks" class="form-control form-control-sm" required="" value="${data.Remarks}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('GSTNo'); ?></label>
                            <input type="text" name="GSTNo" class="form-control form-control-sm" required="" value="${data.GSTNo}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('CINNo'); ?></label>
                            <input type="text" name="CINNo" class="form-control form-control-sm" required="" value="${data.CINNo}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('FSSAINo'); ?></label>
                            <input type="text" name="FSSAINo" class="form-control form-control-sm" required="" value="${data.FSSAINo}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('mobile'); ?></label>
                            <input type="tel" name="PhoneNos" class="form-control form-control-sm" required="" value="${data.PhoneNos}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('email'); ?></label>
                            <input type="email" name="Email" class="form-control form-control-sm" required="" value="${data.Email}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('Website'); ?></label>
                            <input type="text" name="Website" class="form-control form-control-sm" required="" value="${data.Website}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('ContactNos'); ?></label>
                            <input type="text" name="ContactNos" class="form-control form-control-sm" required="" value="${data.ContactNos}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('contactAddress'); ?></label>
                            <input type="text" name="ContactAddr" class="form-control form-control-sm" required="" value="${data.ContactAddr}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('billerName'); ?></label>
                            <input type="text" name="BillerName" class="form-control form-control-sm" required="" value="${data.BillerName}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('billerGSTNo'); ?></label>
                            <input type="text" name="BillerGSTNo" class="form-control form-control-sm" required="" value="${data.BillerGSTNo}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('BTyp'); ?></label>
                            <input type="text" name="BTyp" class="form-control form-control-sm" required="" value="${data.BTyp}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('VFM'); ?></label>
                            <input type="text" name="VFM" class="form-control form-control-sm" required="" value="${data.VFM}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('TaxInBill'); ?></label>
                            <input type="text" name="TaxInBill" class="form-control form-control-sm" required="" value="${data.TaxInBill}">
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label><?= $this->lang->line('QRLink'); ?></label>
                            <input type="text" name="QRLink" class="form-control form-control-sm" required="" value="${data.QRLink}">
                        </div>
                    </div>`;
        $('#eataryRow').html(temp);
        $('#eataryForm').show();
          // console.log(data);
        }else{
            $('#eataryForm').hide();
        }
    });
}


$('#eataryForm').on('submit', function(e){
    e.preventDefault();

    var data = $(this).serializeArray();
    $.post('<?= base_url('restaurant/edit_eatary') ?>',data,function(res){
            if(res.status == 'success'){
              alert(res.response);
              // location.reload();
            }else{
              alert(res.response);
            }
        });

});

</script>