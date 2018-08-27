/**
 * Created by Administrator on 2016/6/23.
 */
var groupbuy_id = $("#groupbuy_id").val();

function initField()
{
   /* if(api.data.groupbuy_state == 2)
    {

    }
    $("input:radio").attr("disabled","disabled");*/
}

function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper,groupbuy_id );
            return cancleGridEdit(),$("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
    $_form.validator({

        display: function (a)
        {
            return $(a).closest(".row-item").find("label").text().replace(":","");
        },
        valid: function (form)
        {
            Public.ajaxPost( SITE_URL + "?ctl=Promotion_GroupBuy&typ=json&met=editGroupBuy" , $(form).serialize(), function (e)
            {
                if (200 == e.status)
                {
                    parent.parent.Public.tips({content: _("修改成功！")});
                    callback && "function" == typeof callback && callback(e.data, t, window)
                }
                else
                {
                    parent.parent.Public.tips({type: 1, content: _("修改失败！") + e.msg})
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
    $("#groupbuy_cat_name").val("");
    $("#groupbuy_cat_sort").val("0");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();
initField();
