var goods_id = getQueryString("goods_id");
var cid = getQueryString("cid");
var lbs_geo = getCookie('lbs_geo');
var pt_detail_id = getQueryString("pt_detail_id") ? getQueryString("pt_detail_id") : 0;
var rec = getQueryString('rec');

var share_goods_pic = '';
var share_goods_text = '';
var share_goods_url = '';
if(rec)
{
    var mydate = new Date();
    addCookie('recserialize',rec,mydate.getTime()+60*60*24*3,'/');
}

var option_window = false; //当前弹框
//如果没有goods_id，则根据cid获取goods_id
if (!goods_id) {
    $.sDialog({
        content: '商品不存在',
        okBtn:true,
        okFn: function() {
            window.location.href = WapSiteUrl+'/tmpl/pintuan_index.html'; 
        }
    });
}


var map_list = [];
var map_index_id = '';
var shop_id;
$(function () {
    var key = getCookie('key');

    // 图片轮播
    function picSwipe() {
        var elem = $("#mySwipe")[0];
        window.mySwipe = Swipe(elem, {
            continuous: false,
            // disableScroll: true,
            stopPropagation: true,
            callback: function (index, element) {
                $('.goods-detail-turn').find('li').eq(index).addClass('cur').siblings().removeClass('cur');
            }
        });
        
    }

    get_detail(goods_id); //获取商品详情
    getGoodsNewReview(goods_id); // 获取商品评论

    function get_detail(goods_id) {
        
        if (lbs_geo == '' || lbs_geo == 'undefined' || lbs_geo == null) {
            loadScriptBaiduLbs();
            var lbs_geo = getCookie('lbs_geo');
        }
        //渲染页面
        $.ajax({
            url: ApiUrl + "/index.php?ctl=Goods_Goods&met=goods&typ=json",
            type: "get",
            data: {goods_id: goods_id, k: key, u: getCookie('id'), cid: cid, lbs_geo: lbs_geo,ua:"wap",pt_detail_id:pt_detail_id},
            dataType: "json",
            success: function (resp) {
                if (resp.status == 200) {
                    var goods_info = resp.data.goods_info;
                    if(typeof(goods_info.pintuan_info) == 'undefined' || typeof(goods_info.pintuan_info.detail) == 'undefined'){
                        window.location.href = WapSiteUrl+'/tmpl/product_detail.html?goods_id='+resp.data.goods_id; 
                    }
                    var pintuan_info = goods_info.pintuan_info;
                    //商品信息
                    var goods_image = resp.data.goods_image.split(',');
                    if(goods_image)
                    {
                        var image_html = '';
                        for(var i in goods_image){
                            image_html = image_html + '<li class="swiper-slide"><img src="'+ goods_image[i] +'" /></li>';
                        }
                        $('#pt_goods_image').html(image_html);
                        imageChange();
                    }
                    var goods_spec = resp.data.goods_info.goods_spec;
                    var goods_spec_html = '';
                    if(goods_spec)
                    {
                        for(var j in goods_spec){
                            goods_spec_html += goods_spec[j]+"&nbsp;";
                            $("#pt_goods_spec").html(goods_spec_html);
                        }
                    }else{
                        $('#spec').hide();
                    }
                    //分享数据
                    share_goods_pic = resp.data.goods_one_image;
                    share_goods_text = goods_info.goods_name;
                    share_goods_url = WapSiteUrl+'/tmpl/pintuan_detail.html?goods_id='+goods_id+'&pt_detail_id='+pintuan_info.detail.id;
                    
                    $('.pt_goods_image').attr('src',resp.data.goods_one_image);
                    $('#goods_stock').html(resp.data.goods_info.goods_stock+'件');
                    $('#price').html('￥'+pintuan_info.detail.price);
                    $('#pt_person_num').html(pintuan_info.person_num);
                    $('.pt_goods_name').html(goods_info.goods_name);
                    $('#pt_price').html(pintuan_info.detail.price);
                    $('#pt_price_one').html(pintuan_info.detail.price_one);
                    $('#pt_price_ori').html(pintuan_info.detail.price_ori);
                    $('#pt_goods_stock').html(resp.data.goods_info.goods_stock);
                    $('#pt_buyer_num').html(pintuan_info.detail.buyer_num);
                    $('#bt_price').html(pintuan_info.detail.price);
                    $('#bt_price_one').html(pintuan_info.detail.price_one);
                    //if(typeof(resp.data.goods_hair_info.transport_data) != 'undefined'){
                    //    $('#pt_transport_fee').html(resp.data.goods_hair_info.transport_data.transport_str);
                    //} else {
                    //    $('#pt_transport_fee').html(resp.data.goods_hair_info.content);
                    //}

                    //库存量小于拼团人数
                    if(Number(resp.data.goods_info.goods_stock) < Number(pintuan_info.person_num))
                    {
                        $(".btn_style2").addClass("low");
						 $('.btn_style2').attr('href', 'javascript:;');  
                    }

                    //团组信息
                    if(pintuan_info.mark_list > 2){
                        $('#pt_mark_url').attr('href',WapSiteUrl+'/tmpl/pintuan_mark.html?detail_id='+pintuan_info.detail.id);
                    }else if(pintuan_info.mark_list <= 0){
                        $('#pt_mark_url').hide();
                    }else{
                        $("#more").html('');
                    }

                    var pintuan_mark = pintuan_info.mark;
                    var pintuan_mark_html = '';
                    if(pintuan_mark.length != 0){
                        for(var i in pintuan_mark){
                            var buyer_diff_num = (pintuan_mark[i].person_num-pintuan_mark[i].buyer_count);
                            if(buyer_diff_num <= 0){
                                continue;
                            }
                            pintuan_mark_html = pintuan_mark_html + '<li class="clearfix"> <img src="'+pintuan_mark[i].user_logo+'" class="fl"> <div class="clearfix pt_det_lists_text fl">'+
                            '<div class="fl"><strong>'+pintuan_mark[i].user_name+'</strong></div> <div class="fr"> <p class="fl"><strong>还差'+buyer_diff_num+'人成团</strong><em>'+pintuan_info.hour+':'+pintuan_info.min+':'+pintuan_info.second+'后结束</em></p>'+
                            '<p class="fr"><a class="gobuy" href="'+WapSiteUrl+'/tmpl/pintuan_info.html?detail_id='+pintuan_info.detail.id+'&mark_id='+pintuan_mark[i]['id']+'">去拼团</a></p> </div> </div> </li>';
                        }
                    }


                    $('#pt_mark_list').html(pintuan_mark_html);
                    pt_detail_id = pintuan_info.detail.id;
                    //店铺
                    var store_info = resp.data.store_info;
                    $('#pt_store_url').attr('href',WapSiteUrl+'/tmpl/store.html?shop_id='+store_info.store_id);
                    $('#pt_store_name').html(store_info.store_name);
                    $('#pt_member_id').val(store_info.member_id);
                    $('#store_grade_1').html(store_info.store_credit.store_desccredit.credit);
                    $('#store_grade_2').html(store_info.store_credit.store_servicecredit.credit);
                    $('#store_grade_3').html(store_info.store_credit.store_deliverycredit.credit);
                    //规格
                    $('#goods_spec_str').html(goods_info.show_goods_spec_str);
                    var goods_map_spec = $.map(goods_info.common_spec_name, function (v, i) {
                        var goods_specs = {};
                        goods_specs["goods_spec_id"] = i;
                        goods_specs['goods_spec_name'] = v;
                        if (goods_info.common_spec_value_c) {
                            $.map(goods_info.common_spec_value_c, function (vv, vi) {
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
                            goods_info.common_spec_value = [];
                        }
                    });
                    var goods_spec_html = '';
                    if (!isEmpty(goods_info.common_spec_name)){
                        if(goods_map_spec.length>0){
                            for(var x =0;x<goods_map_spec.length;x++){
                                goods_spec_html = goods_spec_html +  '<dl class="spec JS-goods-specs "> <dt>'+goods_map_spec[x].goods_spec_name+'：</dt> <dd>';
                                for(var y = 0;y<goods_map_spec[x].goods_spec_value.length;y++){
                                    if (goods_info.goods_spec[goods_map_spec[x].goods_spec_value[y].specs_value_id]){
                                        goods_spec_html = goods_spec_html + '<a href="javascript:void(0);" class="current">'+goods_map_spec[x].goods_spec_value[y].specs_value_name+'</a>';
                                    }
                                }
                                goods_spec_html = goods_spec_html + '</dd> </dl>';
                            }
                        }
                    }
                    $('#goods_spec_html').html(goods_spec_html);
                    

                }
            }

        });
    }

});


//评论
function getGoodsNewReview(goods_id) {
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
                var goodsReviewHtml = template.render('goodsReview', result.data);
                $("#pt_goods_review").append(goodsReviewHtml);
            }
        }
    });
}

