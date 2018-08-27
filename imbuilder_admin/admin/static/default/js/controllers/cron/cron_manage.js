function initField()
{
    /*$('#cron_lasttransact').datepicker({
        controlType: 'select',
        format:'Y-m-d h:i:s'
    });
    $('#cron_nexttransact').datepicker({
        controlType: 'select',
        format:'Y-m-d h:i:s'
    });*/
    if (rowData.cron_id)
    {
        $("#cron_name").val(rowData.cron_name);
        $("#cron_script").val(rowData.cron_script);
        /*$('#cron_lasttransact').val($.DateTime.date('Y-m-d',rowData.cron_lasttransact));
        $('#cron_nexttransact').val($.DateTime.date('Y-m-d',rowData.cron_nexttransact));*/
        $('#cron_minute').val(rowData.cron_minute);
        $('#cron_hour').val(rowData.cron_hour);
        $('#cron_day').val(rowData.cron_day);
        $('#cron_month').val(rowData.cron_month);
        $('#cron_week').val(rowData.cron_week);
        if(rowData.cron_active)
        {
            $("#enable1").attr('checked', true);
            $("#enable0").attr('checked', false);
            $('[for="enable1"]').addClass('selected');
            $('[for="enable0"]').removeClass('selected');
        }
        else
        {
            $("#enable1").attr('checked', false);
            $("#enable0").attr('checked', true);
            $('[for="enable1"]').removeClass('selected');
            $('[for="enable0"]').addClass('selected');        }
        }
}
function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.cron_id);
            return cancleGridEdit(),$("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
        var cron_name = $.trim($("#cron_name").val()),
            cron_script = $.trim($("#cron_script").val()),
            /*cron_lasttransact = $.trim($('#cron_lasttransact').val()),
            cron_nexttransact = $.trim($('#cron_nexttransact').val()),*/
            cron_minute = $.trim($('#cron_minute').val()),
            cron_hour = $.trim($('#cron_hour').val()),
            cron_day = $.trim($('#cron_day').val()),
            cron_month = $.trim($('#cron_month').val()),
            cron_week = $.trim($('#cron_week').val()),
            cron_active = $.trim($('[name = enable]:checked').val()),

            n = "add" == t ? "新增计划任务" : "修改计划任务";

        params = rowData.cron_id ? {
            cron_id: e,
            cron_name: cron_name,
            cron_script: cron_script,
            /*cron_lasttransact: cron_lasttransact,
            cron_nexttransact: cron_nexttransact,*/
            cron_minute: cron_minute,
            cron_hour: cron_hour,
            cron_day: cron_day,
            cron_month: cron_month,
            cron_week: cron_week,
            cron_active: cron_active
        } : {
            cron_name: cron_name,
            cron_script: cron_script,
            /*cron_lasttransact: cron_lasttransact,
            cron_nexttransact: cron_nexttransact,*/
            cron_minute: cron_minute,
            cron_hour: cron_hour,
            cron_day: cron_day,
            cron_month: cron_month,
            cron_week: cron_week,
            cron_active: cron_active
        };
        Public.ajaxPost(SITE_URL + "?ctl=Base_Cron&typ=json&met=" + ("add" == t ? "addBaseCron" : "editBaseCron"), params, function (e)
        {
            if (200 == e.status)
            {
                parent.parent.Public.tips({content: n + "成功！"});
                callback && "function" == typeof callback && callback(e.data, t, window)
            }
            else
            {
                parent.parent.Public.tips({type: 1, content: n + "失败！" + e.msg})
            }
        })
}
function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}

var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();
initField();
