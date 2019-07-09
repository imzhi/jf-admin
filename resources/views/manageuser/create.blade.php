@extends('jfadmin::layouts.base')

@section('title', "管理员 - 成员管理 - {$title}")

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{ $title }}</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <span>管理员管理</span>
            </li>
            <li class="breadcrumb-item">
                <span>成员管理</span>
            </li>
            <li class="breadcrumb-item active">
                <strong>{{ $title }}</strong>
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
                    <h5>{{ $title }}</h5>
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
                                @if($data)
                                <input type="hidden" name="id" value="{{ $data->id }}">
                                @endif
                                <h3 class="m-t-none m-b">用户资料</h3>
                                <div class="form-group">
                                    <label>用户名</label>
                                    <input type="text" class="form-control" name="name" {{ $data ? 'disabled' : '' }} value="{{ $data->name ?? '' }}" maxlength="20">
                                </div>
                                <div class="form-group">
                                    <label>密码</label>
                                    <input type="password" class="form-control" name="password" placeholder="{{ $data ? '留空则不修改' : '' }}">
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
            url: '{{ route('jfadmin::manageuser.create') }}',
            data: $this.serializeArray(),
            success: function (result) {
                if (result.err) {
                    form_submit.prop('disabled', false);
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
</script>
@endsection
