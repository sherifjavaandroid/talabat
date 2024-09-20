@if (setting('vapidKey', '') != '')
    <script src="https://www.gstatic.com/firebasejs/5.9.4/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.9.4/firebase-messaging.js"></script>

    <script>
        var firebaseConfig = {
            messagingSenderId: "{{ setting('messagingSenderId') }}",
        };



        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
        initFirebaseMessagingRegistration();

        function initFirebaseMessagingRegistration() {
            messaging
                .requestPermission()
                .then(function() {
                    return messaging.getToken({
                        vapidKey: "{{ setting('vapidKey') }}"
                    })
                })
                .then(function(token) {
                    console.log(token);
                    var deviceUniqueIdentifier = getDeviceUniqueIdentifier();
                    //
                    livewire.emit('changeFCMToken', token, deviceUniqueIdentifier);

                }).catch(function(err) {
                    console.log('User Token Error' + err);
                });
        }


        messaging.onMessage(function(payload) {
            const noteTitle = payload.notification.title;
            const noteOptions = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(noteTitle, noteOptions);
        });

        // get or generate device unique identifier
        function getDeviceUniqueIdentifier() {
            var deviceUniqueIdentifier = localStorage.getItem('deviceUniqueIdentifier');
            if (!deviceUniqueIdentifier) {
                deviceUniqueIdentifier = generateDeviceUniqueIdentifier();
                localStorage.setItem('deviceUniqueIdentifier', deviceUniqueIdentifier);
            }
            return deviceUniqueIdentifier;
        }

        function generateDeviceUniqueIdentifier() {
            var array = new Uint32Array(8);
            window.crypto.getRandomValues(array);
            var str = '';
            for (var i = 0; i < array.length; i++) {
                str += (i < 2 || i > 5 ? '' : '-') + array[i].toString(16).slice(-4);
            }
            return str;
        }
    </script>
@endif
