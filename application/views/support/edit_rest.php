<?php $this->load->view('layouts/support/head'); ?>
        <?php $this->load->view('layouts/support/top'); ?>
            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <?php $this->load->view('layouts/support/sidebar'); ?>
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

                        <div class="row showBlock" >
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div id="btnBlock" style="display: none;">
                                            <div>
                                                <p class="text-danger">
                                                    Please login through the link and upload your restaurant data.
                                                </p>
                                                <div class="">
                                                    <p id="signinurl" class="text-success"></p>
                                                    <!-- <a href="" id="loginUrl">Login</a> -->
                                                </div>
                                            </div>
                                        </div>
                                    <form method="post" id="customerForm">
                                        <input type="hidden" name="EID" value="<?= $EID; ?>">
                                        <div class="row">

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('restaurant'); ?></label>
                                                    <input type="text" name="Name" id="Name" class="form-control form-control-sm" value="<?= $details['Name']; ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label>Person Name</label>
                                                    <input type="text" name="ContactPerson" id="ContactPerson" class="form-control form-control-sm" value="<?= $details['ContactPerson']; ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('country'); ?></label>
                                                    <select  name="CountryCd" id="CountryCd" class="form-control form-control-sm select2 custom-select" required="" onchange="getCity()">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php 
                                                        foreach ($country as $key) { ?>
                                                            <option value="<?= $key['phone_code']; ?>" <?php if($details['CountryCd'] == $key['phone_code']){ echo 'selected'; } ?> ><?= $key['country_name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('mobile'); ?></label>
                                                    <input type="text" name="CellNo" id="CellNo" class="form-control form-control-sm" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" value="<?= $details['CellNo']; ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('email'); ?></label>
                                                    <input type="email" name="Email" id="Email" class="form-control form-control-sm"  value="<?= $details['Email']; ?>" readonly>
                                                </div>
                                            </div>
                                            <?php
                                            $dateP = date('Y-m-d', strtotime("-20 years", strtotime(date('Y-m-d'))));
                                             ?>
                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('dob'); ?></label>
                                                    <input type="date" name="DOB" id="DOB" class="form-control form-control-sm"  value="<?= $details['DOB']; ?>" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('category'); ?></label>
                                                    <select name="CatgId" id="CatgId" class="form-control form-control-sm" required="">
                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                <?php foreach ($Category as $key) { ?> 
                                                    <option value="<?= $key['CatgID']; ?>" <?php if($details['CatgId'] == $key['CatgID']){ echo 'selected'; } ?> ><?= $key['CatgNm']; ?></option>
                                                <?php } ?>
                                                        </select>

                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('etype'); ?></label>
                                                    <select name="EType" id="" class="form-control form-control-sm" required="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <option value="1" <?php if($details['EType'] == 1){ echo 'selected'; } ?> ><?= $this->lang->line('qsr'); ?></option>
                                                        <option value="5" <?php if($details['EType'] == 5){ echo 'selected'; } ?> ><?= $this->lang->line('sitIn'); ?></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label>E<?= $this->lang->line('category'); ?></label>
                                                    <select name="ECatg" id="ECatg" class="form-control form-control-sm" required="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php foreach ($ECategory as $key) { ?> 
                                                        <option value="<?= $key['CatgID']; ?>" <?php if($details['ECatg'] == $key['CatgID']){ echo 'selected'; } ?> ><?= $key['CatgNm']; ?></option>
                                                    <?php } ?>
                                                        </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('chainNo'); ?></label>
                                                    <input type="number" name="ChainId" id="ChainId" class="form-control form-control-sm" value="<?= $details['ChainId']; ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('GSTNo'); ?></label>
                                                    <input type="text" name="GSTNo" id="GSTNo" class="form-control form-control-sm" value="<?= $details['GSTNo']; ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('area'); ?></label>
                                                    <input type="text" name="Area" id="Area" class="form-control form-control-sm" value="<?= $details['Area']; ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('suburb'); ?></label>
                                                    <input type="text" name="Suburb" id="Suburb" class="form-control form-control-sm" value="<?= $details['Suburb']; ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('location'); ?></label>
                                                    <select name="EWNS" id="EWNS" class="form-control form-control-sm" required="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <option value="E" <?php if($details['EWNS'] == 'E'){ echo 'selected'; } ?> ><?= $this->lang->line('east'); ?></option>
                                                        <option value="W" <?php if($details['EWNS'] == 'W'){ echo 'selected'; } ?> ><?= $this->lang->line('west'); ?></option>
                                                        <option value="N" <?php if($details['EWNS'] == 'N'){ echo 'selected'; } ?> ><?= $this->lang->line('north'); ?></option>
                                                        <option value="S" <?php if($details['EWNS'] == 'S'){ echo 'selected'; } ?> ><?= $this->lang->line('south'); ?></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('city'); ?></label>
                                                    <select name="City" id="City" class="form-control form-control-sm" required="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('pincode'); ?></label>
                                                    <input type="number" name="PIN" id="PIN" class="form-control form-control-sm" value="<?= $details['PIN']; ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('state'); ?></label>
                                                    <input type="text" name="State" id="State" class="form-control form-control-sm" value="<?= $details['State']; ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('address'); ?></label>
                                                    <input type="text" name="HOAddress" id="HOAddress" class="form-control form-control-sm" value="<?= $details['HOAddress']; ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('fromTime'); ?></label>
                                                    <input type="time" name="StTime" id="StTime" class="form-control form-control-sm" value="<?= date('H:i:s', strtotime($details['EndTime'])) ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('toTime'); ?></label>
                                                    <input type="time" name="EndTime" id="EndTime" class="form-control form-control-sm" value="<?= date('H:i:s', strtotime($details['EndTime'])) ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('latitude'); ?></label>
                                                    <input type="text" name="Lat" id="Lat" class="form-control form-control-sm" value="<?= $details['Lat']; ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('longitude'); ?></label>
                                                    <input type="text" name="Lng" id="Lng" class="form-control form-control-sm" value="<?= $details['Lng']; ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('language'); ?></label>
                                                    <select name="langId" id="langId" class="form-control form-control-sm" required="">
                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                <?php foreach ($languages as $key) { ?> 
                                                    <option value="<?= $key['id']; ?>" <?php if($key['id'] == $details['langId']){ echo 'selected'; } ?> ><?= $key['LangName']; ?></option>
                                                <?php } ?>
                                                    </select>

                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('alternate'); ?> <?= $this->lang->line('language'); ?></label>
                                                    <select name="altLangId" id="altLangId" class="form-control form-control-sm" required="">
                                                    <option value=""><?= $this->lang->line('select'); ?></option>
                                                <?php foreach ($languages as $key) { ?> 
                                                    <option value="<?= $key['id']; ?>" <?php if($key['id'] == $details['altLangId']){ echo 'selected'; } ?>><?= $key['LangName']; ?></option>
                                                <?php } ?>
                                                        </select>

                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="text-right">
                                            <input type="submit" class="btn btn-sm btn-success" value="Update">
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <?php $this->load->view('layouts/support/footer'); ?>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <?php $this->load->view('layouts/support/color'); ?>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        
        <?php $this->load->view('layouts/support/script'); ?>
<!-- loader -->
<div class="container text-center" id="loadBlock" style="display: none;">
    <img src="<?= base_url('assets/images/loader.gif'); ?>" alt="Eat Out">
</div>

<script type="text/javascript">

    $(document).ready(function () {
        $('#restTBL').DataTable();
        $('#CountryCd').select2();
    });

    $('#customerForm').on('submit', function(e){
        e.preventDefault();
        var EID = "<?php echo $EID; ?>";

        var data = $(this).serializeArray();
        $.post('<?= base_url('support/edit/') ?>'+EID, data, function(res){
            if(res.status == 'success'){
              alert(res.response);
              location.reload();
            }else{
              alert(res.response);
            }
        });
    });

    getCity();
    function getCity(){

        var CountryCd = $('#CountryCd').val();
        var cti = "<?= $details['city_id']; ?>";
        if(CountryCd > 0){
            $.post('<?= base_url('support/get_city_by_country') ?>',{phone_code:CountryCd},function(res){
                if(res.status == 'success'){
                  var cities = res.response;
                  $('#City').empty();
                  var temp = `<option value=""><?= $this->lang->line('select');?></option>`;
                  cities.forEach((ct) => {
                    slt = ``;
                    if(ct.city_id == cti){
                        slt = `selected`;
                    }
                      temp += `<option value="${ct.city_id}" ${slt}>${ct.city_name}</option>`;
                  });
                  $('#City').html(temp);     
                }else{
                  alert(res.response);
                }
            });
        }
    }
    
</script>