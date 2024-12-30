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
                                Welcome Eatout
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
       
