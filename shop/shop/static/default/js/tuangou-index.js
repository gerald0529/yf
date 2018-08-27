$(document).ready(function(){
	$(".tg_locatp").click(function(){
		var tgP=$(this).find("p").css("display");
		if(tgP=="none"){
			$(this).find("p").show();
		}else{
			$(this).find("p").hide()
		}
		
	})
	$(".classic").mouseover(function(){
		var tleft=$(this).find(".tleft").css("display");
			$(this).find(".tleft").show();
	})
	$(".classic").mouseout(function(){
		var tleft=$(this).find(".tleft").css("display");
			$(this).find(".tleft").hide();
	})
	$('.bbc-store-info').hover(function(){
		$(this).find(".sub").show();
	},function(){
		$(this).find(".sub").hide();
	})
});

/* jQuery(function($){
	$('.tg_center').slideBox({
		duration : 0.3,//滚动持续时间，单位：秒
		easing : 'linear',//swing,linear//滚动特效
		delay : 5,//滚动延迟时间，单位：秒
		hideClickBar : false,//不自动隐藏点选按键
		clickBarRadius : 10
	});
	
}); */

