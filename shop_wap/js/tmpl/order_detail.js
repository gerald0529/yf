$(function () {
    var r = getCookie("key");
    var from = getQueryString('from');
    if (!r) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html"
    }
    $.getJSON(ApiUrl + "/index.php?ctl=Buyer_Order&met=physical&act=details&typ=json", {
        k: r,
        u: getCookie('id'),
        order_id: getQueryString("order_id")
    }, function (t) {
        t.data.WapSiteUrl = WapSiteUrl;
        payment_name = t.data.payment_name;
        $("#order-info-container").html(template.render("order-info-tmpl", t.data));
        $(".cancel-order").click(e);
        $(".delete-order").click(k);
        $(".sure-order").click(o);
        $(".evaluation-order").click(d);
        $(".evaluation-again-order").click(a);
        $(".goods-refund").click(c);
        $(".goods-return").click(_);
        $(".viewdelivery-order").click(l);
        $(".cancel-refund").click(g);

        $.getJSON(SiteUrl + '/index.php?ctl=Api_Wap&met=version_im&typ=json', function (r) {
            var st = r.data.im;

            if (st == 1) {
                $('.im-contact .kefu').show();
            } else {
                $('.im-contact  .kefu').hide();

            }
        });

        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=member_order&op=get_current_deliver",
            data: {key: r, order_id: getQueryString("order_id")},
            dataType: "json",
            success: function (r) {
                checkLogin(r.login);
                var e = r && r.datas;
                if (e.deliver_info) {
                    $("#delivery_content").html(e.deliver_info.context);
                    $("#delivery_time").html(e.deliver_info.time)
                }

            }
        });
        var _TimeCountDown = $(".fnTimeCountDown");
        _TimeCountDown.fnTimeCountDown();


        // 联系客服
        $('.kefu').click(function () {

            if (window.chatTo) {
                chatTo(t.data.seller_user_name.toString());

            }
//            else if(window.android)
//            {
//                if(window.android.chatTo)
//                {
//                    window.android.chatTo(t.data.seller_user_name.toString(),t.data.shop_name,t.data.shop_logo);
//                }
//
//            }
            else {
                window.location.href = WapSiteUrl + '/tmpl/im-chatinterface.html?contact_type=C&contact_you=' + t.data.seller_user_name + '&uname=' + getCookie('user_account');
            }

        })
    });

    function e() {
        var r = $(this).attr("order_id");
        $.sDialog({
            content: "订单一旦取消，将无法恢复", okFn: function () {
                t(r)
            }
        })
    }

    function t(e) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?ctl=Buyer_Order&met=orderCancel&typ=json",
            data: {order_id: e, k: r, u: getCookie('id'), user: 'buyer'},
            dataType: "json",
            success: function (e) {
                if (e.status == 200) {
                    window.location.reload();
                } else {
                    $.sDialog({skin: "red", content: "操作失败！", okBtn: false, cancelBtn: false})
                }
            }
        })
    }

    function k() {
        var v = $(this).attr("order_id");
        $.sDialog({
            content: "是否移除订单？<h6>电脑端订单回收站可找回订单！</h6>", okFn: function () {
                z(v)
            }
        })
    }

    function z(e) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?ctl=Buyer_Order&met=hideOrder&typ=json",
            data: {order_id: e, k: r, u: getCookie('id'), user: 'buyer'},
            dataType: "json",
            success: function (e) {
                if (e.status == 200) {
                    location.href = WapSiteUrl + "/tmpl/member/order_list.html";
                } else {
                    $.sDialog({skin: "red", content: "操作失败！", okBtn: false, cancelBtn: false})
                }
            }
        })
    }

    function g() {
        var g = $(this).attr("order_return_id");
        $.sDialog({
            content: "确定取消退款？", okFn: function () {
                tg(g)
            }
        })
    }

    function tg(g) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?ctl=Buyer_Service_Return&met=cancelRefund&typ=json",
            data: {order_return_id: g, k: r, u: getCookie('id')},
            dataType: "json",
            success: function (e) {
                if (e.status == 200) {
                    window.location.reload()
                } else {
                    $.sDialog({skin: "red", content: "操作失败！", okBtn: false, cancelBtn: false})
                }
            }
        })
    }

    function o() {
        var r = $(this).attr("order_id");
        $.sDialog({
            content: "确定收到了货物吗？", okFn: function () {
                i(r);
            }
        })
    }

    function i(e) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?ctl=Buyer_Order&met=confirmOrder&typ=json",
            data: {order_id: e, k: r, u: getCookie('id')},
            dataType: "json",
            success: function (r) {
                if (r.status == 200) {
                    window.location.reload();
                } else {
                    $.sDialog({skin: "red", content: "操作失败！", okBtn: false, cancelBtn: false})
                }
            }
        });
    }

    function d() {
        var r = $(this).attr("order_id");
        if (from == 'chain') {
            location.href = WapSiteUrl + "/tmpl/member/member_evaluation.html?from=chain&order_id=" + r
        }
        else {
            location.href = WapSiteUrl + "/tmpl/member/member_evaluation.html?order_id=" + r
        }
    }

    function a() {
        var r = $(this).attr("order_id");
        location.href = WapSiteUrl + "/tmpl/member/member_evaluation_again.html?order_id=" + r
    }

    function l() {
        var r = $(this).attr("order_id");
        var express_id = $(this).attr("express_id");
        var shipping_code = $(this).attr("shipping_code");
        location.href = WapSiteUrl + "/tmpl/member/order_delivery.html?order_id=" + r + '&express_id=' + express_id + '&shipping_code=' + shipping_code;
    }

    function c() {
        var r = $(this).attr("order_id");
        var e = $(this).attr("order_goods_id");
        location.href = WapSiteUrl + "/tmpl/member/refund.html?order_id=" + r + "&order_goods_id=" + e
    }

    function _() {
        var sear = new RegExp('白条支付');
        if (sear.test(payment_name)) {
            $.sDialog({
                content: '白条支付的订单，请联系商家线下退款/退货！',
                okBtn: false,
                cancelBtn: false
            });

        } else {
            var r = $(this).attr("order_id");
            var e = $(this).attr("order_goods_id");
            location.href = WapSiteUrl + "/tmpl/member/return.html?order_id=" + r + "&order_goods_id=" + e
        }

    }

    window.payOrder = function (uo, o) {
		$.ajax({
		 type: "post",
		 url:ApiUrl +'?ctl=Buyer_Order&met=checkOrder&typ=json',
		 data:{order_id:o, k: r, u: getCookie('id')},
		 dataType:"json",
		 async:false,
		 success:function(a){
				if(a.status == 250){
					 $.sDialog({skin: "red", content: a.msg, okBtn: false, cancelBtn: false})
				 }else{
					//判断有没有支付单号，如果没有支付单号就去支付中心生成支付单号，如果有直接支付
					if (uo) {
						location.href = PayCenterWapUrl + "/?ctl=Info&met=pay&uorder=" + uo;
					}
					else {
						$.ajax({
							url: ApiUrl + '?ctl=Buyer_Order&met=addUorder&typ=json',
							data: {order_id: o, k: key, u: getCookie('id')},
							dataType: "json",
							contentType: "application/json;charset=utf-8",
							async: false,
							success: function (a) {
								console.info(a);
								if (a.status == 200) {
									location.href = PayCenterWapUrl + "/?ctl=Info&met=pay&uorder=" + a.data.uorder;
								}
								else {
									if (a.msg != 'failure') {
										/*Public.tips.error(a.msg);*/
										$.sDialog({skin: "red", content: a.msg, okBtn: false, cancelBtn: false})
									} else {
										$.sDialog({skin: "red", content: '订单支付失败！', okBtn: false, cancelBtn: false})
										/*Public.tips.error('订单支付失败！');*/
									}

									//alert('订单提交失败');
								}
							},
							failure: function (a) {
								$.sDialog({skin: "red", content: '操作失败！', okBtn: false, cancelBtn: false})
								/*Public.tips.error('操作失败！');*/
							}
						});
					}
				 }
			}
		})

    }

});

function dial(tel) {
    $.sDialog({
        skin: "block",
        content: '拨打' + tel,
        okFn: function () {
            window.location.href = 'tel:' + tel;
        }
    });
}
