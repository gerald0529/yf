var id = getQueryString("groupbuy_id")

//如果没有goods_id，则根据cid获取goods_id
if(id)
{
    $.ajax({
        url: ApiUrl + "/index.php?ctl=GroupBuy&met=detail&typ=json",
        type: "POST",
        data: {id: id},
        dataType: "json",
        success: function ( data )
        {
            if(data.status == 200)
            {
                data = data.data;
                var html_detail = template.render('product_detail', data);
                $("#product_detail_html").html(html_detail);

                var _TimeCountDown = $(".fnTimeCountDown");
                _TimeCountDown.fnTimeCountDown();

                $('body').on('click', '.ljgm', function(){
                    window.location.href = WapSiteUrl+'/tmpl/product_detail.html?goods_id=' + data.groupbuy_detail.goods_id;
                });
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
        window.location.href = WapSiteUrl+'/tmpl/group_buy_info.html?id=' + id;
    });
});
