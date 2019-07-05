var _jfla = {
    csrf_token: $('meta[name=csrf-token]').eq(0).attr('content'),
    daterangepicker_conf: {
        format: 'YYYY-MM-DD',
        minDate: '2018-01-01',
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
        this.ajaxSetup();
        this.checkAll();
        this.iCheck();
    },
    ajaxSetup: function() {
        $.ajaxSetup({
            headers: {'X-CSRF-Token': this.csrf_token},
            type: 'POST',
            dataType: 'JSON',
            error: function(resp, stat, text) {
                if (window.form_submit) {
                    form_submit.prop('disabled', false);
                }
                if (resp.status === 422) {
                    var parse = $.parseJSON(resp.responseText);
                    if (parse && parse.errors) {
                        var key = Object.keys(parse.errors)[0];
                        layer.msg(parse.errors[key][0], {shift: 6});
                    }
                    return false;
                } else {
                    try {
                        var parse = $.parseJSON(resp.responseText);
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
    },
    checkAll: function() {
        var $ibox_con = $('.ibox-content');
        $ibox_con.on('ifToggled', '.checkbox_all[type=checkbox]', function() {
            var $this = $(this);

            $($this.data('target')).iCheck($this.prop('checked') ? 'check' : 'uncheck');
        });

        $('.checkbox_all[type=checkbox]').each(function(i, el) {
            var $el = $(el);
            var target = $el.data('target');
            var $target = $(target);

            $ibox_con.on('ifToggled', target, function() {
                $el.prop('checked', $target.serializeArray().length === $target.length);
                $el.iCheck('update');
            });
            $el.iCheck($target.length && $target.serializeArray().length === $target.length ? 'check' : 'uncheck');
        });
    },
    iCheck: function() {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    },

    /**
     * 功能函数 - 生成随机字串
     */
    makeid: function(len) {
        var text = '';
        var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        for (var i = 0; i < len; i++)
          text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    },
};
_jfla.init();
