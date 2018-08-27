/* nav.js zhaokun 20150709 主要应用于首页右侧导航栏 */
$(document).ready(function(){
	$('.tbar-cart-item').hover(function (){ $(this).find('.p-del').show(); },function(){ $(this).find('.p-del').hide(); });
	$('.jth-item').hover(function (){ $(this).find('.add-cart-button').show(); },function(){ $(this).find('.add-cart-button').hide(); });
	$('.toolbar-tab').hover(function (){ $(this).find('.tab-text').addClass("tbar-tab-hover"); $(this).find('.footer-tab-text').addClass("tbar-tab-footer-hover"); $(this).addClass("tbar-tab-selected");},function(){ $(this).find('.tab-text').removeClass("tbar-tab-hover"); $(this).find('.footer-tab-text').removeClass("tbar-tab-footer-hover"); $(this).removeClass("tbar-tab-selected"); });
	$('.tbar-tab-online-contact').hover(function (){ $(this).find('.tab-text').addClass("tbar-tab-hover"); $(this).find('.footer-tab-text').addClass("tbar-tab-footer-hover"); $(this).addClass("tbar-tab-selected");},function(){ $(this).find('.tab-text').removeClass("tbar-tab-hover"); $(this).find('.footer-tab-text').removeClass("tbar-tab-footer-hover"); $(this).removeClass("tbar-tab-selected"); });
	$('.tbar-tab-cart').click(function (){ 
		if($('.toolbar-wrap').hasClass('toolbar-open')){
			if($(this).find('.tab-text').length > 0){
				if(! $('.tbar-tab-follow').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的关注</em>";
					$('.tbar-tab-follow').append(info);
					$('.tbar-tab-follow').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-follow').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-contrast').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>对比商品</em>";
					$('.tbar-tab-contrast').append(info);
					$('.tbar-tab-contrast').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-contrast').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-assets').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的资产</em>";
					$('.tbar-tab-assets').append(info);
					$('.tbar-tab-assets').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-assets').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-history').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的足迹</em>";
					$('.tbar-tab-history').append(info);
					$('.tbar-tab-history').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-history').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-news').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>通知</em>";
					$('.tbar-tab-news').append(info);
					$('.tbar-tab-news').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-news').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-sav').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的收藏</em>";
					$('.tbar-tab-sav').append(info);
					$('.tbar-tab-sav').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-sav').css({'visibility':"hidden","z-index":"-1"});
				}
				$(this).addClass('tbar-tab-click-selected'); 
				$(this).find('.tab-text').remove();
				$('.tbar-panel-cart').css({'visibility':"visible","z-index":"1"});
				
			}else{
				var info = "<em class='tab-text '>我的购物车</em>";
				$('.toolbar-wrap').removeClass('toolbar-open');
				$(this).append(info);
				$(this).removeClass('tbar-tab-click-selected');
				$('.tbar-panel-cart').css({'visibility':"hidden","z-index":"-1"});
			}
			 
			
		}else{ 
			$(this).addClass('tbar-tab-click-selected'); 
			$(this).find('.tab-text').remove();
			$('.tbar-panel-cart').css({'visibility':"visible","z-index":"1"});
			$('.tbar-panel-follow').css('visibility','hidden');
			$('.tbar-panel-history').css('visibility','hidden');
			$('.tbar-panel-news').css('visibility','hidden');
			$('.tbar-panel-sav').css('visibility','hidden');
			$('.tbar-panel-contrast').css('visibility','hidden');
			$('tbar-panel-assets').css('visibility','hidden');
			$('.toolbar-wrap').addClass('toolbar-open'); 
		}
	});
	$('.tbar-tab-follow').click(function (){
		if($('.toolbar-wrap').hasClass('toolbar-open')){
			if($(this).find('.tab-text').length > 0){
				if(! $('.tbar-tab-cart').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>购物车</em>";
					$('.tbar-tab-cart').append(info);
					$('.tbar-tab-cart').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-cart').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-contrast').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>对比商品</em>";
					$('.tbar-tab-contrast').append(info);
					$('.tbar-tab-contrast').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-contrast').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-assets').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的资产</em>";
					$('.tbar-tab-assets').append(info);
					$('.tbar-tab-assets').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-assets').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-history').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的足迹</em>";
					$('.tbar-tab-history').append(info);
					$('.tbar-tab-history').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-history').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-news').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>通知</em>";
					$('.tbar-tab-news').append(info);
					$('.tbar-tab-news').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-news').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-sav').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的收藏</em>";
					$('.tbar-tab-sav').append(info);
					$('.tbar-tab-sav').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-sav').css({'visibility':"hidden","z-index":"-1"});
				}
				$(this).addClass('tbar-tab-click-selected'); 
				$(this).find('.tab-text').remove();
				$('.tbar-panel-follow').css({'visibility':"visible","z-index":"1"});
				
			}else{
				var info = "<em class='tab-text '>我的关注</em>";
				$('.toolbar-wrap').removeClass('toolbar-open');
				$(this).append(info);
				$(this).removeClass('tbar-tab-click-selected');
				$('.tbar-panel-follow').css({'visibility':"hidden","z-index":"-1"});
			}
			 
			
		}else{ 
			$(this).addClass('tbar-tab-click-selected'); 
			$(this).find('.tab-text').remove();
			$('.tbar-panel-cart').css('visibility','hidden');
			$('.tbar-panel-follow').css({'visibility':"visible","z-index":"1"});
			$('.tbar-panel-history').css('visibility','hidden');
			$('.tbar-panel-news').css('visibility','hidden');
			$('.tbar-panel-history').css('visibility','hidden');
			$('.tbar-panel-sav').css('visibility','hidden');
			$('.tbar-panel-contrast').css('visibility','hidden');
			$('tbar-panel-assets').css('visibility','hidden');
			$('.toolbar-wrap').addClass('toolbar-open'); 
		}
	});
	$('.tbar-tab-history').click(function (){
		if($('.toolbar-wrap').hasClass('toolbar-open')){
			if($(this).find('.tab-text').length > 0){
				if(! $('.tbar-tab-follow').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的关注</em>";
					$('.tbar-tab-follow').append(info);
					$('.tbar-tab-follow').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-follow').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-contrast').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>对比商品</em>";
					$('.tbar-tab-contrast').append(info);
					$('.tbar-tab-contrast').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-contrast').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-assets').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的资产</em>";
					$('.tbar-tab-assets').append(info);
					$('.tbar-tab-assets').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-assets').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-cart').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>购物车</em>";
					$('.tbar-tab-cart').append(info);
					$('.tbar-tab-cart').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-cart').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-news').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>通知</em>";
					$('.tbar-tab-news').append(info);
					$('.tbar-tab-news').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-news').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-sav').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的收藏</em>";
					$('.tbar-tab-sav').append(info);
					$('.tbar-tab-sav').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-sav').css({'visibility':"hidden","z-index":"-1"});
				}
				$(this).addClass('tbar-tab-click-selected'); 
				$(this).find('.tab-text').remove();
				$('.tbar-panel-history').css({'visibility':"visible","z-index":"1"});
				
			}else{
				var info = "<em class='tab-text '>我的足迹</em>";
				$('.toolbar-wrap').removeClass('toolbar-open');
				$(this).append(info);
				$(this).removeClass('tbar-tab-click-selected');
				$('.tbar-panel-history').css({'visibility':"hidden","z-index":"-1"});
			}
			
		}else{ 
			$(this).addClass('tbar-tab-click-selected'); 
			$(this).find('.tab-text').remove();
			$('.tbar-panel-cart').css('visibility','hidden');
			$('.tbar-panel-follow').css('visibility','hidden');
			$('.tbar-panel-news').css('visibility','hidden');
			$('.tbar-panel-sav').css('visibility','hidden');
			$('.tbar-panel-contrast').css('visibility','hidden');
			$('tbar-panel-assets').css('visibility','hidden');
			$('.tbar-panel-history').css({'visibility':"visible","z-index":"1"});
			$('.toolbar-wrap').addClass('toolbar-open'); 
		}
	});
	$('.tbar-tab-sav').click(function (){ 
		if($('.toolbar-wrap').hasClass('toolbar-open')){
			if($(this).find('.tab-text').length > 0){
				if(! $('.tbar-tab-follow').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的关注</em>";
					$('.tbar-tab-follow').append(info);
					$('.tbar-tab-follow').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-follow').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-contrast').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>对比商品</em>";
					$('.tbar-tab-contrast').append(info);
					$('.tbar-tab-contrast').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-contrast').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-cart').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>购物车</em>";
					$('.tbar-tab-cart').append(info);
					$('.tbar-tab-cart').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-cart').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-assets').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的资产</em>";
					$('.tbar-tab-assets').append(info);
					$('.tbar-tab-assets').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-assets').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-news').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>通知</em>";
					$('.tbar-tab-news').append(info);
					$('.tbar-tab-news').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-news').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-history').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的足迹</em>";
					$('.tbar-tab-history').append(info);
					$('.tbar-tab-history').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-history').css({'visibility':"hidden","z-index":"-1"});
				}
				$(this).addClass('tbar-tab-click-selected'); 
				$(this).find('.tab-text').remove();
				$('.tbar-panel-sav').css({'visibility':"visible","z-index":"1"});
				
			}else{
				var info = "<em class='tab-text '>我的收藏</em>";
				$('.toolbar-wrap').removeClass('toolbar-open');
				$(this).append(info);
				$(this).removeClass('tbar-tab-click-selected');
				$('.tbar-panel-sav').css({'visibility':"hidden","z-index":"-1"});
			}
			
		}else{ 
			$(this).addClass('tbar-tab-click-selected'); 
			$(this).find('.tab-text').remove();
			$('.tbar-panel-cart').css('visibility','hidden');
			$('.tbar-panel-follow').css('visibility','hidden');
			$('.tbar-panel-history').css('visibility','hidden');
			$('.tbar-panel-news').css('visibility','hidden');
			$('.tbar-panel-contrast').css('visibility','hidden');
			$('tbar-panel-assets').css('visibility','hidden');
			$('.tbar-panel-sav').css({'visibility':"visible","z-index":"1"});
			$('.toolbar-wrap').addClass('toolbar-open'); 
		}
	});
	$('.tbar-tab-news').click(function (){ 
		if($('.toolbar-wrap').hasClass('toolbar-open')){
			if($(this).find('.tab-text').length > 0){
				if(! $('.tbar-tab-follow').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的关注</em>";
					$('.tbar-tab-follow').append(info);
					$('.tbar-tab-follow').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-follow').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-contrast').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>对比商品</em>";
					$('.tbar-tab-contrast').append(info);
					$('.tbar-tab-contrast').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-contrast').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-cart').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>购物车</em>";
					$('.tbar-tab-cart').append(info);
					$('.tbar-tab-cart').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-cart').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-assets').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的资产</em>";
					$('.tbar-tab-assets').append(info);
					$('.tbar-tab-assets').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-assets').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-history').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的足迹</em>";
					$('.tbar-tab-history').append(info);
					$('.tbar-tab-history').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-history').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-sav').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的收藏</em>";
					$('.tbar-tab-sav').append(info);
					$('.tbar-tab-sav').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-sav').css({'visibility':"hidden","z-index":"-1"});
				}
				$(this).addClass('tbar-tab-click-selected'); 
				$(this).find('.tab-text').remove();
				$('.tbar-panel-news').css({'visibility':"visible","z-index":"1"});
				
			}else{
				var info = "<em class='tab-text '>我的通知</em>";
				$('.toolbar-wrap').removeClass('toolbar-open');
				$(this).append(info);
				$(this).removeClass('tbar-tab-click-selected');
				$('.tbar-panel-news').css({'visibility':"hidden","z-index":"-1"});
			}
			
		}else{ 
			$(this).addClass('tbar-tab-click-selected'); 
			$(this).find('.tab-text').remove();
			$('.tbar-panel-cart').css('visibility','hidden');
			$('.tbar-panel-follow').css('visibility','hidden');
			$('.tbar-panel-history').css('visibility','hidden');
			$('.tbar-panel-sav').css('visibility','hidden');
			$('.tbar-panel-contrast').css('visibility','hidden');
			$('tbar-panel-assets').css('visibility','hidden');
			$('.tbar-panel-news').css({'visibility':"visible","z-index":"1"});
			$('.toolbar-wrap').addClass('toolbar-open'); 
		}
	});
	$('.tbar-tab-assets').click(function (){ 
		if($('.toolbar-wrap').hasClass('toolbar-open')){
			if($(this).find('.tab-text').length > 0){
				if(! $('.tbar-tab-follow').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的关注</em>";
					$('.tbar-tab-follow').append(info);
					$('.tbar-tab-follow').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-follow').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-cart').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>购物车</em>";
					$('.tbar-tab-cart').append(info);
					$('.tbar-tab-cart').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-cart').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-contrast').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>对比商品</em>";
					$('.tbar-tab-contrast').append(info);
					$('.tbar-tab-contrast').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-contrast').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-history').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的足迹</em>";
					$('.tbar-tab-history').append(info);
					$('.tbar-tab-history').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-history').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-news').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>通知</em>";
					$('.tbar-tab-news').append(info);
					$('.tbar-tab-news').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-news').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-sav').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的收藏</em>";
					$('.tbar-tab-sav').append(info);
					$('.tbar-tab-sav').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-sav').css({'visibility':"hidden","z-index":"-1"});
				}
				$(this).addClass('tbar-tab-click-selected'); 
				$(this).find('.tab-text').remove();
				$('.tbar-panel-assets').css({'visibility':"visible","z-index":"1"});
				
			}else{
				var info = "<em class='tab-text '>我的资产</em>";
				$('.toolbar-wrap').removeClass('toolbar-open');
				$(this).append(info);
				$(this).removeClass('tbar-tab-click-selected');
				$('.tbar-panel-assets').css({'visibility':"hidden","z-index":"-1"});
			}
			
		}else{ 
			$(this).addClass('tbar-tab-click-selected'); 
			$(this).find('.tab-text').remove();
			$('.tbar-panel-cart').css('visibility','hidden');
			$('.tbar-panel-follow').css('visibility','hidden');
			$('.tbar-panel-history').css('visibility','hidden');
			$('.tbar-panel-sav').css('visibility','hidden');
			$('.tbar-panel-news').css('visibility','hidden');
			$('.tbar-panel-contrast').css('visibility','hidden');
			$('.tbar-panel-assets').css({'visibility':"visible","z-index":"1"});
			$('.toolbar-wrap').addClass('toolbar-open'); 
		}
	});
	$('.tbar-tab-contrast').click(function (){ 
		if($('.toolbar-wrap').hasClass('toolbar-open')){
			if($(this).find('.tab-text').length > 0){
				if(! $('.tbar-tab-follow').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的关注</em>";
					$('.tbar-tab-follow').append(info);
					$('.tbar-tab-follow').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-follow').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-cart').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>购物车</em>";
					$('.tbar-tab-cart').append(info);
					$('.tbar-tab-cart').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-cart').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-assets').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的资产</em>";
					$('.tbar-tab-assets').append(info);
					$('.tbar-tab-assets').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-assets').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-news').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>通知</em>";
					$('.tbar-tab-news').append(info);
					$('.tbar-tab-news').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-news').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-history').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的足迹</em>";
					$('.tbar-tab-history').append(info);
					$('.tbar-tab-history').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-history').css({'visibility':"hidden","z-index":"-1"});
				}
				if(! $('.tbar-tab-sav').find('.tab-text').length > 0){
					var info = "<em class='tab-text '>我的收藏</em>";
					$('.tbar-tab-sav').append(info);
					$('.tbar-tab-sav').removeClass('tbar-tab-click-selected'); 
					$('.tbar-panel-sav').css({'visibility':"hidden","z-index":"-1"});
				}
				$(this).addClass('tbar-tab-click-selected'); 
				$(this).find('.tab-text').remove();
				$('.tbar-panel-contrast').css({'visibility':"visible","z-index":"1"});
				
			}else{
				var info = "<em class='tab-text '>对比商品</em>";
				$('.toolbar-wrap').removeClass('toolbar-open');
				$(this).append(info);
				$(this).removeClass('tbar-tab-click-selected');
				$('.tbar-panel-contrast').css({'visibility':"hidden","z-index":"-1"});
			}
			
		}else{ 
			$(this).addClass('tbar-tab-click-selected'); 
			$(this).find('.tab-text').remove();
			$('.tbar-panel-cart').css('visibility','hidden');
			$('.tbar-panel-follow').css('visibility','hidden');
			$('.tbar-panel-history').css('visibility','hidden');
			$('.tbar-panel-news').css('visibility','hidden');
			$('.tbar-panel-sav').css('visibility','hidden');
			$('tbar-panel-assets').css('visibility','hidden');
			$('.tbar-panel-contrast').css({'visibility':"visible","z-index":"1"});
			$('.toolbar-wrap').addClass('toolbar-open'); 
		}
	});
	$(".close_p").click(function(){
		$(".toolbar-wrap").removeClass("toolbar-open");
		$(".toolbar-panel").css("visibility","hidden");
		$(".toolbar-tab").removeClass("tbar-tab-click-selected");
		$(".tbar-tab-news").removeClass("tbar-tab-click-selected");
		
	})
	function not_shop()
	{
		Public.tips.warning('非商家用户不可进入批发市场！');
	}
});