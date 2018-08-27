/**
 * Created by Administrator on 2016/5/23.
 */
function initField()
{
    if (rowData.range_id)
    {
        $("#range_name").val(rowData.range_name);
        $("#range_start").val(rowData.range_start);
        $("#range_end").val(rowData.range_end);
    }
}
function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.range_id);
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
            range_name: "required;",
            range_start: "required;integer[+0];",
            range_end: "required;integer[+0];"
        },
        display: function (a)
        {
            return $(a).closest(".row-item").find("label").text().replace(":","");
        },
        valid: function (form)
        {
            var range_name = $.trim($("#range_name").val()),
                range_start = $.trim($("#range_start").val()),
                range_end = $.trim($("#range_end").val()),
                n = "add" == t ? "新增团购价格区间" : "编辑团购价格区间";

            params = rowData.range_id ? {
                range_id: e,
                range_name  : range_name,
                range_start : range_start,
                range_end   : range_end
            } : {
                range_name  : range_name,
                range_start : range_start,
                range_end   : range_end
            };
            Public.ajaxPost( SITE_URL + "?ctl=Promotion_GroupBuy&typ=json&met=" + ("add" == t ? "addPriceRange" : "editPriceRange"), params, function (e)
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
    $("#range_name").val("");
    $("#range_start").val("");
    $("#range_end").val("");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();
initField();