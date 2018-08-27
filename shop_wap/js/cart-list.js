$(function () {
    template.helper('isEmpty', function (o) {
        for (var i in o) {
            return false;
        }
        return true;
    });
    template.helper('decodeURIComponent', function (o) {
        return decodeURIComponent(o);
    });
    var key = getCookie('key');
    if (!key) {
        var goods = decodeURIComponent(getCookie('goods_cart'));
        if (goods != null) {
            var goodsarr = goods.split('|');
        } else {
            goodsarr = {};
        }

        var cart_list = new Array();
        var sum = 0;
        if (goodsarr.length > 0) {
            for (var i = 0; i < goodsarr.length; i++) {
                var info = goodsarr[i].split(',');
                if (isNaN(info[0]) || isNaN(info[1])) continue;
                data = getGoods(info[0], info[1]);

                console.info(data);
                if ($.isEmptyObject(data)) continue;
                if (cart_list.length > 0) {
                    var has = false
                    for (var j = 0; j < cart_list.length; j++) {
                        if (cart_list[j].shop_id == data.shop_id) {
                            cart_list[j].goods.push(data);
                            has = true
                        }
                    }
                    if (!has) {
                        var datas = {};
                        datas.shop_id = data.shop_id;
                        datas.store_name = data.store_name;
                        datas.goods_id = data.goods_id;
                        var goods = new Array();
                        goods = [data];
                        datas.goods = goods;
                        cart_list.push(datas);
                    }
                } else {
                    var datas = {};
                    datas.shop_id = data.shop_id;
                    datas.store_name = data.store_name;
                    datas.goods_id = data.goods_id;
                    var goods = new Array();
                    goods = [data];
                    datas.goods = goods;
                    cart_list.push(datas);
                }

                sum += parseFloat(data.goods_sum);
            }
        }
        var rData = {cart_list: cart_list, sum: sum.toFixed(2), cart_count: goodsarr.length, check_out: false};
        rData.WapSiteUrl = WapSiteUrl;

        if (rData.cart_list.length > 0 && key) {
            $(".JS-header-edit").show();
        }
        var html = template.render('cart-list1', rData);
        $('#cart-list').addClass('no-login');
        if (rData.cart_list.length == 0) {
            get_footer();
        }
        $("#cart-list-wp").html(html);
        $('.goto-settlement,.goto-shopping').parent().hide();
        //删除购物车
        $(".goods-del").click(function () {
            var $this = $(this);
            $.sDialog({
                skin: "red",
                content: '确认删除吗？',
                okBtn: true,
                cancelBtn: true,
                okFn: function () {
                    var goods_id = $this.attr('cart_id');
                    for (var i = 0; i < goodsarr.length; i++) {
                        var info = goodsarr[i].split(',');
                        if (info[0] == goods_id) {
                            goodsarr.splice(i, 1);
                            break;
                        }
                    }
                    addCookie('goods_cart', goodsarr.join('|'));
                    // 更新cookie中商品数量

                    if (goodsarr[0] == 'null') {
                        addCookie('cart_count', goodsarr.length - 1);
                    }
                    else {
                        addCookie('cart_count', goodsarr.length);
                    }
                    location.reload();
                }
            });
        });
        //  //购买数量，减
        $(".minus").click(function () {
            var sPrents = $(this).parents(".cart-litemw-cnt");
            var goods_id = sPrents.attr('cart_id');

            // var buynum = $(".buy-num").val();
            // if(buynum >1){
            //     $(".buy-num").val(parseInt(buynum-1));
            // }

            for (var i = 0; i < goodsarr.length; i++) {
                var info = goodsarr[i].split(',');
                if (info[0] == goods_id) {
                    if (info[1] == 1) {
                        return false;
                    }
                    info[1] = parseInt(info[1]) - 1;
                    goodsarr[i] = info[0] + ',' + info[1];
                    sPrents.find('.buy-num').val(info[1]);
                    sPrents.find('.goods-info .nums').html("x" + info[1]);

                }
            }
            addCookie('goods_cart', goodsarr.join('|'));
        });
        //购买数量加
        $(".add").click(function () {
            var sPrents = $(this).parents(".cart-litemw-cnt");
            var goods_id = sPrents.attr('cart_id');
            //查询当前购物车商品是否是限购商品 2017.5.3
            $.ajax({
                url: ApiUrl + "/index.php?ctl=Goods_Goods&met=goods&typ=json",
                type: "get",
                data: {goods_id: goods_id, k: key, u: getCookie('id')},
                dataType: "json",
                success: function (result) {
                    var data = result.data;
                    console.info(data);
                    if (result.status == 200) {
                        var buynum = parseInt($(".buy-num").val());
                        console.info(buynum);
                        //如果是限购商品
                        if (data.buyer_limit) {
                            if (parseInt(buynum + 1) >= data.buyer_limit) {
                                //如果当前点击商品数量等于限购数，则商品数量不增加，将当前购物车商品保存到cookie 2017.5.3
                                if (buynum == data.buyer_limit) {
                                    for (var i = 0; i < goodsarr.length; i++) {
                                        var info = goodsarr[i].split(',');
                                        console.info(info);
                                        if (info[0] == goods_id) {
                                            info[1] = data.buyer_limit;
                                            goodsarr[i] = info[0] + ',' + info[1];
                                            // sPrents.find('.buy-num').val(info[1]);
                                            sPrents.find('.buy-num').val(parseInt(data.buyer_limit));

                                            sPrents.find('.goods-info .nums').html("x" + parseInt(data.buyer_limit));

                                        }
                                    }
                                    //如果当前点击商品数量小于限购数，则商品数量+1，将当前购物车商品保存到cookie 2017.5.3
                                } else if (buynum < data.buyer_limit) {
                                    for (var i = 0; i < goodsarr.length; i++) {
                                        var info = goodsarr[i].split(',');
                                        console.info(info);
                                        if (info[0] == goods_id) {
                                            info[1] = parseInt(buynum + 1);
                                            goodsarr[i] = info[0] + ',' + info[1];
                                            // sPrents.find('.buy-num').val(info[1]);
                                            sPrents.find('.buy-num').val(parseInt(data.buyer_limit));
                                            sPrents.find('.goods-info .nums').html("x" + parseInt(data.buyer_limit));
                                        }
                                    }
                                }
                                addCookie('goods_cart', goodsarr.join('|'));
                            } else {
                                //如果当前点击商品数量+1小于限购数，则商品数量+1，将当前购物车商品保存到cookie 2017.5.3
                                // $(".buy-num").val(parseInt(buynum+1));
                                // sPrents.find('.buy-num').val(parseInt(buynum+1));
                                for (var i = 0; i < goodsarr.length; i++) {
                                    var info = goodsarr[i].split(',');
                                    console.info(info);
                                    if (info[0] == goods_id) {
                                        info[1] = parseInt(buynum + 1);
                                        goodsarr[i] = info[0] + ',' + info[1];
                                        sPrents.find('.buy-num').val(info[1]);
                                        sPrents.find('.goods-info .nums').html("x" + info[1]);
                                    }
                                }
                            }
                            //如果不是限购商品，则无需判断，商品数量+1，将当前购物车商品保存到cookie 2017.5.3
                        } else {
                            for (var i = 0; i < goodsarr.length; i++) {
                                var info = goodsarr[i].split(',');
                                console.info(info);
                                if (info[0] == goods_id) {
                                    info[1] = parseInt(info[1]) + 1;
                                    goodsarr[i] = info[0] + ',' + info[1];
                                    sPrents.find('.buy-num').val(info[1]);
                                    sPrents.find('.goods-info .nums').html("x" + info[1]);
                                }
                            }
                            addCookie('goods_cart', goodsarr.join('|'));
                        }
                    }
                }
            })


        });

    } else {
        //初始化页面数据
        function initCartList() {
            $.ajax({
                url: ApiUrl + "/index.php?ctl=Buyer_Cart&met=cart&typ=json",
                type: "post",
                dataType: "json",
                data: {k: key, u: getCookie('id')},
                success: function (result) {
                    if (checkLogin(result.login)) {
                        if (!result.data.error) {
                            if (result.data.cart_list.length == 0) {
                                addCookie('cart_count', 0);
                            }
                            var rData = result.data;

                            if (rData.cart_list.length > 0) {
                                $(".JS-header-edit").show();
                            } else {
                                $(".JS-header-edit").hide();
                            }

                            rData.WapSiteUrl = WapSiteUrl;
                            rData.check_out = true;
                            console.info(rData);
                            var html = template.render('cart-list', rData);
                            if (rData.cart_list.length == 0) {
                                get_footer();
                            }
                            $("#cart-list-wp").html(html);
                            //删除购物车
                            $(".goods-del").click(function () {
                                var cart_id = $(this).attr("cart_id");
                                $.sDialog({
                                    skin: "red",
                                    content: '确认删除吗？',
                                    okBtn: true,
                                    cancelBtn: true,
                                    okFn: function () {
                                        delCartList(cart_id);
                                    }
                                });
                            });
                            //购买数量，减
                            $(".minus").click(minusBuyNum);
                            //购买数量加
                            $(".add").click(addBuyNum);
                            //手动输入数量
                            $('.buynum').click(clickNumber).change(customBuyNum);
                            $(".buynum").blur(buyNumer);
                            // 从下到上动态显示隐藏内容
                            for (var i = 0; i < result.data.cart_list.length; i++) {
                                $.animationUp({
                                    valve: '.animation-up' + i,          // 动作触发，为空直接触发
                                    wrapper: '.nctouch-bottom-mask' + i,    // 动作块
                                    scroll: '.nctouch-bottom-mask-rolling' + i,     // 滚动块，为空不触发滚动
                                });
                            }
                            // 领店铺代金券
                            $('.nctouch-voucher-list').on('click', '.btn', function () {
                                getFreeVoucher($(this).attr('data-tid'));
                            });
                            $('.store-activity').click(function () {
                                $(this).css('height', 'auto');
                            });
                        } else {
                            alert(result.data.error);
                        }
                    }
                }
            });
        }

        initCartList();

        //删除购物车
        function delCartList(cart_id) {
            $.ajax({
                url: ApiUrl + "/index.php?ctl=Buyer_Cart&met=delCartByCid&typ=json",
                type: "post",
                data: {k: key, u: getCookie('id'), id: cart_id},
                dataType: "json",
                success: function (res) {
                    console.info(res);
                    if (checkLogin(res.login)) {
                        if (res.status == 200) {
                            initCartList();
                            delCookie('cart_count');
                            // 更新购物车中商品数量
                            getCartCount();
                        } else {
                            alert(res.msg);
                        }
                    }
                }
            });
        }

        //购买数量减
        function minusBuyNum() {
            var self = this;
            editQuantity(self, "minus");
        }

        //购买数量加
        function addBuyNum() {
            var self = this;
            editQuantity(self, "add");
        }

        //手动输入数量
        function customBuyNum() {

            //检查输入正确性
            var num = parseInt(this.value);
            if (!num || !/^\d+$/.test(num)) {
                return this.value = 0;
            }

            var data_max = parseInt($(this).attr('data_max'));
            var data_min = parseInt($(this).attr('data_min'));
            if (num < data_min) {
                num = data_min;
            }
            else if (num > data_max) {
                num = data_max;
            }
            $(this).val(num);
            var self = this;
            editQuantity(self, "custom");
        }

        function clickNumber() {
            //记录修改前number
            this.beforChangeNum = this.value;
        }

        //购买数量增或减，请求获取新的价格
        function editQuantity(self, type) {
            var sPrents = $(self).parents(".cart-litemw-cnt");
            var cart_id = sPrents.attr("cart_id");
            var numInput = sPrents.find(".buy-num");
            //设置限购数量 2017.5.2
            var data_max = sPrents.find(".buy-num").attr('data_max');
            var data_min = sPrents.find(".buy-num").attr('data_min');
            var promotion = sPrents.find(".buy-num").attr('promotion');
            var goodsPrice = sPrents.find(".goods-price");
            console.log(sPrents.find(".buy-num"));
            var buynum = parseInt(numInput.val());

            var old = sPrents.find('.goods-info .nums');
            var quantity = 1;
            //设置限购数量 2017.5.2
            if (type == "add") {
                if (buynum + 1 >= data_max) {
                    if (buynum == data_max) {
                        return false;
                    }
                    quantity = parseInt(data_max);
                }
                else {
                    quantity = parseInt(buynum + 1);
                }

            } else if (type == "minus") {
                if (buynum > data_min) {
                    quantity = parseInt(buynum - 1);
                } else {
                    if (promotion == 1) {
                        var content = '该限时折扣商品最少需购买' + data_min + '件';
                    }
                    else {
                        var content = '该商品最少需购买' + data_min + '件';
                    }
                    $.sDialog({
                        content: content,
                        okBtn: false,
                        cancelBtn: false
                    });
                    return false;
                }
            } else if (type == "custom") {
                quantity = self.value;
            }
            $('.pre-loading').removeClass('hide');
            $.ajax({
                url: ApiUrl + "/index.php?ctl=Buyer_Cart&met=editCartNum&typ=json",
                type: "post",
                data: {k: key, u: getCookie('id'), cart_id: cart_id, num: quantity},
                dataType: "json",
                success: function (res) {
                    initCartList();
                    console.info(res);
                    if (checkLogin(res.login)) {
                        if (res.status == 200) {
                            numInput.val(quantity);

                            old.html("x" + quantity);
                            /*goodsPrice.html('￥<em>' + res.data.price + '</em>');*/
                            calculateTotalPrice();
                        } else {
                            $.sDialog({
                                skin: "red",
                                content: res.msg,
                                okBtn: false,
                                cancelBtn: false
                            });
                            type == "custom" && (self.value = self.beforChangeNum);
                        }
                        $('.pre-loading').addClass('hide');
                    }
                }
            });
        }

        //去结算
        $('#cart-list-wp').on('click', ".check-out > a", function () {
            if (!$(this).parent().hasClass('ok')) {
                return false;
            }
            //购物车ID
            var cartIdArr = [];
            var cartNumArr = [];
            $('.cart-litemw-cnt').each(function () {
                if ($(this).find('input[name="cart_id"]').prop('checked')) {
                    var cartId = $(this).find('input[name="cart_id"]').val();
                    var cartNum = parseInt($(this).find('.value-box').find("input").val());
                    var cartIdNum = cartId/*+"|"+cartNum*/;
                    cartIdArr.push(cartIdNum);
                    cartNumArr.push(cartNum);
                }
            });
            var cart_id = cartIdArr;
            var nums = cartNumArr;
            if (cart_id != "") {
                $.post(ApiUrl + '/index.php?ctl=Buyer_Cart&met=newconfirm&typ=json', {
                    k: key,
                    u: getCookie('id'),
                    product_id: cart_id,
                    nums: nums
                }, function (result) {
                    if (result.status == 250) {
                        $.sDialog({
                            skin: 'red',
                            content: result.msg,
                            okBtn: false,
                            cancelBtn: false
                        });

                    } else {
                        window.location.href = WapSiteUrl + "/tmpl/order/buy_step1.html?ifcart=1&cart_id=" + cart_id;
                    }
                });

            }

        });


        //验证
        $.sValid.init({
            rules: {
                buynum: "digits"
            },
            messages: {
                buynum: "请输入正确的数字"
            },
            callback: function (eId, eMsg, eRules) {
                if (eId.length > 0) {
                    var errorHtml = "";
                    $.map(eMsg, function (idx, item) {
                        errorHtml += "<p>" + idx + "</p>";
                    });
                    $.sDialog({
                        skin: "red",
                        content: errorHtml,
                        okBtn: false,
                        cancelBtn: false
                    });
                }
            }
        });

        function buyNumer() {
            $.sValid();
        }

        //批量删除
        $(document).on("click", "#batchRemove", function () {

            $.sDialog({
                content: '确认删除吗?',
                okFn: function () {
                    var $checkedCartGoods = $("#cart-list-wp").find("input[name=cart_id]:checked");
                    if ($checkedCartGoods && $checkedCartGoods.length > 0) {
                        var cartIds = $checkedCartGoods.map(function (i, v) {
                            return $(this).val();
                        });
                        delCartList(cartIds);
                    }
                }
            })
        })
    }

    // 店铺全选
    $('#cart-list-wp').on('click', '.store_checkbox', function () {
        $(this).parents('.nctouch-cart-container').find('input[name="cart_id"]').prop('checked', $(this).prop('checked'));
        storeCehckbox();
        calculateTotalPrice();
    });
    // 所有全选
    $(document).on('click', '.all_checkbox', function () {
        if ($(this).prop('checked') == false) {
            $('.nctouch-cart-container').find('input[type="checkbox"]').prop('checked', false);
        } else {
            $('.nctouch-cart-container').find('input[type="checkbox"]').prop('checked', true);
        }
        calculateTotalPrice();
    })

    $('#cart-list-wp').on('click', 'input[name="cart_id"]', function () {
        if (listCehckbox($(this))) {
            $(this).parents('.nctouch-cart-container').find('input[class="store_checkbox"]').prop('checked', '');
        } else {
            $(this).parents('.nctouch-cart-container').find('input[class="store_checkbox"]').prop('checked', 'checked');
        }
        storeCehckbox();
        calculateTotalPrice();
    });


});

