@extends('layouts.guest')

@push('scripts')
    <script>
        //on page ready
        $(document).ready(function() {
            // Redirect to the register page
            const hash = window.location.hash.substring(1);
            //if hash is driver
            if (hash === 'driver') {
                //redirect to register driver route
                window.location.replace("{{ route('register.driver') }}");

            } else if (hash === 'vendor') {
                window.location.replace("{{ route('register.vendor') }}");
            } else {
                window.location.href = '/404';
            }
        });

        // Redirect to the register page
    </script>
@endpush
