function initField()
{
    if (rowData.spec_id)
    {
        $("#spec_name").val(rowData.spec_name);
        $("#spec_displayorder").val(rowData.spec_displayorder)
    }
}
function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.spec_id);
            return cancleGridEdit(),$("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}


function postData(t, e) {
    var spec_name = $.trim($("#spec_name").val()),
        spec_displayorder = $.trim($("#spec_displayorder").val()),
        n = "add" == t ? "新增规格" : "修改规格";

    params = rowData.spec_id ? {
        spec_id: e,
        spec_name: spec_name,
        spec_displayorder: spec_displayorder
    } : {
        spec_name: spec_name,
        spec_displayorder: spec_displayorder
    };

        $_form.validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: true,
            fields: {
                'spec_name': 'required;'
            },
            display: function (a)
            {
                return $(a).closest(".row-item").find("label").text()
            },
            valid: function (form) {
                Public.ajaxPost(SITE_URL + "?ctl=Goods_Spec&typ=json&met=" + ("add" == t ? "addGoodsSpec" : "editGoodsSpec"), params, function (e) {
                    if (200 == e.status) {
                        parent.parent.Public.tips({content: n + "成功！"});
                        callback && "function" == typeof callback && callback(e.data, t, window)
                    }
                    else {
                        parent.parent.Public.tips({type: 1, content: n + "失败！" + e.msg})
                    }
                })
            },
        }).on("click", "a.submit-btn", function (e) {
            $(e.delegateTarget).trigger("validate");
        });

}
function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}
function resetForm(t)
{
    $("#manage-form").validate().resetForm();
    $("#spec_name").val("");
    $("#spec_displayorder").val("");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();
initField();