function init()
{
    typeof rowData != "undefined" ? ( initField(), initEvent())  : (initField(), initEvent())
}

function initPopBtns()
{
    var a = "add" == oper ? ["生成", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: a[0], focus: !0, callback: function ()
        {
            return cancleGridEdit(), $_form.trigger("validate"), !1
        }
    }, {id: "cancel", name: a[1]})
}

function initValidator()
{
    $_form.validator({
        messages: {
            required: "请填写{0}"
        },
        fields: {
            card_id: "required;"
        },
        display: function (a)
        {
            return $(a).closest(".row-item").find("label").text()
        },
        valid: function (form)
        {
            var a = "add" == oper ? "新增购物卡" : "修改购物卡", b = getData();
            Public.ajaxPost(SITE_URL +"?ctl=Paycen_PayInfo&typ=json&met=" + ("add" == oper ? "add" : "editCardBase"), b, function (e)
            {
                if (200 == e.status)
                {

                    parent.parent.Public.tips({content: a + "成功！"});
                    callback && "function" == typeof callback && callback(e.data, oper, window)
                }
                else
                {
                    parent.parent.Public.tips({type: 1, content: a + "失败！" + e.msg})
                }
            })
        },
        ignore: ":hidden",
        theme: "yellow_bottom",
        timely: 1,
        stopOnError: !0
    })
}

function getData()
{

    var card_id   =  $source.getValue();      //卡号
    var card_sum   =  $("#card_sum").val();   //生成卡的数量
    var data  = {
        card_id:card_id,
        card_sum:card_sum
    };
    return data;
}

function getEntriesData()
{
    for (var a = {}, b = [], c = $grid.jqGrid("getDataIDs"), d = !1, e = 0, f = c.length; f > e; e++)
    {
        var g, h = c[e], i = $grid.jqGrid("getRowData", h);
        console.info(i);
        g = {
            money: i.m,
            point: i.p,
        };
        b.push(g)
    }
    return  a.entriesData = b, a
}
function initField()
{
    if (rowData.card_id)
    {
        $("#card_id").val(rowData.card_id);
        $("#card_sum").val(rowData.card_sum);
    }
    else
    {
        $("#date").val(parent.parent.SYSTEM.startDate);
    }
}

function initEvent()
{
    var card_id = rowData.card_id;
    // console.info(card_row);
    $source = $("#card_id").combo({
        data: card_row,
        value: "id",
        text: "name",
        width: 180
    }).getCombo();
    var b = $("#date");
    b.blur(function ()
    {
        "" == b.val() && b.val(parent.parent.SYSTEM.startDate)
    }), b.datepicker({
        onClose: function ()
        {
            var a = /^\d{4}-((0?[1-9])|(1[0-2]))-\d{1,2}/;
            a.test(b.val()) || b.val("")
        }
    });

    $(document).on("click.cancle", function (a)
    {
        var b = a.target || a.srcElement;
        !$(b).closest("#grid").length > 0 && cancleGridEdit()
    }), bindEventForEnterKey(), initValidator()
}

function bindEventForEnterKey()
{
    Public.bindEnterSkip($("#base-form"), function ()
    {
        $("#grid tr.jqgrow:eq(0) td:eq(0)").trigger("click")
    })
}
function addressFmt(a, b, c)
{
    if (!c.member_contacter_address)
    {
        if(a)
            return a;
    }
    var d = {};
    return d.province = c.member_contacter_province || "",
        d.city = c.member_contacter_city || "",
        d.county = c.member_contacter_county || "",
        d.address = c.member_contacter_address || "",
        $("#" + c.id).data("addressInfo", d),
    d.province + d.city + d.county + d.address || "&#160;"
}
function addressElem()
{
    var a = $(".address")[0];
    return a
}
function addressValue(a, b, c)
{
    if ("get" === b)
    {
        var d = $.trim($(".address").val());
        return "" !== d ? d : ""
    }
    "set" === b && $("input", a).val(c)
}
function addressHandle()
{
    $(".hideFile").append($(".address").val("").unbind("focus.once"))
}
function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}

$(function (){
    $("#card_num").bind("change", function(){
        var card_n = $("#card_num").val();
        var diff_num = rowData.card_num-card_n;
        if(diff_num > rowData.card_new_num)
        {
            alert('超出可删除卡数量!');
        }

    });
})
var curRow, curCol, curArrears, api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, linksIds = [],callback = api.data.callback, card_row = api.data.card_row, defaultPage = Public.getDefaultPage(), $grid = $("#grid"), $_form = $("#manage-form");
initPopBtns(), init();

// console.info(card_row);