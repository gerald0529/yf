/**
 * @author     朱羽婷
 */
$(document).ready(function(){
	// 满即送、加价购显示隐藏
	$('[nctype="show-rule"]').click(function(){
		$(this).parent().find('[nctype="rule-content"]').show();
	});
	$('[nctype="hide-rule"]').click(function(){
		$(this).parents('[nctype="rule-content"]:first').hide()
	});

	$('.goods_det').find("li a").click(function(){
		$('.goods_det').find("li a").removeClass('checked');
		$(this).addClass('checked');
	});


	//评价列表
	load_goodseval(SITE_URL  + '?ctl=Supplier_Goods&met=getGoodsEvaluationList&common_id=' + common_id + '&type=all','goodseval');
	
	//销售列表
	load_goodseval(SITE_URL  + '?ctl=Supplier_Goods&met=getGoodsSaleList&goods_id=' + goods_id,'saleseval');

    //咨询
    load_goodseval(SITE_URL  + '?ctl=Supplier_Goods&met=getConsultListRows&goods_id=' + goods_id,'goodsadvisory');
	
	function load_goodseval(url,div) {
		$("#" + div).load(url, function(){
		});
	}

	//跟换商品图片
	$('#jqzoom li').hover(function(){
		$(this).addClass("check").siblings().removeClass("check");
	});
	$(".jqzoom").imagezoom();
	$("#jqzoom li").mouseover(function(){
		$(".jqzoom").attr('src',$(this).find("input").val());
		$(".jqzoom").attr('rel',$(this).find("input").attr('rel'));
	});

	//选择商品规格
	$(".goods_pl a").click(function(){
		common_id = $("#common_id").val();
		$(this).addClass("check").siblings().removeClass("check");
		var spec =[];//定义一个数组
		$(".goods_pl .check").each(function(){
			//查找相关商品的id
			spec.push($(this).attr('value'));//将值添加到数组中

		});

		$.post(SITE_URL  + '?ctl=Supplier_Goods&met=getGoodsIdBySpec&typ=json',{common_id:common_id,spec:spec},function(data)
		{
			console.info(data);
			if(data.data)
			{
				window.location.href = SITE_URL + "?ctl=Supplier_Goods&met=goods&gid="+data.data.goods_id;
			}
		})
	});

	window.evaluat = function(e){
		$(e).parent().parent().find(".checked").removeClass('checked');
		$(e).addClass('checked');
	}

	$(function(){
		//购买数量
		var c=$(".goods_num");
		var e=null;
		c.each(function(){
			var g=$(this).find("a");	  //添加减少按钮
			var h=$(this).find("input#nums");  //当前商品数量
			var o=this;
			var f=h.attr("data-max");  //最大值 - 库存
			var i=1;
			var id=h.attr("data-id");  //购物车id
			h.bind("input propertychange",function(){
				var j=this;
				var k=$(j).val();
				e&&clearTimeout(e);
				e=setTimeout(function(){
					var l=Math.max(Math.min(f,k.replace(/\D/gi,"").replace(/(^0*)/,"")||1),i);
					$(j).val(l);

					if(l==f){
						g.eq(1).attr("class","no_add");
						if(l==i)
							g.eq(0).attr("class","no_reduce")
						else
							g.eq(0).attr("class","reduce")
					}else{
						if(l<=i){
							g.eq(0).attr("class","no_reduce");
							g.eq(1).attr("class","add")
						}else{
							g.eq(0).attr("class","reduce");
							g.eq(1).attr("class","add")
						}
					}
				},50)
			}).trigger("input propertychange").blur(function(){$(this).trigger("input propertychange")}).keydown(function(l){
				if(l.keyCode==38||l.keyCode==40)
				{
					var j=0;
					l.keyCode==40&&(j=1);g.eq(j).trigger("click")
				}
			});
			g.bind("click",function(l){
				if(!$(this).hasClass("no_reduce")){
					var j=parseInt(h.val(),10)||1;
					if($(this).hasClass("add")&&!$(this).hasClass("no_add")){
						$(this).prev().prev().attr("class","reduce");
						if(f>=i&&j>=f){
							$(this).attr("class","no_add")
						}
						else
						{
							j++;
						}
					}else{
						if($(this).hasClass("reduce")&&!$(this).hasClass("no_reduce")){
							j--;
							$(this).next().next().attr("class","add");
							j<=i&&$(this).attr("class","no_reduce")
						}
					}
					h.val(j)
				}
			})
		})

	})


})
