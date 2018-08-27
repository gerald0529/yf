/**
 * @author     朱羽婷
 */
$(document).ready(function(){
	$("input[type='checkbox']").prop("checked",false);

	window.get = function (e)
	{
		$(e).parent().parent().parent().find(".sale_detail").show();
	}

	window.showVoucher = function(e)
	{
		$(e).parent().parent().parent().find(".voucher_detail").show();
	}

	$(".bk").click(function(){
		$(this).parent().parent().hide();
	})

	function getTransport()
	{
		var address = $(".add_choose").find('p').html();
		var cart_id =[];//定义一个数组
		$("input[name='cart_id']").each(function(){
			cart_id.push($(this).val());//将值添加到数组中
		});

		$.post(SITE_URL  + '?ctl=Seller_Transport&met=getTransportCost&typ=json',{address:address,cart_id:cart_id},function(data)
			{
				console.info(data);
				if(data && 200 == data.status) {
					$.each(data.data ,function(key,val){
						$(".trancon"+key).html(val.con);
						$(".trancost"+key).html(val.cost.toFixed(2));

						//计算店铺合计
						$(".sprice"+key).html(($(".price"+key).html()*1 + val.cost*1).toFixed(2));
					})

					//计算订单中金额
					var total = 0;
					$(".dian_total i").each(function(){
						total += $(this).html()*1;
					});
					$(".total").html(total.toFixed(2));
					//$(".rate_total").html((total*rate/100).toFixed(2));

				}
			}
		);

	}
	var c=$(".goods_num");
	var e=null;
	c.each(function(){
		var g=$(this).find("a");	  //添加减少按钮
		var h=$(this).find("input#nums");  //当前商品数量
		var o=this;
		var f=h.attr("data-max");  //最大值 - 库存
		var i=h.attr("data-min");
		var id=h.attr("data-id");  //购物车id
		if(h.val() <= i)
		{
			$(this).find('input#nums').prev().attr('class', 'no_reduce');
			$(this).find('input#nums').val(i);
		}
		h.bind("input propertychange",function(){
			var j=this;
			var k=$(j).val();
			e&&clearTimeout(e);
			e=setTimeout(function(){
				var l=Math.max(Math.min(f,k.replace(/\D/gi,"").replace(/(^0*)/,"")||1),i);
				$(j).val(l);
				edit_num(id,l,o);
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
					if(f>i&&j>=f){
						$(this).attr("class","no_add")
					}
					else
					{
						j++;
						edit_num(id,j,o);
					}
				}else{
					if($(this).hasClass("reduce")&&!$(this).hasClass("no_reduce")){
						j--;
						edit_num(id,j,o);
						$(this).next().next().attr("class","add");
						j<=i&&$(this).attr("class","no_reduce")
					}
				}
				h.val(j)
			}
		})
	})

	function edit_num(id,num,obj){
		gprice = $("#goods_price").val();
		price = gprice * num;
		$('.cell' + id + ' span').html((Number(price).toFixed(2)));
		$(".subtotal_all").html(Number(price).toFixed(2));
	}


	//付款按钮
	$('.submit-btn').click(function(){
		$('#form').submit();
	});

	//验证手机号
	window.checkmobile = function()
	{
		var value = $("#buyer_phone").val();
		var errorFlag = false;
		var errorMessage = "";
		var reg=/^(\+\d{2,3}\-)?\d{11}$/;
		if (value != '') {
			if (!reg.test(value)) {
				errorFlag = true;
				errorMessage = "手机号码格式不正确";
			}
		} else {
			errorFlag = true;
			errorMessage = "请输入手机号码";
		}
		if (errorFlag) {
			$("#e_consignee_mobile_error").html(errorMessage);
			$("#e_consignee_mobile_error").addClass("error-msg");
			return false;
		} else {
			$("#e_consignee_mobile_error").removeClass("error-msg");
			$("#e_consignee_mobile_error").html("");
			return true;
		}

		alert(errorMessage);
	}


	//加价购的商品
	var increase_goods_id = [];
	$(".increase_list").each(function(){
		if($(this).is('.checked'))
		{
			increase_goods_id.push($(this).find("#redemp_goods_id").val());
		}
	})

	//去付款按钮（生成订单）
	$("#pay_btn").click(function(){

		var has_physical = $('#has_physical').val();
		if(typeof(has_physical) != 'undefined' && has_physical == 1){
			if($('#goodsremarks').val() == ''){
				$('#goodsremarks').focus();
				Public.tips.error('请在备注中填写收货信息');
				return false;
			}
		}
		//1.获取手机号码
			buyer_phone = $("#buyer_phone").val();
			flag = checkmobile();
		//2.获取商品留言
			remarks = $("#goodsremarks").val();
		//3.获取商品信息（商品id，商品备注）
			goods_id = $("#goods_id").val();
			goods_num = $("#goods_num").val();

		//加价购的商品信息，包括商品id，商品数量，规则id，店铺id
		// var increase_goods_id = [];
		var increase_goods_num = [];
		var increase_shop_id = [];
		var increase_arr = [];
		// var increase_price = [];//店铺加价购商品总金额
		var increase_max_num = [];//店铺加价购商品可购买的最大数
		var increase_rule_id = [];//加价购规则id，一个店铺只能选择一个规则下面的多个商品
		var status = 0;
		$(".select_increase").each(function(){
			if($(this).is(':checked'))
			{
				increase_shop_id.push($(this).attr("shop_id"));
				var inc_shop_id = $(this).attr("shop_id");
				// var inc_shop_ids = [];
				var inc_rule_ids = [];
				$(".clearfix.status."+inc_shop_id).each(function () {
					inc_rule_ids.push($(this).find(".select_increase").attr('rule_id'));
				});
				//
				// inc_shop_ids = inc_shop_ids.join(',');
				inc_rule_ids = inc_rule_ids.join(',');
				// increase_shop_id.push(inc_shop_ids);
				increase_rule_id.push(inc_rule_ids);
				// increase_goods_id.push($(this).parents('tr:eq(0)').find(".increase_num").attr('goods_id'));
				// increase_goods_num.push($(this).parents('tr:eq(0)').find(".increase_num").val());
				// increase_max_num.push($(this).parents('tr:eq(0)').find(".increase_num").attr('data-max'));
				// increase_rule_id.push($(this).find(".select_increase").attr('rule_id'));

				// increase_price.push($(this).parents('tr:eq(0)').find(".w150.tc").attr('data_price'));
				var goods_num = $(this).parents('tr:eq(0)').find(".increase_num").val();
				if(! /^[1-9]\d*$/.test(goods_num))
				{
					status = 1;
				}
				console.info($(this).attr('rule_id'));
				var str = {
					increase_goods_id:$(this).parents('tr:eq(0)').find(".increase_num").attr('goods_id'),
					increase_rule_id:$(this).attr('rule_id'),
					increase_shop_id:$(this).attr("shop_id"),
					increase_goods_num:goods_num,
					increase_max_num:$(this).parents('tr:eq(0)').find(".increase_num").attr('data-max'),
					increase_price:$(this).parents('tr:eq(0)').find(".w150.tc").attr('data_price')
				};
				increase_arr.push(str);
			}
		})

		console.info(increase_shop_id);
		console.info(increase_rule_id);
		console.info(increase_arr);
		// return false;
		//加价购的商品
		// var increase_goods_id = [];
		// $(".increase_list").each(function(){
		// 	if($(this).is('.checked'))
		// 	{
		// 		increase_goods_id.push($(this).find("#redemp_goods_id").val());
		// 	}
		// })

		//判断同个店铺加价购商品是否是一个规则下的
		$.unique(increase_rule_id);
		var rule_id_arr = [];
		for(var k=0;k<increase_rule_id.length;k++)
		{
			//如果当前店铺选择了多个加价购商品，判断它们规则id是否相同
			if(increase_rule_id[k].indexOf(',') > 0)
			{
				rule_id_arr = increase_rule_id[k].split(',');
				$.unique(rule_id_arr);
				if(rule_id_arr.length > 1)
				{
					Public.tips.error('请选择同一种规则的加价购商品');
					return false;
				}
			}
			else
			{
				continue;
			}
		}

		//判断店铺加价购商品购买数量是否超过加价购规则限购数
		$.unique(increase_shop_id);//获得去重后的店铺id
		for(var i=0;i<increase_shop_id.length;i++)
		{
			var goods_num_total = 0;
			var max_num = 0;
			for(var j=0;j<increase_arr.length;j++)
			{
				if(increase_shop_id[i] === increase_arr[j].increase_shop_id)
				{
					max_num = parseInt(increase_arr[j].increase_max_num);
					goods_num_total += parseInt(increase_arr[j].increase_goods_num);
					console.info(increase_arr[j].increase_shop_id +'---------'+increase_arr[j].increase_goods_num);
				}
				else
				{
					continue;
				}
			}
			if(goods_num_total > max_num)
			{
				Public.tips.error('加价购商品数量不能大于店铺限购数！');
				return false;
			}
		}

		if(status === 1)
		{
			Public.tips.error('加价购商品数量有误！');
			return false;
		}
		
		//代金券信息
		var voucher_id = [];
		$(".select").each(function(){
			if($(this).find('option').is(":selected"))
			{
				voucher_id.push($(this).find("option:selected").attr('voucher_id'));
			}
		});

		//优惠券信息
		var rpt_info = '';
		var rpt   	= 0;
		if($('#rpt').length > 0)
		{
			rpt_info = $('#rpt').val().split('|');
		}
		if(rpt_info)
		{
			rpt = rpt_info[0];
		}

		//是否开启会员折扣
		var is_discount = 0;
		if($('#is_discount').is(':checked')) {
			is_discount = 1;
		}
		else
		{
			is_discount = 0;
		}

			if(flag)
			{
				$("#mask_box").show();
            
				$.ajax({
					url: SITE_URL  + '?ctl=Buyer_Order&met=addVirtualOrder&typ=json',
					data:{buyer_phone:buyer_phone,goods_id:goods_id,goods_num:goods_num,remarks:remarks,increase_arr:increase_arr,voucher_id:voucher_id,pay_way_id:1,rpt:rpt,from:'pc',is_discount:is_discount},
					dataType: "json",
					contentType: "application/json;charset=utf-8",
					async:false,
					success:function(a){
						console.info(a);
            
						if(a.status == 200)
						{
							window.location.href = PAYCENTER_URL + "?ctl=Info&met=pay&uorder=" + a.data.uorder+'&order_g_type=virtual';
						}
						else
						{
							Public.tips.error('订单提交失败！');
							//alert('订单提交失败');
						}
            
					},
					failure:function(a)
					{
						Public.tips.error('操作失败！');
					}
				});
			}
			else
			{
				var errormsg = $("#e_consignee_mobile_error").html();
				Public.tips.error(errormsg);
				return;
			}
	});

	window.jiabuy = function(e)
	{
		limit = $(e).parents('.increase').find('#exc_goods_limit').val();
		shop_id = $(e).parents('.increase').find('#shop_id').val();

		if($(e).is('.checked'))
		{
			clanRpt();

			$(e).removeClass('checked');
			$(e).parents('.increase_list').removeClass('checked');

			good_price = $(e).parents('.increase_list').find(".redemp_price").val();
			good_price_rate = $(e).parents('.increase_list').find(".redemp_price_rate").val();
			good_price_arate = good_price - good_price_rate;

			//总会员折扣减价
			total_rate = Number(Number($('.rate_total').html()) - good_price_rate*1).toFixed(2);
			$('.rate_total').html(total_rate);

			//总价减价
			total_price = Number(Number($('.total').html())*1-good_price*1).toFixed(2);
			after_total = Number($('.after_total').html());

			$('.total').html(total_price);
			$(".after_total").html((after_total - good_price_arate*1).toFixed(2));


			//修改订单金额后需要修改平台红包
			iniRpt(after_total.toFixed(2));
			$('#orderRpt').html('-0.00');
		}
		else
		{
			//计算已经选择了加价购商品
			num = $(e).parents('.increase').children(".increase_list").find('.checked').length;

			if(limit <= 0 || (limit > 0 && num < limit))
			{
				clanRpt();

				$(e).addClass('checked');
				$(e).parents('.increase_list').addClass('checked');

				good_price = $(e).parents('.increase_list').find(".redemp_price").val();
				good_price_rate = $(e).parents('.increase_list').find(".redemp_price_rate").val();
				good_price_arate = good_price - good_price_rate;

				//总会员折扣加价
				total_rate = Number(Number($('.rate_total').html()) + good_price_rate*1).toFixed(2);
				$('.rate_total').html(total_rate);

				//总价加价
				total_price = Number(Number($('.total').html())*1+good_price*1).toFixed(2);
				after_total = Number($('.after_total').html());

				$('.total').html(total_price);
				$(".after_total").html((after_total + good_price_arate*1).toFixed(2));


				//修改订单金额后需要修改平台红包
				iniRpt(after_total.toFixed(2));
				$('#orderRpt').html('-0.00');
			}


		}

	}

	window.useVoucher = function (e)
	{
		shop_id = $(e).parent().find('#shop_id').val();

		//获取本代金券的价值
		voucher_price = $(e).parent().find("#voucher_price").val();

		if($(e).is('.checked'))
		{
			clanRpt();

			$(e).removeClass("checked");
			$(e).removeClass("bgred");
			$(e).parents('.voucher_list').removeClass('checked');

			//删除代金券信息
			$(".shop_voucher").html("");

			//总价加价
			total_price = Number(Number($('.total').html())*1+voucher_price*1).toFixed(2);
			after_total = Number($('.after_total').html());

			$('.total').html(total_price);
			$(".after_total").html((after_total + voucher_price*1).toFixed(2));

			//修改订单金额后需要修改平台红包
			iniRpt(after_total.toFixed(2));
			$('#orderRpt').html('-0.00');
		}else
		{
			clanRpt();

			$(e).parents(".voucher").find(".checked").removeClass("checked");
			$(e).parents(".voucher").find(".bgred").removeClass("bgred");
			$(e).addClass("checked");
			$(e).addClass("bgred");
			$(e).parents('.voucher_list').addClass('checked');

			//显示代金券信息
			$(".shop_voucher").html("使用" + voucher_price + "元代金券");

			//总价减价
			total_price = Number(Number($('.total').html())*1-voucher_price*1).toFixed(2);
			after_total = Number($('.after_total').html());

			$('.total').html(total_price);
			$(".after_total").html((after_total - voucher_price*1).toFixed(2));

			//修改订单金额后需要修改平台红包
			iniRpt(after_total.toFixed(2));
			$('#orderRpt').html('-0.00');
		}
	}

	//会员折扣
	$("#is_discount").click(function(){
		var discount_price = parseFloat($("#use_discount").attr("discount_price"));
		var after_total = parseFloat($('.after_total').html());//订单原价
		if($('#is_discount').is(':checked')) {
			//会员折扣
			$("#no_discount").hide();
			$("#use_discount").show();
			$(".rate_total ").html("减￥"+discount_price.toFixed(2));
			$(".rate_total").removeClass('isFavoritesShop');
			//代金券
			$("#shop_voucher").hide();
			$("#voucher_discount").show();
			//红包
			$("#redpacket").hide();
			$("#redpacket_discount").show();
			$(".redpacket").html('未使用');
			$(".redpacket").addClass('isFavoritesShop');
			//支付金额
			$(".after_total").html((after_total - discount_price).toFixed(2));
		}
		else
		{
			//会员折扣
			$("#no_discount").show();
			$("#use_discount").hide();
			$(".rate_total ").html("未使用");
			$(".rate_total").addClass('isFavoritesShop');
			//代金券
			$("#shop_voucher").show();
			$("#voucher_discount").hide();
			//红包
			$("#redpacket").show();
			$("#redpacket_discount").hide();
			$(".after_total").html((after_total + discount_price).toFixed(2));
		}
	});


})
