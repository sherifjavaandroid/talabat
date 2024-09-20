<!DOCTYPE html>
<html lang="{{ setting('localeCode', 'en') }}" dir="{{ isRTL() ? 'rtl' : 'ltr' }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>

    <!--[if !mso]><!-->
    <style type="text/css">
        /* @import url('https://fonts.googleapis.com/css?family=Montserrat:400,600'); */
        /* @import url('https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i'); */
        @import url('https://fonts.googleapis.com/css?family=Rubik:400,600');
        @import url('https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css');
    </style>
    <!--<![endif]-->

    <style type="text/css" rel="stylesheet" media="all">
        html,
        body {
            font-family: 'Rubik', sans-serif;
        }

        @media only screen and (max-width: 640px) {

            .ms-header {
                display: none !important;
            }

            .ms-content {
                width: 100% !important;
                border-radius: 0;
            }

            .ms-content-body {
                padding: 30px !important;
            }

            .ms-footer {
                width: 100% !important;
            }

            .mobile-wide {
                width: 100% !important;
            }

            .info-lg {
                padding: 30px;
            }
        }
    </style>
    @yield('style')
</head>

<body>

    <div>
        @yield('body')
    </div>
</body>

</html>
