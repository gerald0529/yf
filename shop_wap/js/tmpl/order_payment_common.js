var key = getCookie("key");
var password, rcb_pay, pd_pay, payment_code;

window.payOrder = function(uo,o)
{
    //判断有没有支付单号，如果没有支付单号就去支付中心生成支付单号，如果有直接支付
    if(uo)
    {
        location.href = PayCenterWapUrl + "/?ctl=Info&met=pay&uorder=" + uo;
    }
    else
    {
        $.ajax({
            url: ApiUrl  + '?ctl=Buyer_Order&met=addUorder&typ=json',
            data:{order_id:o,k:key, u:getCookie('id')},
            dataType: "json",
            contentType: "application/json;charset=utf-8",
            async:false,
            success:function(a){
                console.info(a);
                if(a.status == 200)
                {
                    location.href = PayCenterWapUrl + "/?ctl=Info&met=pay&uorder=" + a.data.uorder;
                }
                else
                {
                    if(a.msg != 'failure')
                    {
                        $.sDialog({skin: "red", content: a.msg, okBtn: false, cancelBtn: false})
                    }else
                    {
                        $.sDialog({skin: "red", content: '订单支付失败！', okBtn: false, cancelBtn: false})
                    }

                }
            },
            failure:function(a)
            {
                $.sDialog({skin: "red", content: '操作失败！', okBtn: false, cancelBtn: false})
            }
        });
    }
}
