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
                                        Open this page for Printing.
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
      var data = event.data;
      // var myJSON = JSON.parse(data);
      // console.log("Data received from server: ", myJSON.EID);
      dd(51, 1);
      // dd(myJSON.EID, myJSON.BillId);
  };

  socket.onclose = function(event) {
      console.log("WebSocket connection closed");
  };

  socket.onerror = function(event) {
      console.error("WebSocket error observed: ", event);
  };

  function dd(EID, BillId){
      var url = "<?php echo base_url('restaurant/bill_print/') ?>"+BillId+"/"+EID;
      window.open(url, "_blank");
      return false;
  }

  $('#sendButton').click(function() {
      
      const message = 'how r u';
      const senderId = 1;
      const receiverId = 2;



      $.post('<?= base_url('ChatController/sendMessage') ?>', {
          message: message,
          sender_id: senderId,
          receiver_id: receiverId
      }, function(res) {

          if(res.status == 'success'){
            // alert(res.response);
            socket.send(res.response);
          }else{
            alert(res.response);
          }
      });

  });

</script>