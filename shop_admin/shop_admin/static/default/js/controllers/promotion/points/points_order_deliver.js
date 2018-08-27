/**
 * Created by Administrator on 2016/5/13.
 */

function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.points_order_id);
            return cancleGridEdit(),$("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
    $_form.validator({
//        messages: {
//            required: "请填写物流单号"
//        },
        rules: {
           check_shippingcode:[/^[\w-_]{1,30}$/,'物流单号格式仅支持数字，字母和英文横线'],
        },
        fields: {
            points_shippingcode: "required;check_shippingcode"
        },
        
        display: function (a)
        {
            return $(a).closest(".row-item").find("label").text().replace(":","");
        },
        valid: function (form)
        {
                var points_shippingcode = $.trim($("#points_shippingcode").val()),
                    points_logistics = $.trim($("#e_code").val()),
                    n = "add" == t ? "" : "发货";

            params = {
                points_order_id: e,
                points_shippingcode: points_shippingcode,
                points_logistics : points_logistics
            }
            Public.ajaxPost( SITE_URL + "?ctl=Promotion_Points&typ=json&met=pointsOrderDeliver", params, function (e)
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
    $("#points_shippingcode").val("");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();

