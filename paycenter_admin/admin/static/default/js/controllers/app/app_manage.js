function initField()
{
    if (rowData.app_id)
    {
        $("#app_id").val(rowData.app_id);
        $("#app_name").val(rowData.app_name);
        $("#app_url").val(rowData.app_url);
        $('#app_key').val(rowData.app_key);

        if(rowData.app_status)
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
            postData(oper, rowData.app_id);
            return cancleGridEdit(),$("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
        var app_name = $.trim($("#app_name").val()),
            app_url = $.trim($("#app_url").val()),
            app_key = $.trim($('#app_key').val()),

            app_status = $.trim($('[name = app_status]:checked').val()),

            n = "add" == t ? "新增支付应用" : "修改支付应用";

        params = rowData.app_id ? {
            user_app_id: e,
            app_name: app_name,
            app_url: app_url,
            app_key: app_key,
            app_status: app_status
        } : {
            app_name: app_name,
            app_url: app_url,
            app_key: app_key,
            app_status: app_status
        };
        Public.ajaxPost(SITE_URL + "?ctl=User_App&typ=json&met=" + ("add" == t ? "addApp" : "editApp"), params, function (e)
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
