<?php $this->load->view('layouts/admin/head'); ?>
<head>
    <!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.4.3/firebase-app.js"></script>

<!-- Add additional services that you want to use -->
<script src="https://www.gstatic.com/firebasejs/8.4.3/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.4.3/firebase-firestore.js"></script>
<!-- Load the Firebase SDK -->
<!-- <script src="https://www.gstatic.com/firebasejs/8.4.3/firebase-app.js"></script> -->

<!-- Load the Firebase Messaging module -->
<script src="https://www.gstatic.com/firebasejs/8.4.3/firebase-messaging.js"></script>


</head>
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
                                        <div class="row">
                                          <?php 
                                          $folderPath = 'uploads/e51';
        foreach(glob($folderPath.'/*.*') as $file) {
                                          ?>
                                          <div class="col-md-3">
                                            <p><?= $file; ?></p>
                                          </div>
                                        <?php } ?>
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

