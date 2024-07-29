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

var g_city = 0;

getEataryData = () =>{
    $('#eataryForm').hide();

    var EID = $('#EID').val();
    var type = 'search';

    $.post('<?= base_url('restaurant/eatary') ?>',{EID:EID, type:type},function(res){
        if(res.status == 'success'){
          var data = res.response.eatary;
          var category = res.response.category;
          var country = res.response.country;

          g_city = data.CityCd;

          var catg = "<select name='CatgId' class='form-control form-control-sm' required=''><option value= ><?= $this->lang->line('select'); ?></option>";
            category.forEach((item, index) => {
                var slct = '';
                if(item.CatgID == data.CatgID){
                    slct = 'selected';
                }
                catg += "<option value="+item.CatgID+" "+slct+">"+item.CatgNm+"</option>";
            });
            catg += "</select>";

            var ccd = "<select name='CountryCd' id='CountryCd' class='form-control form-control-sm' required='' onchange='getCity()'><option value= ><?= $this->lang->line('select'); ?></option>";
            country.forEach((item, index) => {
                var slct = '';
                if(item.phone_code == data.CountryCd){
                    slct = 'selected';
                }

                ccd += "<option value="+item.phone_code+" "+slct+">"+item.country_name+"</option>";
            });
            ccd += "</select>";

          var temp = '<input type="hidden" name="EID" value="'+data.EID+'">\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('category'); ?></label>\
                            '+catg+'\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('etype'); ?></label>\
                            <select name="EType" id="EType" class="form-control form-control-sm" required="" >\
                            <option value=""><?= $this->lang->line('select'); ?></option>\
                            <option value="1"><?= $this->lang->line('qsr'); ?></option>\
                            <option value="5"><?= $this->lang->line('sitIn'); ?></option>\
                            </select>\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label>E<?= $this->lang->line('category'); ?></label>\
                            <select name="ECatg" id="ECatg" class="form-control form-control-sm" required="" >\
                            <option value=""><?= $this->lang->line('select'); ?></option>\
                            <option value="1"><?= $this->lang->line('fastFood'); ?></option>\
                            <option value="2"><?= $this->lang->line('fineDine'); ?></option>\
                            <option value="3"><?= $this->lang->line('disco'); ?></option>\
                            <option value="4"><?= $this->lang->line('pub'); ?></option>\
                            <option value="5"><?= $this->lang->line('cafe'); ?></option>\
                            <option value="6"><?= $this->lang->line('loungeBar'); ?></option>\
                            <option value="7"><?= $this->lang->line('canteen'); ?></option>\
                            <option value="8"><?= $this->lang->line('sitInRestaurant'); ?></option>\
                            </select>\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('country'); ?> <?= $this->lang->line('code'); ?></label>\
                            '+ccd+'\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('city'); ?> <?= $this->lang->line('code'); ?></label>\
                            <select name="CityCd" id="CityCd" class="form-control form-control-sm" required="" >\
                            <option value=""><?= $this->lang->line('select'); ?></option>\
                            </select>\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('address'); ?></label>\
                            <input type="text" name="Addr" class="form-control form-control-sm" required="" value="'+data.Addr+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('suburb'); ?></label>\
                            <input type="text" name="Suburb" class="form-control form-control-sm" required="" value="'+data.Suburb+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('EWNS'); ?></label>\
                            <select name="EWNS" class="form-control form-control-sm" required="" value="'+data.EWNS+'" id="EWNS">\
                            <option value=""><?= $this->lang->line('select'); ?></option>\
                            <option value="E"><?= $this->lang->line('east'); ?></option>\
                            <option value="W"><?= $this->lang->line('west'); ?></option>\
                            <option value="N"><?= $this->lang->line('north'); ?></option>\
                            <option value="S"><?= $this->lang->line('south'); ?></option>\
                            </select>\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('city'); ?></label>\
                            <input type="text" name="City" class="form-control form-control-sm" required="" value="'+data.City+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('pincode'); ?></label>\
                            <input type="text" name="Pincode" class="form-control form-control-sm" required="" value="'+data.Pincode+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('tagline'); ?></label>\
                            <input type="text" name="Tagline" class="form-control form-control-sm" required="" value="'+data.Tagline+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('remarks'); ?></label>\
                            <input type="text" name="Remarks" class="form-control form-control-sm" required="" value="'+data.Remarks+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('GSTNo'); ?></label>\
                            <input type="text" name="GSTNo" class="form-control form-control-sm" required="" value="'+data.GSTNo+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('CINNo'); ?></label>\
                            <input type="text" name="CINNo" class="form-control form-control-sm" required="" value="'+data.CINNo+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('FSSAINo'); ?></label>\
                            <input type="text" name="FSSAINo" class="form-control form-control-sm" required="" value="'+data.FSSAINo+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('mobile'); ?></label>\
                            <input type="tel" name="PhoneNos" class="form-control form-control-sm" required="" value="'+data.PhoneNos+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('email'); ?></label>\
                            <input type="email" name="Email" class="form-control form-control-sm" required="" value="'+data.Email+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('Website'); ?></label>\
                            <input type="text" name="Website" class="form-control form-control-sm" required="" value="'+data.Website+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('BTyp'); ?></label>\
                            <select name="BTyp" id="BTyp" class="form-control form-control-sm" required="" onchange="changeBiller()">\
                            <option value=""><?= $this->lang->line('select'); ?></option>\
                            <option value="0"><?= $this->lang->line('outlet'); ?></option>\
                            <option value="1"><?= $this->lang->line('biller'); ?></option>\
                            </select>\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('billerName'); ?></label>\
                            <input type="text" name="BillerName" id="BillerName" class="form-control form-control-sm" required="" value="'+data.BillerName+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('billerGSTNo'); ?></label>\
                            <input type="text" name="BillerGSTNo" id="BillerGSTNo" class="form-control form-control-sm" required="" value="'+data.BillerGSTNo+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('ContactNos'); ?></label>\
                            <input type="text" name="ContactNos" id="ContactNos" class="form-control form-control-sm" required="" value="'+data.ContactNos+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('contactAddress'); ?></label>\
                            <input type="text" name="ContactAddr" id="ContactAddr" class="form-control form-control-sm" required="" value="'+data.ContactAddr+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('VFM'); ?></label>\
                            <input type="text" name="VFM" class="form-control form-control-sm" required="" value="'+data.VFM+'">\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('TaxInBill'); ?></label>\
                            <select name="TaxInBill" id="TaxInBill" class="form-control form-control-sm" required="">\
                            <option value=""><?= $this->lang->line('select'); ?></option>\
                            <option value="0"><?= $this->lang->line('included'); ?></option>\
                            <option value="1"><?= $this->lang->line('displayed'); ?></option>\
                            </select>\
                        </div>\
                    </div>\
                    <div class="col-md-3 col-6">\
                        <div class="form-group">\
                            <label><?= $this->lang->line('QRLink'); ?></label>\
                            <input type="text" name="QRLink" class="form-control form-control-sm" required="" value="'+data.QRLink+'">\
                        </div>\
                    </div>';
        $('#eataryRow').html(temp);
        $('#EWNS').val(data.EWNS);
        $('#EType').val(data.EType);
        $('#ECatg').val(data.ECatg);
        $('#BTyp').val(data.BTyp);
        $('#TaxInBill').val(data.TaxInBill);
        $('#eataryForm').show();
        
        getCity();
        changeBiller();
          // console.log(data);
        }else{
            $('#eataryForm').hide();
        }
    });
}

