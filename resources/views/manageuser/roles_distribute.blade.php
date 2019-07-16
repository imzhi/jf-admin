@extends('jfadmin::layouts.base')

@section('title', "管理员 - 角色管理 - 分配权限")

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>分配权限</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <span>管理员管理</span>
            </li>
            <li class="breadcrumb-item">
                <span>角色管理</span>
            </li>
            <li class="breadcrumb-item active">
                <strong>分配权限</strong>
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
                    <h5>分配权限</h5>
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
                            <form id="mform" data-url="{{ route('jfadmin::manageuser.roles.distribute') }}">
                                <input type="hidden" name="id" value="{{ $data->id }}">
                                <div class="form-group">
                                    <label>角色名称</label>
                                    <input type="text" class="form-control" value="{{ $data->name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>勾选权限</label>
                                </div>
                                @foreach($list as $key => $item)
                                <div class="form-group">
                                    <label class="text-success" for="group-id-{{ $loop->index }}">
                                        <input type="checkbox" id="group-id-{{ $loop->index }}" class="i-checks checkbox_all" data-target=".id_class-{{ $loop->index }}"> {{ $key }}
                                    </label>
                                </div>
                                <div class="row">
                                    @foreach($item as $v_item)
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="permission-id-{{ $v_item->id }}">
                                                <input type="checkbox" value="{{ $v_item->id }}" name="permission_ids[]" id="permission-id-{{ $v_item->id }}" class="id_class-{{ $loop->parent->index }} i-checks" {{ in_array($v_item->id, $role_permissions) ? 'checked' : '' }}> {{ $v_item->permissionExtra->extra_name }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
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
                const $this = $(this);

                JFA.swalQuestion('确定提交吗？', function() {
                    that.ajaxBtn = $('#submit-btn');

                    $.ajax({
                        url: $this.data('url'),
                        data: $this.serializeArray(),
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
