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
                            <div class="col-md-8 mx-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <?php if($this->session->flashdata('success')): ?>
                                            <div class="alert alert-success" role="alert" id="alertBlock"><?= $this->session->flashdata('success') ?></div>
                                            <?php endif; ?>
                                            
                                        <form method="post" action="<?php echo base_url('restaurant/add_user'); ?>">
                                            <div class="row">
                                                <div class="col-md-6 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('firstName'); ?></label>
                                                        <input type="text" name="FName" class="form-control form-control-sm" placeholder="<?= $this->lang->line('firstName'); ?>" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('lastName'); ?></label>
                                                        <input type="text" name="LName" class="form-control form-control-sm" placeholder="<?= $this->lang->line('lastName'); ?>" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('mobile'); ?></label>
                                                        <input type="tel" name="MobileNo" class="form-control form-control-sm" placeholder="<?= $this->lang->line('mobile'); ?>" required="" pattern="[6789][0-9]{9}" maxlength="10" minlength="10">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('email'); ?></label>
                                                        <input type="email" name="PEmail" class="form-control form-control-sm" required="" placeholder="<?= $this->lang->line('email'); ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('dob'); ?></label>
                                                        <input type="date" name="DOB" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 ">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('gender'); ?></label><br>
                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="customRadio7" name="Gender" class="custom-control-input" value="0" required="">
                                                                <label class="custom-control-label" for="customRadio7"><?= $this->lang->line('male'); ?></label>
                                                            </div>
                                                        </div>

                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="customRadio8" name="Gender" class="custom-control-input" value="1" required="">
                                                                <label class="custom-control-label" for="customRadio8"><?= $this->lang->line('female'); ?></label>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-6">
                                                    <div class="form-group">
                                                        <label><?= $this->lang->line('userType'); ?></label>
                                                        <select class="form-control form-control-sm" required="" name="UTyp">
                                                            <option value=""><?= $this->lang->line('chooseUser'); ?></option>
                                                            <option value="1"><?= $this->lang->line('normal'); ?></option>
                                                            <option value="5"><?= $this->lang->line('manager'); ?></option>
                                                            <option value="9"><?= $this->lang->line('admin'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-6">
                                                    <div class="form-group">

                                                        <label><?= $this->lang->line('outletName'); ?></label>
                                                        <select class="form-control form-control-sm" required="" name="EID">
                                                            <option value="">Choose</option>
                                                            <?php
                                                            foreach ($restaurant as $res) {
                                                            ?>
                                                            <option value="<?php echo $res['EID']; ?>" <?php if($EID == $res['EID']){ echo 'selected'; } ?>><?php echo $res['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('submit'); ?>">
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
                                                    <th><?= $this->lang->line('type'); ?></th>
                                                    <th><?= $this->lang->line('action'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($users)){
                                                        $i=1;
                                                        foreach ($users as $key) {
                                                            if($key['UTyp'] == 1){
                                                                $type = $this->lang->line('normal');
                                                            }else if($key['UTyp'] == 5){
                                                                $type = $this->lang->line('manager');
                                                            }if($key['UTyp'] == 9){
                                                                $type = $this->lang->line('admin');
                                                            }
                                                     ?>
                                                    
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo $key['FName'].' '.$key['LName']; ?></td>
                                                    <td>
                                                        <?php echo $key['MobileNo']; ?><br>
                                                           <small><?php echo $key['PEmail']; ?></small> 
                                                        </td>
                                                    <td><?php echo $type; ?></td>
                                                    <td></td>
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
});

</script>