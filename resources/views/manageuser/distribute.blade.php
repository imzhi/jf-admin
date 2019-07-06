@extends('admin::layouts.base')

@section('title', "管理员 - 成员管理 - 分配角色")

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>分配角色</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <span>管理员管理</span>
            </li>
            <li class="breadcrumb-item">
                <span>成员管理</span>
            </li>
            <li class="breadcrumb-item active">
                <strong>分配角色</strong>
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
                    <h5>分配角色</h5>
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
                        <div class="col-md-12">
                            <form id="mform">
                                <input type="hidden" name="id" value="{{ $data->id }}">
                                <div class="form-group">
                                    <label>用户名</label>
                                    <input type="text" class="form-control" value="{{ $data->name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>勾选角色</label>
                                </div>
                                <div class="row">
                                    @foreach($list as $item)
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="role-id-{{ $item->id }}">
                                                <input type="checkbox" value="{{ $item->id }}" name="role_ids[]" id="role-id-{{ $item->id }}" class="i-checks" {{ in_array($item->id, $user_roles) ? 'checked' : '' }}> {{ $item->name }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
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
        layer.confirm('确定提交吗？', function (layerIndex) {
            layer.close(layerIndex);

            window.form_submit = $('#submit-btn');
            form_submit.prop('disabled', true);
            $.ajax({
                url: '{{ route('admin::manageuser.distribute') }}',
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
        });
        return false;
    });
</script>
@endsection
