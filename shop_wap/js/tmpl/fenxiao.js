$(function(){
   var e = getCookie("key");
    if (!e) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html"
    }
 
    $.ajax({
        type:'post',
        url:ApiUrl+"/?ctl=Fenxiao&met=wapIndex&typ=json",
        data:{k:e,u:getCookie('id')},
        dataType:'json',
        //jsonp:'callback',
        success:function(result){
            checkLogin(result.login);
			if(result.data.shop_name){
				var shop_name = result.data.shop_name;
				var shop_url = '/tmpl/store.html?shop_id='+result.data.shop_id;	
			}else{
				var shop_name = '尚未设置';
				var shop_url = 'javascript:;';
			}
            var html = '<div class="member-info">'
                    + '<div class="user-avatar"> <img src="' + result.data.user_logo + '"/> </div>'
                    + '<div class="user-name"> <span>'+result.data.user_name+'<sup style="padding:0rem">V' + result.data.user_grade + '</sup></span> </div>'
                    + '</div>'+'<div class="member-collect"><span><a href="'+shop_url+'"><em>' +shop_name+ '</em>'
                + '</a> </span></div>';
            //渲染页面
            
             $(".member-top").html(html);
            
            var html = '<li><a class="br" href="fenxiao_userspread.html">'+'<p class="number">'+result.data.invitors+'</p><p>推广用户</p></a></li>'
                + '<li style="border-bottom: solid 0.05rem #EEE;"><a class="br" href="fenxiao_order.html">'+'<p class="number">'+result.data.promotion_order_nums+'</p><p>推广订单</p></a></li>'
				
                + '<li><a href="fenxiao_commission.html" class="br">'+ '<p class="number">'+result.data.user_fenxiao_commission+'</p><p>结算佣金</p></a></li>';
            //渲染页面
            
            $("#order_ul").html(html);
            

            return false;
        }
    });
	
	//滚动header固定到顶部
	$.scrollTransparent();
 
});