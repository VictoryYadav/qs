importScripts('https://www.gstatic.com/firebasejs/9.1.3/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.1.3/firebase-messaging-compat.js');

firebase.initializeApp({
  apiKey: "AIzaSyAmEAaStxXGQM0_MIB4NSzXMdFK8pIkgOs",
    authDomain: "quick-service-e248d.firebaseapp.com",
    projectId: "quick-service-e248d",
    storageBucket: "quick-service-e248d.appspot.com",
    messagingSenderId: "430697983359",
    appId: "1:430697983359:web:74cdf763ee9077392a7e4a",
    measurementId: "G-64V1TKM6PX"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
  console.log('Received background message:', payload);
  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: payload.notification.icon,
  };
  self.registration.showNotification(notificationTitle, notificationOptions);
});