function getCity(){
    var phone_code = $('#CountryCd').val();
    $.post('<?= base_url('restaurant/get_city_by_country') ?>',{phone_code:phone_code},function(res){
        if(res.status == 'success'){
          var temp = `<option value=""><?= $this->lang->line('select'); ?></option>`;
            res.response.forEach((item, index) => {
                var slct = ``;
                if(g_city > 0){
                    if(item.city_id == g_city){
                        slct = `selected`;
                    }
                }
                temp += `<option value="${item.city_id}" ${slct}>${item.city_name}</option>`;
            });
            $(`#CityCd`).html(temp);
        }else{
          alert(res.response);
        }
    });
}

function changeBiller(){
    var btyp = $('#BTyp').val();

    $('BillerName').attr('required', false);
    $('BillerGSTNo').attr('required', false);
    $('ContactNos').attr('required', false);
    $('ContactAddr').attr('required', false);

    if(btyp == 1){
        $('BillerName').attr('required', true);
        $('BillerGSTNo').attr('required', true);
        $('ContactNos').attr('required', true);
        $('ContactAddr').attr('required', true);
    }
}


$('#eataryForm').on('submit', function(e){
    e.preventDefault();

    var data = $(this).serializeArray();
    $.post('<?= base_url('restaurant/eatary') ?>',data,function(res){
        if(res.status == 'success'){
          alert(res.response);
        }else{
          alert(res.response);
        }
          location.reload();
    });

});

</script>