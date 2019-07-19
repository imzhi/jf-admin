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
                                        <input class="form-control" type="text" name="daterange" value="{{ $request_data['daterange'] ?? null }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary"> 搜索</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover golden-table">
                            <colgroup>
                                <col width="80px">
                                <col width="150px">
                                <col>
                                <col width="200px">
                                <col width="80px">
                                <col width="200px">
                                <col width="80px">
                                <col width="200px">
                                <col width="80px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>名称</th>
                                    <th>描述</th>
                                    <th>表模型</th>
                                    <th>表ID</th>
                                    <th>操作员模型</th>
                                    <th>操作员ID</th>
                                    <th>操作时间</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($list as $item)
                                <tr data-id="{{ $item->id }}" data-properties='{!! $item->properties !!}'>
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
<div class="modal inmodal" id="log-detail-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title">操作日志详情</h4>
            </div>
            <div class="modal-body">
                <div id="request-block" class="d-none">
                    <h3 class="m-t-none m-b">请求详情</h3>
                    <div class="table-responsive" style="max-height: 250px;">
                        <table class="table table-bordered table-hover golden-table request-table">
                            <colgroup>
                                <col width="20%">
                                <col width="80%">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td>路由别名</td>
                                    <td id="_route_" class="td-break"></td>
                                </tr>
                                <tr>
                                    <td>请求 URL</td>
                                    <td id="_url_" class="td-break"></td>
                                </tr>
                                <tr>
                                    <td>请求路径</td>
                                    <td id="_path_" class="td-break"></td>
                                </tr>
                                <tr>
                                    <td>请求方法</td>
                                    <td id="_method_"></td>
                                </tr>
                                <tr>
                                    <td>请求参数</td>
                                    <td id="_params_" class="td-break"></td>
                                </tr>
                                <tr>
                                    <td>Ajax 请求</td>
                                    <td id="_ajax_"></td>
                                </tr>
                                <tr>
                                    <td>IP</td>
                                    <td id="_ip_"></td>
                                </tr>
                                <tr>
                                    <td>User Agent</td>
                                    <td id="_ua_" class="td-break"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="field-block" class="d-none">
                    <h3 class="m-t-none m-b">字段修改</h3>
                    <div class="table-responsive" style="max-height: 250px;">
                        <table class="table table-bordered table-hover golden-table field-table">
                            <colgroup>
                                <col width="20%">
                                <col width="40%">
                                <col width="40%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>字段名</th>
                                    <th>新值</th>
                                    <th>旧值</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('foot_js')
@parent
<script src="{{ asset('vendor/jfadmin/inspinia/js/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script>
    const JFA_PAGE = {
        // 操作日志详情
        detail: function() {
            const that = this;

            $('#log-detail-modal').on('show.bs.modal', function (evt) {
                const modal_body = $(this).find('.modal-body');
                const tr = $(evt.relatedTarget).closest('tr');
                const properties = tr.data('properties');
                const attributes = properties.attributes;
                const old = properties.old;

                let html = '';
                let i = 0;
                if (attributes) {
                    $.each(attributes, function(key, val) {
                        html += '<tr>';
                        html += '<td>' + key + '</td>';
                        html += '<td class="td-break">' + that.wrapVal(val) + '</td>';
                        if (!old) {
                            if (!i) {
                                html += '<td class="td-break text-center" rowspan="' + Object.keys(attributes).length + '">暂无数据</td>';
                            }
                        } else {
                            html += '<td class="td-break">' + that.wrapVal(old[key]) + '</td>';
                        }
                        html += '</tr>';
                        i++;
                    });
                    $('#field-block').removeClass('d-none');
                }
                modal_body.find('.field-table tbody').html(html ? html : '<tr><td class="td-break text-center" colspan="1000">暂无数据</td></tr>');

                $.each(properties, function(key, val) {
                    if (key === 'attributes' || key === 'old') {
                        return true;
                    } else {
                        $('#request-block').removeClass('d-none');
                    }

                    modal_body.find('.request-table tbody').find('#' + key).html(that.wrapVal(val));
                });
            });

            $('#log-detail-modal').on('hide.bs.modal', function() {
                $('#request-block').addClass('d-none');
                $('#field-block').addClass('d-none');
            });
        },
        wrapVal: function(val) {
            if (val === null || val === true || val === false) {
                return '<span class="badge">' + val + '</span>';
            }
            if ($.isPlainObject(val)) {
                // return JSON.stringify(val, null, 2);
                return JSON.stringify(val);
            }
            return val;

        },
        init: function() {
            $('[name=daterange]').daterangepicker(JFA.daterangepicker_conf);
            this.detail();
        }
    };
    JFA_PAGE.init();
</script>
@endsection