//店铺全选时更改所有全选按钮状态
function storeCehckbox() {
    var _has = false
    $('input[class="store_checkbox"]').each(function () {
        if (!($(this).prop('checked'))) {
            _has = true;
        }
    });
    if (_has) {
        $('input[class="all_checkbox"]').prop('checked', '');
    } else {
        $('input[class="all_checkbox"]').prop('checked', 'checked');
    }

}

//单个商品选择时更改 店铺全选、所有全选
function listCehckbox(th) {
    var _has = false;
    var cart_all = th.parents('.nctouch-cart-container').find('input[name="cart_id"]');
    cart_all.each(function () {
        if (!($(this).prop('checked'))) {
            _has = true;
        }
    });
    return _has;

}

function calculateTotalPrice() {
    var totalPrice = parseFloat("0.00");
    $('.cart-litemw-cnt').each(function () {
        if ($(this).find('input[name="cart_id"]').prop('checked')) {
            totalPrice += parseFloat($(this).find('.goods-price').find('em').html()) * parseInt($(this).find('.value-box').find('input').val());
        }
    });
    $(".total-money").find('em').html(totalPrice.toFixed(2));
    check_button();
    return true;
}

function getGoods(goods_id, goods_num) {
    var data = {};
    $.ajax({
        type: 'get',
        url: ApiUrl + '/index.php?ctl=Goods_Goods&met=goodDetail&typ=json&goods_id=' + goods_id,
        dataType: 'json',
        async: false,
        success: function (result) {
            if (result.status !== 200) {
                return false;
            }

            data.cart_id = goods_id;
            data.shop_id = result.data.goods_base.shop_id;
            data.store_name = result.data.goods_base.shop_name;
            data.goods_id = goods_id;
            data.goods_name = result.data.goods_base.goods_name;
            data.goods_price = result.data.goods_base.now_price;
            data.goods_num = goods_num;
            data.goods_image_url = result.data.goods_base.goods_image;
            data.goods_sum = (parseInt(goods_num) * parseFloat(result.data.goods_base.now_price)).toFixed(2);
        }
    });
    return data;
}

