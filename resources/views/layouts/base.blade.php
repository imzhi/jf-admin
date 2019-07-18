<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('jfadmin.title') }}</title>
    @section('head_css')
    <link href="{{ asset('vendor/jfadmin/inspinia/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/jfadmin/inspinia/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/jfadmin/inspinia/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/jfadmin/inspinia/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/jfadmin/inspinia/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/jfadmin/jfadmin/jfadmin.css') }}" rel="stylesheet">
    @show
</head>
<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <img alt="image" class="rounded-circle" src="{{ asset('vendor/jfadmin/inspinia/img/profile_small.jpg') }}"/>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="block m-t-xs font-bold">{{ $admin_user->name }}</span>
                                <span class="text-muted text-xs block">{{ $admin_user->getRoleNames()->implode(', ') }} <b class="caret"></b></span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a class="dropdown-item" href="{{ route('jfadmin::show.profile.pwd') }}">修改密码</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('jfadmin::logout') }}">退出</a></li>
                            </ul>
                        </div>
                        <div class="logo-element">
                            <a href="{{ route('jfadmin::show.index') }}">{{ config('jfadmin.caption') }}</a>
                        </div>
                    </li>

                    @if($admin_user->any(['jfadmin::show.index']))
                    <li class="{{ isset($nav_id) && $nav_id === 'home.index' ? 'active' : '' }}">
                        <a href="{{ route('jfadmin::show.index') }}"><i class="fa fa-home"></i> <span class="nav-label">首页</span></a>
                    </li>
                    @endif

                    @if($admin_user->any(['jfadmin::show.manageuser.list', 'jfadmin::show.manageuser.roles', 'jfadmin::show.manageuser.permissions']))
                    <li class="{{ isset($nav_id) && in_array($nav_id, ['manageuser.list', 'manageuser.roles', 'manageuser.permissions']) ? 'active' : '' }}">
                        <a href="javascript:"><i class="fa fa-users"></i> <span class="nav-label">管理员管理</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            @if($admin_user->can('jfadmin::show.manageuser.list'))
                            <li class="{{ isset($nav_id) && $nav_id === 'manageuser.list' ? 'active' : '' }}"><a href="{{ route('jfadmin::show.manageuser.list') }}">成员管理</a></li>
                            @endif
                            @if($admin_user->can('jfadmin::show.manageuser.roles'))
                            <li class="{{ isset($nav_id) && $nav_id === 'manageuser.roles' ? 'active' : '' }}"><a href="{{ route('jfadmin::show.manageuser.roles') }}">角色管理</a></li>
                            @endif
                            @if($admin_user->can('jfadmin::show.manageuser.permissions'))
                            <li class="{{ isset($nav_id) && $nav_id === 'manageuser.permissions' ? 'active' : '' }}"><a href="{{ route('jfadmin::show.manageuser.permissions') }}">权限管理</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if($admin_user->any(['jfadmin::show.setting.log', 'jfadmin::show.profile.pwd']))
                    <li class="{{ isset($nav_id) && in_array($nav_id, ['setting.log', 'profile.pwd']) ? 'active' : '' }}">
                        <a href="javascript:"><i class="fa fa-th-list"></i> <span class="nav-label">设置</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            @if($admin_user->can('jfadmin::show.profile.pwd'))
                            <li class="{{ isset($nav_id) && $nav_id === 'profile.pwd' ? 'active' : '' }}"><a href="{{ route('jfadmin::show.profile.pwd') }}">修改密码</a></li>
                            @endif
                            @if($admin_user->can('jfadmin::show.setting.log'))
                            <li class="{{ isset($nav_id) && $nav_id === 'setting.log' ? 'active' : '' }}"><a href="{{ route('jfadmin::show.setting.log') }}">操作日志</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message">{{ config('jfadmin.welcome') }}</span>
                        </li>
                        <li>
                            <a href="{{ route('jfadmin::logout') }}">
                                <i class="fa fa-sign-out"></i> 退出
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            @yield('content')
            <div class="footer">
                <div>
                    <strong>Powered by <a href="https://github.com/imzhi/jf-admin" target="_blank">jf-admin</a></strong>
                </div>
            </div>
        </div>
    </div>
    @section('foot_js')
    <script src="{{ asset('vendor/jfadmin/inspinia/js/jquery-2.1.1.js') }}"></script>
    <script src="{{ asset('vendor/jfadmin/inspinia/js/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/jfadmin/inspinia/js/bootstrap.js') }}"></script>
    <script src="{{ asset('vendor/jfadmin/inspinia/js/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('vendor/jfadmin/inspinia/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('vendor/jfadmin/inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('vendor/jfadmin/inspinia/js/plugins/fullcalendar/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/jfadmin/inspinia/js/inspinia.js') }}"></script>
    <script src="{{ asset('vendor/jfadmin/inspinia/js/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ asset('vendor/jfadmin/jfadmin/plugins/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendor/jfadmin/jfadmin/jfadmin.js') }}"></script>
    @show
    <script>
        @if(session('layer_msg'))
        JFA.swalInfo('{{ session('layer_msg') }}');
        @endif
        @if(isset($errors) && count($errors))
        JFA.swalError('{{ $errors->first() }}');
        @endif
    </script>
</body>
</html>
