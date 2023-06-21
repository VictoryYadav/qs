<?php $this->load->view('layouts/customer/head'); ?>
<style>

</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->

    <section class="common-section p-2">
        <div class="container">
            <form method="post" id="signupForm">
                <input type="hidden" name="token" id="token">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="tel" name="MobileNo" class="form-control" placeholder="Enter Mobile" required="" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" name="FName" class="form-control" placeholder="Enter Firstname">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" name="LName" class="form-control" placeholder="Enter Lastname">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="Enter Email">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <select name="Gender" class="form-control">
                                <option value="">Select Sex</option>
                                <option value="0">Male</option>
                                <option value="1">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="date" name="DOB" class="form-control" placeholder="Enter DOB">
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-sm btn-success" value="Submit">
            </form>

            <form method="post" id="otpForm" style="display: none;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="number" name="otp" class="form-control" placeholder="Enter OTP" autocomplete="off" required="">
                            <span class="text-danger" id="errorMsg" style="font-size: 9px;"></span>
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-sm btn-success" value="Verify OTP">
            </form>
        </div>
    </section>

    <!-- footer section -->
    <?php $this->load->view('layouts/customer/footer'); ?>
    <!-- end footer section -->


    <!-- Js Plugins -->
    <?php $this->load->view('layouts/customer/script'); ?>
    <!-- end Js Plugins -->

</body>

<!-- Load the Firebase SDK -->
<script src="https://www.gstatic.com/firebasejs/8.4.3/firebase-app.js"></script>
<!-- Add additional services that you want to use -->
<script src="https://www.gstatic.com/firebasejs/8.4.3/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.4.3/firebase-firestore.js"></script>
<!-- Load the Firebase Messaging module -->
<script src="https://www.gstatic.com/firebasejs/8.4.3/firebase-messaging.js"></script>

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
            
          // $.ajax({
          //       url: "<?php echo base_url('restaurant/tokenGenerate'); ?>",
          //       type: 'POST',
          //       dataType: 'json',
          //       data: {
          //           save_firebase_token: 1,
          //           token: token
          //       },
          //       success: function(response) {
          //        // alert(token);
          //        $('#loader_div').hide();
          //       },
          //       error: function(xhr, status, error) {
          //           console.log(xhr);
          //           console.log(status);
          //           console.log(error);
          //       }
          //   });
          console.log('Tokenv:'+token); // Display user token
          $('#token').val(token);
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
          $('#token').val(token);
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

<script type="text/javascript">
   $(document).ready(function() {
        
    });

   $('#signupForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('customer/signup') ?>',data,function(res){
            if(res.status == 'success'){
              $('#otpForm').show();
              $('#signupForm').hide();
            }else{
              $('#signupForm').show();
              $('#otpForm').hide();
            }
        });
    });

   $('#otpForm').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serializeArray();
        $.post('<?= base_url('customer/verifyOTP') ?>',data,function(res){
            if(res.status == 'success'){
                window.location = '<?= base_url('customer'); ?>';
            }else{
              $('#errorMsg').html(res.response);
            }
        });
    });

</script>

</html>