<?php $this->load->view('layouts/gen/head'); ?>
<style>
    .fixedHeight{
        max-height: 555px;
        overflow-x: hidden;
    }
</style>

    <body data-topbar="dark">

        <!-- Begin page -->
        <div id="layout-wrapper">           

            <div class="container mt-4">

                <div class="row">
                
                    <div class="col-xl-12">
                        <div class="card fixedHeight">
                            <div class="card-body">
                                <form method="post" id="profileForm">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">First Name</label>
                                                <input type="text" class="form-control" name="FName" placeholder="Firstname" required="" value="<?= $user['FName']; ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Last Name</label>
                                                <input type="text" class="form-control" name="LName" placeholder="Lastname" required="" value="<?= $user['LName']; ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Mobile No</label>
                                                <input type="text" class="form-control" name="MobileNo" placeholder="MobileNo" readonly="" value="<?= $user['MobileNo']; ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Email</label>
                                                <input type="text" class="form-control" name="email" placeholder="Email" required="" value="<?= $user['email']; ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Gender</label>
                                                <select class="form-control" name="Gender">
                                                    <option value="">Select</option>
                                                    <?php 
                                                    $sex = array(1 => 'Male',2 => 'Female',3 => 'Transgender');
                                                    foreach ($sex as $key => $value) { ?>
                                                        <option value="<?= $key; ?>" <?php if($key == $user['Gender']){ echo 'selected'; } ?> ><?= $value; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">DOB</label>
                                                <input type="text" class="form-control" name="DOB" id="DOB" required="" value="<?= date('d-M-Y', strtotime($user['DOB'])); ?>">
                                                 
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <input type="submit" class="btn btn-success" value="Update">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>                                                              
                </div><!--end row-->
                
            </div> <!-- container-fluid -->

        </div>
        <?php $this->load->view('layouts/gen/footer'); ?>
        <!-- END layout-wrapper -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <?php $this->load->view('layouts/gen/scripts'); ?>

        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

        <script>

            $(function () { 
                $("#DOB").datepicker({  
                    dateFormat: "dd-M-yy",
                    defaultDate: new Date() 
                }); 
            });

            $('#profileForm').on('submit', function(e){
                e.preventDefault();

                var data = $(this).serializeArray();
                $.post('<?= base_url('general/profile') ?>',data,function(res){
                    if(res.status == 'success'){
                        alert(res.message);
                    }else{
                        alert(res.message);
                    }
                        location.reload();
                });
            });

        </script>

       
