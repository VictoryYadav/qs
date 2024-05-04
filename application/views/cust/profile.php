<?php $this->load->view('layouts/customer/head'); ?>
<style>
body{
    font-size: 12px;
}
</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <section class="common-section p-2">
        <div class="container">
          <div class="row">
            <div class="col-md-6 mx-auto">
                <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success" role="alert"><?= $this->session->flashdata('success') ?></div>
                    <?php endif; ?>
              <form method="post">
                  <input type="hidden" name="token" id="token">
                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <input type="text" name="MobileNo" class="form-control" placeholder="Enter Mobile" autocomplete="off" minlength="10" maxlength="10" pattern="[6-9]{1}[0-9]{9}" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" value="<?= $detail['MobileNo']; ?>" readonly>
                          </div>
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                              <input type="email" name="email" class="form-control" placeholder="Enter Email (Bills will be sent on this Email)" value="<?= $detail['email']; ?>" readonly>
                          </div>
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                              <input type="text" name="FName" class="form-control" placeholder="Enter Firstname" value="<?= $detail['FName']; ?>">
                          </div>
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                              <input type="text" name="LName" class="form-control" placeholder="Enter Lastname" value="<?= $detail['LName']; ?>">
                          </div>
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                              <select name="Gender" class="form-control">
                                  <option value=""><?= $this->lang->line('select'); ?></option>
                                  <option value="1" <?php if($detail['Gender'] == 1){ echo 'selected'; } ?>><?= $this->lang->line('male'); ?></option>
                                  <option value="2" <?php if($detail['Gender'] == 2){ echo 'selected'; } ?>><?= $this->lang->line('female'); ?></option>
                                  <option value="3" <?php if($detail['Gender'] == 3){ echo 'selected'; } ?>><?= $this->lang->line('transgender'); ?></option>
                              </select>
                          </div>
                      </div>
                      <div class="col-md-12">
                        <?php
                        $dob = '';
                        if(!empty($detail['DOB'])){
                            $dob = date('Y-m-d', strtotime($detail['DOB']));
                        }
                         ?>
                          <div class="form-group">
                              <input type="date" name="DOB" class="form-control" placeholder="Enter DOB" value="<?= $dob; ?>">
                          </div>
                      </div>
                  </div>
                  <input type="submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('update'); ?>">
              </form>
            </div>
          </div>
        </div>
    </section>

    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
    <!-- end footer section -->


    <!-- Js Plugins -->
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

<script type="text/javascript">
   $(document).ready(function() {
        
    });

</script>

</html>