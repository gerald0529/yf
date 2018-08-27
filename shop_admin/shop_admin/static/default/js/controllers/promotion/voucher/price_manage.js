/**
 * Created by Administrator on 2016/5/13.
 */
function initField()
{
    if (rowData.voucher_price_id)
    {
        $("#voucher_price").val(rowData.voucher_price);
        $("#voucher_price_describe").val(rowData.voucher_price_describe);
        $("#voucher_defaultpoints").val(rowData.voucher_defaultpoints);
    }
}
function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.voucher_price_id);
            return cancleGridEdit(),$("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
    $_form.validator({
		
		ignore: ":hidden",
        theme: "yellow_bottom",
        timely: 1,
        stopOnError: true,
		debug:true,
		rules: {
        //自定义一个规则，用来代替remote（注意：要把$.ajax()返回出来）
			myRemote: function(element){
				var flag = false;
				$.ajax({
					url: SITE_URL + '?ctl=Promotion_Voucher&met=checkVoucherPrice&typ=json',
					type: 'POST',
					data: element.name +'='+ element.value,
					dataType: 'json',
					async: false,
					success: function(d){
						//window.console && console.log(d);
						if(d.status ==200)
						{
							flag = true;
						}
						else 
							flag = false;
					}
				});
				return flag;
			}
		},
        messages: {
            required: "请填写{0}",
			myRemote: "{0}不能重复"
        },
        fields:rowData.voucher_price_id ? {
            voucher_price: "required;integer[+];",
            voucher_price_describe: "required;",
            voucher_defaultpoints: "required;integer[+]"
        }: {
            voucher_price: "required;integer[+];myRemote;",
            voucher_price_describe: "required;",
            voucher_defaultpoints: "required;integer[+]"
        },
        display: function (a)
        {
            return $(a).closest(".row-item").find("label").text().replace(":","");
        },
        valid: function (form)
        {
            var voucher_price = $.trim($("#voucher_price").val()),
                voucher_price_describe = $.trim($("#voucher_price_describe").val()),
                voucher_defaultpoints = $.trim($("#voucher_defaultpoints").val()),
                n = "add" == t ? "新增代金券面额" : "修改代金券面额";

            params = rowData.voucher_price_id ? {
                voucher_price_id: e,
                voucher_price: voucher_price,
                voucher_price_describe: voucher_price_describe,
                voucher_defaultpoints: voucher_defaultpoints
            } : {
                voucher_price: voucher_price,
                voucher_price_describe: voucher_price_describe,
                voucher_defaultpoints: voucher_defaultpoints
            };
            Public.ajaxPost( SITE_URL + "?ctl=Promotion_Voucher&typ=json&met=" + ("add" == t ? "addVoucherPrice" : "editVoucherPrice"), params, function (e)
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
    });
}
function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}
function resetForm(t)
{
    $("#manage-form").validate().resetForm();
    $("#voucher_price").val("");
    $("#voucher_price_describe").val("");
    $("#voucher_defaultpoints").val("");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();
initField();