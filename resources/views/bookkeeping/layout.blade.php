<!DOCTYPE html>
<html>
    <head>
        @include('bookkeeping.include.head')
        <style>
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
            .wrapper {
                display: flex;
                flex-direction: column;
                min-height: 100%;
            }
            .body-content {
                flex: 1;
            }
            .footer {
                flex-shrink: 0;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="body-content">
                @include('bookkeeping.include.header')
                @yield('content')
            </div>
            <div class="footer clearfix">
                <p class="copyright">Copyright ©2017~2020 Vision Technology Co., Ltd. All rights reserved.</p>
            </div>
            <link rel="stylesheet" type="text/css" href="/bookkeeping/assets/js/jquery-ui-1.10.4.custom.css">
            <script type="text/javascript" src="/bookkeeping/assets/js/jquery-1.10.2.min.js"></script>
            <script type="text/javascript" src="/bookkeeping/assets/js/jquery-ui-1.10.4.custom.min.js"></script>
            <script type="text/javascript" src="/bookkeeping/assets/js/jquery-ui-timepicker-addon.js"></script>
            <script type="text/javascript" src="/bookkeeping/assets/js/bootstrap.min.js"></script>
            <script type="text/javascript" src="/bookkeeping/assets/js/jquery.fancybox.pack.js"></script>
        </div>
    </body>
</html>