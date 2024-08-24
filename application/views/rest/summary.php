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
            
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4 align-self-center">
                                                <div class="icon-info">
                                                    <i class="mdi mdi-diamond text-warning"></i>
                                                </div> 
                                            </div>
                                            <div class="col-8 align-self-center text-center">
                                                <div class="ml-2 text-right">
                                                    <p class="mb-1 text-muted font-size-13">Projects</p>
                                                    <h4 class="mt-0 mb-1 font-20">35</h4>                                                                                                                                           
                                                </div>
                                            </div>                    
                                        </div>
                                        <div class="progress mt-2" style="height:3px;">
                                            <div class="progress-bar progress-animated  bg-warning" role="progressbar" style="max-width: 55%;" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4 align-self-center">
                                                <div class="icon-info">
                                                    <i class="mdi mdi-account-multiple text-purple"></i>
                                                </div> 
                                            </div>
                                            <div class="col-8 align-self-center text-center">
                                                <div class="ml-2 text-right">
                                                    <p class="mb-1 text-muted font-size-13">Member</p>
                                                    <h4 class="mt-0 mb-1 font-20">12</h4>                                                                                                                                           
                                                </div>
                                            </div>                    
                                        </div>
                                        <div class="progress mt-2" style="height:3px;">
                                            <div class="progress-bar progress-animated  bg-purple" role="progressbar" style="max-width: 55%;" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
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
  const socket = new WebSocket('ws://localhost:8080');

  socket.onopen = function(event) {
      console.log("Connected to WebSocket server");
      socket.send("Your message"); // You can send a message to the server if needed
  };

  socket.onmessage = function(event) {
      const data = event.data;
      // console.log("Data received from server: ", data);
      sendPrint(51, 1);
  };

  socket.onclose = function(event) {
      console.log("WebSocket connection closed");
  };

  socket.onerror = function(event) {
      console.error("WebSocket error observed: ", event);
  };

  function sendPrint(EID, BillId){
    var url = "<?php echo base_url('restaurant/bill_print/') ?>"+BillId+"/"+EID;
    window.open(url, "_blank");
    return false;
}

</script>
