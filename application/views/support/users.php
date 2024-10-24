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

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="userTBL" class="table table-bordered topics">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?= $this->lang->line('name'); ?></th>
                                                    <th><?= $this->lang->line('mobile'); ?></th>
                                                    <th><?= $this->lang->line('restaurant'); ?></th>
                                                    <th><?= $this->lang->line('type'); ?></th>
                                                    <th><?= $this->lang->line('language'); ?></th>
                                                    <th><?= $this->lang->line('mode'); ?></th>
                                                </tr>
                                                </thead>
            
                                                <tbody>
                                                    <?php
                                                    if(!empty($users)){
                                                        $i=1;
                                                        foreach ($users as $key) {
                                                            $sts = ($key['stat'] == 0)? $this->lang->line('active'):$this->lang->line('inactive');

                                                            $clr = ($key['stat'] == 0)?'success':'danger';

                                                            $usertype = ($key['userType'] == 1)? 'Rest Create':'Rest Support';
                                                     ?>
                                                    
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo $key['fullname']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $key['mobileNo']; ?><br>
                                                        <?php echo $key['email']; ?>
                                                    </td>
                                                    <td>Main : <?php echo $key['main']; ?><br>
                                                        Alternate : <?php echo $key['alternate']; ?>
                                                    </td>
                                                    <td><?php echo $usertype; ?></td>
                                                    <td><?= getMultiLangName($key['langId']); ?></td>
                                                    <td>
                                                        <?php if($key['stat'] == 0){ ?>
                                                        <span class="badge badge-boxed  badge-<?= $clr; ?>" style="cursor: pointer;" onclick="changeStatus(<?= $key['userId']; ?>, <?= $key['stat']; ?>, <?= $key['mobileNo']; ?>);"><?= $sts; ?></span>
                                                    <?php }else{ ?>
                                                        <span class="badge badge-boxed  badge-<?= $clr; ?>" ><?= $sts; ?></span>
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


<script type="text/javascript">

    $(document).ready(function () {
        $('#userTBL').DataTable();
    });

    function changeStatus(userId, stat, mobileNo){

        $.post('<?= base_url('support/users') ?>',{userId:userId, stat:stat, mobileNo:mobileNo},function(res){
            if(res.status == 'success'){
              alert(res.response);
            }else{
              alert(res.response);
            }
            location.reload();
        });
    }
</script>