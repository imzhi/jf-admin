<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('admin.title') }}</title>
    @section('head_css')
    <link href="{{ asset('vendor/inspinia-admin/inspinia/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/inspinia-admin/inspinia/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/inspinia-admin/inspinia/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/inspinia-admin/inspinia/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/inspinia-admin/inspinia/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/inspinia-admin/admin/css/admin.css') }}" rel="stylesheet">
    @show
</head>
<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    {{-- <li class="nav-header">
                        <div class="dropdown profile-element">
                            <span class="views-number"><a href="{{ route('admin::show.index') }}">{{ config('admin.title') }}</a></span>
                        </div>
                        <div class="logo-element">
                            <a href="{{ route('admin::show.index') }}">{{ config('admin.caption') }}</a>
                        </div>
                    </li> --}}

                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <img alt="image" class="rounded-circle" src="{{ asset('vendor/inspinia-admin/inspinia/img/profile_small.jpg') }}"/>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="block m-t-xs font-bold">{{ $user->name }}</span>
                                <span class="text-muted text-xs block">Art Director <b class="caret"></b></span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a class="dropdown-item" href="{{ route('admin::show.profile.pwd') }}">修改密码</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin::logout') }}">退出</a></li>
                            </ul>
                        </div>
                        <div class="logo-element">
                            <a href="{{ route('admin::show.index') }}">{{ config('admin.caption') }}</a>
                        </div>
                    </li>

                    @if($user->any(['admin::show.index']))
                    <li class="{{ isset($nav_id) && $nav_id === 'home.index' ? 'active' : '' }}">
                        <a href="{{ route('admin::show.index') }}"><i class="fa fa-home"></i> <span class="nav-label">首页</span></a>
                    </li>
                    @endif

                    @if($user->any(['admin::show.manageuser.list', 'admin::show.manageuser.roles', 'admin::show.manageuser.permissions']))
                    <li class="{{ isset($nav_id) && in_array($nav_id, ['manageuser.list', 'manageuser.roles', 'manageuser.permissions']) ? 'active' : '' }}">
                        <a href="javascript:"><i class="fa fa-users"></i> <span class="nav-label">管理员管理</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            @if($user->can('admin::show.manageuser.list'))
                            <li class="{{ isset($nav_id) && $nav_id === 'manageuser.list' ? 'active' : '' }}"><a href="{{ route('admin::show.manageuser.list') }}">成员管理</a></li>
                            @endif
                            @if($user->can('admin::show.manageuser.roles'))
                            <li class="{{ isset($nav_id) && $nav_id === 'manageuser.roles' ? 'active' : '' }}"><a href="{{ route('admin::show.manageuser.roles') }}">角色管理</a></li>
                            @endif
                            @if($user->can('admin::show.manageuser.permissions'))
                            <li class="{{ isset($nav_id) && $nav_id === 'manageuser.permissions' ? 'active' : '' }}"><a href="{{ route('admin::show.manageuser.permissions') }}">权限管理</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if($user->any(['admin::show.setting.log']))
                    <li class="{{ isset($nav_id) && in_array($nav_id, ['setting.log']) ? 'active' : '' }}">
                        <a href="javascript:"><i class="fa fa-th-list"></i> <span class="nav-label">设置</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            @if($user->can('admin::show.setting.log'))
                            <li class="{{ isset($nav_id) && $nav_id === 'setting.log' ? 'active' : '' }}"><a href="{{ route('admin::show.setting.log') }}">操作日志</a></li>
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
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li style="padding: 20px">
                            <span class="m-r-sm text-muted welcome-message">欢迎来到 inspinia-admin 后台管理系统</span>
                        </li>
                        <li>
                            <a href="{{ route('admin::logout') }}">
                                <i class="fa fa-sign-out"></i> 退出
                            </a>
                        </li>
                        {{-- <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                {{ $user->name }} <i class="fa  fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li>
                                    <a href="{{ route('admin::show.pwd') }}"><i class="fa fa-unlock-alt"></i> 修改密码</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="{{ route('admin::logout') }}"><i class="fa fa-sign-out"></i> 退出</a>
                                </li>
                            </ul>
                        </li> --}}
                    </ul>
                </nav>
            </div>
            @yield('content')
            <div class="footer">
                <div>
                    <strong>Copyright</strong> 聚丰 &copy; 2018
                </div>
            </div>
        </div>
    </div>
    @section('foot_js')
    <script src="{{ asset('vendor/inspinia-admin/inspinia/js/jquery-2.1.1.js') }}"></script>
    <script src="{{ asset('vendor/inspinia-admin/inspinia/js/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/inspinia-admin/inspinia/js/bootstrap.js') }}"></script>
    <script src="{{ asset('vendor/inspinia-admin/inspinia/js/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('vendor/inspinia-admin/inspinia/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('vendor/inspinia-admin/inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('vendor/inspinia-admin/inspinia/js/plugins/fullcalendar/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/inspinia-admin/inspinia/js/inspinia.js') }}"></script>
    <script src="{{ asset('vendor/inspinia-admin/inspinia/js/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ asset('vendor/inspinia-admin/admin/js/plugins/layer/layer.js') }}"></script>
    <script src="{{ asset('vendor/inspinia-admin/admin/js/admin.js') }}"></script>
    @show
    <script>
        @if(session('layer_msg'))
        layer.alert('{{ session('layer_msg') }}');
        @endif
        @if(isset($errors) && count($errors))
        layer.msg('{{ $errors->first() }}', {shift: 6});
        @endif
    </script>
</body>
</html>
