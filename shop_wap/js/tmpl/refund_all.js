var order_id;
$(function ()
{
    var e = getCookie("key");
    var u = getCookie("id");
    if (!e)
    {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html"
    }
    $.getJSON(ApiUrl + "/index.php?ctl=Buyer_Service_Return&met=index&act=add&typ=json", {
        k: e,
        u:u,
        oid: getQueryString("order_id")
    }, function (a)
    {
        if(a.status == 250){
            $.sDialog({skin: "red", content: a.msg, okBtn: false, cancelBtn: false});
            return false;
        }
        a.data.WapSiteUrl = WapSiteUrl;
        $("#order-info-container").html(template.render("order-info-tmpl", a.data));
        order_id = a.data.order_id;
        allow_refund_amount = a.data.cash_limit;
        var html = '';
        $.each(a.data.reason,function(k,v){
            html+='<option value="'+ v.id+'">'+ v.order_return_reason_content+'</option>';
        });
        $('#res_content').append(html);

        $("#allow_refund_amount").html("支付金额：￥" + allow_refund_amount);
        $('input[name="refund_pic"]').ajaxUploadImage({
            url: ApiUrl + "/index.php?act=member_refund&op=upload_pic",
            data: {key: e},
            start: function (e)
            {
                e.parent().after('<div class="upload-loading"><i></i></div>');
                e.parent().siblings(".pic-thumb").remove()
            },
            success: function (e, a)
            {
                checkLogin(a.login);
                if (a.status == 250)
                {
                    e.parent().siblings(".upload-loading").remove();
                    $.sDialog({skin: "red", content: "图片尺寸过大！", okBtn: false, cancelBtn: false});
                    return false
                }
                e.parent().after('<div class="pic-thumb"><img src="' + a.datas.pic + '"/></div>');
                e.parent().siblings(".upload-loading").remove();
                e.parents("a").next().val(a.datas.file_name)
            }
        });


        $(".btn-l").click(function ()
        {
            var a = $("form").serializeArray();
            var r = {};
            r.k = e;
            r.u = u;
            r.order_id = order_id;
            for (var n = 0; n < a.length; n++)
            {
                r[a[n].name] = a[n].value
            }
            if(!r.return_cash)
            {
                $.sDialog({skin: "red", content: "请填写退款金额", okBtn: false, cancelBtn: false});
                return false
            }
            if(isNaN(r.return_cash))
            {
                $.sDialog({skin: "red", content: "请输入正确的数字", okBtn: false, cancelBtn: false});
                return false
            }
            if(!isNaN(r.return_cash) && r.return_cash < 0.01)
            {
                $.sDialog({skin: "red", content: "最小退款金额为0.01", okBtn: false, cancelBtn: false});
                return false
            }
            if(!isNaN(r.return_cash) && r.return_cash > allow_refund_amount)
            {
                $.sDialog({skin: "red", content: "最大退款金额为" + allow_refund_amount , okBtn: false, cancelBtn: false});
                return false
            }
            if (r.return_message.length == 0)
            {
                $.sDialog({skin: "red", content: "请填写退款说明", okBtn: false, cancelBtn: false});
                return false
            }

            console.info(r);
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?ctl=Buyer_Service_Return&met=addReturn&typ=json",
                data: r,
                dataType: "json",
                async: false,
                success: function (e)
                {
                    checkLogin(e.login);
                    if (e.status == 250)
                    {
                        $.sDialog({skin: "red", content: "申请退款失败", okBtn: false, cancelBtn: false});
                        return false
                    }
                    window.location.href = WapSiteUrl + "/tmpl/member/member_refund.html"
                }
            })
        })
    })
});