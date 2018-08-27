
function init()
{
    SYSTEM = system = parent.SYSTEM;
    this.$_card_start_time = $('#card_start_time').val(system.beginDate);
    this.$_card_end_time = $('#card_end_time').val(system.endDate);
    this.$_card_start_time.datepicker();
    this.$_card_end_time.datepicker();
    typeof rowData != "undefined" ? ( initField(), initEvent())  : (initField(), initEvent());
}

function initPopBtns()
{
    var a = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
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
            card_id: "required;range[1000~9999]"
        },
        display: function (a)
        {
            return $(a).closest(".row-item").find("label").text()
        },
        valid: function (form)
        {
            var a = "add" == oper ? "新增购物卡" : "修改购物卡", b = getData();
            Public.ajaxPost(SITE_URL +"?ctl=Operation_Card&typ=json&met=" + ("add" == oper ? "addCardBase" : "editCardBase"), b, function (e)
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
    // var prize_info = getEntriesData(), prize = prize_info.entriesData, data = cRowId ? {
    //     id: cRowId.card_id,
    //     card_name: $.trim($("#card_name").val()),
    //     card_num: $.trim($("#card_num").val()),
    // source: source.getValue(),
    //     start_date: $.trim($("#start_date").val()),
    //     end_date: $.trim($("#end_date").val()),
    //     money: $.trim($("#money").val()),
    //     point: $.trim($("#point").val()),
    //     member_create_time: $.trim($("#date").val()),
    //     member_salesman: salesman.getValue(),
    //     member_salesman_name: salesman.getText(),
    // link_mans: JSON.stringify(links),
    // member_desc: $.trim($("#member_desc").val()),
    //     member_amount_money: $.trim($("#member_amount_money").val()),
    //     member_period_money: $.trim($("#member_period_money").val())
    // } : {
    //     member_number: $.trim($("#member_number").val()),
    //     member_name: $.trim($("#member_name").val()),
    //     member_realname: $.trim($("#member_realname").val()),
    // member_sex: sex.getValue(),
    // member_level_id: level.getValue(),
    // member_type_id: type.getValue(),
    // member_source: source.getValue(),
    //     member_email: $.trim($("#member_email").val()),
    //     member_mobile: $.trim($("#member_mobile").val()),
    //     member_qq: $.trim($("#member_qq").val()),
    //     member_ww: $.trim($("#member_ww").val()),
    //     member_create_time: $.trim($("#date").val()),
    //     member_salesman: salesman.getValue(),
    //     member_salesman_name: salesman.getText(),
    // link_mans: JSON.stringify(links),
    // member_desc: $.trim($("#member_desc").val()),
    //     member_amount_money: $.trim($("#member_amount_money").val()),
    //     member_period_money: $.trim($("#member_period_money").val())
    // };
    // return data.firstLink = link_info.firstLink, data
    //******************************************************************************************************************
    var card_id   =  $("#card_id").val();
    var card_name   =  $("#card_name").val();
    var card_num   =  $("#card_num").val();
    var card_start_time   =  $("#card_start_time").val();
    var card_end_time   =  $("#card_end_time").val();
    var card_desc   =  $("#card_desc").val();
    var point   =  $("#point").val();//积分
    var money   =  $("#money").val();//积分


    var data  = {
        card_id:card_id,
        card_name:card_name,
        card_num:card_num,
        card_start_time:card_start_time,
        card_end_time:card_end_time,
        card_desc:card_desc,
        point:point,
        money:money


    };
    return data;
}

/*function getData()
 {
 var cat_id = categoryTree.getValue()
 var data = {
 brand_name: $.trim($("#brand_name").val()),
 cat_id: cat_id,
 brand_pic: $.trim($("#brand_pic").attr('src')),
 brand_show_type: $.trim($('[name = brand_show_type]:checked').val()),
 brand_recommend: $.trim($('[name = brand_recommend]:checked').val()),
 brand_enable: $.trim($('[name = brand_enable]:checked').val()),
 brand_displayorder: $.trim($("#brand_displayorder").val()),
 brand_pic: $.trim($("#brand_logo").val())
 };
 cRowId ?data['id'] = cRowId['id']: '';
 return data
 }*/

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
        $("#card_name").val(rowData.card_name);
        $("#card_num").val(rowData.card_num);
        $("#card_start_time").val(rowData.card_start_time);
        $("#card_end_time").val(rowData.card_end_time);
        $("#card_desc").val(rowData.card_desc);
        $("#point").val(rowData.point);
        $("#money").val(rowData.money);

    }
    else
    {
        $("#date").val(parent.parent.SYSTEM.startDate);
    }
}

function initEvent()
{
    var app_id = rowData.app_id;

    $source = $("#source").combo({
        data: [{
            id: "0",
            name: "请选择平台"
        },{
            id: "9999",
            name: "通用"
        }, {
            id: "101",
            name: "MallBuilder"
        }, {
            id: "102",
            name: "ShopBuilder"
        }, {
            id: "103",
            name: "ImBuilder"
        }],
        value: "id",
        text: "name",
        width: 195,
        defaultSelected: ['id', app_id] || void 0
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
var curRow, curCol, curArrears, api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, linksIds = [],callback = api.data.callback, defaultPage = Public.getDefaultPage(), $grid = $("#grid"), $_form = $("#manage-form");
initPopBtns(), init();