@extends('jf-admin::layouts.base')

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
                            <form id="mform">
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
    $('#mform').submit(function() {
        var $this = $(this);

        window.form_submit = $('#submit-btn');
        form_submit.prop('disabled', true);
        $.ajax({
            url: '{{ route('jf-admin::profile.pwd') }}',
            data: $this.serializeArray(),
            success: function (result) {
                if (result.err) {
                    form_submit.prop('disabled', false);
                    layer.msg(result.msg, {shift: 6});
                    return false;
                }
                layer.msg(result.msg, {icon: 1, time: 1000}, function() {
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
</script>
@endsection