//购买
$('.js-buy').on( 'click', function ( e ) {
    var type = $(this).data('type'),
        param = [
            'goods_id=' + goods_id,
            'goods_num=1',
            'pt_detail_id='+pt_detail_id,
            'type=' + type //单独购买、拼单购买
        ];
    var user_id = getCookie('id');//登录user_id
    var pintuan_uid = $('#pt_member_id').val();//店铺user_id
    if(user_id == pintuan_uid)
    {
        $.sDialog({
            content: '商家不可以购买自己店铺的商品！',
            okBtn:false,
            cancelBtnText:'返回',
        });
        return false;
    }
    if(type == 'pintuan')
    {
        $(this).css('background','#e63146');
        if(Number($('#pt_person_num').html()) > Number($('#pt_goods_stock').html()))
        {
            return false;
        }
    }else
    {
        $(this).css('background','#ffa2ad');
    }
    window.location.href = WapSiteUrl + '/tmpl/order/buy_step2.html?' + param.join('&');
});

$(document).on('click','.gobuy',function(){
    var user_id = getCookie('id');//登录user_id
    var pintuan_uid = $('#pt_member_id').val();//店铺user_id
    if(user_id == pintuan_uid)
    {
        $.sDialog({
            content: '商家不可以购买自己店铺的商品！',
            okBtn:false,
            cancelBtnText:'返回',
        });
        return false;
    }
})

