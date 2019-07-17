<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('jfadmin.title') }}</title>
    <link href="{{ asset('vendor/jfadmin/inspinia/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/jfadmin/inspinia/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/jfadmin/inspinia/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/jfadmin/inspinia/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/jfadmin/inspinia/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/jfadmin/jfadmin/jfadmin.css') }}" rel="stylesheet">
</head>
<body class="gray-bg" style="@if(config('jfadmin.wallpaper')) background-image: url({{ filter_var(config('jfadmin.wallpaper'), FILTER_VALIDATE_URL) ? config('jfadmin.wallpaper') : asset(config('jfadmin.wallpaper')) }}); background-size: cover; @endif">
    <div class="loginColumns animated fadeInDown">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="font-bold text-center {{ config('jfadmin.wallpaper_class') }}">{{ config('jfadmin.title') }}</h2>
                <div class="ibox-content">
                    <form id="mform" data-url="{{ route('jfadmin::login') }}">
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="用户名">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="密码">
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-7">
                                    <input type="text" class="form-control" name="captcha" placeholder="图形验证码">
                                </div>
                                <div class="col-5" style="height: 34px;">
                                    <img src="{{ captcha_src() }}" class="golden-captcha" data-toggle="tooltip" data-placement="top" title="点击刷新" onclick="$(this).prop('src', $(this).prop('src').split('?')[0] + '?' + Math.random())">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="m-b-none"><input type="checkbox" name="remember" class="i-checks"> 记住我</label>
                        </div>
                        <button type="submit" class="btn btn-primary block full-width m-b" id="submit-btn">登录</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('vendor/jfadmin/inspinia/js/jquery-2.1.1.js') }}"></script>
    <script src="{{ asset('vendor/jfadmin/inspinia/js/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/jfadmin/inspinia/js/bootstrap.js') }}"></script>
    <script src="{{ asset('vendor/jfadmin/inspinia/js/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('vendor/jfadmin/inspinia/js/plugins/fullcalendar/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/jfadmin/jfadmin/plugins/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendor/jfadmin/jfadmin/jfadmin.js') }}"></script>
    <script>
        @if(session('layer_msg'))
        JFA.swalInfo('{{ session('layer_msg') }}');
        @endif
        @if(isset($errors) && count($errors))
        JFA.swalError('{{ $errors->first() }}');
        @endif

        const JFA_PAGE = {
            ajaxBtn: null,
            ajaxStart: function() {
                this.ajaxBtn.prop('disabled', true);
            },
            ajaxStop: function() {
                this.ajaxBtn.prop('disabled', false);
            },
            ajaxError: function() {
                $('.golden-captcha').click();
            },
            submit: function() {
                const that = this;

                $('#mform').submit(function() {
                    that.ajaxBtn = $('#submit-btn');

                    $.ajax({
                        url: $(this).data('url'),
                        data: $(this).serializeArray(),
                        success: function (result) {
                            JFA.swalSuccess(result.msg, function() {
                                if (result.reload) {
                                    location.reload();
                                }
                                if (result.redirect) {
                                    location.href = result.redirect;
                                }
                            });
                        }
                    });
                    return false;
                });
            },
            init: function() {
                this.submit();
            },
        };
        JFA_PAGE.init();
    </script>
</body>
</html>
