(function(window, $) {
    const ajaxSetup = function() {
        $.ajaxSetup({
            headers: {'X-CSRF-Token': JFA.csrf_token},
            type: 'POST',
            dataType: 'JSON',
            error: function(resp, stat, text) {
                if (resp.status === 422 || resp.status === 423) {
                    const parse = $.parseJSON(resp.responseText);
                    if (parse && parse.errors) {
                        const key = Object.keys(parse.errors)[0];
                        JFA.swalError(parse.errors[key][0], {shift: 6});
                    }
                    return false;
                } else {
                    try {
                        const parse = $.parseJSON(resp.responseText);
                        if (parse && parse.err) {
                            alert(parse.msg);
                        }
                    } catch (exception) {
                        alert(JSON.stringify(resp));
                    }
                    return false;
                }
            },
        });

        $(document).ajaxStart(function(event, jqxhr, settings) {
            checkFunc('ajaxStart') && JFA_PAGE.ajaxStart();
        });

        $(document).ajaxStop(function(event, jqxhr, settings) {
            checkFunc('ajaxStop') && JFA_PAGE.ajaxStop();
        });

        $(document).ajaxError(function(event, jqxhr, settings) {
            checkFunc('ajaxError') && JFA_PAGE.ajaxError();
        });
    };

    const checkAll = function() {
        const $ibox_con = $('.ibox-content');
        const $check_all = $('.checkbox_all[type=checkbox]');
        if (!$ibox_con.length && !$check_all.length) {
            return;
        }
        $ibox_con.on('ifToggled', '.checkbox_all[type=checkbox]', function() {
            const $this = $(this);

            $($this.data('target')).iCheck($this.prop('checked') ? 'check' : 'uncheck');
        });

        $check_all.each(function(i, el) {
            const $el = $(el);
            const target = $el.data('target');
            const $target = $(target);

            $ibox_con.on('ifToggled', target, function() {
                $el.prop('checked', $target.serializeArray().length === $target.length);
                $el.iCheck('update');
            });
            $el.iCheck($target.length && $target.serializeArray().length === $target.length ? 'check' : 'uncheck');
        });
    };

    const iCheck = function() {
        const $el = $('.i-checks');
        if (!$el.length || !jQuery().iCheck) {
            return;
        }
        $el.iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    };

    const tooltip = function() {
        $('[data-toggle=tooltip]').tooltip();
    };

    const checkFunc = function(name) {
        return typeof JFA_PAGE !== 'undefined' && $.isFunction(JFA_PAGE[name]);
    };

    const JFA = {
        csrf_token: $('meta[name=csrf-token]').eq(0).attr('content'),
        daterangepicker_conf: {
            format: 'YYYY-MM-DD',
            showDropdowns: true,
            showWeekNumbers: true,
            ranges: {
                '今天': [moment(), moment()],
                '昨天': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '近7天': [moment().subtract(6, 'days'), moment()],
                '近30天': [moment().subtract(29, 'days'), moment()],
                '本月': [moment().startOf('month'), moment().endOf('month')],
                '上月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            },
            locale : {
                applyLabel : '确定',
                cancelLabel : '取消',
                fromLabel : '起始时间',
                toLabel : '结束时间',
                customRangeLabel: '自定义',
                daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月' ],
                firstDay: 1,
            }
        },
        init: function() {
            ajaxSetup();
            checkAll();
            iCheck();
            tooltip();
        },
        swalInfo: function(text, callback, options) {
            const defaults = {
                type: 'info',
                title: '信息',
                text: text,
                confirmButtonText: '确定',
                allowOutsideClick: false,
                allowEscapeKey: false,
            };
            $.extend(true, defaults, options || {});
            Swal.fire(defaults).then(function(result) {
                $.isFunction(callback) && callback();
            });
        },
        swalSuccess: function(text, callback, options) {
            const defaults = {
                type: 'success',
                title: '提示',
                text: text,
                timer: 2000,
                confirmButtonText: '确定',
                allowOutsideClick: false,
                allowEscapeKey: false,
            };
            $.extend(true, defaults, options || {});
            Swal.fire(defaults).then(function(result) {
                $.isFunction(callback) && callback();
            });
        },
        swalError: function(text, callback, options) {
            const defaults = {
                type: 'error',
                title: '错误',
                text: text,
                confirmButtonText: '确定',
                allowOutsideClick: false,
                allowEscapeKey: false
            };
            $.extend(true, defaults, options || {});
            Swal.fire(defaults).then(function(result) {
                $.isFunction(callback) && callback();
            });
        },
        swalQuestion: function(text, callback, options) {
            const defaults = {
                type: 'question',
                title: '提示',
                text: text,
                confirmButtonText: '确定',
                showCancelButton: true,
                cancelButtonText: '取消',
                allowOutsideClick: false,
                allowEscapeKey: false
            };
            $.extend(true, defaults, options || {});
            Swal.fire(defaults).then(function(result) {
                if (result.value) {
                    $.isFunction(callback) && callback();
                }
            });
        },
        swalPrompt: function(text, callback, options) {
            const defaults = {
                title: text,
                input: 'text',
                confirmButtonText: '确定',
                showCancelButton: true,
                cancelButtonText: '取消',
                allowOutsideClick: false,
                allowEscapeKey: false,
                inputValidator: function(value) {
                    if (!value) {
                        return '不能为空';
                    }
                }
            };
            $.extend(true, defaults, options || {});
            Swal.fire(defaults).then(function(result) {
                if (result.value) {
                    $.isFunction(callback) && callback(result);
                }
            });
        },
        // 生成随机字符串
        makeid: function(len) {
            let text = '';
            const possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

            for (let i = 0; i < len; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }
    };
    JFA.init();

    window.JFA = JFA;
})(window, jQuery);
