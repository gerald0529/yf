$(function () {

    var addressList,
        address_id,
        user_address_contact,
        user_address_phone,
        order, //后台返回所有数据
        paymentMethod = 1, //支付方式
        orderAmount = 0, //订单金额
        voucherId = 0, //店铺代金券
        voucherDiscount = 0, //店铺代金券折扣额
        redPacketId = 0, //平台红包
        redPacketDiscount = 0, //平台红包折扣额
        isUseMemberDiscount = 0, //是否使用会员折扣
        memberDiscount = 0, //会员折扣额
        voucherDiscountMsg = '', //店铺代金券信息
        redPacketDiscountMsg = '', //平台红包信息
        isUserOptOutVoucher = 0, //用户选择不使用店铺代金券
        isUserOptOutRedPacket = 0, //用户选择不使用平台红包
        $selectVoucherValve = $("#select-voucher-valve"),
        $selectPlatformRedPacketValve = $("#select-platform-red-packet-valve"),
        $voucherMsg = $selectVoucherValve.find('span'),
        $platformRedPacketMsg = $selectPlatformRedPacketValve.find('span'),
        minPurchaseQuantity = 0;




    initAddress();
    initOrder(true);
    initEvent();
    initSubmitOrder();

    //买家留言icon
    $("#buyerMessage").bind("input propertychange",function(){
       if($(this).val())
       {
            $(this).next().removeClass('hide');
       }
       else
       {
            $(this).next().addClass('hide');
       }
    });

    /*渲染店铺代金券*/
    function renderVoucher() {
        var html = template.render('voucher-script', {
            voucherList: order.voucherList,
            voucherId: voucherId,
            enabledVoucher: order.enabledVoucher,
            orderAmount: orderAmount - redPacketDiscount
        });
        $("#voucher-html").html(html);
    }

    /*渲染平台红包*/
    function renderPlatformRedPacket() {
        var html = template.render('platform-red-packet-script', {
            redPacketList: order.redPacketList,
            redPacketId: redPacketId,
            enabledRedPacket: order.enabledRedPacket,
            orderAmount: orderAmount - voucherDiscount
        });
        $("#platform-red-packet-html").html(html);
    }

    /*渲染会员折扣*/
    function renderMemberDiscount() {

        if (order.goodsPromotionStatus > 0) {
            $('#use-member-discount').parent().remove();
            $('#member-discount').removeClass("btn-ziti");
            return $('#member-discount').text('涉及活动商品不能使用会员折扣');
        }
        if (order.user_rate < 1) {
            if (isUseMemberDiscount) {
                memberDiscount = orderAmount - orderAmount * order.user_rate;
                memberDiscount = memberDiscount > 0.01 ? memberDiscount : 0;
                $('#member-discount').text("减￥"+memberDiscount.toFixed(2));
                $('#member-discount').addClass("btn-ziti");
            } else {
                $('#member-discount').text('此功能不与优惠券共用');
                $('#member-discount').removeClass("btn-ziti");
            }
        }
    }

    /*计算订单金额*/
    function changeStoreTotal() {
        renderVoucher(); //店铺优惠券
        renderPlatformRedPacket(); //平台红包
        renderMemberDiscount(); //会员折扣
        //会员折扣不与店铺优惠劵和平台红包共用
        var discountedOrderAmount = isUseMemberDiscount
            ? orderAmount - memberDiscount
            : orderAmount - voucherDiscount - redPacketDiscount;
        $('.js-store-total').text(discountedOrderAmount.toFixed(2));
    }

    /*初始化用户地址*/
    function initAddress() {
        getUserDefaultAddress();

        function getUserDefaultAddress() //获取用户默认地址
        {
            Public.ajaxPost(SiteUrl + '?ctl=Buyer_User&met=getUserConfigAddress&typ=json', {
                k: getCookie('key'),
                u: getCookie('id')
            }, function (e) {
                if (e.status == 200) {
                    var address = e.data;
                    setAddress(address);
                }
            })
        }

        function setAddress(address) {
            $('.js-add-address').hide();
            $(".js-submit").removeClass("bgc");
            var h = template.render('user-address', address);
            $('.js-user-address').empty().show().html(h);
            address_id = address.user_address_id;
            user_address_contact = address.user_address_contact;
            user_address_phone = address.user_address_phone;
        }

        /*地址列表*/
        $('.js-user-address').click(function () {

            Public.ajaxPost(SiteUrl + '?ctl=Buyer_User&met=getUserAddressList&typ=json', {
                k: getCookie('key'),
                u: getCookie('id')
            }, function (e) {
                if (e.status == 200) {
                    addressList = e.data;
                    console.info(addressList);
                    var html = template.render('list-address-add-list-script', {
                        addressList: addressList,
                        address_id: address_id
                    });
                    $("#list-address-add-list-ul").html(html);
                }
            })
        });

        // 地区选择
        $('#list-address-add-list-ul').on('click', 'li', function () {
            var selectedAddressId = $(this).data('address_id');
            for (var i = 0; i < addressList.length; i++) {
                if (addressList[i].id == selectedAddressId) {
                    setAddress(addressList[i]);
                    $('#list-address-wrapper').find('.header-l > a').click();
                    break;
                }
            }
        });

        // 地址保存
        $.sValid.init({
            rules:{
                vtrue_name:{required: true, maxlength: 20},
                vmob_phone:{required: true, mobile: true},
                varea_info:"required",
                vaddress:{required: true, maxlength: 100}
            },
            messages:{
                vtrue_name:{required: "姓名必填！", maxlength: "姓名最多20个字符！"},
                vmob_phone:{required: "手机号必填！", mobile: "手机号码不正确！"},
                varea_info:"地区必填！",
                vaddress:{required: "街道必填！", maxlength: "地址最多100个字符！"}
            },
            callback: function (eId, eMsg, eRules) {
                if (eId.length > 0) {
                    var errorHtml = "";
                    $.map(eMsg, function (idx, item) {
                        errorHtml += "<p>" + idx + "</p>";
                    });
                    errorTipsShow(errorHtml);
                } else {
                    errorTipsHide();
                }
            }
        });

        $('#add_address_form').find('.btn').click(function () {
            if ($.sValid()) {
                var param = {};
                param.user_address_contact = $('#vtrue_name').val();
                param.user_address_phone = $('#vmob_phone').val();
                param.user_address_address = $('#vaddress').val();
                param.address_area = $('#varea_info').val();
                param.province_id = province_id;
                param.city_id = city_id;
                param.area_id = area_id;
                param.user_address_default = 0;
                param.k = getCookie('key');
                param.u = getCookie('id');

                $.ajax({
                    type: 'post',
                    url: ApiUrl + "/index.php?ctl=Buyer_User&met=addAddressInfo&typ=json",
                    data: param,
                    dataType: 'json',
                    success: function (e) {
                        if (e.status == 200) {
                            setAddress(e.data);
                            $('#new-address-wrapper,#list-address-wrapper').find('.header-l > a').click();
                        }
                        else {
                            errorTipsShow("<p>" + address.msg + "</p>");
                        }
                    }
                });
            }
        });

        // 地区选择
        $('#new-address-wrapper').on('click', '#varea_info', function () {
            $.areaSelected({
                success: function (data) {
                    province_id = data.area_id_1;
                    city_id = data.area_id_2;
                    area_id = data.area_id_3;
                    area_info = data.area_info;
                    $('#varea_info').val(data.area_info);
                }
            });
        });

        $.animationLeft({ //选择地址
            valve: '.js-user-address',
            wrapper: '#list-address-wrapper',
            scroll: '#list-address-scroll'
        });

        // 地址新增
        $.animationLeft({
            valve: '#new-address-valve, .js-add-address',
            wrapper: '#new-address-wrapper'
        });
    }

    /*初始化订单信息*/
    function initOrder(isGetBestOffer) {
        function renderGoods() {
            $('.js-goods-img').attr('src', order.goods_base.goods_image);
            $('.js-goods-name').text(order.goods_base.goods_name);
            $('.js-goods-price').text(order.goods_base.now_price);
            $('#goodsNumber').val(order.cart.goods_num);
            order.goods_base.spec_str && $('.js-goods-spec').show().text(order.goods_base.spec_str);
        }

        function renderChain() {
            $('.js-chain-name').text(order.chain.chain_name);
            $('.js-store-name').text(order.shop_base.shop_name);
            $('.js-store-logo').attr('src', order.shop_base.shop_logo?order.shop_base.shop_logo:'../images/default_store_image.png');
        }

        Public.ajaxPost(SiteUrl + '?ctl=Buyer_Cart&met=confirmChain&typ=json', {
            goods_id: getQueryString('goods_id'),
            chain_id: getQueryString('chain_id'),
            voucherId: voucherId,
            redPacketId: redPacketId,
            isGetBestOffer: Number(isGetBestOffer)
        }, function (e) {
            if (e.status == 200) {
                order = e.data;
                renderChain(); //门店信息
                renderGoods(); //商品信息
                //判断店铺是否有会员折扣
                if(order.shop_base.shop_self_support == 'false' && Number(order.rate_service_status) ==1)
                {
                    $('.js-member-discount').hide();
                }
                orderAmount = order.orderAmount;
                memberDiscount = order.memberDiscount;
                minPurchaseQuantity = order.goods_base.lower_limit == undefined ? 0 : order.goods_base.lower_limit;
                voucherDiscountMsg = isUserOptOutVoucher ? '不使用店铺代金券' : '请选择使用店铺代金券';
                redPacketDiscountMsg = isUserOptOutRedPacket ? '不使用平台红包' : '请选择使用平台红包';

                if (order.goodsPromotionStatus > 0) {
                    $selectVoucherValve.removeClass('js-enabled');
                    $selectPlatformRedPacketValve.removeClass('js-enabled');
                    voucherDiscountMsg = '涉及活动商品不可用店铺代金券';
                    redPacketDiscountMsg = '涉及活动商品不可用平台红包';
                    $voucherMsg.removeClass('btn-ziti');
                    $platformRedPacketMsg.removeClass('btn-ziti');
                    $selectVoucherValve.find('img').addClass('packets-type');
                    $selectPlatformRedPacketValve.find('img').addClass('packets-type');
                } else {
                    voucherId = order.bestOffer.voucherId, //店铺代金券
                    voucherDiscount = order.bestOffer.voucherPrice, //店铺代金券折扣额
                    redPacketId = order.bestOffer.redPackId, //平台红包
                    redPacketDiscount = order.bestOffer.redPackPrice; //平台红包折扣额

                    if (isUseMemberDiscount == 0) {
                        if (order.enabledVoucher == 0) {
                            voucherDiscountMsg = '暂无可使用的代金券';
                        }

                        if (order.enabledRedPacket == 0) {
                            redPacketDiscountMsg = '暂无可使用的平台红包';
                        }
                        $voucherMsg.removeClass('btn-ziti');
                        $platformRedPacketMsg.removeClass('btn-ziti');
                    }
                    if (voucherId) {
                        for (var i in order.voucherList) {
                            if (order.voucherList[i].id == voucherId) {
                                $voucherMsg.addClass('btn-ziti');
                                voucherDiscountMsg = "满" + order.voucherList[i].voucher_limit + "减" + order.voucherList[i].voucher_price;
                                voucherDiscount = order.voucherList[i].voucher_price;
                                break;
                            }
                        }
                    }

                    if (redPacketId) {
                        for (var i in order.redPacketList) {
                            if (order.redPacketList[i].id == redPacketId) {
                                $platformRedPacketMsg.addClass('btn-ziti');
                                redPacketDiscountMsg = "满" + order.redPacketList[i].redpacket_t_orderlimit + "减" + order.redPacketList[i].redpacket_price;
                                redPacketDiscount = order.redPacketList[i].redpacket_price;
                                break;
                            }
                        }
                    }
                }

                if (isUseMemberDiscount) {
                    $voucherMsg.removeClass('btn-ziti');
                    $platformRedPacketMsg.removeClass('btn-ziti');
                    voucherDiscountMsg = '代金券不与会员折扣共用';
                    redPacketDiscountMsg = '平台红包不与会员折扣共用';
                }
                $voucherMsg.text(voucherDiscountMsg);
                $platformRedPacketMsg.text(redPacketDiscountMsg);
                changeStoreTotal(); //计算订单金额
            } else {
                $.sDialog({
                    skin: "red",
                    content: e.msg,
                    okBtn: !0,
                    cancelBtn: false,
                    okFn: function () {
                        return window.history.back();
                    }
                });
            }
        });
    }

    /*更新购物车*/
    function updateCart() {
        var num = $("#goodsNumber").val();
        Public.ajaxGet(SiteUrl + '?ctl=Buyer_Cart&met=editCartNum&typ=json', {
            cart_id: order.cart.cart_id,
            chainId: order.chain.chain_id,
            num: num
        }, function(e) {
            if (e.status == 200) {
                if (isUserOptOutRedPacket || isUserOptOutVoucher) {
                    initOrder(false);
                } else {
                    initOrder(true);
                }
            } else {
                $.sDialog({
                    skin: "red",
                    content: e.msg,
                    okBtn: false,
                    cancelBtn: false
                });
                $("#goodsNumber").val(1);
            }
        })
    }

    /*初始化事件*/
    function initEvent() {
        //商品数量事件
        $('.js-add, .js-reduce').click(function () {
            var currentGoodsNumber = +$("#goodsNumber").val();
            currentGoodsNumber = $(this).hasClass('js-add')
                ? ++currentGoodsNumber
                : --currentGoodsNumber;
            if (currentGoodsNumber < 1) {
                return $.sDialog({
                    skin: "red",
                    content: "该商品最少需要购买1件",
                    okBtn: false,
                    cancelBtn: false
                });
            }

            if (minPurchaseQuantity > 0 && currentGoodsNumber < minPurchaseQuantity) {
                return $.sDialog({
                    skin: "red",
                    content: "该商品最少需要购买" + minPurchaseQuantity + '件',
                    okBtn: false,
                    cancelBtn: false
                });
            }
            $("#goodsNumber").val(currentGoodsNumber);
            updateCart();
        });

        $("#goodsNumber").change(function () {
            var num = $(this).val();
            if (minPurchaseQuantity > 0 && num < minPurchaseQuantity) {
                $.sDialog({
                    skin: "red",
                    content: "该商品最少需要购买" + minPurchaseQuantity + '件',
                    okBtn: false,
                    cancelBtn: false
                });
                return $(this).val(minPurchaseQuantity);
            }
            if (num <= 0) {
                $.sDialog({
                    skin: "red",
                    content: "最少为一件",
                    okBtn: false,
                    cancelBtn: false
                });
                return $(this).val(1);
            }

            if (!/^[0-9]+$/.test(num)) {
                return $(this).val(1);
            }
            updateCart();
        });

        //清除买家留言
        $('.clearBuyerMessage').click(function () {
            $('#buyerMessage').val('');
            $(this).addClass("hide");
        });


        // 支付方式
        $.animationUp({
            valve: '#select-payment-valve',
            wrapper: '#select-payment-wrapper',
            scroll: ''
        });
        // 支付方式选择
        $('#payment-online,#payment-offline').click(function () {
            var text;
            if (this.id == 'payment-online') {
                paymentMethod = 1;
                text = '在线支付';
            } else {
                paymentMethod = 3;
                text = '门店付款';
            }
            $('#select-payment-valve').find('.current-con').html(text);
        });

        // 店铺代金券
        $.animationLeft({
            valve: '#select-voucher-valve.js-enabled',
            wrapper: '#select-voucher-wrapper'
        });
        $('#select-voucher-wrapper').on('click', 'input[name="voucher"]',function () {
            $('#select-voucher-wrapper').find('.header-l>a').click();

            voucherId = $('[name=voucher]:checked').val();
            isUserOptOutVoucher = Number(voucherId) ? 0 : 1;
            initOrder(false);
        });

        // 平台红包
        var handler = function () {
            event.preventDefault();
            event.stopPropagation();
        }
        $.animationLeft({
            valve: '#select-platform-red-packet-valve.js-enabled',
            wrapper: '#select-platform-red-packet-wrapper',
            openCallback:function(){
                $(".nctouch-cart-bottom").hide();
                $(".module-ziti").hide();
            }
        });
        $(document).on("click","#js-btn-back",function(){
             $(".nctouch-cart-bottom").show();
             $(".module-ziti").show();
        })
        $('#select-platform-red-packet-wrapper').on('click', 'input[name="platform-red-packet"]',function () {
            $('#select-platform-red-packet-wrapper').find('.header-l>a').click();

            redPacketId = $('[name=platform-red-packet]:checked').val();
            isUserOptOutRedPacket = Number(redPacketId) ? 0 : 1;
            initOrder(false);
        });

        //会员折扣
        $('#use-member-discount').change(function () {
            isUseMemberDiscount = this.checked;
            if (isUseMemberDiscount) {
                $voucherMsg
                    .removeClass("btn-ziti")
                    .text('代金券不与会员折扣共用');
                $platformRedPacketMsg
                    .removeClass("btn-ziti")
                    .text('平台红包不与会员折扣共用');
                $selectVoucherValve
                    .removeClass('js-enabled')
                    .find('img').addClass('packets-type');
                $selectPlatformRedPacketValve
                    .removeClass('js-enabled')
                    .find('img').addClass('packets-type');
            } else {
                voucherId && $voucherMsg.addClass("btn-ziti");
            	redPacketId && $platformRedPacketMsg.addClass("btn-ziti");
                $voucherMsg.text(voucherDiscountMsg);
                $platformRedPacketMsg.text(redPacketDiscountMsg);
                $selectVoucherValve
                    .addClass('js-enabled')
                    .find('img').removeClass('packets-type');
                $selectPlatformRedPacketValve
                    .addClass('js-enabled')
                    .find('img').removeClass('packets-type');
            }
            changeStoreTotal();
        });
    }

    /*提交订单*/
    function initSubmitOrder() {
        function isValid() {
            if (user_address_phone && user_address_contact) {
                return true;
            } else {
                $.sDialog({
                    skin: "red",
                    content: "请填写收货人信息",
                    okBtn: false,
                    cancelBtn: false
                });
                return false;
            }
        }

        function isLimit() {
            var limit = order.goods_base.upper_limit;
            if (Number(limit) && $("#goodsNumber").val() > limit) {
                $.sDialog({
                    skin: "red",
                    content: "商品数量超出上限",
                    okBtn: false,
                    cancelBtn: false
                });
                return false;
            }

            limit = order.common_base.common_limit;
            if (Number(limit) && $("#goodsNumber").val() > limit) {
                $.sDialog({
                    skin: "red",
                    content: "商品数量超出上限",
                    okBtn: false,
                    cancelBtn: false
                });
                return false;
            }

            return true;
        }
        //提交订单
        $('.js-submit').click(function () {

            if (!isValid()) {
                return false;
            }

            if (!isLimit()) {
                return false;
            }
            if(isUseMemberDiscount)
            {
                voucherId = 0;
            }
            Public.ajaxPost(SiteUrl + '?ctl=Buyer_Order&met=addOrder&typ=json', {
                k: getCookie('key'),
                u: getCookie('id'),
                receiver_phone: user_address_phone,
                receiver_name: user_address_contact,
                cart_id: [order.cart.cart_id],
                shop_id: [order.shop_base.shop_id],
                chain_id: getQueryString('chain_id'),
                pay_way_id: paymentMethod,
                remark: [$('#buyerMessage').val()],
                voucher_id: [voucherId],
                redpacket_id: redPacketId,
                is_discount: Number(isUseMemberDiscount)
            }, function (a) {
                if (a.status == 200) {
                    if (paymentMethod == 1) {
                        window.location.href = PayCenterWapUrl + "/?ctl=Info&met=pay&uorder=" + a.data.uorder + '&order_g_type=chain';
                        return false;
                    } else {
                        window.location.href = WapSiteUrl + '/tmpl/ziti_success.html';
                        return false;
                    }
                } else {
                    $.sDialog({
                        skin: "red",
                        content: a.msg,
                        okBtn: false,
                        cancelBtn: false
                    });
                }
            })
        });
    }
});
