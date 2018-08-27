/* 退货JS
 * @copyright  Copyright (c) 2007-2017 ShopNC Inc. (http://www.shopnc.net)
 * @license    http://www.shopnc.net
 * @link       http://www.shopnc.net
*/
var order_id, order_goods_id, goods_pay_price, goods_num;
$(function () {
    var key = getCookie("key");
    if (!key) {
        callback = window.location.href;
        login_url = UCenterApiUrl + "?ctl=Login&met=index&typ=e";
        callback = ApiUrl + "?ctl=Login&met=check&typ=e&redirect=" + encodeURIComponent(callback);
        login_url = login_url + "&from=wap&callback=" + encodeURIComponent(callback);
        window.location.href = login_url;
        return false;
    }
    $.getJSON(ApiUrl + "/index.php?ctl=Buyer_Service_Return&met=index&act=add&typ=json", {k: key, u: getCookie("id"), oid: getQueryString("order_id"), gid: getQueryString("order_goods_id")}, function (result) {
        console.info(result);
        result.data.WapSiteUrl = WapSiteUrl;
        $("#order-info-container").html(template.render("order-info-tmpl", result.data));
        /*退款和退货区别对待
        * 已付款、待自提订单或 虚拟订单  可申请退款
        * */
        if (result.data.order.order_status == 2 || result.data.order.order_status == 11) {
            $(".header-title").find("h1").html("申请退款");
            $(".reason").html("退款原因");
            $(".cash").html("退款金额");
            $(".num").html("退款数量");
            $(".remark").html("退款说明");
            $("#buyer_message").attr("placeholder", "请输入退款说明");
            return_type = 1;
        }
        /*交易完成和已签收订单可申请退货*/
        if (result.data.order.order_status == 6 || result.data.order.order_status == 5) {
            $(".header-title").find("h1").html("申请退货");
            $(".reason").html("退货原因");
            $(".cash").html("退货金额");
            $(".num").html("退货数量");
            $(".remark").html("退货说明");
            $("#buyer_message").attr("placeholder", "请输入退货说明");
            return_type = 2;
        }
        if (result.data.order.order_is_virtual == 1) {
            $(".header-title").find("h1").html("申请退款");
            $(".reason").html("退款原因");
            $(".cash").html("退款金额");
            $(".num").html("退款数量");
            $(".remark").html("退款说明");
            $("#buyer_message").attr("placeholder", "请输入退款说明");
            return_type = 3;
        }
        
        order_id = result.data.order.order_id;
        order_goods_id = result.data.goods_id;
        goods_price = result.data.goods.goods_price;
        
        // 退款原因
        var _option = "";
        for (var k in result.data.reason) {
            _option += "<option value=\"" + result.data.reason[k].order_return_reason_id + "\">" + result.data.reason[k].order_return_reason_content + "</option>";
        }
        $("#refundReason").append(_option);
        
        /* 退款原因 陈述字数控制在200字数以内*/
        $("#buyer_message").keyup(function () {
            var lengths = $(this).val().length;
            if (lengths > 199) {
                $(this).val($(this).val().substring(0, 200));
            }
            if (lengths <= 0) {
                lengths = 0;
            }
            $("#words").text(lengths);
        });
        
        // 可退金额
        goods_pay_price = result.data.return_cash;
        $("input[name=\"refund_amount\"]").val(goods_pay_price);
        // $('#returnAble').html('￥'+goods_pay_price);
        
        var sump = (Math.floor((result.data.return_goods_nums * result.data.goods.order_goods_payment_amount) * 100) / 100).toFixed(2);
        
        //如果所有商品都退款则在退款金额处填写
        if (sump < goods_pay_price) {
            $(".note").find("h6").html("");
        }
        
        // 可退数量
        goods_num = result.data.return_goods_nums;
        
        $("input[name=\"nums\"]").val(goods_num);
        if (goods_num == 1) {
            $(".numsclick").addClass("no_reduce");
            $(".numsclick").removeClass("reduce");
        }
        
        //修改退货数量
        $(".refundnum a").click(function () {
            var h = $(this).parents(".refundnum").find(".refundnums");
            if (!$(this).hasClass("no_reduce")) {
                var j = parseInt(h.val(), 10) || 1;
                var f = result.data.return_goods_nums;  //商品可退款退货的最大数量
                var i = 1;
                if ($(this).hasClass("add") && !$(this).hasClass("no_add")) {
                    $(this).prev().prev().removeClass("no_reduce");
                    $(this).prev().prev().addClass("reduce");
                    if (f > i && j >= f) {
                        $(this).removeClass("add");
                        $(this).addClass("no_add");
                    } else {
                        j++;
                    }
                } else {
                    if ($(this).hasClass("reduce") && !$(this).hasClass("no_reduce")) {
                        j--;
                        $(this).next().next().removeClass("no_add");
                        $(this).next().next().addClass("add");
                        if (j <= i) {
                            $(this).removeClass("reduce");
                            $(this).addClass("no_reduce");
                        }
                    }
                }
                h.val(j);
                //判断订单中的商品退款/退货状态，将价格显示到输出框中
                choseGoods();
            }
        });
        
        $(".refundnums").change(function () {
            var h = $(this);
            var j = this;
            var k = $(j).val();
            var f = result.data.return_goods_nums;
            var i = 1;
            var l = Math.max(Math.min(f, k.replace(/\D/gi, "").replace(/(^0*)/, "") || 1), i);
            $(j).val(l);
            var g = $(".refundnum a");
            if (l == f) {
                g.eq(1).removeClass("add");
                g.eq(1).addClass("no_add");
                if (l == i) {
                    g.eq(0).removeClass("reduce");
                    g.eq(0).addClass("no_reduce");
                } else {
                    g.eq(0).removeClass("no_reduce");
                    g.eq(0).addClass("reduce");
                }
            } else {
                if (l <= i) {
                    g.eq(0).removeClass("reduce");
                    g.eq(0).addClass("no_reduce");
                    g.eq(1).removeClass("no_add");
                    g.eq(1).addClass("add");
                } else {
                    g.eq(0).removeClass("no_reduce");
                    g.eq(0).addClass("reduce");
                    g.eq(1).removeClass("no_add");
                    g.eq(1).addClass("add");
                }
            }
            
            choseGoods();
        });
        
        window.choseGoods = function (e) {
            //判断是否退还所有可退商品
            var allchose = 1;
            
            var nums = $(".refundnums").val();  //退还商品数量
            var gnum = result.data.return_goods_nums;  //商品最多可退换数量
            
            if (nums != gnum) {
                allchose = 0;
            }
            
            var j = nums > gnum ? gnum : nums;
            var price = result.data.goods.order_goods_payment_amount;
            
            var sum = (Math.floor((j * price) * 100) / 100).toFixed(2);
            //如果所有商品都退款则在退款金额处填写
            if (allchose) {
                $("input[name=\"refund_amount\"]").val(goods_pay_price);
                // $('#returnAble').html('￥'+goods_pay_price);
                if (sum < goods_pay_price) {
                    $(".note").find("h6").html("");
                }
            } else {
                //计算退款金额
                $("input[name=\"refund_amount\"]").val(sum);
                // $('#returnAble').html('￥'+sum);
            }
            
        };
        
        $(".btn-l").click(function () {
            
            var _form_param = $("form").serializeArray();
            var param = {};
            param.key = key;
            param.order_id = order_id;
            param.goods_id = order_goods_id;
            param.return_cash = $("input[name=\"refund_amount\"]").val();
            param.return_message = $("textarea[name=\"buyer_message\"]").val();
            param.return_reason_id = $("#refundReason").val();
            param.nums = $("input[name=\"nums\"]").val();
            
            if (isNaN(parseFloat(param.return_cash)) || parseFloat(param.return_cash) > parseFloat(goods_pay_price) || parseFloat(param.return_cash) == 0) {
                $.sDialog({
                    skin: "red",
                    content: "退款金额不能为空，或不能超过可退金额",
                    okBtn: false,
                    cancelBtn: false
                });
                return false;
            }
            
            if (param.return_message.length == 0) {
                $.sDialog({
                    skin: "red",
                    content: "请填写退款说明",
                    okBtn: false,
                    cancelBtn: false
                });
                return false;
            }
            
            if (isNaN(param.nums) || isNaN(parseInt(param.nums)) || parseInt(param.nums) == 0 || parseInt(param.nums) > parseInt(goods_num)) {
                $.sDialog({
                    skin: "red",
                    content: "退货数据不能为空，或不能超过可退数量",
                    okBtn: false,
                    cancelBtn: false
                });
                return false;
            }
            
            param.k = key;
            param.u = getCookie("id");
            
            // 退货申请提交
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?ctl=Buyer_Service_Return&met=addReturn&typ=json",
                data: param,
                dataType: "json",
                async: false,
                success: function (result) {
                    checkLogin(result.login);
                    if (result.status == 200) {
                        $.sDialog({
                            skin: "red",
                            content: result.msg,
                            okBtn: false,
                            cancelBtn: false
                        });
                    } else {
                        $.sDialog({
                            skin: "red",
                            content: result.msg,
                            okBtn: false,
                            cancelBtn: false
                        });
                        return false;
                    }
                    
                    if (return_type == 1) {
                        window.location.href = WapSiteUrl + "/tmpl/member/member_refund.html";
                    } else if (return_type == 2) {
                        window.location.href = WapSiteUrl + "/tmpl/member/member_return.html";
                    } else {
                        window.location.href = WapSiteUrl + "/tmpl/member/vr_member_refund.html";
                    }
                    
                }
            });
            
        });
    });
});
