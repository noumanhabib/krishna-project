<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="" name="description" />
    <meta content="webthemez" name="author" />
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <title>RenewHub | @yield('title')</title>
    <!-- Bootstrap Styles-->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}

    <link href="{{ url('assets/css/bootstrap.css')}}" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="{{ url('assets/css/font-awesome.css')}}" rel="stylesheet" />
    <!-- Morris Chart Styles-->
    <link href="{{ url('assets/js/morris/morris-0.4.3.min.css')}}" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="{{ url('assets/css/custom-styles.css')}}" rel="stylesheet" />
    <!-- admin custom style css -->
    <link href="{{ url('assets/css/admin_style.css')}}" rel="stylesheet" />
    <!-- Google Fonts-->







    {{--
    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkbox3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}"> --}}




    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    <link rel="stylesheet" href="{{ url('assets/js/Lightweight-Chart/cssCharts.css')}}">
    {{--
    <link rel="stylesheet" href="{{ asset('assets/js/Lightweight-Chart/cssCharts.css') }}"> --}}

    <link rel="stylesheet" href="{{ url('assets/css/toastr.min.css')}}">
    {{--
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}"> --}}









    <style type="text/css">
        .navbar-side {
            overflow: auto;
            min-height: 100vh;
            max-height: 100vh;
            scrollbar-color: #ed6b5d;
            scrollbar-width: thin;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#" style="display:flex;padding: 0px 15px;">
                    <strong><span style="font-size: 18px"> <img style="width:40px"
                                src="{{ asset('assets/img/loog.jpg') }}">
                            RenewHub</span></strong></a>
                <div id="sideNav" href="javascript:void(0)">
                    <i class="fa fa-bars icon"></i>
                </div>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();"><i
                                    class="fa fa-sign-out fa-fw"></i> Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
        </nav>
        <!--/. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    {!! Helper::menu() !!} 
                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE  -->
        @yield('content')
        <footer>
            <p>@2021 All right reserved.<a href="https://www.cityinnovates.com/">City Innovates</a></p>
        </footer>

    </div>
    <!-- /. WRAPPER  -->
    <!-- JS Scripts-->
    <!-- jQuery Js -->
    <script src="{{url('assets/js/jquery-1.10.2.js')}}"></script>
    {{-- <script src="{{ asset('js/jquery-1.10.2.js') }}"></script> --}}
    <!-- Bootstrap Js -->
    <script src="{{url('assets/js/bootstrap.min.js')}}"></script>
    {{-- <script src="{{ asset('js/bootstrap.min.js') }}"></script> --}}

    <!-- Metis Menu Js -->
    <script src="{{url('assets/js/jquery.metisMenu.js')}}"></script>
    {{-- <script src="{{ asset('js/jquery.metisMenu.js') }}"></script> --}}
    <!-- Morris Chart Js -->
    <script src="{{url('assets/js/morris/raphael-2.1.0.min.js')}}"></script>
    {{-- <script src="{{ asset('js/morris/raphael-2.1.0.min.js') }}"></script> --}}


    <!--<script src="{{url('assets/js/morris/morris.js')}}"></script>-->


    <script src="{{url('assets/js/easypiechart.js')}}"></script>
    {{-- <script src="{{ asset('js/easypiechart.js') }}"></script> --}}

    <script src="{{url('assets/js/easypiechart-data.js')}}"></script>
    {{-- <script src="{{ asset('js/easypiechart-data.js') }}"></script> --}}

    <script src="{{url('assets/js/Lightweight-Chart/jquery.chart.js')}}"></script>
    {{-- <script src="{{ asset('js/Lightweight-Chart/jquery.chart.js') }}"></script> --}}

    <!-- Custom Js -->
    <script src="{{url('assets/js/custom-scripts.js')}}"></script>
    {{-- <script src="{{ asset('js/custom-scripts.js') }}"></script> --}}

    <script src="{{url('assets/js/toastr.min.js')}}"></script>
    {{-- <script src="{{ asset('js/toastr.min.js') }}"></script> --}}
    <!-- DATA TABLE SCRIPTS -->

    <script src="{{url('assets/js/dataTables/jquery.dataTables.js')}}"></script>
    {{-- <script src="{{ asset('js/dataTables/jquery.dataTables.js') }}"></script> --}}

    <script src="{{url('assets/js/dataTables/dataTables.buttons.min.js')}}"></script>
    {{-- <script src="{{ asset('js/dataTables/dataTables.buttons.min.js') }}"></script> --}}


    <script src="{{url('assets/js/dataTables/dataTables.bootstrap.js')}}"></script>
    {{-- <script src="{{ asset('js/dataTables/dataTables.bootstrap.js') }}"></script> --}}

    <script>
        $('.data_tablelist').DataTable();
                                                        @if (session('sucess'))
                                                        toastr.success('{!!session('msg')!!}');
                                                @endif
                                                        $("#sideNav").click(function () {
                                                    $("#sideNav").toggleClass("closed");
                                                    $(".navbar-side").toggleClass("nav_collapsed");
                                                    $("#page-wrapper").toggleClass("page_collapsed");
                                                });


    </script>

</body>

</html>