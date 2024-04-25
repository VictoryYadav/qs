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

                        <div class="row" id="showBlock">
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
                                        <div class="row">

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('restaurant'); ?></label>
                                                    <input type="text" name="Name" id="Name" class="form-control form-control-sm" required="">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label>Person Name</label>
                                                    <input type="text" name="ContactPerson" id="ContactPerson" class="form-control form-control-sm"  required="">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('mobile'); ?></label>
                                                    <input type="number" name="CellNo" id="CellNo" class="form-control form-control-sm" required="">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('email'); ?></label>
                                                    <input type="email" name="Email" id="Email" class="form-control form-control-sm" required="">
                                                </div>
                                            </div>
                                            <?php
                                            $dateP = date('Y-m-d', strtotime("-20 years", strtotime(date('Y-m-d'))));
                                             ?>
                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('dob'); ?></label>
                                                    <input type="date" name="DOB" id="DOB" class="form-control form-control-sm" required="" value="<?= $dateP; ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label>E<?= $this->lang->line('category'); ?></label>
                                                    <input type="text" name="ECatg" id="ECatg" class="form-control form-control-sm" value="1">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('chainNo'); ?></label>
                                                    <input type="number" name="ChainId" id="ChainId" class="form-control form-control-sm" value="0">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('category'); ?></label>
                                                    <input type="text" name="CatgId" id="CatgId" class="form-control form-control-sm" value="1">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('country'); ?></label>
                                                    <select  name="CountryCd" id="CountryCd" class="form-control form-control-sm select2 custom-select" required="">
                                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                                        <?php 
                                                        foreach ($country as $key) { ?>
                                                            <option value="<?= $key['phone_code']; ?>"><?= $key['country_name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('GSTNo'); ?></label>
                                                    <input type="number" name="GSTNo" id="GSTNo" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('area'); ?></label>
                                                    <input type="text" name="Area" id="Area" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('suburb'); ?></label>
                                                    <input type="text" name="Suburb" id="Suburb" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('city'); ?></label>
                                                    <input type="text" name="City" id="City" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('pincode'); ?></label>
                                                    <input type="number" name="PIN" id="PIN" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('state'); ?></label>
                                                    <input type="text" name="State" id="State" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('address'); ?></label>
                                                    <input type="text" name="HOAddress" id="HOAddress" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('fromTime'); ?></label>
                                                    <input type="time" name="StTime" id="StTime" class="form-control form-control-sm" value="<?= date('H:i:s') ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('toTime'); ?></label>
                                                    <input type="time" name="EndTime" id="EndTime" class="form-control form-control-sm" value="<?= date('H:i:s') ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('latitude'); ?></label>
                                                    <input type="text" name="Lat" id="Lat" class="form-control form-control-sm" >
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="form-group">
                                                    <label><?= $this->lang->line('longitude'); ?></label>
                                                    <input type="text" name="Lng" id="Lng" class="form-control form-control-sm" >
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="text-right">
                                            <input type="submit" class="btn btn-sm btn-success" value="Submit">
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
                                            <table id="restTBL" class="table table-bordered topics">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('restaurant'); ?></th>
                                                    <th><?= $this->lang->line('mobile'); ?></th>
                                                    <th><?= $this->lang->line('email'); ?></th>
                                                    <th><?= $this->lang->line('link'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($rests)){
                                                        $i=1;
                                                        foreach ($rests as $key) {
                                                            $url = base_url().'login?o='.$key['CNo'].'&c='.$key['CatgId'];
                                                     ?>
                                                    
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo $key['Name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $key['CellNo']; ?>
                                                        </td>
                                                    <td><?php echo $key['Email']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $url; ?>
                                                    </td>
                                                </tr>
                                                <?php }
                                                 }
                                                ?>
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

        var data = $(this).serializeArray();
        $.post('<?= base_url('support/new_customer_create') ?>',data,function(res){
            if(res.status == 'success'){
              // alert(res.response);
              // location.reload();
              var EID = res.response.EID;
              var CatgId = res.response.CatgId;
              $('#showBlock').hide();
              $('#loadBlock').show();
              setInterval(function(){ updateData(EID, CatgId); }, 30000);
              
            }else{
              alert(res.response);
            }
        });
    });


    function updateData(EID, CatgId){
        $.post('<?= base_url('support/update_customer') ?>',{EID:EID},function(res){
            if(res.status == 'success'){
              alert(res.response);
              $('#loadBlock').hide();
              $('#showBlock').show();
              
              // $('#loginUrl').attr("href", "<?= base_url('login?o='); ?>"+EID+"&c="+CatgId);
              $('#signinurl').html("<?= base_url('login?o='); ?>"+EID+"&c="+CatgId);
              document.getElementById("customerForm").reset();
              $('#btnBlock').show();
              // location.reload();
            }else{
              alert(res.response);
            }
        });
    }



    
</script>