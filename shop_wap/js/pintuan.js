$(function () {
    
    var param = {
        k: getCookie("key"),
        u: getCookie("id"),
        
    };
    
    if (!getCookie('sub_site_id')) {
        addCookie('sub_site_id', 0, 0);
    }
    
    
    function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    }
    
    
    var cat_id = getQueryString("cat_id");
    
    
    var sub_site_id = getCookie('sub_site_id');
    $.ajax({
        url: ApiUrl + "/index.php?ctl=PinTuan&met=index&typ=json&ua=wap&sub_site_id=" + sub_site_id + '&cat_id=' + cat_id,
        type: 'get',
        dataType: 'json',
        data: param,
        success: function (resp) {
            if (resp.status == 200) {
                //分类
                var category = resp.data.category;
                if (category) {
                    var cat_html = '<a href="' + WapSiteUrl + '/tmpl/pintuan_index.html?cat_id=0" class="swiper-slide">全部</a>';
                    for (var i in category) {
                        var active_class = '';
                        if (resp.data.cat_id == category[i].cat_id) {
                            active_class = 'active';
                        }
                        cat_html = cat_html + '<a href="' + WapSiteUrl + '/tmpl/pintuan_index.html?cat_id=' + category[i].cat_id + '" class="swiper-slide ' + active_class + '">' + category[i].cat_name + '</a>';
                    }
                    $('#pt_category').html(cat_html);
                    navChange();
                }
                //banner
                var banner = resp.data.banner;
                
                if (banner) {
                    var banner_html = '';
                    for (var a in banner) {
                        banner_html = banner_html + '<li class="swiper-slide"><a href="' + banner[a].link + '"><img src="' + banner[a].image + '" alt=""></a></li>';
                    }
                    $('#pt_banner').html(banner_html);
                    imageChange();
                    
                }
                //商品
                var goods = resp.data.goods;
                console.log(goods);
                if (goods) {
                    var goods_html = '';
                    for (var b in goods) {
                        if (goods[b].goods.goods_stock > 0) {
                            goods_html = goods_html + '<li><a href="' + WapSiteUrl + '/tmpl/pintuan_detail.html?goods_id=' + goods[b].detail.goods_id + '&pt_detail_id=' + goods[b].detail.id + '"><img src="' + goods[b].goods.goods_image + '"></a><div class="pt_goods_title">' +
                                '<strong class="common-red">[' + goods[b].person_num + '人团]</strong><p>' + goods[b].goods.goods_name + '</p></div> <dl class="clearfix"> <dt class="fl">' +
                                '<p class="part1"><strong class="common-red">￥' + goods[b].detail.price + '</strong><b>单买价：￥' + goods[b].detail.price_one + '</b></p> <p class="part2"><b class="through">原价：￥' + goods[b].detail.price_ori + '</b><b>已拼' + goods[b].detail.buyer_num + '件</b></p>' +
                                '</dt> <dd class="fr"><a href="' + WapSiteUrl + '/tmpl/pintuan_detail.html?goods_id=' + goods[b].detail.goods_id + '&pt_detail_id=' + goods[b].detail.id + '" class="pt_btn_go tc" style="background:#fd3d53">去拼团</a></dd> </dl> </li>';
                        }
                    }
                    $('#pt_goods').html(goods_html);
                }
                
                var _TimeCountDown = $(".fnTimeCountDown");
                _TimeCountDown.fnTimeCountDown();
            } else {
                $.sDialog({skin: "red", content: "网络异常", okBtn: false, cancelBtn: false});
            }
        }
    });
});

$(document).on('click', '.pt_btn_go', function () {
    $(this).css('background', '#e63146');
});

function navChange() {
    var mySwiper = new Swiper('.pt_nav', {
//        freeMode: true,
//        freeModeMomentumRatio: 0.5,
        slidesPerView: 'auto',
        spaceBetween: 30
    });
}

function imageChange() {
    var swiper = new Swiper('.swiper-pt-banner', {
        pagination: '.swiper-pt-pagination',
        paginationClickable: true,
        autoplay: 3000,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        spaceBetween: 30
    });
}

