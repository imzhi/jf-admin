@extends('jfadmin::layouts.base')

@section('title', '设置 - 操作日志')

@section('head_css')
@parent
<link href="{{ asset('vendor/jfadmin/inspinia/css/plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>操作日志</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <span>设置</span>
            </li>
            <li class="breadcrumb-item active">
                <strong>操作日志</strong>
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
                    <h5>操作日志列表</h5>
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
                    <form action="{{ route('jfadmin::show.setting.log') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">日期</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="daterange" value="{{ $request_data['daterange'] ?? '' }}">
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-primary"> 搜索</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover golden-table">
                            <thead>
                                <tr>
                                    <th width="80px">ID</th>
                                    <th width="150px">名称</th>
                                    <th>描述</th>
                                    <th width="200px">表模型</th>
                                    <th width="80px">表ID</th>
                                    <th width="200px">操作员模型</th>
                                    <th width="80px">操作员ID</th>
                                    <th width="200px">操作时间</th>
                                    <th width="80px">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($list as $item)
                                <tr data-id="{{ $item->id }}" data-changes='{!! $item->changes() !!}'>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->log_name }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td class="td-break">
                                        <div style="width: 200px;">{{ $item->subject_type }}</div>
                                    </td>
                                    <td>{{ $item->subject_id }}</td>
                                    <td class="td-break">
                                        <div style="width: 200px;">{{ $item->causer_type }}</div>
                                    </td>
                                    <td>{{ $item->causer_id }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <button type="button" class="btn btn-default log-detail-btn" data-toggle="modal" data-target="#log-detail-modal">详情</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center" colspan="1000">{{ __('jfadmin.no_data') }}</td>
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
<div class="modal inmodal" id="log-detail-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title">操作日志详情</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>字段修改</label>
                    <pre class="form-control modal-changes" style="white-space: pre-wrap;">无</pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('foot_js')
@parent
<script src="{{ asset('vendor/jfadmin/inspinia/js/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('vendor/jfadmin/inspinia/js/plugins/diff_match_patch/javascript/diff_match_patch.js') }}"></script>
<script src="{{ asset('vendor/jfadmin/inspinia/js/plugins/preetyTextDiff/jquery.pretty-text-diff.min.js') }}"></script>
<script>
    $('[name=daterange]').daterangepicker(JFA.daterangepicker_conf);

    // 操作日志详情
    $('#log-detail-modal').on('show.bs.modal', function (evt) {
        var btn = $(evt.relatedTarget);
        var modal = $(this);
        var modal_body = modal.find('.modal-body');
        var tr = btn.closest('tr');

        var changes = tr.data('changes');
        var original_con = changes.old && Object.keys(changes.old).length ? changes.old : {};
        var changed_con = changes.attributes && Object.keys(changes.attributes).length ? changes.attributes : {};
        $('.modal-body').prettyTextDiff({
            originalContent: JSON.stringify(original_con, null, 2),
            changedContent: JSON.stringify(changed_con, null, 2),
            diffContainer: '.modal-changes',
        });
    });
</script>
@endsection
