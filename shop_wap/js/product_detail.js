var goods_id = getQueryString("goods_id");
var uu_id = getQueryString("uu_id");
var cid = getQueryString("cid");
var lbs_geo = getCookie('lbs_geo');
var rec = getQueryString('rec');

var mydate = new Date();
var goodsInformation;
var goods_detail = [];

if (uu_id) {
    var str1 = document.domain;
    var str2 = str1.substring(str1.indexOf("."));
    
    $.fn.cookie('uu_id', uu_id, {expires: mydate.getTime() + 60 * 60 * 24 * 3, path: '/', domain: str2});
}

if (rec) {
    var str1 = document.domain;
    var str2 = str1.substring(str1.indexOf("."));
    
    $.fn.cookie('yf_recserialize', rec, {expires: mydate.getTime() + 60 * 60 * 24 * 3, path: '/', domain: str2});
}

var option_window = false; //当前弹框
//如果没有goods_id，则根据cid获取goods_id
if (!goods_id && cid) {
    $.ajax({
        url: ApiUrl + "/index.php?ctl=Goods_Goods&met=getGoodsidByCid&typ=json",
        type: "POST",
        data: {k: getCookie('key'), u: getCookie('id'), cid: cid},
        dataType: "json",
        async: false,
        success: function (result) {
            if (result.status == 200) {
                goods_id = result.data.goods_id;
            }
        }
    });
}
var map_list = [];
var map_index_id = '';
var shop_id;
$(function () {
    var key = getCookie('key');
    var num = 0;
    var unixTimeToDateString = function (ts, ex) {
        ts = parseFloat(ts) || 0;
        if (ts < 1) {
            return '';
        }
        var d = new Date();
        d.setTime(ts * 1e3);
        var s = '' + d.getFullYear() + '-' + (1 + d.getMonth()) + '-' + d.getDate();
        if (ex) {
            s += ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
        }
        return s;
    };
    
    var buyLimitation = function (a, b) {
        a = parseInt(a) || 0;
        b = parseInt(b) || 0;
        var r = 0;
        if (a > 0) {
            r = a;
        }
        if (b > 0 && r > 0 && b < r) {
            r = b;
        }
        return r;
    };
    
    template.helper('isEmpty', function (o) {
        for (var i in o) {
            return false;
        }
        return true;
    });
    
    // 图片轮播
    function picSwipe() {
        // var elem = $("#mySwipe")[0];
        var swiperpic = new Swiper('#mySwipe', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            spaceBetween: 30
        });
    }
    
    get_detail(goods_id);
    
    
    // 点击规格属性时，如果当前为选中状态则取消
    // 只有当每种规格属性都选择，才可以发起请求，拉去商品信息
    
    // 点击规格属性时，判断是否发起请求
    function checkSpec(spec) {
        var $spec = $(spec);
        
        $spec.hasClass("current")
            ? ($spec.removeClass("current"), $("span.goods-storage").text())
            : $spec.addClass("current").siblings().removeClass("current");
        
        return $("#product_roll").find("dl.spec").length == $("#product_roll").find("a.current").length
            ? true
            : false;
    }
    
    //点击商品规格，获取新的商品
    function arrowClick(self, myData) {
        
        if (!checkSpec(self)) {
            return false;
        }
        
        //拼接属性
        var curEle = $(".spec").find("a.current");
        var curSpec = [];
        $.each(curEle, function (i, v) {
            // convert to int type then sort
            curSpec.push(parseInt($(v).attr("specs_value_id")) || 0);
        });
        var spec_string = curSpec.sort(function (a, b) {
            return a - b;
        }).join("|");
        //获取商品ID
        goods_id = myData.spec_list[spec_string];
        get_detail(goods_id);
    }
    
    function contains(arr, str) {//检测goods_id是否存入
        var i = arr.length;
        while (i--) {
            if (arr[i] === str) {
                return true;
            }
        }
        return false;
    }
    
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
    
    //检测商品数目是否为正整数
    function buyNumer() {
        $.sValid();
    }
    
    function get_detail(goods_id) {
        if (lbs_geo == '' || lbs_geo == 'undefined' || lbs_geo == null) {
            var lbs_geo = getCookie('lbs_geo');
        }
        //渲染页面
        $.ajax({
            url: ApiUrl + "/index.php?ctl=Goods_Goods&met=goods&typ=json",
            type: "get",
            data: {goods_id: goods_id, k: key, u: getCookie('id'), cid: cid, lbs_geo: lbs_geo, ua: "wap"},
            dataType: "json",
            success: function (result) {
                var data = result.data;
                if (!data.goods_id) {
                    $.sDialog({
                        content: '该商品已下架或该店铺已关闭！<br>请返回上一页继续操作…',
                        width: 330,
                        height: 200,
                        okBtn: false,
                        cancelBtnText: '返回',
                        cancelFn: function () {
                            history.back();
                        }
                    });
                }
                evalcount = data.goods_info['evalcount'];
                goods_detail = result.data;
                if (result.status == 200) {
                    goodsInformation = result.data;
                    var tel = result.data.store_info.store_tel;
                    $.getJSON(SiteUrl + '/index.php?ctl=Api_Wap&met=version_im&typ=json', function (r) {
                        var st = r.data.im;
                        if (st == 1) {
                            $('.goods-detail-foot .kefu').show();
                        } else if (tel) {
                            setTimeout(function () {
                                $('.goods-detail-foot .kefu').addClass('phone').attr('href', "tel:" + tel).show();
                            }, 500);
                        }
                    });
                    $("title").html(data.goods_info.goods_name);
                    //商品图片格式化数据
                    if (data.goods_image) {
                        var goods_image = data.goods_image.split(",");
                        data.goods_image = goods_image;
                    } else {
                        data.goods_image = [];
                    }
                    if (data.goods_info) {
                        //商品规格格式化数据
                        if (data.goods_info.common_spec_name) {
                            var goods_map_spec = $.map(data.goods_info.common_spec_name, function (v, i) {
                                var goods_specs = {};
                                goods_specs["goods_spec_id"] = i;
                                goods_specs['goods_spec_name'] = v;
                                if (data.goods_info.common_spec_value_c) {
                                    $.map(data.goods_info.common_spec_value_c, function (vv, vi) {
                                        if (i == vi) {
                                            goods_specs['goods_spec_value'] = $.map(vv, function (vvv, vvi) {
                                                var specs_value = {};
                                                specs_value["specs_value_id"] = vvi;
                                                specs_value["specs_value_name"] = vvv;
                                                return specs_value;
                                            });
                                        }
                                    });
                                    return goods_specs;
                                } else {
                                    data.goods_info.common_spec_value = [];
                                }
                            });
                            data.goods_map_spec = goods_map_spec;
                        } else {
                            data.goods_map_spec = [];
                        }
                        
                        // 虚拟商品限购时间和数量
                        if (data.goods_info.common_is_virtual == '1') {
                            data.goods_info.virtual_indate_str = unixTimeToDateString(data.goods_info.virtual_indate, true);
                            data.goods_info.buyLimitation = buyLimitation(data.goods_info.virtual_limit, data.goods_info.upper_limit);
                        }
                        
                        // 预售发货时间
                        /*if (data.goods_info.is_presell == '1') {
                         data.goods_info.presell_deliverdate_str = unixTimeToDateString(data.goods_info.presell_deliverdate);
                         }*/
                        
                        //渲染模板
                        console.info(data);
                        var html = template.render('product_detail', data);
                        $("#product_detail_html").html(html);
                        var userAgentInfos = /iphone/gi.test(navigator.userAgent) && (screen.height == 812 && screen.width == 375);
                        if (userAgentInfos == true) {
                            $(".goods-detail-foot").css({"padding-bottom": "34px"});
                        }
                        if (getCookie('is_app_guest')) {
                            $('#shareit').attr("href", "/share_toall.html?goods_id=" + data.goods_info.goods_id + "&title=" + encodeURIComponent(data.goods_info.goods_name) + "&img=" + data.goods_image[0] + "&url=" + WapSiteUrl + "/tmpl/product_detail.html?goods_id=" + data.goods_info.goods_id);
                        }
                        if (data.goods_info.common_is_virtual == '0') {
                            $('.goods-detail-o2o').remove();
                        }
                        //渲染模板
                        var html = template.render('product_detail_sepc', data);
                        $("#product_detail_spec_html").html(html);
                        
                        //渲染模板
                        if (typeof(data.promotion_info.voucher_list) != 'undefined' && data.promotion_info.voucher_list.length > 0) {
                            var voucher_list_text = '';
                            var voucher_list = data.promotion_info.voucher_list;
                            for (var i in voucher_list) {
                                /*if(voucher_list[i].is_get == 1){*/
                                voucher_list_text = ' ￥' + voucher_list[i].voucher_t_price + '代金券;' + voucher_list_text;
                                /*}*/
                            }
                            if (voucher_list_text != '') {
                                if (voucher_list_text.length > 23) {
                                    voucher_list_text = voucher_list_text.substr(1, 20) + '...';
                                }
                                $('#voucher_list_text').html('领取代金券');
                            }
                            var html = template.render('voucher_script', data.promotion_info);
                            $("#voucher_html").html(html);
                        } else {
                            $("#voucher_html").hide();
                            $("#getVoucher").hide();
                        }
                        
                        if (!data.goods_info.chain_stock) {
                            $("#ziti").hide();
                        }
                        
                        //渲染模板
                        var html = template.render('sale-activity', data);
                        $("#sale-activity-html").html(html);
                        
                        shop_id = data.store_info.store_id;
                        if (data.goods_info.is_virtual == '1') {
                            virtual();
                        }
                        getCartCount();
                        // 购物车中商品数量
                        if (getCookie('cart_count')) {
                            if (getCookie('cart_count') > 0) {
                                $('#cart_count,#cart_count1').html('<sup>' + getCookie('cart_count') + '</sup>');
                            }
                        }
                        
                        //图片轮播
                        picSwipe();
                        //商品描述
                        $(".pddcp-arrow").click(function () {
                            $(this).parents(".pddcp-one-wp").toggleClass("current");
                        });
                        //规格属性
                        var myData = {};
                        myData["spec_list"] = data.spec_list;
                        $(".spec a").click(function () {
                            var self = this;
                            arrowClick(self, myData);
                        });
                        //购买数量，减
                        $(".minus").click(function () {
                            var buynum = $(".buy-num").val();
                            var data_min = parseInt($(".buy-num").data('min'));
                            var promotion = parseInt($(".buy-num").attr('promotion'));
                            if (buynum > data_min) {
                                $(".buy-num").val(parseInt(buynum - 1));
                            } else {
                                if (promotion == 1) {
                                    var content = '该限时折扣商品最少需购买' + data_min + '件';
                                } else {
                                    var content = '该商品最少需购买' + data_min + '件';
                                }
                                $.sDialog({
                                    content: content,
                                    okBtn: false,
                                    cancelBtn: false
                                });
                                return false;
                            }
                        });
                        
                        //购买数量加
                        $(".add").click(function () {
                            var buynum = parseInt($(".buy-num").val());
                            var data_max = parseInt($(".buy-num").data('max'));
                            if (parseInt(buynum + 1) >= data_max && data_max > 0) {
                                if (buynum == data_max && buynum > 0) {
                                    $.sDialog({
                                        content: '每个用户最多只能购买' + data_max + '件',
                                        okBtn: false,
                                        cancelBtn: false
                                    });
                                    return false;
                                }
                                $(".buy-num").val(parseInt(data_max));
                            } else {
                                $(".buy-num").val(parseInt(buynum + 1));
                            }
                        });
                        //手动修改商品数量
                        ~function initChangeProductNum() {
                            $("#buynum").bind({
                                focus: function () {
                                    $(this).toggleClass('chose-product-num');
                                },
                                blur: function () {
                                    $(this).toggleClass('chose-product-num');
                                },
                                change: function () {
                                    var buy_num = $(".buy-num").val();
                                    var data_min = parseInt($(".buy-num").data('min'));
                                    var data_max = parseInt($(".buy-num").data('max'));
                                    var promotion = parseInt($(".buy-num").attr('promotion'));
                                    
                                    //小于最低限制
                                    if (buy_num < data_min) {
                                        this.value = data_min;
                                        if (promotion == 1) {
                                            var content = '该限时折扣商品最少需购买' + data_min + '件';
                                        } else {
                                            var content = '该商品最少需购买' + data_min + '件';
                                        }
                                        $.sDialog({
                                            content: content,
                                            okBtn: false,
                                            cancelBtn: false
                                        });
                                        return false;
                                    } else if (buy_num > data_max) {
                                        this.value = data_max;
                                        $.sDialog({
                                            content: '每个用户最多只能购买' + data_max + '件',
                                            okBtn: false,
                                            cancelBtn: false
                                        });
                                        return false;
                                    }
                                }
                            });
                        }();
                        
                        // 一个F码限制只能购买一件商品 所以限制数量为1
                        if (data.goods_info.is_fcode == '1') {
                            $('.minus').hide();
                            $('.add').hide();
                            $(".buy-num").attr('readOnly', true);
                        }
                        //收藏
                        $(".pd-collect").click(function () {
                            if ($(this).hasClass('favorate')) {
                                if (dropFavoriteGoods(goods_id)) $(this).removeClass('favorate');
                            } else {
                                if (favoriteGoods(goods_id)) $(this).addClass('favorate');
                            }
                        });
                        //加入购物车
                        $("#add-cart").click(function () {
                            if ($(".buy-handle").hasClass('no-buy')) {
                                $.sDialog({
                                    skin: "red",
                                    content: '该商品暂时无货！',
                                    okBtn: false,
                                    cancelBtn: false
                                });
                                return;
                            }
                            var key = getCookie('key');//登录标记
                            var quantity = parseInt($(".buy-num").val());
                            if (!key) {
                                var goods_info = decodeURIComponent(getCookie('goods_cart'));
                                if (goods_info == null) {
                                    goods_info = '';
                                }
                                if (goods_id < 1) {
                                    show_tip();
                                    return false;
                                }
                                var cart_count = 0;
                                if (!goods_info) {
                                    goods_info = goods_id + ',' + quantity;
                                    cart_count = 1;
                                } else {
                                    var goodsarr = goods_info.split('|');
                                    for (var i = 0; i < goodsarr.length; i++) {
                                        var arr = goodsarr[i].split(',');
                                        if (contains(arr, goods_id)) {
                                            $(".nctouch-bottom-mask-bg").trigger("click");
                                            show_tip();
                                            return false;
                                        }
                                    }
                                    goods_info += '|' + goods_id + ',' + quantity;
                                    cart_count = goodsarr.length;
                                }
                                // 加入cookie
                                addCookie('goods_cart', goods_info);
                                console.info(goods_info);
                                // 更新cookie中商品数量
                                addCookie('cart_count', cart_count);
                                show_tip();
                                getCartCount();
                                $('#cart_count,#cart_count1').html('<sup>' + cart_count + '</sup>');
                                // $(".nctouch-bottom-mask-bg").trigger("click");
                                return false;
                            } else {
                                //判断用户是否已经绑定手机号
                                if (!checkUserMobile()) {
                                    $.sDialog({
                                        skin: 'red',
                                        content: '请先绑定手机号',
                                        okBtn: false,
                                        cancelBtn: false
                                    });
                                    return false;
                                }
                                
                                if (data.shop_owner) {
                                    $.sDialog({
                                        skin: "red",
                                        content: '不能购买自己商店的商品！',
                                        okBtn: false,
                                        cancelBtn: false
                                    });
                                    return;
                                }
                                if (data.isBuyHave) {
                                    $.sDialog({
                                        skin: "red",
                                        content: '您已达购买上限！',
                                        okBtn: false,
                                        cancelBtn: false
                                    });
                                    return;
                                }
                                if (data.buyer_limit > 0 && data.buyer_limit < quantity) {
                                    $.sDialog({
                                        skin: "red",
                                        content: '该商品每人限购' + data.buyer_limit + '件！',
                                        okBtn: false,
                                        cancelBtn: false
                                    });
                                    return;
                                }
                                $.ajax({
                                    url: ApiUrl + "/index.php?ctl=Buyer_Cart&met=addCart&typ=json",
                                    data: {k: key, u: getCookie('id'), goods_id: goods_id, goods_num: quantity},
                                    type: "post",
                                    success: function (result) {
                                        /*var rData = $.parseJSON(result);*/
                                        if (checkLogin(result.login)) {
                                            if (result.status == 200) {
                                                show_tip();
                                                // 更新购物车中商品数量
                                                delCookie('cart_count');
                                                getCartCount();
                                                $('#cart_count,#cart_count1').html('<sup>' + getCookie('cart_count') + '</sup>');
                                                $("#product_detail_spec_html>.nctouch-bottom-mask-bg").trigger("click");
                                            } else {
                                                $.sDialog({
                                                    skin: "red",
                                                    content: result.msg,
                                                    okBtn: false,
                                                    cancelBtn: false
                                                });
                                            }
                                        }
                                    }
                                })
                            }
                        });
                        
                        //立即购买
                        if (data.goods_info.common_is_virtual == '1') {
                            $("#buy-now").click(function () {
                                if ($(".buy-handle").hasClass('no-buy')) {
                                    $.sDialog({
                                        skin: "red",
                                        content: '该商品暂时无货！',
                                        okBtn: false,
                                        cancelBtn: false
                                    });
                                    return;
                                }
                                var key = getCookie('key');//登录标记
                                if (!key) {
                                    //window.location.href = WapSiteUrl+'/tmpl/member/login.html';
                                    callback = window.location.href;
                                    login_url = UCenterApiUrl + '?ctl=Login&met=index&typ=e';
                                    callback = ApiUrl + '?ctl=Login&met=check&typ=e&redirect=' + encodeURIComponent(callback);
                                    login_url = login_url + '&from=wap&callback=' + encodeURIComponent(callback);
                                    window.location.href = login_url;
                                    return false;
                                }
                                //判断用户是否已经绑定手机号
                                if (!checkUserMobile()) {
                                    $.sDialog({
                                        skin: 'red',
                                        content: '请先绑定手机号',
                                        okBtn: false,
                                        cancelBtn: false
                                    });
                                    return false;
                                }
                                
                                var buynum = parseInt($('.buy-num').val()) || 0;
                                
                                if (buynum < 1) {
                                    $.sDialog({
                                        skin: "red",
                                        content: '参数错误！',
                                        okBtn: false,
                                        cancelBtn: false
                                    });
                                    return;
                                }
                                if (buynum > data.goods_info.goods_storage) {
                                    $.sDialog({
                                        skin: "red",
                                        content: '库存不足！',
                                        okBtn: false,
                                        cancelBtn: false
                                    });
                                    return;
                                }
                                
                                // 虚拟商品限购数量
                                if (data.goods_info.buyLimitation > 0 && buynum > data.goods_info.buyLimitation) {
                                    $.sDialog({
                                        skin: "red",
                                        content: '超过限购数量！',
                                        okBtn: false,
                                        cancelBtn: false
                                    });
                                    return;
                                }
                                
                                if (data.shop_owner) {
                                    $.sDialog({
                                        skin: "red",
                                        content: '不能购买自己商店的商品！',
                                        okBtn: false,
                                        cancelBtn: false
                                    });
                                    return;
                                }
                                if (data.isBuyHave) {
                                    $.sDialog({
                                        skin: "red",
                                        content: '您已达购买上限！',
                                        okBtn: false,
                                        cancelBtn: false
                                    });
                                    return;
                                }
                                
                                if (data.buyer_limit > 0 && data.buyer_limit < buynum) {
                                    $.sDialog({
                                        skin: "red",
                                        content: '该商品每人限购' + data.buyer_limit + '件！',
                                        okBtn: false,
                                        cancelBtn: false
                                    });
                                    return;
                                }
                                
                                var json = {};
                                json.key = key;
                                json.cart_id = goods_id;
                                json.quantity = buynum;
                                
                                /* location.href = WapSiteUrl + '/tmpl/order/vr_buy_step1.html?goods_id=' + goods_id + '&quantity=' + buynum;*/
                                $.ajax({
                                    type: 'post',
                                    url: ApiUrl + '/index.php?ctl=Goods_Goods&met=checkVirtual&typ=json',
                                    data: {k: key, u: getCookie('id'), goods_id: goods_id, goods_num: buynum, buy_now:1},
                                    dataType: 'json',
                                    success: function (result) {
                                        
                                        if (result.status == 250) {
                                            $.sDialog({
                                                skin: "red",
                                                content: '您已达购买上限',
                                                okBtn: false,
                                                cancelBtn: false
                                            });
                                        } else {
                                            location.href = WapSiteUrl + '/tmpl/order/vr_buy_step1.html?goods_id=' + goods_id + '&quantity=' + buynum;
                                        }
                                    }
                                });
                            });
                        } else {
                            $("#buy-now").click(function () {
                                if ($(".buy-handle").hasClass('no-buy')) {
                                    $.sDialog({
                                        skin: "red",
                                        content: '该商品暂时无货！',
                                        okBtn: false,
                                        cancelBtn: false
                                    });
                                    return;
                                }
                                
                                if (data.goods_info.goods_stock <= 0) {
                                    return $.sDialog({
                                        skin: 'red',
                                        content: '该商品暂时无货！',
                                        okBtn: false,
                                        cancelBtn: false
                                    });
                                }
                                
                                var key = getCookie('key');//登录标记
                                
                                if (!key) {
                                    //window.location.href = WapSiteUrl+'/tmpl/member/login.html';
                                    callback = window.location.href;
                                    login_url = UCenterApiUrl + '?ctl=Login&met=index&typ=e';
                                    callback = ApiUrl + '?ctl=Login&met=check&typ=e&redirect=' + encodeURIComponent(callback);
                                    login_url = login_url + '&from=wap&callback=' + encodeURIComponent(callback);
                                    window.location.href = login_url;
                                } else {
                                    //判断用户是否已经绑定手机号
                                    if (!checkUserMobile()) {
                                        $.sDialog({
                                            skin: 'red',
                                            content: '请先绑定手机号',
                                            okBtn: false,
                                            cancelBtn: false
                                        });
                                        return false;
                                    }
                                    
                                    var buynum = parseInt($('.buy-num').val()) || 0;
                                    if (buynum < 1) {
                                        $.sDialog({
                                            skin: "red",
                                            content: '参数错误！',
                                            okBtn: false,
                                            cancelBtn: false
                                        });
                                        return;
                                    }
                                    
                                    if (buynum > data.goods_info.buyer_limit && data.goods_info.buyer_limit) {
                                        $.sDialog({
                                            skin: "red",
                                            content: '库存不足！',
                                            okBtn: false,
                                            cancelBtn: false
                                        });
                                        return;
                                    }
                                    
                                    if (data.shop_owner) {
                                        $.sDialog({
                                            skin: "red",
                                            content: '不能购买自己商店的商品！',
                                            okBtn: false,
                                            cancelBtn: false
                                        });
                                        return;
                                    }
                                    if (data.isBuyHave) {
                                        $.sDialog({
                                            skin: "red",
                                            content: '您已达购买上限！',
                                            okBtn: false,
                                            cancelBtn: false
                                        });
                                        return;
                                    }
                                    
                                    if (data.buyer_limit > 0 && data.buyer_limit < buynum) {
                                        $.sDialog({
                                            skin: "red",
                                            content: '该商品每人限购' + data.buyer_limit + '件！',
                                            okBtn: false,
                                            cancelBtn: false
                                        });
                                        return;
                                    }
                                    if (data.goods_info.goods_parent_id > 0 && data.goods_info.product_is_behalf_delivery == 1) {
                                        location.href = WapSiteUrl + "/tmpl/order/buy_step2.html?goods_id=" + goods_id + "&goods_num=" + buynum;
                                    } else {
                                        var json = {};
                                        json.key = key;
                                        json.cart_id = goods_id + '|' + buynum;
                                        $.ajax({
                                            url: ApiUrl + "/index.php?ctl=Buyer_Cart&met=addCart&typ=json",
                                            data: {k: key, u: getCookie('id'), goods_id: goods_id, goods_num: buynum},
                                            type: "post",
                                            success: function (result) {
                                                console.info(result);
                                                if (checkLogin(result.login)) {
                                                    if (result.status == 200) {
                                                        // show_tip();
                                                        // 更新购物车中商品数量
                                                        delCookie('cart_count');
                                                        getCartCount();
                                                        location.href = WapSiteUrl + "/tmpl/order/buy_step1.html?ifcart=1&cart_id=" + result.data.cart_id;
                                                        //location.href = WapSiteUrl+'/tmpl/order/buy_step1.html?goods_id='+goods_id+'&buynum='+buynum;
                                                    } else {
                                                        $.sDialog({
                                                            skin: "red",
                                                            content: result.msg,
                                                            okBtn: false,
                                                            cancelBtn: false
                                                        });
                                                    }
                                                }
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    } else {
                        $.sDialog({
                            content: '该商品已下架或该店铺已关闭！<br>请返回上一页继续操作…',
                            okBtn: false,
                            cancelBtnText: '返回',
                            cancelFn: function () {
                                history.back();
                            }
                        });
                    }
                    var _TimeCountDown = $(".fnTimeCountDown");
                    _TimeCountDown.fnTimeCountDown();
                } else {
                    $.sDialog({
                        content: result.msg + '！<br>请返回上一页继续操作…',
                        okBtn: false,
                        cancelBtnText: '返回',
                        cancelFn: function () {
                            history.back();
                        }
                    });
                }
                //验证购买数量是不是数字
                $("#buynum").blur(buyNumer);
                //库存为0,提示无货
                // if ($('.add-cart, .buy-now ').parent().hasClass("no-buy")) {
                $('.add-cart, .buy-now ').click(function () {
                    if ($('.add-cart, .buy-now ').parent().hasClass("no-buy")) {
                        $.sDialog({
                            skin: "red",
                            content: '该商品暂时无货！',
                            okBtn: false,
                            cancelBtn: false
                        });
                    }
                });
                // }
                
                // 从下到上动态显示隐藏内容
                $.animationUp({
                    valve: '.animation-up,#goods_spec_selected',            // 动作触发
                    wrapper: '#product_detail_spec_html',                   // 动作块
                    scroll: '#product_roll',                                // 滚动块，为空不触发滚动
                    start: function () {                                    // 开始动作触发事件
                        $('.goods-detail-foot').addClass('hide').removeClass('block');
                        if ($(this).hasClass("add-cart")) {
                            option_window = "add_cart";
                            $("#add-cart").css("width", "100%").show();
                            $("#buy-now").hide();
                        } else if ($(this).hasClass("buy-now")) {
                            option_window = "buy_now";
                            $("#buy-now").css("width", "100%").show();
                            $("#add-cart").hide();
                        }
                    },
                    close: function () {                                    // 关闭动作触发事件
                        option_window = false;
                        $("#add-cart,#buy-now").css("width", "50%").show();
                        $('.goods-detail-foot').removeClass('hide').addClass('block');
                    }
                });
                
                $.animationUp({
                    valve: '#getVoucher',          // 动作触发
                    wrapper: '#voucher_html',    // 动作块
                    scroll: '#voucher_roll'     // 滚动块，为空不触发滚动
                });
                
                $.animationUp({
                    valve: '#for-sale',            // 动作触发
                    wrapper: '#sale-activity-html',                   // 动作块
                    scroll: '#voucher_roll'     // 滚动块，为空不触发滚动
                });
                
                $('#voucher_html').on('click', '.btn', function () {
                    getFreeVoucher($(this).attr('data-tid'));
                });
                
                $('#voucher_html').on('click', '.new-btn', function () {
                    $(this).removeClass('up');
                    $(this).add('down');
                });
                
                
                // 联系客服
                $('.kefu').click(function () {
                    //判断不是手机号时使用IM
                    if ($(this).attr('href').indexOf('tel:') == -1) {
                        if (!getCookie('user_account') || getCookie('user_account') == undefined) {
                            if (!getCookie("key")) {
                                $.sDialog({
                                    skin: "red",
                                    content: '您还没有登录',
                                    okBtn: true,
                                    okBtnText: '立即登录',
                                    okFn: function () {
                                        window.location.href = WapSiteUrl + '/tmpl/member/login.html';
                                    },
                                    cancelBtn: true,
                                    cancelBtnText: '取消',
                                    cancelFn: function () {
                                    
                                    }
                                });
                                return false;
                            }
                        }
                        
                        if (window.chatTo) {
                            chatTo(result.data.store_info.member_name.toString());
                            
                        } else {
                            window.location.href = WapSiteUrl + '/tmpl/im-chatinterface.html?contact_type=C&contact_you=' + result.data.store_info.member_name + '&uname=' + getCookie('user_account');
                        }
                    }
                });
                getGoodsNewReview();
                option_window && initOptionWindow(option_window);
            }
        });
    }
    
    $.scrollTransparent();
    $('#product_detail_html').on('click', '#get_area_selected', function () {
        var common_id = $(this).data('common_id');
        var transport_type_id = $(this).data('transport_type_id');
        $.areaSelected({
            hideThirdLevel: true,
            success: function (data) {
                $('#get_area_selected_name').html(data.area_info);
                var area_id = data.area_id_2 == 0 ? data.area_id_1 : data.area_id_2;
                $.getJSON(ApiUrl + '/index.php?ctl=Goods_Goods&met=getTramsport&typ=json', {
                    common_id: common_id,
                    area_id: area_id
                }, function (result) {
                    console.log(result);
                    $('#get_area_selected_content').html(result.data.transport_str);
                    if (result.data.result != true) {
                        $('#get_area_selected_whether').html('无货');
                        $('.buy-handle').addClass('no-buy');
                        $('.add-cart, .buy-now ').removeClass("animation-up")
                    } else {
                        if (result.status == 250) {
                            $('#get_area_selected_whether').html('无货');
                            $('.buy-handle').addClass('no-buy');
                            $('.add-cart, .buy-now ').removeClass("animation-up")
                        } else {
                            $('#get_area_selected_whether').html('有货');
                            $('.buy-handle').removeClass('no-buy');
                            $('.add-cart, .buy-now ').addClass("animation-up")
                        }
                    }
                });
            }
        });
    });
    
    $('body').on('click', '#goodsBody,#goodsBody1', function () {
        window.location.href = WapSiteUrl + '/tmpl/product_info.html?goods_id=' + goods_id;
    });
    $('body').on('click', '#goodsEvaluation,#goodsEvaluation1,#reviewLink', function () {
        window.location.href = WapSiteUrl + '/tmpl/product_eval_list.html?goods_id=' + goods_id;
    });
    $('body').on('click', '#goodsRecommendation', function () {
        window.location.href = WapSiteUrl + '/tmpl/product_recommendation.html?goods_id=' + goods_id;
    });
    
    $('body').on('click', '#ziti', function () {
        var key = getCookie('key');//登录标记
        if (!key) {
            //window.location.href = WapSiteUrl+'/tmpl/member/login.html';
            callback = window.location.href;
            login_url = UCenterApiUrl + '?ctl=Login&met=index&typ=e';
            callback = ApiUrl + '?ctl=Login&met=check&typ=e&redirect=' + encodeURIComponent(callback);
            login_url = login_url + '&from=wap&callback=' + encodeURIComponent(callback);
            window.location.href = login_url;
            return false;
        }
        Public.ajaxGet(SiteUrl + '?ctl=Chain_Goods&met=isValidUser&typ=json', {
            k: getCookie('key'),
            u: getCookie('id')
        }, function (e) {
            if (e.data.status == 250) {
                return $.sDialog({
                    skin: "red",
                    content: e.data.msg,
                    okBtn: false,
                    cancelBtn: false
                });
            }
        });
        if (getCookie('id') == goodsInformation.store_info.member_id) {
            return $.sDialog({
                skin: "red",
                content: '不能购买自己商店的商品！',
                okBtn: false,
                cancelBtn: false
            });
        }
        
        var goods_num = parseInt($('.buy-num').val()) || 0;
        $.getJSON(ApiUrl + '/index.php?ctl=Goods_Goods&met=chain&typ=json', {
            goods_id: goods_id, shop_id: shop_id, goods_num: goods_num, k: getCookie('key'),
            u: getCookie('id')
        }, function (result) {
            console.info(result);
            if (result.status == 200) {
                window.location.href = WapSiteUrl + '/tmpl/ziti.html?goods_id=' + goods_id + '&shop_id=' + shop_id + '&goods_num=' + goods_num;
            } else {
                $.sDialog({
                    skin: "red",
                    content: result.msg,
                    okBtn: false,
                    cancelBtn: false
                });
                
            }
        });
    });
    $('#list-address-scroll').on('click', 'dl > a', map);
    $('#map_all').on('click', map);
});

function show_tip() {
    var flyer = $('.goods-pic > img').clone().css({'z-index': '999', 'height': '3rem', 'width': '3rem'});
    flyer.fly({
        start: {
            left: $('.goods-pic > img').offset().left,
            top: parseInt($('.goods-pic > img').offset().top - $(window).scrollTop())
        },
        end: {
            left: $("#cart_count").offset().left + 140,
            //top:  $("#cart_count").offset().top - $(window).scrollTop()
            top: 600
        },
        onEnd: function () {
            flyer.remove();
        }
    });
}

function virtual() {
    $('#get_area_selected').parents('.goods-detail-item').remove();
    $.getJSON(ApiUrl + '/index.php?act=goods&op=store_o2o_addr', {shop_id: shop_id}, function (result) {
        if (!result.data.error) {
            if (result.data.addr_list.length > 0) {
                $('#list-address-ul').html(template.render('list-address-script', result.data));
                map_list = result.data.addr_list;
                var _html = '';
                _html += '<dl index_id="0">';
                _html += '<dt>' + map_list[0].name_info + '</dt>';
                _html += '<dd>' + map_list[0].address_info + '</dd>';
                _html += '</dl>';
                _html += '<p><a href="tel:' + map_list[0].phone_info + '"></a></p>';
                $('#goods-detail-o2o').html(_html);
                $('#goods-detail-o2o').on('click', 'dl', map);
                if (map_list.length > 1) {
                    $('#store_addr_list').html('查看全部' + map_list.length + '家分店地址');
                } else {
                    $('#store_addr_list').html('查看商家地址');
                }
                $('#map_all > em').html(map_list.length);
            } else {
                $('.goods-detail-o2o').hide();
            }
        }
    });
    $.animationLeft({
        valve: '#store_addr_list',
        wrapper: '#list-address-wrapper',
        scroll: '#list-address-scroll'
    });
}

function map() {
    $('#map-wrappers').removeClass('hide').removeClass('right').addClass('left');
    $('#map-wrappers').on('click', '.header-l > a', function () {
        $('#map-wrappers').addClass('right').removeClass('left');
    });
    $('#baidu_map').css('width', document.body.clientWidth);
    $('#baidu_map').css('height', document.body.clientHeight);
    map_index_id = $(this).attr('index_id');
    if (typeof map_index_id != 'string') {
        map_index_id = '';
    }
    if (typeof(map_js_flag) == 'undefined') {
        $.ajax({
            url: WapSiteUrl + '/js/map.js',
            dataType: "script",
            async: false
        });
    }
    if (typeof BMap == 'object') {
        baidu_init();
    } else {
        load_script();
    }
}

//2017-07-12相关需求加入
function getGoodsNewReview() {
    $.ajax({
        url: ApiUrl + "/index.php?ctl=Goods_Goods&met=getGoodsNewReview&typ=json&sort=scores",
        type: "POST",
        data: {
            k: getCookie('key'),
            u: getCookie('id'),
            goods_id: goods_id
        },
        dataType: "json",
        success: function (result) {
            if (result.status == 200) {
                console.log(evalcount);
                var goodsReviewHtml = template.render('goodsReview', {
                    'goods_review_rows': result.data.goods_review_rows,
                    'evalcount': evalcount
                });
                $("#s-rate").append(goodsReviewHtml);
            }
        }
    });
}

function initOptionWindow(option_type) {
    if (option_type == "buy_now") {
        $("#buy-now").css("width", "100%").show();
        $("#add-cart").hide();
    } else {
        $("#add-cart").css("width", "100%").show();
        $("#buy-now").hide();
    }
}

$(document).on('click', '.goods_geval a', function () {
    var start = $(this).data('start');
    var o = $(this).parents(".goods_geval");
    o.find(".nctouch-bigimg-layout").removeClass("hide");
    var i = o.find(".pic-box");
    o.find(".close").click(function () {
        o.find(".nctouch-bigimg-layout").addClass("hide")
    });
    
    if (i.find("li").length < 2) {
        return
    }
    
    Swipe(i[0], {
        startSlide: start,
        speed: 400,
        auto: 3e3,
        continuous: false,
        disableScroll: false,
        stopPropagation: false,
        callback: function (o, i) {
            $(i).parents(".nctouch-bigimg-layout").find("div").last().find("li").eq(o).addClass("cur").siblings().removeClass("cur")
        },
        transitionEnd: function (o, i) {
        }
    })
    
    
});


$('#sale-activity-html').on('click', '.new-btn', function () {
    $('#sale-activity-html').removeClass('up');
    $('#sale-activity-html').addClass('down');
});
$('#voucher_html').on('click', '.new-btn', function () {
    $('#voucher_html').removeClass('up');
    $('#voucher_html').addClass('down');
});

var pos = getQueryString('pos');
addCookie('goods_pos', pos);

function backGoodsList() {
    window.history.go(-1);
    
}