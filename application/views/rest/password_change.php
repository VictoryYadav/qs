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
                                        <form method="post" id="changePassword">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Current Password</label>
                                                        <input type="text" name="old_password" class="form-control" placeholder="Password" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>New Password</label>
                                                        <input type="text" name="password" class="form-control" placeholder="New Password" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Confirm New Password</label>
                                                        <input type="text" name="c_password" class="form-control" placeholder="Confirm Password" required="">
                                                    </div>
                                                </div>

                                                

                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-success" value="GET OTP">
                                            </div>
                                        </form>

                                        <form method="post" id="sendOtp" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Enter OTP</label>
                                                        <input type="number" name="otp" class="form-control" placeholder="OTP" required="" id="otp_text">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-sm btn-danger" value="Verify OTP">
                                                <a href="#" onclick="resend()">Resend OTP</a>
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

<script type="text/javascript">

    $('#changePassword').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/change_password') ?>',data,function(res){
            if(res.status == 'success'){
              alert(res.response);
              $('#sendOtp').show();
              $('#changePassword').hide();
              // location.reload();
            }else{
              alert(res.response);
              $('#changePassword').show();
              $('#sendOtp').hide();
            }
        });
    });

    $('#sendOtp').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('restaurant/verifyOTP') ?>',data,function(res){
            if(res.status == 'success'){
              // alert(res.response);
              window.location = res.response;
              return false;
              // location.reload();
            }else{
              alert(res.response);
              $('#sendOtp').show();
              $('#otp_text').val('');
            }
        });

    });

    function resend(){
        var old = "<?php echo $this->session->userdata('old_pwd'); ?>";
        var pass = "<?php echo $this->session->userdata('new_pwd'); ?>";
        // console.log(old);
        // console.log(pass);
        $.post('<?= base_url('restaurant/change_password') ?>',{password:pass,c_password:pass,old_password:old},function(res){
            if(res.status == 'success'){
              alert(res.response);
            }else{
              alert(res.response);
            }
            $('#sendOtp').show();
            $('#otp_text').val('');
        });
    }

</script>

<script type="module">
    
  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/9.19.1/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.19.1/firebase-analytics.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig1 = {
    apiKey: "AIzaSyAmEAaStxXGQM0_MIB4NSzXMdFK8pIkgOs",
    authDomain: "quick-service-e248d.firebaseapp.com",
    projectId: "quick-service-e248d",
    storageBucket: "quick-service-e248d.appspot.com",
    messagingSenderId: "430697983359",
    appId: "1:430697983359:web:74cdf763ee9077392a7e4a",
    measurementId: "G-64V1TKM6PX"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig1);
  const analytics = getAnalytics(app);

            
  // Let's check if the browser supports notifications
  if (!("Notification" in window)) {
    alert("This browser does not support desktop notification");
  }
  
  // Let's check whether notification permissions have already been granted
  else if (Notification.permission === "granted") {
    console.log('kk');
    // If it's okay let's create a notification
    // var notification = new Notification("Hi there!");
    var config = {

            apiKey: "AIzaSyD2p6lWVTEbneI2gm6aheeHjTFRQdBdN2o",

            authDomain: "progressive-apps-builder.firebaseapp.com",

            databaseURL: "https://progressive-apps-builder.firebaseio.com",

            projectId: "progressive-apps-builder",

            storageBucket: "progressive-apps-builder.appspot.com",

            messagingSenderId: "692054876427"

        };
        var firebaseConfig = {
            apiKey: "AIzaSyAmEAaStxXGQM0_MIB4NSzXMdFK8pIkgOs",
            authDomain: "quick-service-e248d.firebaseapp.com",
            projectId: "quick-service-e248d",
            storageBucket: "quick-service-e248d.appspot.com",
            messagingSenderId: "430697983359",
            appId: "1:430697983359:web:74cdf763ee9077392a7e4a",
            measurementId: "G-64V1TKM6PX"
          };

        firebase.initializeApp(firebaseConfig);

        const messaging = firebase.messaging();

        messaging.requestPermission()
        .then(function() {
          console.log('Notification permission granted.');
          // alert("granted");
          return messaging.getToken();
          $('#loader_div').show();
        })
        .then(function(token) {
            
          $.ajax({
                url: "<?php echo base_url('restaurant/tokenGenerate'); ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    save_firebase_token: 1,
                    token: token
                },
                success: function(response) {
                 // alert(token);
                 $('#loader_div').hide();
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
          console.log('Tokenv:'+token); // Display user token
        })
        .catch(function(err) { // Happen if user deney permission
          console.log('Unable to get permission to notify.', err);
        });
  }

  // Otherwise, we need to ask the user for permission
  else if (Notification.permission !== "denied") {
    Notification.requestPermission().then(function (permission) {
      // If the user accepts, let's create a notification
      if (permission === "granted") {
        // var notification = new Notification("Hi there!");
        var config = {

            apiKey: "AIzaSyD2p6lWVTEbneI2gm6aheeHjTFRQdBdN2o",

            authDomain: "progressive-apps-builder.firebaseapp.com",

            databaseURL: "https://progressive-apps-builder.firebaseio.com",

            projectId: "progressive-apps-builder",

            storageBucket: "progressive-apps-builder.appspot.com",

            messagingSenderId: "692054876427"

        };
        var firebaseConfig = {
            apiKey: "AIzaSyAmEAaStxXGQM0_MIB4NSzXMdFK8pIkgOs",
            authDomain: "quick-service-e248d.firebaseapp.com",
            projectId: "quick-service-e248d",
            storageBucket: "quick-service-e248d.appspot.com",
            messagingSenderId: "430697983359",
            appId: "1:430697983359:web:74cdf763ee9077392a7e4a",
            measurementId: "G-64V1TKM6PX"
          };

        firebase.initializeApp(firebaseConfig);

        const messaging = firebase.messaging();

        messaging.requestPermission()
        .then(function() {
          console.log('Notification permission granted.');
          // alert("granted");
          return messaging.getToken();
        })
        .then(function(token) {
          
          console.log('Token:'+token); // Display user token
        })
        .catch(function(err) { // Happen if user deney permission
          console.log('Unable to get permission to notify.', err);
        });
      }
    });
  }

  // At last, if the user has denied notifications, and you
  // want to be respectful there is no need to bother them any more.

</script>