function get_footer() {
    footer = true;
    /*$.ajax({
        url: WapSiteUrl+'/js/tmpl/footer.js',
        dataType: "script"
      });*/
}

function check_button() {
    var _has = false
    $('input[name="cart_id"]').each(function () {
        if ($(this).prop('checked')) {
            _has = true;
        }
    });
    if (_has) {
        $('.check-out').addClass('ok');
    } else {
        $('.check-out').removeClass('ok');
    }
}

$(function () {
    $(document).on("click", ".JS-edit", function () {

        var $this = $(this);
        if ($this.hasClass("done")) {
            $this.text("编辑").removeClass("done").parents(".nctouch-cart-container").find(".edit-area").hide();
            $this.text("编辑").removeClass("done").parents(".nctouch-cart-container").find(".goods-info").show();
        } else {
            $this.text("完成").addClass("done").parents(".nctouch-cart-container").find(".edit-area").show();
            $this.text("完成").addClass("done").parents(".nctouch-cart-container").find(".goods-info").hide()
        }
    });

    //头部对所有商品编辑
    $(document).on("click", ".JS-header-edit", function () {

        var $this = $(this);

        if ($this.hasClass("done")) {
            $this.text("编辑").removeClass("done");
            //$(".goods-del").hide();//展示所有的按钮
            $(".JS-edit").show();//店铺的编辑隐藏
            $("div.check-out").show();
            $("#batchRemove").hide();
        } else {
            $this.text("完成").addClass("done");
            //$(".goods-del").show();//展示所有的按钮
            $(".JS-edit").removeClass("done").hide();//店铺的编辑隐藏
            $("div.check-out").hide();
            $("#batchRemove").show();
        }
    });

    //计算商品数量
    function computeProductNumber() {
        var $productNumber = $('#productNumber'),
            checkboxes = $('input[type="checkbox"]:checked');

        if (checkboxes.length === 0) {
            return $productNumber.text('去付款(0)');
        }

        if (checkboxes.length === 1 && $(checkboxes[0]).hasClass('all_checkbox')) {
            checkboxes[0].checked = false;
        }

        var productNumber = 0;
        checkboxes.each(function (i, e) {
            if (!$(e).hasClass('store_checkbox') && !$(e).hasClass('all_checkbox')) {
                productNumber += Number($(e).data('num'));
            }
        });
        $productNumber.text('去付款(' + productNumber + ')');
    }

    $(document).on('change', 'input[type="checkbox"]', function () {
        computeProductNumber();
    });
});