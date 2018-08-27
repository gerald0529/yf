var id = getQueryString("id"),
    points_goods_storage = 0;

//如果没有goods_id，则根据cid获取goods_id
if(id)
{
    $.ajax({
        url: ApiUrl + "/index.php?ctl=Points&met=detail&typ=json",
        type: "POST",
        data: {id: id},
        dataType: "json",
        success: function ( data )
        {
            if(data.status == 200)
            {
                data = data.data;
                var html_detail = template.render('product_detail', data);
                var html_detail_spec = template.render('product_detail_spec', data);
                $("#product_detail_html").html(html_detail);
                $("#product_detail_spec_html").html(html_detail_spec);

                points_goods_storage = data.goods_detail.points_goods_storage;

            } else {
                $.sDialog({
                    skin:"red",
                    content:data.msg,
                    okBtn:false,
                    cancelBtn:false
                });
            }
        }
    });
}

function show_tip() {
    var flyer = $('.goods-pic > img').clone().css({'z-index':'999','height':'3rem','width':'3rem'});
    flyer.fly({
        start: {
            left: $('.goods-pic > img').offset().left,
            top: $('.goods-pic > img').offset().top-$(window).scrollTop()
        },
        end: {
            left: $("#cart_count1").offset().left+40,
            top: $("#cart_count1").offset().top-$(window).scrollTop(),
            width: 0,
            height: 0
        },
        onEnd: function(){
            flyer.remove();
        }
    });
}

$(function (){

    $.animationUp({
        valve : '.animation-up,#goods_spec_selected',           // 动作触发
        wrapper : '#product_detail_spec_html',                  // 动作块
        scroll : '#product_roll',                               // 滚动块，为空不触发滚动
        start : function(){                                     // 开始动作触发事件
            $('.goods-detail-foot').addClass('hide').removeClass('block');
        },
        close : function(){                                     // 关闭动作触发事件
            $('.goods-detail-foot').removeClass('hide').addClass('block');
        }
    });

    $('body').on('click', '#productBody', function(){
        window.location.href = WapSiteUrl+'/tmpl/integral_product_info.html?id=' + id;
    });

    //购买数量，减
    $("#product_detail_spec_html").on("click", ".minus", function (){
        var buynum = $(".buy-num").val();
        if(buynum >1){
            $(".buy-num").val(parseInt(buynum-1));
        }
    });
    //购买数量加
    $("#product_detail_spec_html").on("click", ".add", function (){
        var buynum = parseInt($(".buy-num").val());
        if(buynum < points_goods_storage){
            $(".buy-num").val(parseInt(buynum+1));
        }
    });

    //加入购物车
    $("body").on("click", "#add-cart", function () {

        var key = getCookie('key');//登录标记

        if (!key) {
            window.location.href = WapSiteUrl + '/tmpl/member/login.html';
            return false;
        }
    })
    //点击立即兑换
    $('body').on('click', '#buy-now', function(){
        var quantity = parseInt($(".buy-num").val()) || 0;

        if (quantity < 1) {
            return $.sDialog({
                skin:"red",
                content:'参数错误！',
                okBtn:false,
                cancelBtn:false
            });
        }

        if ( points_goods_storage && quantity > points_goods_storage ) {
            return $.sDialog({
                skin:"red",
                content:'库存不足！',
                okBtn:false,
                cancelBtn:false
            });
        }
        
        var param = {
            k: getCookie('key'),
            u: getCookie('id'),
            points_goods_id: id,
            quantity: quantity
        };

        if(!getCookie('key'))
        {
            $.sDialog({
                skin: "red",
                content: '需要登录！',
                okBtn: true,
                cancelBtn: true,
                okFn: function (){
                    callback = window.location.href;
                    login_url   = UCenterApiUrl + '?ctl=Login&met=index&typ=e';

                    callback = ApiUrl + '?ctl=Login&met=check&typ=e&redirect=' + encodeURIComponent(callback);

                    login_url = login_url + '&from=wap&callback=' + encodeURIComponent(callback);

                    window.location.href = login_url;
                },
                cancelFn:function (){

                }
            });
            return;
        }

        $.ajax({
            url: ApiUrl+"/index.php?ctl=Points&met=addPointsCart&typ=json",
            data: param,
            type: "POST",
            success: function ( data ){

                if( data.status == 200 ) {
                    // show_tip();
                    location.href = WapSiteUrl + "/tmpl/integral_cart_list.html";
                } else {
                    $.sDialog({
                        skin: "red",
                        content: data.msg,
                        okBtn: false,
                        cancelBtn: false
                    });
                }
            }
        });
    })
});
