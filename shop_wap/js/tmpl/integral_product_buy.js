var key = getCookie('key');
$(function() {

    //判断当前页面是否为积分商品页面
    //这是一个坑，积分商品和普通商品混在了一起，要特别小心

    if (window.location.href.indexOf('integral_product_buy.html') == -1) {
        return false;
    }

    var isIntegral = getQueryString("isIntegral"), sumPoints = getQueryString("sumPoints"), point_cart_id = getQueryString("point_cart_id") ? getQueryString("point_cart_id").split(",") : [];
    address_list = [];

    if ( point_cart_id.length > 0 ) {
        $(".check-out").addClass("ok");
    }

    $(".nctouch-cart-block.mt5").remove();

    $(".nctouch-cart-bottom").find("dt").html("支付总积分：");
    $(".nctouch-cart-bottom").find("dd").html("<em id='totalPayPrice'>" + sumPoints + "</em>");

    $('#ToBuyStep2').unbind("click");
    //加载页面
    initPointProductList();
    function initPointProductList() {
        $.ajax({
            type:'POST',
            url: ApiUrl  + '?ctl=Points&met=confirm&typ=json',
            data: {
                points_cart_id: point_cart_id,
                k: key,
                u: getCookie('id')
            },
            dataType: "json",
            success: function( resp ) {

                if ( resp.status == 200 ) {
                    if(typeof(resp.data.address) != 'undefined' && resp.data.address){
                        address_list = resp.data.address;
                        var default_address = resp.data.address[0];
                        $('#address_id').val(default_address.user_address_id);
                        $('#true_name').html(default_address.user_address_contact);
                        $('#mob_phone').html(default_address.user_address_phone);
                        $('#address').html(default_address.user_address_area);
                    }
                    var list = resp.data.items;
                    if(!list){
                        $.sDialog({
                        content: '暂无兑换商品',
                        okBtn:true,
                        okFn: function() {
                            window.location.href = WapSiteUrl+'/tmpl/integral_cart_list.html'; 
                        }
                    });
                    }
                    $('#point_goods_list').html();  
                    for(var k in list){
                        var html = '<li class="buy-item borb1" data-goods_name="'+list[k].points_goods_name+'" ><div class="buy-li clearfix">'+
                            '<div class="goods-pic"><a href="'+WapSiteUrl+'"/tmpl/product_detail.html?goods_id="'+list[k].points_goods_id+'"> <img src="'+list[k].points_goods_image+'"/></a> </div>'+
                            '<dl class="goods-info fl pl-20"><dt class="goods-name"> <a href="'+WapSiteUrl+'"/tmpl/product_detail.html?goods_id="'+list[k].points_goods_id+'"> '+list[k].points_goods_name+'</a> </dt> </dl>'+
                            '<div class="goods-subtotal"> <span class="goods-price"><em>'+list[k].points_goods_points+'</em>积分</span> </div>'+
                            '<div class="goods-num"> <em>x'+list[k].points_goods_choosenum+'</em>  </div>'+
                            '</div></li>';
                        $('#point_goods_list').append(html);    
                    }
                    
                } else {
                    $.sDialog({
                        content: data.msg,
                        okBtn:true,
                        okFn: function() {
                            window.location.href = WapSiteUrl+'/tmpl/integral_cart_list.html'; 
                        }
                    });
                }
            }
        })
    }
    //提交订单
    $('#ToBuyStep2').on("click", function() {

        var address_id = $("#address_id").val();

        if( !address_id ) {
            return $.sDialog({
                skin:"red",
                content:'请选择收货地址！',
                okBtn:false,
                cancelBtn:false
            });
        }

        //1.获取收货地址
        var param = {
            k: key,
            u: getCookie('id'),
            receiver_name: $("#true_name").html(),
            receiver_address: $("#address").html(),
            receiver_phone: $("#mob_phone").html(),
            point_cart_id: point_cart_id,
            remark: $("#remark").val()
        };
        $.ajax({
            type:'POST',
            url: ApiUrl  + '?ctl=Points&met=addPointsOrder&typ=json',
            data: param,
            dataType: "json",
            success: function( data ) {

                if ( data.status == 200 ) {
                    $.sDialog({
                        content: data.msg,
                        okBtn:true,
                        okFn: function() {
                            window.location.href = WapSiteUrl+'/tmpl/member/member.html'; //没有对应页面，先跳到这里
                        }
                    });
                } else {
                    $.sDialog({
                        content: data.msg,
                        okBtn:false,
                        cancelBtnText:'返回',
                        cancelFn: function() { history.back(); }
                    });
                }
            }
        });
    });

    template.helper('isEmpty', function(o) {
        var b = true;
        $.each(o, function(k, v) {
            b = false;
            return false;
        });
        return b;
    });
    // 地址列表
    $('#list-address-valve').click(function(){
        var address_id = $(this).find("#address_id").val();
        var data = new Array();
        data.address_list = address_list;
        data.address_id = address_id;
        var html = template.render('list-address-add-list-script', data);
        $("#list-address-add-list-ul").html(html);
    });
    $.animationLeft({
        valve : '#list-address-valve',
        wrapper : '#list-address-wrapper',
        scroll : '#list-address-scroll'
    });

    // 地区选择
    $('#list-address-add-list-ul').on('click', 'li', function(){
        $(this).addClass('selected').siblings().removeClass('selected');
        eval('address_info = ' + $(this).attr('data-param'));
        $('#true_name').html(address_info.user_address_contact);
        $('#mob_phone').html(address_info.user_address_phone);
        $('#address').html(address_info.user_address_area + address_info.user_address_address);
        $("#address_id").val(address_info.user_address_id);
        $('#list-address-wrapper').find('.header-l > a').click();
    });

    // 地址新增
    $.animationLeft({
        valve : '#new-address-valve',
        wrapper : '#new-address-wrapper',
        scroll : ''
    });
    
    // 地区选择
    $('#new-address-wrapper').on('click', '#varea_info', function(){

        $.areaSelected({
            success : function(data){
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
        rules:{
            vtrue_name:"required",
            vmob_phone:"required",
            varea_info:"required",
            vaddress:"required"
        },
        messages:{
            vtrue_name:"姓名必填！",
            vmob_phone:"手机号必填！",
            varea_info:"地区必填！",
            vaddress:"街道必填！"
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                errorTipsShow(errorHtml);
            }else{
                errorTipsHide();
            }
        }
    });
    //新增地区
    $('#add_address_form').find('.btn').click(function(){
        if($.sValid()){
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
                type:'post',
                url:ApiUrl+"/index.php?ctl=Buyer_User&met=addAddressInfo&typ=json",
                data:param,
                dataType:'json',
                success:function(result){
                    if (result.status == 200) {
                        address_list.push(result.data);
                        $('#true_name').html(result.data.user_address_contact);
                        $('#mob_phone').html(result.data.user_address_phone);
                        $('#address').html(result.data.user_address_area + result.data.user_address_address);
                        $("#address_id").val(result.data.user_address_id);
                        $('#new-address-wrapper,#list-address-wrapper').find('.header-l > a').click();
                    }
                }
            });
        }
    });
});


