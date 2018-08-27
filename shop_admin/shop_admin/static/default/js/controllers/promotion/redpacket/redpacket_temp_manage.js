/**
 * Created by Administrator on 2016/5/23.
 */
function initField()
{
	$('#redpacket_t_start_date').datetimepicker({lang:'ch'}).prop('readonly', 'readnoly');
	$('#redpacket_t_end_date').datetimepicker({lang:'ch'}).prop('readonly', 'readnoly');	
}

function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.redpacket_t_id);
            return cancleGridEdit(),$("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
    $_form.validator({
        rules: {
            greaterThanPrice: function(element) {
                // 返回true，则验证必填
                return (parseInt(element.value) > parseInt($("#redpacket_t_price").val()))?true:false;
            },
            changeImg:function(element)
            {
                if(!element.value){
                    return '请上传图片';
                }
            }
        },
        messages: {
            required: "",
            greaterThanPrice:"使用限额必须大于面额"
        },
        fields: {
            redpacket_t_title: "required;length[~30]",               //红包名称
            redpacket_t_type:"required;range[1~2];integer[+];",     //红包类型
            redpacket_t_start_date: "required;",                      //开始时间
            redpacket_t_end_date: "required;",                        //结束时间
            redpacket_t_price: "required;integer[+];",               //红包面额
            redpacket_t_total: "required;integer[+0];",              //可发放总数
            redpacket_t_eachlimit: "required;integer[+0];",         //每人限领
            redpacket_t_user_grade_limit: "required;integer[+0];",  //用户等级限制
            redpacket_t_orderlimit: "required;integer[+];greaterThanPrice",         //消费限额
            redpacket_t_desc: "required;length[~200];",              //红包描述
            redpacket_t_img: "required;changeImg;"//红包图片
        },
        display: function (a)
        {
            return $(a).closest(".row-item").find("label").text().replace("*","");
        },
        valid: function (form)
        {
            var redpacket_t_title               = $.trim($("#redpacket_t_title").val()),
                redpacket_t_type                = $("input[name='redpacket_t_type']:checked").val(),
                redpacket_t_start_date          = $.trim($("#redpacket_t_start_date").val()),
                redpacket_t_end_date            = $.trim($("#redpacket_t_end_date").val()),
                redpacket_t_price               = $.trim($("#redpacket_t_price").val()),
                redpacket_t_orderlimit          = $.trim($("#redpacket_t_orderlimit").val()),
                redpacket_t_total               = $.trim($("#redpacket_t_total").val()),
                redpacket_t_eachlimit           = $.trim($("#redpacket_t_eachlimit").val()),
                redpacket_t_user_grade_limit    = $.trim($("#redpacket_t_user_grade_limit").val()),
                redpacket_t_img                 = $.trim($("#redpacket_t_img").val()),
                redpacket_t_desc                = $.trim($("#redpacket_t_desc").val()),
                n = "add" == t ? "新增红包模板" : "编辑红包模板";

            params = {
                redpacket_t_title  : redpacket_t_title,
                redpacket_t_type   : redpacket_t_type,
                redpacket_t_start_date : redpacket_t_start_date,
                redpacket_t_end_date : redpacket_t_end_date,
                redpacket_t_price:redpacket_t_price,
                redpacket_t_total:redpacket_t_total,
                redpacket_t_eachlimit:redpacket_t_eachlimit,
                redpacket_t_user_grade_limit:redpacket_t_user_grade_limit,
                redpacket_t_orderlimit:redpacket_t_orderlimit,
                redpacket_t_img:redpacket_t_img,
                redpacket_t_desc:redpacket_t_desc
            };
            Public.ajaxPost( SITE_URL + "?ctl=Promotion_RedPacket&typ=json&met=addRedPacketTemp", params, function (e)
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
        ignore: "",
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
    $("#redpacket_t_title").val("");
    $("#redpacket_t_start_date").val("");
    $("#range_end").val("");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();
initField();