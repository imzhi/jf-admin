@extends('jfadmin::layouts.base')

@section('title', "管理员 - 角色管理 - {$title}")

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{ $title }}</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <span>管理员管理</span>
            </li>
            <li class="breadcrumb-item">
                <span>角色管理</span>
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
                        <div class="col-sm-6">
                            <form id="mform" data-url="{{ route('jfadmin::manageuser.roles.create') }}">
                                @if($data)
                                <input type="hidden" name="id" value="{{ $data->id }}">
                                @endif
                                <h3 class="m-t-none m-b">角色资料</h3>
                                <div class="form-group">
                                    <label>角色名称</label>
                                    <input type="text" class="form-control" name="name" value="{{ $data->name ?? '' }}" maxlength="20">
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
    const JFA_PAGE = {
        ajaxBtn: null,
        ajaxStart: function() {
            this.ajaxBtn.prop('disabled', true);
        },
        ajaxStop: function() {
            this.ajaxBtn.prop('disabled', false);
        },
        submit: function() {
            const that = this;

            $('#mform').submit(function() {
                that.ajaxBtn = $('#submit-btn');

                $.ajax({
                    url: $(this).data('url'),
                    data: $(this).serializeArray(),
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
                return false;
            });
        },
        init: function() {
            this.submit();
        }
    };
    JFA_PAGE.init();
</script>
@endsection