$(document).on('click','.goods_geval a', function(){
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

function more_review(){
     window.location.href = WapSiteUrl + '/tmpl/product_eval_list.html?goods_id=' + goods_id;
}

//规格和分享的显示和隐藏
function show_mask(id,flag){
    var goods_spec = $('#'+id);
    if(flag == 1){
        //分享使用
//        if(id == 'share_mask'){
//            if(!share_goods_url || !share_goods_text || !share_goods_pic){
//                return false;
//            }
//            window._bd_share_config = {
//                common : {
//                    bdText : share_goods_text,	
//                    bdDesc : share_goods_text,
//                    bdUrl : share_goods_url, 	
//                    bdPic : share_goods_pic
//                },
//                share : [{
//                    "bdSize" : 32
//                }],
//
//                image : [{
//                    viewType : 'list',
//                    viewPos : 'top',
//                    viewColor : 'black',
//                    viewSize : '16',
//                    viewList : ["tsina","qzone"]
//                }]
//
//            }
//        }
        goods_spec.addClass('up');
        goods_spec.removeClass('down');
        
    }else{
        goods_spec.addClass('down');
        goods_spec.removeClass('up');
    }
}


function isEmpty(o){
    for (var i in o) {
        return false;
    }
    return true;
}

function imageChange(){
    var swiper = new Swiper('.swiper-pt-banner', {
        pagination: '.swiper-pt-pagination',
        paginationClickable: true,
        autoplay: 3000,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
    });
}

function cpurl(id){

    var url = share_goods_url != '' ?  share_goods_url : WapSiteUrl+'/tmpl/pintuan_detail.html?goods_id='+goods_id+'&pt_detail_id='+pt_detail_id;
        
    var clipboard = new Clipboard('#cp_url_link', {
        text: function() {
            return url;
        }
    });

    clipboard.on('success', function(e) {
        show_mask(id,false);
        $.sDialog({
            content: '链接复制成功！',
            okBtn:false,
            cancelBtn:false
        });
    });

    clipboard.on('error', function(e) {
        $.sDialog({
            content: '链接复制失败！',
            okBtn:false,
            cancelBtn:false
        });
    });
    return false;

}