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
                                            
                                        <form method="post" action="<?php echo base_url('restaurant/add_user'); ?>">
                                            <input type="hidden" name="RUserId" id="RUserId" value="0">
                                            <input type="hidden" name="EID" id="EID" value="<?= $EID; ?>">
                                                Note: <span class="text-danger">Mobile and Email Can't be changed later. Users can not reactivated.</span>
                                            <div class="row">
                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('firstName'); ?></label>
                                                        <input type="text" name="FName" class="form-control form-control-sm" placeholder="<?= $this->lang->line('firstName'); ?>" required="" id="FName">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('lastName'); ?></label>
                                                        <input type="text" name="LName" class="form-control form-control-sm" placeholder="<?= $this->lang->line('lastName'); ?>" required="" id="LName">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('mobile'); ?></label>
                                                        <input type="text" name="MobileNo" class="form-control form-control-sm" placeholder="<?= $this->lang->line('mobile'); ?>" required="" maxlength="10" minlength="10" id="MobileNo" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('email'); ?></label>
                                                        <input type="email" name="PEmail" class="form-control form-control-sm" required="" placeholder="<?= $this->lang->line('email'); ?>" id="PEmail">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('dob'); ?></label>
                                                        <?php 

                                                        $dateP = date('Y-m-d', strtotime("-20 years", strtotime(date('Y-m-d'))));
                                                        ?>
                                                        <input type="date" name="DOB" class="form-control form-control-sm" value="<?php echo $dateP; ?>" required id="DOB">
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('gender'); ?></label>
                                                        <select class="form-control form-control-sm" required="" name="Gender" id="Gender">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <option value="1"><?= $this->lang->line('male'); ?></option>
                                                            <option value="2"><?= $this->lang->line('female'); ?></option>
                                                            <option value="3"><?= $this->lang->line('transgender'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('userType'); ?></label>
                                                        <select class="form-control form-control-sm" required="" name="UTyp" id="UTyp">
                                                            <option value=""><?= $this->lang->line('chooseUser'); ?></option>
                                                            <?php
                                                            foreach ($userType as $key) { ?>
                                                            <option value="<?= $key['UTyp']; ?>"><?= $key['UTypName']; ?></option>
                                                        <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('restaurant'); ?> <?= $this->lang->line('role'); ?></label>
                                                        <select class="form-control form-control-sm" required="" name="RestRole" id="RestRole">
                                                            <option value=""><?= $this->lang->line('chooseUser'); ?></option>
                                                            <?php
                                                            foreach ($restRole as $key) { ?>
                                                            <option value="<?= $key['UTyp']; ?>"><?= $key['UTypName']; ?></option>
                                                        <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('mode'); ?></label>
                                                        <select name="Stat" id="Stat" class="form-control form-control-sm" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>

                                                            <option value="0"><?= $this->lang->line('active'); ?></option>
                                                            <option value="1"><?= $this->lang->line('inactive'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- <div class="col-md-3 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('outletName'); ?></label>
                                                        <select class="form-control form-control-sm" required="" name="EID" id="EID">
                                                            <option value="">Choose</option>
                                                            <?php
                                                            foreach ($restaurant as $res) {
                                                            ?>
                                                            <option value="<?php echo $res['EID']; ?>" <?php if($EID == $res['EID']){ echo 'selected'; } ?>><?php echo $res['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div> -->

                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('submit'); ?>" id="saveBtn">
                                                <input type="submit" class="btn btn-success btn-sm" value="<?= $this->lang->line('update'); ?>" id="updateBtn" style="display: none;">
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
                                            <table id="usersTBL" class="table table-bordered topics">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('userName'); ?></th>
                                                    <th><?= $this->lang->line('mobile'); ?></th>
                                                    <th><?= $this->lang->line('role'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($users)){
                                                        $i=1;
                                                        foreach ($users as $key) {

                                                            $sts = ($key['Stat'] == 0)? $this->lang->line('active'):$this->lang->line('inactive');

                                                            $clr = ($key['Stat'] == 0)?'success':'danger';
                                                     ?>
                                                    
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo $key['FName'].' '.$key['LName']; ?>
                                                        <br>
                                                        <small><?php echo date('d-M-Y',strtotime($key['DOB'])); ?></small>
                                                    </td>
                                                    <td>
                                                        <?php echo $key['MobileNo']; ?><br>
                                                           <small><?php echo $key['PEmail']; ?></small> 
                                                        </td>
                                                    <td><?php echo $key['UTypName'].' ['.$key['designation'].']'; ?><br>
                                                        <span class="badge badge-boxed  badge-<?= $clr; ?>"><?= $sts; ?></span>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        if($key['Stat'] != 1){
                                                        ?>
                                                        <button class="btn btn-sm btn-rounded btn-warning" onclick="editData(<?= $key['RUserId'] ?>,'<?= $key['FName'] ?>','<?= $key['LName'] ?>', '<?= $key['MobileNo'] ?>','<?= $key['PEmail'] ?>', '<?= $key['DOB'] ?>', <?= $key['Gender'] ?>, <?= $key['UTyp'] ?>,<?= $key['RestRole'] ?>, <?= $key['Stat'] ?>, <?= $key['EID'] ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        <?php } ?>
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


<script type="text/javascript">
$(document).ready(function () {
    $('#usersTBL').DataTable();
    $('#MobileNo').prop('readonly', false);
    $('#PEmail').prop('readonly', false); 
});

function editData(RUserId, FName, LName, MobileNo, PEmail, DOB, Gender, UTyp, RestRole, Stat, EID){
        
    $('#RUserId').val(RUserId);
    $('#FName').val(FName);
    $('#LName').val(LName);
    $('#MobileNo').val(MobileNo);
    $('#PEmail').val(PEmail);
    $('#DOB').val(DOB);
    $('#Gender').val(Gender);
    $('#UTyp').val(UTyp);
    $('#RestRole').val(RestRole);
    $('#EID').val(EID);
    $('#Stat').val(Stat);  

    $('#MobileNo').prop('readonly', true);
    $('#PEmail').prop('readonly', true); 

    $('#saveBtn').hide();
    $('#updateBtn').show();
}

</script>