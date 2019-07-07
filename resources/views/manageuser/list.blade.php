@extends('jfadmin::layouts.base')

@section('title', '管理员 - 成员管理')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>成员管理</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <span>管理员管理</span>
            </li>
            <li class="breadcrumb-item active">
                <strong>成员管理</strong>
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
                    <h5>成员列表</h5>
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
                        <div class="col-md-3">
                            <a href="{{ route('jfadmin::show.manageuser.create') }}" class="btn btn-w-m btn-default">新增</a>
                        </div>
                    </div>
                    <form action="{{ route('jfadmin::show.manageuser.list') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">用户名</label>
                                    <div class="col-md-8">
                                        <input type="text" name="account" class="form-control" value="{{ $request_data['account'] ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">所属角色</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <select class="form-control" name="role" style="padding: 0 12px;">
                                                <option value="">选择角色</option>
                                                @foreach($roles as $item)
                                                <option value="{{ $item->id }}" {{  !empty($request_data['role']) && $request_data['role'] == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-primary">搜索</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover golden-table">
                            <colgroup>
                                <col width="80px">
                                <col width="150px">
                                <col width="220px">
                                <col width="200px">
                                <col width="200px">
                                <col width="120px">
                                <col width="100px">
                                <col>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>用户名</th>
                                    <th>所属角色</th>
                                    <th>创建时间</th>
                                    <th>登录时间</th>
                                    <th>登录IP</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($list as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        {{ $item->getRoleNames()->implode(', ') }}
                                    </td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->login_time }}</td>
                                    <td>{{ $item->login_ip }}</td>
                                    <td>{{ $status_rels[$item->status] ?? __('jfadmin.unknow') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-default status-btn" data-status="{{ 1 - $item->status }}">{{ $status_rels[1 - $item->status] ?? __('jfadmin.unknow') }}</button>
                                        <a href="{{ route('jfadmin::show.manageuser.create', [$item->id]) }}" class="btn btn-default">编辑</a>
                                        <a href="{{ route('jfadmin::show.manageuser.distribute', [$item->id]) }}" class="btn btn-default">分配角色</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center" colspan="1000">{{ __('jfadmin.no_data') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @include('jfadmin::layouts.pagination', ['paginator' => $list->appends($request_data)])
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
    $('.status-btn').click(function() {
        var btn = $(this);
        var text = btn.text().trim();
        layer.confirm('确定要' + text + '该账号吗？', function (layerIndex) {
            layer.close(layerIndex);

            var user_id = btn.closest('tr').data('id');
            var status = btn.data('status');

            window.form_submit = btn;
            form_submit.prop('disabled', true);
            $.ajax({
                url: '{{ route('jfadmin::manageuser.status') }}',
                data: {user_id: user_id, status: status},
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
                            location.href = redirect;
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
