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
                    <div class="row form-group">
                        <div class="col-md-3">
                            <a href="{{ route('jfadmin::show.manageuser.create') }}" class="btn btn-default">新增</a>
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
                                    <label class="col-md-4 col-form-label">邮箱</label>
                                    <div class="col-md-8">
                                        <input type="text" name="email" class="form-control" value="{{ $request_data['email'] ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">所属角色</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="role" style="padding: 0 12px;">
                                            <option value="">选择角色</option>
                                            @foreach($roles as $item)
                                            <option value="{{ $item->id }}" {{  !empty($request_data['role']) && $request_data['role'] == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">搜索</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover golden-table">
                            <colgroup>
                                <col width="80px">
                                <col width="200px">
                                <col width="250px">
                                <col width="250px">
                                <col width="180px">
                                <col width="180px">
                                <col width="120px">
                                <col width="100px">
                                <col>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>用户名</th>
                                    <th>邮箱</th>
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
                                    <td>{{ $item->email }}</td>
                                    <td class="td-break">
                                        <div style="width: 250px;">{{ $item->getRoleNames()->implode(', ') }}</div>
                                    </td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->login_time }}</td>
                                    <td>{{ $item->login_ip }}</td>
                                    <td>{{ $item->status_text }}</td>
                                    <td>
                                        <button type="button" class="btn btn-default status-btn" data-url="{{ route('jfadmin::manageuser.status') }}">{{ $item->status_text_r }}</button>
                                        <a href="{{ route('jfadmin::show.manageuser.create', [$item->id]) }}" class="btn btn-default">编辑</a>
                                        <a href="{{ route('jfadmin::show.manageuser.distribute', [$item->id]) }}" class="btn btn-default {{ $item->is_init ? 'disabled' : '' }}">分配角色</a>
                                        @if($item->is_init)
                                        <i class="fa fa-info-circle" data-toggle="tooltip" title="禁止修改初始管理员角色"></i>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center" colspan="1000">{{ __('jfadmin::jfadmin.empty') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @include('jfadmin::layouts.pagination', ['paginator' => $list->appends($request_data)])
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
        status: function() {
            const that = this;

            $('.status-btn').click(function() {
                const $this = $(this);
                const text = $this.text().trim();

                JFA.swalQuestion('确定要' + text + '该账号吗？', function() {
                    that.ajaxBtn = $this;

                    const id = $this.closest('tr').data('id');
                    $.ajax({
                        url: $this.data('url'),
                        data: {id: id},
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
                                    location.href = redirect;
                                }
                            });
                        }
                    });
                });
            });
        },
        init: function() {
            this.status();
        }
    };
    JFA_PAGE.init();
</script>
@endsection
