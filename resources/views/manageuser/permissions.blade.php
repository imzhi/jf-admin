@extends('jfadmin::layouts.base')

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
                    <div class="row form-group">
                        <div class="col-md-12">
                            <a href="javascript:" class="btn btn-default" id="detect-btn" data-url="{{ route('jfadmin::manageuser.permissions.detect') }}">检测</a>
                            <a href="javascript:" class="btn btn-default" id="group-btn" data-url="{{ route('jfadmin::manageuser.permissions.group') }}">分组</a>
                        </div>
                    </div>
                    <form action="{{ route('jfadmin::show.manageuser.permissions') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">所属分组</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="cate" style="padding: 0 12px;">
                                            <option value="">选择分组</option>
                                            @foreach($permission_extra_cates as $item)
                                            <option value="{{ $item }}" {{  !empty($request_data['cate']) && $request_data['cate'] == $item ? 'selected' : '' }}>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">权限</label>
                                    <div class="col-md-8">
                                        <input type="text" name="name" class="form-control" value="{{ $request_data['name'] ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">路由</label>
                                    <div class="col-md-8">
                                        <input type="text" name="route" class="form-control" value="{{ $request_data['route'] ?? '' }}">
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
                                <col width="40px">
                                <col width="80px">
                                <col width="200px">
                                <col>
                                <col width="380px">
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
                                    <td class="td-break">
                                        <div style="width: 380px;">{{ $item->name }}</div>
                                    </td>
                                    <td>{{ $item->created_at }}</td>
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
        // 检测
        detect: function() {
            const that = this;

            $('#detect-btn').click(function() {
                that.ajaxBtn = $(this);

                $.ajax({
                    url: $(this).data('url'),
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
                        }, {timer: 4000});
                    }
                });
            });
        },
        // 分组
        group: function() {
            const that = this;

            $('#group-btn').click(function() {
                const $this = $(this);
                const ajax_data = $('.id_class').serializeArray();

                if (!ajax_data.length) {
                    JFA.swalError('未勾选要分组的权限');
                    return false;
                }

                JFA.swalPrompt('分组名称', function(swalResult) {
                    that.ajaxBtn = $this;
                    ajax_data.push({name: 'name', value: swalResult.value});

                    $.ajax({
                        url: $this.data('url'),
                        data: ajax_data,
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
                })
            });
        },
        init: function() {
            this.detect();
            this.group();
        }
    };
    JFA_PAGE.init();
</script>
@endsection
