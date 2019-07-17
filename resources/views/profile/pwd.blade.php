@extends('jfadmin::layouts.base')

@section('title', '修改密码')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>修改密码</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">
                <strong>修改密码</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>修改密码</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-6">
                            <form id="mform" data-url="{{ route('jfadmin::profile.pwd') }}">
                                <h3 class="m-t-none m-b">用户资料</h3>
                                <div class="form-group">
                                    <label>用户名</label>
                                    <input type="text" class="form-control" name="name" value="{{ $admin_user->name }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label>邮箱</label>
                                    <input type="text" class="form-control" name="email" value="{{ $admin_user->email }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label>密码</label>
                                    <input type="password" class="form-control" name="password">
                                    <span class="form-text m-b-none">不能少于 6 个字符</span>
                                </div>
                                <div class="form-group">
                                    <label>重复密码</label>
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                                <div class="m-b-xs">
                                    <button class="btn btn-primary" type="submit" id="submit-btn">提交</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('foot_js')
@parent
<script>
    const JFA_PAGE = {
        ajaxBtn: null,
        ajaxStart: function() {
            this.ajaxBtn.prop('disabled', true);
        },
        ajaxStop: function() {
            this.ajaxBtn.prop('disabled', false);
        },
        submit: function() {
            const that = this;

            $('#mform').submit(function() {
                that.ajaxBtn = $('#submit-btn');

                $.ajax({
                    url: $(this).data('url'),
                    data: $(this).serializeArray(),
                    success: function (result) {
                        if (result.err) {
                            JFA.swalError(result.msg);
                            return false;
                        }
                        JFA.swalSuccess(result.msg, function() {
                            if (result.reload) {
                                location.reload();
                            }
                            if (result.redirect) {
                                location.href = '{!! url()->previous() !!}';
                            }
                        });
                    }
                });
                return false;
            });
        },
        init: function() {
            this.submit();
        }
    };
    JFA_PAGE.init();

</script>
@endsection
