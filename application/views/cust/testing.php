<!DOCTYPE html>
<html>
<head>
    <title>Firebase Phone Authentication</title>
</head>
<body>
    <input type="text" id="phoneNumber" placeholder="Enter your phone number">
    <div id="recaptcha-container"></div>
    <button onclick="sendVerificationCode()">Send Verification Code</button>
    <div id="verificationCodeContainer" style="display:none;">
        <input type="hidden" id="verificationId">
        <input type="text" id="verificationCode" placeholder="Enter verification code">
        <button onclick="verifyCode()">Verify Code</button>
    </div>

    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-auth.js"></script>

    <script>
        var appVerifier = '';
        rendor();

        function rendor(){
        const firebaseConfig = {
            apiKey: "AIzaSyAmEAaStxXGQM0_MIB4NSzXMdFK8pIkgOs",
            authDomain: "quick-service-e248d.firebaseapp.com",
            projectId: "quick-service-e248d",
            storageBucket: "quick-service-e248d.appspot.com",
            messagingSenderId: "430697983359",
            appId: "1:430697983359:web:74cdf763ee9077392a7e4a",
            measurementId: "G-64V1TKM6PX"
        };

        firebase.initializeApp(firebaseConfig);
            appVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');

        }
        function sendVerificationCode() {
            // alert('kkk')
            // ... (your code for sending verification code)
              const phoneNumber = document.getElementById('phoneNumber').value;
              // const appVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
              var testVerificationCode = "123456";

              firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier)
                .then((confirmationResult) => {
                    // console.log('code '+confirmationResult)
                    return confirmationResult.confirm(testVerificationCode)
                document.getElementById( 'verificationCodeContainer' ).style.display = 'block';
                  const verificationId = confirmationResult.verificationId;
                  // document.getElementById( 'verificationId' ).value = verificationId
                  
                  // console.log('code '+verificationId)
                  // Store verificationId for later use
                })
                .catch((error) => {
                  console.error(error);
                });
        }

        function verifyCode() {
              const verificationId = document.getElementById( 'verificationId' ).value;/* retrieve verificationId from earlier */;
              const verificationCode = document.getElementById('verificationCode').value;

              const credential = firebase.auth.PhoneAuthProvider.credential(verificationId, verificationCode);

              firebase.auth().signInWithCredential(credential)
                .then((userCredential) => {
                  const user = userCredential.user;
                  console.log('User authenticated:', user);
                })
                .catch((error) => {
                  console.error(error);
                });
        }
    </script>
</body>
</html>
