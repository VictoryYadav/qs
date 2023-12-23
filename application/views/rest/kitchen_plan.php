<?php $this->load->view('layouts/admin/head'); ?>
<style>
    .select_option{
        background: white;
        padding: 10px;
        display: none;
        max-height: 254px;
        overflow-y: scroll;
        position: absolute;
        width: 95%;
        z-index: 2;
        box-shadow: 2px 3px 6px #00000070;
    }

    .select_option a {
        color: black;
        width: 100%;
        display: block;
        text-decoration: none;
        margin-bottom: 5px;
        border-bottom: 1px solid #8080806b;
    }
</style>
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
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select name="kitchen" id="kitchen" class="form-control form-control-sm" required="">
                                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($kitchen as $key) {
                                                            ?>
                                                            <option value="<?= $key['KitCd']; ?>" <?php if($kitcd ==$key['KitCd']){ echo 'selected'; } ?>><?= $key['KitName']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="Submit" class="btn btn-sm btn-success" value="<?= $this->lang->line('search'); ?>">
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
                                        <div class="table-responsive">
                                          <table class="table table-bordered" id="kitchenTbl">
                                            <thead>
                                            <tr>
                                                <th><?= $this->lang->line('item'); ?></th>
                                                <th><?= $this->lang->line('quantity'); ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php 
                                            if(!empty($kplanner)){
                                                foreach ($kplanner as $kit) {
                                            ?>
                                            <tr>
                                                <td><?= $kit['ItemNm']; ?></td>
                                                <td><?= $kit['Qty']; ?></td>
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


<script>
    $(document).ready(function () {
        $('#kitchenTbl').DataTable();
    });

</script>