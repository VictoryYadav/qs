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

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18 text-center"><?php echo $title; ?>
                                    </h4>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-md-6 mx-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="post" id="logoForm" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Logo File</label>
                                                        <input type="file" name="logo_file" class="form-control" required="" accept="image/png, image/gif, image/jpeg">
                                                    </div>
                                                </div>

                                                <div id="processBlock" style="display: none;">
                                                  <p class="text-info">Processing...</p>
                                                </div>                                              

                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="Update">
                                            </div>
                                        </form>
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


<!-- Load the Firebase SDK -->
<script src="https://www.gstatic.com/firebasejs/8.4.3/firebase-app.js"></script>
<!-- Add additional services that you want to use -->
<script src="https://www.gstatic.com/firebasejs/8.4.3/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.4.3/firebase-firestore.js"></script>
<!-- Load the Firebase Messaging module -->
<script src="https://www.gstatic.com/firebasejs/8.4.3/firebase-messaging.js"></script>

<script>
$('#logoForm').on('submit', function(e){
    e.preventDefault();

    var formData = new FormData(document.getElementById("logoForm"));
    callAjax(formData);
});

function callAjax(formData){
  $(`#processBlock`).show();
   $.ajax({
           url : '<?= base_url('restaurant/change_logo') ?>',
           type : 'POST',
           data : formData,
           processData: false,  
           contentType: false,  
           success : function(data) {
               // alert(data.response);
               if(data.status == 'success'){
                    $(`#processBlock`).hide();
                    location.reload();
               }else{
                alert(data.response);
                $(`#processBlock`).hide();
               }
           }
    }); 
}
</script>
