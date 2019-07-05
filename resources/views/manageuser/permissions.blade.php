@extends('admin.layouts.base')

@section('title', '管理员 - 权限管理')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>权限管理</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <span>管理员管理</span>
            </li>
            <li class="breadcrumb-item active">
                <strong>权限管理</strong>
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
                    <h5>权限列表</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content clearfix">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="javascript:" class="btn btn-w-m btn-default" id="detect-btn">检测</a>
                            <a href="javascript:" class="btn btn-w-m btn-default" id="group-btn">分组</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover golden-table">
                            <colgroup>
                                <col width="40px">
                                <col width="80px">
                                <col width="200px">
                                <col width="350px">
                                <col>
                                <col width="200px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th><label class="m-b-none"><input type="checkbox" class="checkbox_all i-checks" data-target=".id_class"></label></th>
                                    <th>ID</th>
                                    <th>分组</th>
                                    <th>权限</th>
                                    <th>路由</th>
                                    <th>创建时间</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($list as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td><label class="m-b-none"><input type="checkbox" name="ids[]" class="id_class i-checks" value="{{ $item->id }}"></label></td>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->permissionExtra->extra_cate }}</td>
                                    <td>{{ $item->permissionExtra->extra_name }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->created_at }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center" colspan="1000">{{ __('admin.no_data') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @include('admin.layouts.pagination', ['paginator' => $list->appends($request_data)])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('foot_js')
@parent
<script>
    // 检测
    $('#detect-btn').click(function() {
        window.form_submit = $('#detect-btn');
        form_submit.prop('disabled', true);
        $.ajax({
            url: '{{ route('admin::manageuser.permissions.detect') }}',
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

    // 分组
    $('#group-btn').click(function() {
        var ajax_data = $('.id_class').serializeArray();
        if (!ajax_data.length) {
            layer.msg('未勾选要分组的权限', {shift: 6});
            return false;
        }
        layer.prompt({
            title: '分组名称',
        }, function(value, layerPrompt, elem) {
            layer.close(layerPrompt);
            window.form_submit = $('#group-btn');
            form_submit.prop('disabled', true);
            ajax_data.push({name: 'name', value: value});
            $.ajax({
                url: '{{ route('admin::manageuser.permissions.group') }}',
                data: ajax_data,
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
    });
</script>
@endsection
