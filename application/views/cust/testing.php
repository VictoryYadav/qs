<?php $this->load->view('layouts/customer/head'); ?>
<style>

</style>
</head>

<body>

    <!-- Header Section Begin -->
    <?php $this->load->view('layouts/customer/top'); ?>
    <!-- Header Section End -->
    <section class="common-section p-2 dashboard-container">
        <div class="container">
            <form method="post">
                <div class="form-group">
                    <input type="number" class="form-group" name="phone_number" required="" id="phone">
                    <div id="recaptcha-container"></div>
                </div>
                <button type="button" class="btn btn-sucess btn-sm" onclick="checkNumber()">Submit</button>
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

 

  // At last, if the user has denied notifications, and you
  // want to be respectful there is no need to bother them any more.

</script>

<script type="text/javascript">

    function checkNumber(){
        const phoneNumber = document.getElementById('phone').value;
    
        var appVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
        appVerifier.rendor();
        // signInWithPhoneNumber will call appVerifier.verify() which will resolve with a fake
        // reCAPTCHA response.
        firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier)
            .then(function (confirmationResult) {
              // confirmationResult can resolve with the fictional testVerificationCode above.
              // return confirmationResult.confirm(testVerificationCode)
              console.log(confirmationResult);
              alert('sent');
            }).catch(function (error) {
              // Error; SMS not sent
              // ...
              alert(error.message);
            });

    }

   $(document).ready(function() {
        
    });

</script>

</html>