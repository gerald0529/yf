var key = getCookie('key');
var goods_id = getQueryString("goods_id");
var goods_num = getQueryString("goods_num");
var address_id = !getQueryString("address_id") ? 0 : getQueryString("address_id");
var goods_type = !getQueryString("type") ? 0 : getQueryString("type");
var mark_id = !getQueryString("mark_id") ? 0 : getQueryString("mark_id");
var pt_detail_id = !getQueryString("pt_detail_id") ? 0 : getQueryString("pt_detail_id");

if (!key) {
    checkLogin(0);
}

template.helper('isEmpty', function (o) {
    var b = true;
    $.each(o, function (k, v) {
        b = false;
        return false;
    });
    return b;
});

$(function () {
    
    if (goods_id <= 0 || goods_num <= 0) {
        window.history.go(-1);
    }
    
    var goods_info = getGoodsInfo(goods_id, goods_num, address_id);
    //显示地址
    address(goods_info.address);
    //显示商品信息
    $('#shop_name').html(goods_info.shop.shop_name);
    
    $('#goods_num').html('x' + goods_info.base.goods_num);
    if (!goods_info.common.buy_able) {
        $('#goods_name_a').html(goods_info.base.goods_name + '<span style="color: #db4453;">无货</span>');
        $('.check-out').removeClass('ok');
    } else {
        $('#goods_name_a').html(goods_info.base.goods_name);
        $('.check-out').addClass('ok');
    }
    if (goods_type != 0 && goods_type != goods_info.promotion.promotion_type) {
        window.history.go(-1);
    }
    switch (goods_type) {
        case 'pintuan':
            $('#goods_name_a').attr('href', '/tmpl/pintuan_detail.html?goods_id=' + goods_id + '&goods_num=' + goods_num);
            $('#goods_image_a').attr('href', '/tmpl/pintuan_detail.html?goods_id=' + goods_id + '&goods_num=' + goods_num);
            $('#storeTotal').html(goods_info.shop.sprice);
            $('#goods_price').html(goods_info.promotion.detail.price);
            $('#totalPayPrice').html(goods_info.shop.sprice);
            $('#pay_selected_icon').hide();
            $('#payment-offline').hide();
            $('#storeFreight').html('包邮免费');
            $('.storeMessage').hide();
            $("#select-payment-wrapper").hide();
            break;
        case 'alone':
            $('#goods_name_a').attr('href', '/tmpl/pintuan_detail.html?goods_id=' + goods_id + '&goods_num=' + goods_num);
            $('#goods_image_a').attr('href', '/tmpl/pintuan_detail.html?goods_id=' + goods_id + '&goods_num=' + goods_num);
            $('#storeTotal').html(goods_info.shop.sprice);
            $('#goods_price').html(goods_info.promotion.detail.price_one);
            $('#totalPayPrice').html(goods_info.shop.sprice);
            $('#pay_selected_icon').hide();
            $('#payment-offline').hide();
            $('#storeFreight').html('包邮免费');
            $('.storeMessage').hide();
            break;
        default:
            $('#goods_name_a').attr('href', '/tmpl/product_detail.html?goods_id=' + goods_id);
            $('#goods_image_a').attr('href', '/tmpl/product_detail.html?goods_id=' + goods_id);
            $('#storeTotal').html(goods_info.shop.sprice);
            $('#goods_price').html(goods_info.base.goods_price);
            $('#totalPayPrice').html(goods_info.shop.sprice);
            $('#storeFreight').html('￥' + goods_info.transport.cost);
    }
    
    
    $('#goods_image').attr('src', goods_info.base.goods_image);
    $('#goods_spec_str').html(goods_info.base.spec_str);
    
    
    //地址选择
    $.animationLeft({
        valve: '#list-address-valve',
        wrapper: '#list-address-wrapper',
        scroll: '#list-address-scroll'
    });
    // 地址新增
    $.animationLeft({
        valve: '#new-address-valve',
        wrapper: '#new-address-wrapper',
        scroll: ''
    });
    // 支付方式
    $.animationUp({
        valve: '#select-payment-valve',
        wrapper: '#select-payment-wrapper',
        scroll: ''
    });
    // 地区选择
    $('#list-address-add-list-ul').on('click', 'li', function () {
        var addr_id = $(this).data('user_address_id');
        window.location.href = '/tmpl/order/buy_step2.html?goods_id=' + goods_id + '&goods_num=' + goods_num + '&address_id=' + addr_id + '&type=' + goods_type + '&mark_id=' + mark_id + '&pt_detail_id=' + pt_detail_id;
    });
    // 地区选择
    $('#new-address-wrapper').on('click', '#varea_info', function () {
        $.areaSelected({
            success: function (data) {
                //console.info(data);
                province_id = data.area_id_1;
                city_id = data.area_id_2;
                area_id = data.area_id_3;
                area_info = data.area_info;
                $('#varea_info').val(data.area_info);
            }
        });
    });
    // 地址保存
    $.sValid.init({
        rules: {
            vtrue_name: "required",
            vmob_phone: "required",
            varea_info: "required",
            vaddress: "required"
        },
        messages: {
            vtrue_name: "姓名必填！",
            vmob_phone: "手机号必填！",
            varea_info: "地区必填！",
            vaddress: "街道必填！"
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
            param.k = key;
            param.user_address_contact = $('#vtrue_name').val();
            param.user_address_phone = $('#vmob_phone').val();
            param.user_address_address = $('#vaddress').val();
            param.address_area = $('#varea_info').val();
            param.province_id = province_id;
            param.city_id = city_id;
            param.area_id = area_id;
            param.user_address_default = 0;
            param.u = getCookie('id');
            $.ajax({
                type: 'post',
                url: ApiUrl + "/index.php?ctl=Buyer_User&met=addAddressInfo&typ=json",
                data: param,
                dataType: 'json',
                success: function (result) {
                    if (result.status == 200) {
                        window.location.href = '/tmpl/order/buy_step2.html?goods_id=' + goods_id + '&goods_num=' + goods_num + '&address_id=' + result.data.user_address_id + '&type=' + goods_type + '&mark_id=' + mark_id + '&pt_detail_id=' + pt_detail_id;
                    }
                }
            });
        }
    });
    
    
    // 支付方式选择
    // 在线支付
    $('#payment-online').click(function () {
        pay_name = 'online';
        $('#select-payment-wrapper').find('.header-l > a').click();
        $('#select-payment-valve').find('.current-con').html('在线支付');
        $("#pay-selected").val('1');
        $(this).addClass('sel').siblings().removeClass('sel');
    });
    // 货到付款
    $('#payment-offline').click(function () {
        pay_name = 'offline';
        $('#select-payment-wrapper').find('.header-l > a').click();
        $('#select-payment-valve').find('.current-con').html('货到付款');
        $("#pay-selected").val('2');
        $(this).addClass('sel').siblings().removeClass('sel');
    });
    
    
    // 支付
    $('#ToBuyStep2').click(function () {
        if (!goods_info.common.buy_able) {
            $.sDialog({
                content: '商品【' + goods_info.base.goods_name + '】不在配送范围，请更换收货地址或者选择其他商品！',
                okBtn: true,
                cancelBtn: true,
                cancelBtnText: '取消',
                okBtnText: '返回',
                okFn: function () {
                    history.back();
                }
            });
            return false;
        }
        
        if ($("#totalPayPrice").html() >= 99999999.99) {
            $.sDialog({
                content: '订单金额过大，请分批购买！',
                okBtn: false,
                cancelBtnText: '返回',
                cancelFn: function () {
                    history.back();
                }
            });
            return false;
        }
        
        //1.获取收货地址
        var address_id = $("#address_id").val();
        if (address_id == 'undefined' || !address_id) {
            $.sDialog({
                skin: "red",
                content: '请选择收货地址！',
                okBtn: false,
                cancelBtn: false
            });
            return false;
        }
        //2.获取发票信息
        var invoice = $("#invContent").html();
        var invoice_id = $("#order_invoice_id").val();
        var invoice_title = $("#order_invoice_title").val();
        var invoice_content = $("#order_invoice_content").val();
        var remark = $("input[name='remarks']").val();
        var shop_id = goods_info.shop.shop_id;
        var pay_way_id = $("#pay-selected").val();
        var goods_id = goods_info.base.goods_id;
        var goods_num = goods_info.base.goods_num;
        var token = goods_info.token;
        $.ajax({
            type: 'post',
            url: ApiUrl + '?ctl=Buyer_Order&met=addGoodsOrder&typ=json',
            data: {
                goods_id: goods_id,
                goods_num: goods_num,
                shop_id: shop_id,
                address_id: address_id,
                invoice: invoice,
                invoice_id: invoice_id,
                invoice_title: invoice_title,
                invoice_content: invoice_content,
                remark: remark,
                pay_way_id: pay_way_id,
                k: key,
                u: getCookie('id'),
                from: "wap",
                token: token,
                goods_type: goods_type,
                mark_id: mark_id,
                pt_detail_id: pt_detail_id
            },
            dataType: "json",
            success: function (a) {
                if (a.status == 200) {
                    if (pay_way_id == 1) {
                        window.location.href = PayCenterWapUrl + "?ctl=Info&met=pay&uorder=" + a.data.uorder + "&mark_id=" + mark_id + '&pt_detail_id=' + pt_detail_id;
                        return false;
                    }
                    else {
                        window.location.href = WapSiteUrl + '/tmpl/member/order_list.html';
                        return false;
                    }
                    
                }
                else {
                    if (a.msg != 'failure') {
                        $.sDialog({
                            content: a.msg,
                            okBtn: false,
                            cancelBtnText: '返回',
                            cancelFn: function () { /*history.back();*/
                            }
                        });
                    } else {
                        $.sDialog({
                            content: '订单提交失败！',
                            okBtn: false,
                            cancelBtnText: '返回',
                            cancelFn: function () { /*history.back();*/
                            }
                        });
                    }
                }
            },
            failure: function (a) {
                $.sDialog({
                    content: '操作失败！',
                    okBtn: false,
                    cancelBtnText: '返回',
                    cancelFn: function () { /*history.back();*/
                    }
                });
            }
        });
        
    });
    
});

