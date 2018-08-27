function initField()
{
    $("#vendor_type_name").val(rowData);
}
function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确认发送", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.vendor_type_id);
            return cancleGridEdit(),$("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
    $_form.validator({
        messages: {
            required: "请填写{0}"
        },
        fields: {
            vendor_type_name: "required;"
        },
        display: function (a)
        {
            return $(a).closest(".row-item").find("label").text().replace(":","");
        },
        valid: function (form)
        {
            var vendor_type_name = $.trim($("#vendor_type_name").val()),
                vendor_type_desc = $.trim($("#vendor_type_desc").val()),
                n = "add" == t ? "发送消息" : "发送消息";

            params = rowData.vendor_type_id ? {
                vendor_type_id: e,
                vendor_type_name: vendor_type_name,
                vendor_type_desc: vendor_type_desc
            } : {
                vendor_type_name: vendor_type_name,
                vendor_type_desc: vendor_type_desc
            };
            Public.ajaxPost("./index.php?ctl=Message_Record&met=sendMessage&typ=json", params, function (e)
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
        },
        ignore: ":hidden",
        theme: "yellow_bottom",
        timely: 1,
        stopOnError: !0
    });
}
function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}
function resetForm(t)
{
    $("#manage-form").validate().resetForm();
    $("#vendor_type_name").val("");
    $("#vendor_type_desc").val("");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data || {}, callback = api.data.callback;
initPopBtns();
initField();