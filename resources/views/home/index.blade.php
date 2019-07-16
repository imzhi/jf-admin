@extends('jfadmin::layouts.base')

@section('title', '首页')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-md-6">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>环境参数</h5>
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover golden-table">
                            <thead>
                                <tr>
                                    <th width="40%">参数名称</th>
                                    <th width="60%">参数值</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($envs as $item)
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td class="td-break">{{ $item['value'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>引入扩展包</h5>
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover golden-table">
                            <thead>
                                <tr>
                                    <th width="40%">名称</th>
                                    <th width="60%">版本</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dependencies as $key => $item)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td>{{ $item }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
