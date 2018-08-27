function init()
{
    typeof rowData != "undefined" ? ( initField(), initEvent())  : (initField(), initEvent())
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
			card_id: "required;",
			card_name: "required;",
			card_num: "required;",
			money: "integer[+];",
            point: "integer[+];"
        },
        display: function (a)
        {
            return $(a).closest(".row-item").find("label").text()
        },
        valid: function (form)
        {
            var a = "add" == oper ? "新增购物卡" : "修改购物卡", b = getData(), c = b.firstLink || {};
			delete b.firstLink, 
			//http://localhost/paycenter/index.php?ctl=Index&met=PayCardList&typ=json
			Public.ajaxPost("http://localhost/paycenter/index.php?ctl=Index&typ=json&met=" + ("add" == oper ? "add" : "editCardBase"), b, function (e)
			{
                alert('sss');
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
    var prize_info = getEntriesData(), prize = prize_info.entriesData, data = cRowId ? {
        id: cRowId.card_id,
        card_name: $.trim($("#card_name").val()),
        card_num: $.trim($("#card_num").val()),
		source: source.getValue(),
        start_date: $.trim($("#start_date").val()),
        end_date: $.trim($("#end_date").val()),
        money: $.trim($("#money").val()),
        point: $.trim($("#point").val()),
        member_create_time: $.trim($("#date").val()),
        member_salesman: salesman.getValue(),
        member_salesman_name: salesman.getText(),
		link_mans: JSON.stringify(links),
		member_desc: $.trim($("#member_desc").val()),
        member_amount_money: $.trim($("#member_amount_money").val()),
        member_period_money: $.trim($("#member_period_money").val())
    } : {
        member_number: $.trim($("#member_number").val()),
        member_name: $.trim($("#member_name").val()),
        member_realname: $.trim($("#member_realname").val()),
		member_sex: sex.getValue(),
		member_level_id: level.getValue(),
		member_type_id: type.getValue(),
		member_source: source.getValue(),
        member_email: $.trim($("#member_email").val()),
        member_mobile: $.trim($("#member_mobile").val()),
        member_qq: $.trim($("#member_qq").val()),
        member_ww: $.trim($("#member_ww").val()),
        member_create_time: $.trim($("#date").val()),
        member_salesman: salesman.getValue(),
        member_salesman_name: salesman.getText(),
		link_mans: JSON.stringify(links),
		member_desc: $.trim($("#member_desc").val()),
        member_amount_money: $.trim($("#member_amount_money").val()),
        member_period_money: $.trim($("#member_period_money").val())
	};
    return data.firstLink = link_info.firstLink, data
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
 		$("#card_name").val(rowData.card_name);
        $("#start_date").val(rowData.card_start_time);
        $("#end_date").val(rowData.card_end_time);
        $('#money').val(rowData.money);
        $('#point').val(rowData.point);
        $("#card_desc").val(rowData.card_desc);
        $("#card_num").val(rowData.card_num);
        $('#card_img').attr("src",rowData.image);
        $("#card_image").val(rowData.image);
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
var curRow, curCol, curArrears, api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, linksIds = [], callback = api.data.callback, defaultPage = Public.getDefaultPage(), $grid = $("#grid"), $_form = $("#manage-form");
initPopBtns(), init();