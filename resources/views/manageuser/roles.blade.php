@extends('jfadmin::layouts.base')

@section('title', '管理员 - 角色管理')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>角色管理</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <span>管理员管理</span>
            </li>
            <li class="breadcrumb-item active">
                <strong>角色管理</strong>
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
                    <h5>角色列表</h5>
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
                            <a href="{{ route('jfadmin::show.manageuser.roles.create') }}" class="btn btn-default">新增</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover golden-table">
                            <colgroup>
                                <col width="80px">
                                <col width="300px">
                                <col width="200px">
                                <col>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>角色名称</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td class="td-break">
                                        <div style="width: 300px;">{{ $item->name }}</div>
                                    </td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <a href="{{ route('jfadmin::show.manageuser.roles.create', [$item->id]) }}" class="btn btn-default {{ $item->is_super ? 'disabled' : '' }}">编辑</a>
                                        <a href="{{ route('jfadmin::show.manageuser.roles.distribute', [$item->id]) }}" class="btn btn-default {{ $item->is_super ? 'disabled' : '' }}">分配权限</a>
                                        @if($item->is_super)
                                        <i class="fa fa-info-circle" data-toggle="tooltip" title="禁止编辑超级管理员角色"></i>
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
