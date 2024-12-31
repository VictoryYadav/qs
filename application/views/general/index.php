<?php $this->load->view('layouts/gen/head'); ?>
<style>
    .fixedHeight{
        max-height: 480px;
        overflow-x: hidden;
    }
</style>

    <body data-topbar="dark">

        <!-- Begin page -->
        <div id="layout-wrapper">           
            <?php $this->load->view('layouts/gen/top'); ?>
            <div class="container" style="margin-top: 80px !important;">

                <div class="row">
                
                    <div class="col-xl-12">
                        <!-- <div class="card fixedHeight">
                            <div class="card-body">
                                
                                
                            </div>
                        </div> -->
                    </div>                                                              
                </div><!--end row-->
                
            </div> <!-- container-fluid -->

        </div>
        <?php $this->load->view('layouts/gen/footer'); ?>
        <!-- END layout-wrapper -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <?php $this->load->view('layouts/gen/scripts'); ?>
       
