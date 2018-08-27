function initField()
{
    if (rowData.id)
    {
        $('#msg_tpl_name').val(rowData.config_value);
        $('#msg_tpl_desc').val(rowData.config_comment);
    }
}
function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.id);
            return cancleGridEdit(),$("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
    var msg_tpl_name = $.trim($("#msg_tpl_name").val()),
        msg_tpl_desc = $.trim($("#msg_tpl_desc").val()),
        n = "add" == t ? "新增模版" : "修改模版";
    var msg_tpl = {};
        msg_tpl[e]= msg_tpl_name;
    var params = {msg_tpl:msg_tpl,config_type:['msg_tpl']};
    console.log(params);

    Public.ajaxPost(SITE_URL + "?ctl=Config&typ=json&met=" + ("add" == t ? "add" : "edit"), params, function (e)
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
function resetForm(t)
{
    $("#manage-form").validate().resetForm();
    $("#msg_tpl_name").val("");
    $("#msg_tpl_desc").val("");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();
initField();