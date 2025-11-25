<!DOCTYPE html>
<html lang="en" class="dark-theme">


<!-- auth-basic-reset-password. 11:07:54 GMT -->
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="assets/images/logo-icon.png" type="image/png" />
    <!-- loader-->
    <link href="{{ asset('frontend-assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('frontend-assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('frontend-assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend-assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('frontend-assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend-assets/css/icons.css') }}" rel="stylesheet">
    <title>Baze Live Dashboard</title>
</head>

<body>
<!-- wrapper -->
<div class="wrapper">
    @yield('content')
</div>
<!-- end wrapper -->
<script src="{{ asset('frontend-assets/js/bootstrap.bundle.min.js') }}"></script>
<!--plugins-->
<script src="{{ asset('frontend-assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('frontend-assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ asset('frontend-assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
<script src="{{ asset('frontend-assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<!--Password show & hide js -->
<script>
    $(document).ready(function () {
        $("#show_hide_password a").on('click', function (event) {
            event.preventDefault();
            if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass("bx-hide");
                $('#show_hide_password i').removeClass("bx-show");
            } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass("bx-hide");
                $('#show_hide_password i').addClass("bx-show");
            }
        });
    });
</script>
<!--app JS-->
<script src="{{ asset('frontend-assets/js/app.js') }}"></script>
</body>


</html>
