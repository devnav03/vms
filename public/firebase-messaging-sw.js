/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
        apiKey: "AIzaSyCF7qSTr5mn_XnnjitFq-JyV7ORsq1BJtw",
        authDomain: "laravel-vms-dev.firebaseapp.com",
        projectId: "laravel-vms-dev",
        storageBucket: "laravel-vms-dev.appspot.com",
        messagingSenderId: "988890903255",
        appId: "1:988890903255:web:17fe139864c2b6a3d37692",
        measurementId: "G-ZSWKHQKLJ3"
            // measurementId: "G-R1KQTR3JBN"
        });

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
    // Customize notification here
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "/itwonders-web-logo.png",
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});