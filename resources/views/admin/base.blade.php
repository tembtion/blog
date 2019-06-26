<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>H+ 后台主题UI框架 - 数据表格</title>
    <link rel="shortcut icon" href="favicon.ico"> 
    <link href="{{ URL::asset('/') }}css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="{{ URL::asset('/') }}css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <!-- Data Tables -->
    <link href="{{ URL::asset('/') }}css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}css/animate.min.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}css/style.min862f.css?v=4.1.0" rel="stylesheet">
    <link href="{{ URL::asset('/') }}css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
@section('css')
@show
</head>

<body class="gray-bg">
@section('contents')
@show
    <script src="{{ URL::asset('/') }}js/jquery.min63b9.js?v=2.1.4"></script>
    <script src="{{ URL::asset('/') }}js/bootstrap.min14ed.js?v=3.3.6"></script>
    <script src="{{ URL::asset('/') }}js/plugins/jeditable/jquery.jeditable.js"></script>
    <script src="{{ URL::asset('/') }}js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="{{ URL::asset('/') }}js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="{{ URL::asset('/') }}js/content.mine209.js?v=1.0.0"></script>
    <script src="{{ URL::asset('/') }}js/plugins/toastr/toastr.min.js"></script>
    <script src="{{ URL::asset('/') }}js/plugins/sweetalert/sweetalert.min.js"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "progressBar": false,
            "positionClass": "toast-top-center",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    </script>
@section('js')
@show
</body>
</html>