<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LassLister</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset('admin/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admin/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="{{ asset('admin/dist/css/skins/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/dist/css/custome-css.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/dist/css/bootstrap-datetimepicker.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery 2.2.3 -->
    <script src="{{ asset('admin/dist/js/jquery-2.2.3.min.js') }}"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="{{ asset('admin/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/js/numeric.js') }}"></script>
    <script src="{{ asset('/js/bootstrap-datetimepicker.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('admin/dist/js/app.min.js') }}"></script>
    <script src="{{ asset('/js/angular/angular.js') }}"></script>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <!-- Main Header -->
    <header class="main-header">
        <!-- Logo -->
        <a href="{{ URL::to('dashboard') }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>Lass.</b>no</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Lass.</b>no</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">{{ trans('list.toggleNavigation') }}</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="user-image" alt="User Image">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ Auth::user()->first_name.' '.Auth::user()->last_name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">

                                <p>
                                    {{ Auth::user()->name }}
                                    <small>Member since {{ date('F d, Y', strtotime(Auth::user()->created_at)) }}</small>
                                </p>
                            </li>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">

                            @if(Auth::user()->isImpersonating())
                                <div class="pull-left">
                                    <a href="{{ URL::to('/dashboard/users/stop') }}" class="btn btn-default btn-flat">Logout {{ trans('list.manager') }}</a>
                                </div>
                            @else
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">{{ trans('list.profile') }}</a>
                                </div>
                            @endif
                                <div class="pull-right">
                                    <a href="#" class="btn btn-default btn-flat logout-click">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="hide">
                    <form method="post" id="logoutForm" action="{{ URL::route('logout') }}">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- Sidebar user panel (optional) -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->first_name.' '.Auth::user()->last_name}}</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('list.online') }}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <!-- Optionally, you can add icons to the links -->
                @if(Auth::user()->isImpersonating())
                    <li {!! (Request::is('dashboard/manager') ? 'class="active"' : '') !!}>
                        <a href="{{ URL::to('dashboard/manager') }}"><i class="fa fa-tachometer"></i> <span>{{ trans('list.manager') }} {{ trans('list.dashboard') }}</span></a>
                    </li>
                @else
                    <li {!! (Request::is('dashboard') ? 'class="active"' : '') !!}>
                        <a href="{{ URL::to('dashboard') }}"><i class="fa fa-tachometer"></i> <span>{{ trans('list.dashboard') }}</span></a>
                    </li>
                @endif

                <li {!! (Request::is('dashboard/lasslister') || Request::is('dashboard/lasslister/*') ? 'class="active"' : '') !!}>
                    <a href="{{ URL::route('lasslister') }}"><i class="fa fa-list"></i> <span>{{ trans('list.truckList') }}</span></a>
                </li>
                <li {!! (Request::is('dashboard/customers') || Request::is('dashboard/project/*') ? 'class="active"' : '') !!}>
                    <a href="{{ URL::route('listCustomers') }}"><i class="fa fa-folder"></i> <span>{{ trans('list.customersAndProjects') }}</span></a>
                </li>
                <li class="treeview {!! (Request::is('dashboard/settings') || Request::is('dashboard/settings/*') ? 'active' : '') !!}">
                    <a href="#"><i class="fa fa-cog"></i> <span>{{ trans('list.settings') }}</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li {!! (Request::is('dashboard/settings/driver') ? 'class="active"' : '') !!}><a href="{{ URL::route('listDrivers') }}"><i class="fa fa-user"></i> <span>{{ trans('list.manage') }} {{ trans('list.drivers') }}</span></a></li>
                        <li {!! (Request::is('dashboard/settings/scale') ? 'class="active"' : '') !!}><a href="{{ URL::route('scale') }}"><i class="fa fa-balance-scale"></i> <span>{{ trans('list.manage') }} {{ trans('list.scale') }}</span></a></li>
                        <li {!! (Request::is('dashboard/settings/massType') ? 'class="active"' : '') !!}><a href="{{ URL::route('massType') }}"><i class="fa fa-square"></i> <span>{{ trans('list.manage') }} {{ trans('list.massTypes') }}</span></a></li>
                        <li {!! (Request::is('dashboard/settings/vehicle') ? 'class="active"' : '') !!}><a href="{{ URL::route('vehicle') }}"><i class="fa fa-truck"></i> <span>{{ trans('list.manage') }} {{ trans('list.vehicles') }}</span></a></li>
                        <li {!! (Request::is('dashboard/settings/company') ? 'class="active"' : '') !!}><a href="{{ URL::route('getCompany') }}"><i class="fa fa-building-o"></i> <span>{{ trans('list.manage') }} {{ trans('list.company') }}</span></a></li>
                    </ul>
                </li>
                @if(Auth::user()->isImpersonating())
                    <li>
                        <a href="{{ url('/dashboard') }}"><i class="fa fa-link"></i> <span>{{ trans('list.switchToAdmin') }}</span></a>
                    </li>
                @endif
            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            @yield('header')
        </section>
        <!-- Main content -->
        <section class="content">

            @yield('content')

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            Lass.no
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2017 <a href="#">Lass.no</a>.</strong> All rights reserved.
    </footer>

</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<script>
    $(document).on('click', '.logout-click', function(e){
        $("#logoutForm").submit();
        e.preventDefault();
    });
</script>
@yield('script')
</body>
</html>