//获取商品信息
function getGoodsInfo(goods_id, goods_num, address_id) {
    var data = '';
    $.ajax({
        type: 'post',
        url: ApiUrl + "/index.php?ctl=Buyer_Cart&met=confirmGoods&typ=json",
        data: {k: key, u: getCookie('id'), goods_id: goods_id, goods_num: goods_num, address_id: address_id, goods_type: goods_type, mark_id: mark_id, pt_detail_id: pt_detail_id},
        dataType: 'json',
        async: false,
        success: function (result) {
            if (result.status == 200) {
                data = result.data;
            } else {
                var msg = '下单失败！';
                if (typeof(result.msg) != 'undefined' && typeof(result.msg)) {
                    msg = result.msg;
                }
                $.sDialog({
                    content: msg,
                    okBtn: false,
                    cancelBtnText: '返回',
                    cancelFn: function () {
                        history.back(-1);
                    }
                });
            }
        }
    });
    return data;
}

//显示地址
function address(address) {
    if (address.length > 0) {
        $('#list-address-add-list-ul').html('');
        $.each(address, function (i, value) {
            if ((address_id == address[i].user_address_id && address_id > 0) || (address[i].user_address_default == 1 && address_id == 0)) {
                if (address_id > 0) {
                    $('#address_id').val(address_id);
                } else {
                    $('#address_id').val(address[i].user_address_id);
                }
                
                $('#true_name').html(address[i].user_address_contact);
                $('#mob_phone').html(address[i].user_address_phone);
                $('#address').html(address[i].user_address_area + ' ' + address[i].user_address_address);
                var is_select = 'class="selected"';
            } else {
                var is_select = '';
            }
            if (address[i].user_address_default == 1) {
                var is_default = '<sub>默认</sub>';
            } else {
                var is_default = '';
            }
            var html = '<li ' + is_select + ' data-user_address_id="' + address[i].user_address_id + '" data-user_address_contact="' + address[i].user_address_contact + '" data-user_address_phone="' + address[i].user_address_phone + '" data-user_address_area="' + address[i].user_address_area + '" data-user_address_address="' + address[i].user_address_address + '"> <i></i> <dl class="col2"> <dt class="clearfix"><p id="" class="fl one-overflow w3">' + address[i].user_address_contact + '</p><p id=""class="fl ml-40">' + address[i].user_address_phone + '</p>' + is_default + '</dt><dd><span class="more-overflow wp100">' + address[i].user_address_area + '&nbsp;' + address[i].user_address_address + '</span></dd> </dl> </li>';
            $('#list-address-add-list-ul').append(html);
        });
    } else {
        $('#list-address-add-list-ul').html('');
    }
}















