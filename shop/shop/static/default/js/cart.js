/**
 * @author     朱羽婷
 */

var
	address_id,
	address_contact,
	address_address,
	address_phone,
	invoice,
	invoice_id,
	shop_id,
	voucher_price,
	total_price,
	total_rate,
	num,
	good_price,
	good_price_rate;

var companyTaxNum = "";


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
	/*$(".get").click(function(){
		$(this).parent().parent().parent().find(".sale_detail").show();
	})*/
	$(".bk,.btn-close").click(function(){
		$(this).parent().parent().hide();
	})

	//切换用户收货地址，获取物流运费
	$(".receipt_address li").click(function(){
		$(".receipt_address li").removeClass('add_choose');
		$(this).addClass('add_choose');

		getTransport();
	});

	//返回购物车
	$("#back_cart").click(function (){
		location.href = SITE_URL + "?ctl=Buyer_Cart&met=cart";
	});

	function getTransport()
	{
		var address_id = $(".add_choose").find('#address_id').val();
		product_id = getQueryString('product_id');
		location.href = SITE_URL + "?ctl=Buyer_Cart&met=confirm&product_id="+product_id+"&address_id="+address_id;
	}

	var ww=$(document).height()-173;
	var top;
	top=$(window).scrollTop()+$(window).height();
	top>=ww ? $(".pay_fix").css("position","relative") : $(".pay_fix").css("position","fixed");
	$(window).scroll(function (){
		top=$(window).scrollTop()+$(window).height();
		if(top>=ww){
			$(".pay_fix").css("position","relative");
		}else{
			$(".pay_fix").css("position","fixed");
		}
	});


	//全选的删除按钮
	$('.delete').click(function(){
		//获取所有选中的商品id
		var chk_value =[];//定义一个数组
		$("input[name='product_id[]']:checked").each(function(){
			chk_value.push($(this).val());//将选中的值添加到数组chk_value中
		})

		$.dialog({
			title: '删除',
			content: '您确定要删除吗？',
			height: 100,
			width: 410,
			lock: true,
			drag: false,
			ok: function () {
				$.post(SITE_URL  + '?ctl=Buyer_Cart&met=delCartByCid&typ=json',{id:chk_value},function(data)
					{
						console.info(data);
						if(data && 200 == data.status) {
							//$.dialog.alert('删除成功');
							Public.tips.success('删除成功!');
							window.location.reload(); //刷新当前页
						} else {
							//$.dialog.alert('删除失败');
							Public.tips.error('删除失败!');
						}
					}
				);
			}
		})
	});

	//全选
	$('.checkall').click(function(){
		var _self = this;
		$('.checkitem').each(function(){
			if (!this.disabled){
				$(this).prop('checked', _self.checked);

				if(_self.checked)
				{
					$(this).parent().parent().parent().addClass('item-selected');
				}
				else
				{
					$(this).parent().parent().parent().removeClass('item-selected');
				}
			}
		});
		$('.checkall').prop('checked', this.checked);
		count();
	});

	//勾选店铺
	$('.checkshop').click(function(){
		var _self = this;
		if(_self.checked)
		{
			$(this).parents(".carts_content").find(".checkitem").each(function(){

				if (!this.disabled){
					$(this).prop('checked', _self.checked);
					$(this).parent().parent().parent().addClass('item-selected');

				}

			});

		}
		else
		{
			$(this).parents(".carts_content").find(".checkitem").prop('checked', false);
			$(this).parent().parent().parent().find(".row_line").removeClass('item-selected');
		}

		count();
	});

	//单度选择商品
	$('.checkitem').click(function(){
		var _self = this;
		if (!this.disabled){
			$(this).prop('checked', _self.checked);

			if(_self.checked)
			{
				$(this).parent().parent().parent().addClass('item-selected');

				//判断该店铺下的商品是否已全选
				if($(this).parents('.table_list').find(".checkitem").not("input:checked").length == 0)
				{
					$(this).parents(".carts_content").find(".checkshop").prop('checked', true);
				}

				//判断是否所有商品都已选择，如果所有商品都选择了就勾选全选
				if($(".checkitem").not("input:checked").length == 0)
				{
					$('.checkall').prop('checked', true);
				}
			}
			else
			{
				$(this).parent().parent().parent().removeClass('item-selected');

				//判断该店铺下的商品是否已全选
				if($(this).parents('.table_list').find(".checkitem").not("input:checked").length != 0)
				{
					$(this).parents(".carts_content").find(".checkshop").prop('checked', false);
				}

				//判断全选是否勾选，如果勾选就去除
				if($(".checkitem").not("input:checked").length != 0)
				{
					$('.checkall').prop('checked', false);
				}
			}
		}
		count();
	});

	function count()
	{
		var count = 0;
		var num = 0;
		$(".cart-checkbox").find("input[name='product_id[]']:checked").each(function(){
			var value = $(this).val();
			var price = $(this).parent().parent().parent().find(".price_all span").html();
			price = price.replace(/,/g, "")
			price = Number(price);
			count = count + price;
			num ++;
		});
		$(".subtotal_all").html(count.toFixed(2));
		//$(".cart-count em").html(num);
		if(num>0)
		{
			$(".submit-btn").removeClass("submit-btn-disabled");
		}
		else
		{
			$(".submit-btn").addClass("submit-btn-disabled");
		}
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
		var limit = h.attr("data-limit");//限购数量
		if(parseInt(h.val()) <= parseInt(i))
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

				var checkitem = $(this).parents().parents().prev().prev().prev().prev().find('.checkitem');
				if($(this).hasClass("add")&&!$(this).hasClass("no_add")){
					$(this).prev().prev().attr("class","reduce");
					if(f>i&&j>=f){
						$(this).attr("class","no_add")
					}
					else
					{
						j++;
						if(j>limit && limit>0){
							checkitem.attr('disabled', true);
						}
						edit_num(id,j,o);
					}
				}else{
					if($(this).hasClass("reduce")&&!$(this).hasClass("no_reduce")){
						j--;
						if(j<=limit){
							checkitem.attr('disabled', false);

						}
						edit_num(id,j,o);
						$(this).next().next().attr("class","add");
						j<=i&&$(this).attr("class","no_reduce")
					}
				}

				if(checkitem.attr('disabled')){
					console.log(1);

					checkitem.prop('checked', false);
					$(this).parents().parents().parents().removeClass('item-selected');



				}
				h.val(j)
			}
		})
	})

	function edit_num(id,num,obj){
		var url = "?ctl=Buyer_Cart&met=editCartNum&typ=json";
		var pars = 'cart_id='+id+'&num='+num;
		$.post(url, pars,showResponse);
		function showResponse(originalRequest)
		{
			if(originalRequest.status == 200 )
			{
				$('.cell' + id + ' span').html((Number(originalRequest.data.price).toFixed(2)));
				count();
			}
		}
	}


    var infoss = $('#infoss').data('info');
    if(infoss){
        send(infoss);
    }

    function send(obj){
        var infoss = obj;
        console.log(infoss);
        $.post(SITE_URL  + '?ctl=Buyer_Cart&met=editCartDiscountNum&typ=json',{info:infoss},function(data)
        {
            if(data && data.status == 200){
                Public.tips.warning(data.msg);
            }
        });
    }


	$('.del a').click(function(){
		var e = $(this);
		var data_str = e.attr('data-param');
		eval( "data_str = "+data_str);
		$.dialog({
			title: '删除',
			content: '您确定要删除吗？',
			height: 100,
			width: 410,
			lock: true,
			drag: false,
			ok: function () {
				$.post(SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json',{id:data_str.id},function(data)
					{
						console.info(data);
						if(data && 200 == data.status) {
							//$.dialog.alert('删除成功');
							Public.tips.success('删除成功!');
							e.parents('tr').hide('slow', function() {
								var row_count = $('#table_list').find('.row_line:visible').length;
								if(row_count <= 0)
								{
									$('#list_norecord').show();
								}
							});
							window.location.reload(); //刷新当前页
						} else {
							//$.dialog.alert('删除失败');
							Public.tips.error('删除失败!');
						}
					}
				);
			}
		})
	});

	//付款按钮
	$('.submit-btn').click(function(){

        //获取所有选中的商品id
        var chk_value =[];//定义一个数组
        var nums_value = [];
        $("input[name='product_id[]']:checked").each(function(){
            chk_value.push($(this).val());//将选中的值添加到数组chk_value中
            var nums_id = $(this).data('nums_id');
            nums_value.push($("input[name='"+nums_id+"']").val());
        })
        console.log(nums_value);
        if(chk_value != "")
        {
            $.post(SITE_URL  + '?ctl=Buyer_Cart&met=newconfirm&typ=json',{product_id:chk_value,nums:nums_value},function(data)
                {
                    if(data && 250 == data.status) {
                        Public.tips.error(data.msg);
                    }else{
                        $("#form").attr('action','?ctl=Buyer_Cart&met=confirm&product_id='+chk_value);
                        $('#form').submit();
                    }
                }
            );
        }

    });

	function changeURLPar(destiny, par, par_value)
	{
		var pattern = par+'=([^&]*)';
		var replaceText = par+'='+par_value;
		if (destiny.match(pattern))
		{
			var tmp = new RegExp(pattern);
			tmp = destiny.replace(tmp, replaceText);
			return (tmp);
		}
		else
		{
			if (destiny.match('[\?]'))
			{
				return destiny+'&'+ replaceText;
			}
			else
			{
				return destiny+'?'+replaceText;
			}


		}
		return destiny+'\n'+par+'\n'+par_value;
	}

	window.addAddress = function(val)
	{
		console.info(val);
		//地址中的参数
		var params= window.location.search;

		params = changeURLPar(params,'address_id',val.user_address_id);

		window.location.href = SITE_URL + params;
		
		if(val.user_address_default == 1)
		{
			def = 'add_choose';

			$(".add_choose").removeClass("add_choose");
		}
		else
		{
			def = '';
		}
		str = '<li class=" ' + def + ' " id="addr'+ val.user_address_id + ' "><div class="editbox"><a onclick="edit_address( ' + val.user_address_id + ' )">编辑</a> <a onclick="del_address( ' + val.user_address_id + ' )">删除</a></div><h5> ' + val.user_address_contact +' </h5><p> ' + val.user_address_area + ' ' + val.user_address_address +' </p><div><span class="phone"><i class="iconfont">&#xe64c;</i><span> ' + val.user_address_phone +' </span></span></div></li>';

		$("#address_list").append(str);

		//location.reload();
	}

	window.editAddress = function(val)
	{
		area = val.user_address_area + ' ' + val.user_address_address;
		$("#addr"+val.user_address_id).find("h5").html(val.user_address_contact);
		$("#addr"+val.user_address_id).find("p").html(area);
		$("#addr"+val.user_address_id).find(".phone").find("span").html(val.user_address_phone);

	}

	window.addInvoice = function(state,title,con,id)
	{
		str = state + ' ' + title + ' ' + con;
		$(".mr10").html(str);
		$("input[name='invoice_id']").val(id);
		$("input[name='invoice_title']").val(title);
		$("input[name='invoice_content']").val(con);
		//企业税号
		companyTaxNum = arguments[4];
        $("a.invoice-cancel").show();
	};

	$("a.invoice-cancel").on('click', function () {
        $(this).hide();
        $("input[name='invoice_id']").val('');
        $("input[name='invoice_title']").val('');
        $("input[name='invoice_content']").val('');
        companyTaxNum = '';
        $('span.invoice-no').html('不开发票');
    });

	//删除收货地址
	$(".del_address").click(function(event){
		var id =  $(this).attr('data_id');
		$.dialog({
			title: '删除',
			content: '您确定要删除吗？',
			height: 100,
			width: 410,
			lock: true,
			drag: false,
			ok: function () {
				$.post(SITE_URL  + '?ctl=Buyer_User&met=delAddress&typ=json',{id:id},function(data)
					{
						if(data && 200 == data.status) {
							Public.tips.success('删除成功!');
							$("#addr"+id).remove();
						} else {
							Public.tips.error('删除失败!');
						}
					}
				);
			}
		})

		if(event && event.stopPropagation)
		{
			event.stopPropagation();
		}
		else
		{
			event.cancelBubble = true;
		}
	});

	//编辑收货地址
	$(".edit_address").click(function(event){
		url = SITE_URL + "?ctl=Buyer_Cart&met=resetAddress&id="+$(this).attr('data_id');

		$.dialog({
			title: '修改地址',
			content: 'url: ' + url ,
			height: 340,
			width: 580,
			lock: true,
			drag: false

		});

		if(event && event.stopPropagation)
		{
			event.stopPropagation();
		}
		else
		{
			event.cancelBubble = true;
		}
	});

	//去付款按钮（生成订单）
	if($('.colred').html())
		{
			$("#pay_btn").addClass("gray");
		}
	$("#pay_btn").click(function(){
		//判断是否存在超出配送范围的商品
		if(!buy_able)
		{
			Public.tips.error('有部分商品配送范围无法覆盖您选择的地址，请更换其它商品！');
			return false;
		}

		if($(".total").html() >= 99999999.99 )
		{
			Public.tips.error('订单金额过大，请分批购买！');
			return false;
		}

		//1.获取收货地址
		address_id   = $(".add_choose").find("#address_id").val();
		address_contact = $(".add_choose").find("h5").html();
		address_address = $(".add_choose").find("p").html();
		address_phone   = $(".add_choose").find(".phone").html();

		if(!address_contact)
		{
			$('.add_address').click();
			Public.tips.error('请填写收货地址！');
			return false;
		}

		//提交订单时添加判断，收货地址区域是否有货
		if($('.colred').html())
		{
			Public.tips.error('该区域无货，请重新选择地址');
			$(this).addClass("gray");
			return false;
		}
		//2.获取发票信息
		invoice = $(".invoice-cont").find(".mr10").html();
		invoice_id = $("input[name='invoice_id']").val();
		invoice_title = $("input[name='invoice_title']").val();
		invoice_content = $("input[name='invoice_content']").val();

		//3.获取商品信息（商品id，商品备注）
		var cart_id =[];//定义一个数组
		$("input[name='cart_id']").each(function(){
			cart_id.push($(this).val());//将值添加到数组中
		});
		var remark = [];
		var shop_id = [];
		$("input[name='remarks']").each(function(){
			shop_id.push($(this).attr("id"));
			remark.push($(this).val());//将值添加到数组中
		});

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
				var inc_rule_ids = [];
				$(".clearfix.status."+inc_shop_id).each(function () {
					inc_rule_ids.push($(this).find(".select_increase").attr('rule_id'));
				});

				inc_rule_ids = inc_rule_ids.join(',');
				increase_rule_id.push(inc_rule_ids);

				var goods_num = $(this).parents('tr:eq(0)').find(".increase_num").val();
				if(! /^[1-9]\d*$/.test(goods_num))
				{
					status = 1;
				}
				var str = {
					increase_goods_id:$(this).parents('tr:eq(0)').find(".increase_num").attr('goods_id'),
					increase_shop_id:$(this).attr("shop_id"),
					increase_goods_num:goods_num,
					increase_price:$(this).parents('tr:eq(0)').find(".select_increase").attr('goods_price'),
				};
				increase_arr.push(str);
			}
		})

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
				if($(this).find("option:selected").attr('voucher_id'))
				{
					voucher_id.push($(this).find("option:selected").attr('voucher_id'));
				}
			}
		})

		//平台红包
		var redpacket_id = $("#redpacket").find("option:selected").attr('red_id');
		if(!redpacket_id) redpacket_id = 0;

		//是否开启会员折扣
		var is_discount = getQueryString('is_discount')>0?getQueryString('is_discount'):0;

		//获取支付方式
		pay_way_id = $(".pay-selected").attr('pay_id');
		$("body").css("overflow", "hidden");
		$("#mask_box").show();
		$.ajax({
			type:"POST",
			url: SITE_URL  + '?ctl=Buyer_Order&met=addOrder&typ=json',
			data:{
				receiver_name:address_contact,
				receiver_address:address_address,
				receiver_phone:address_phone,
				invoice:invoice,
				invoice_id:invoice_id,
				invoice_title:invoice_title,
				invoice_content:invoice_content,
				cart_id:cart_id,
				shop_id:shop_id,
				remark:remark,
				// increase_goods_id:increase_goods_id,
				// increase_shop_id:increase_shop_id,
				// increase_goods_num:increase_goods_num,
				// increase_price:increase_price,
				increase_arr:increase_arr,
				voucher_id:voucher_id,
				redpacket_id:redpacket_id,
				pay_way_id:pay_way_id,
				address_id:address_id,
				from:'pc',
                companyTaxNum: companyTaxNum,
                is_discount:is_discount
            },
			dataType: "json",
			contentType: "application/json;charset=utf-8",
			async:false,
			success:function(a){
				if(a.status == 200)
				{
					if(pay_way_id == 1)
					{
						window.location.href = PAYCENTER_URL + "?ctl=Info&met=pay&uorder=" + a.data.uorder+'&order_g_type=physical';
						return false;
					}
					else
					{
						window.location.href = SITE_URL + '?ctl=Buyer_Order&met=physical';
						return false;
					}
				}
				else
				{
                    $("#mask_box").hide();
					if(a.msg != 'failure')
					{
						Public.tips.error(a.msg);
					}else
					{
						Public.tips.error('订单提交失败！');
					}


				}
			},
			failure:function(a)
			{
				Public.tips.error('操作失败！');

				window.location.reload(); //刷新当前页
			}
		});

	});

	window.jiabuy = function(e)
	{
		limit = $(e).parents('.increase').find('#exc_goods_limit').val();
		shop_id = $(e).parents('.increase').find('#shop_id').val();

		if($(e).is('.checked'))
		{
			//使用红包后会修改店铺的金额，所以要清除原来的红包选择
			cancelRedpacket(shop_id);

			$(e).removeClass('checked');
			$(e).parents('.increase_list').removeClass('checked');

			good_price = $(e).parents('.increase_list').find(".redemp_price").val();
			good_price_rate = $(e).parents('.increase_list').find(".redemp_price_rate").val();
			good_price_arate = good_price - good_price_rate;

			addPrice(shop_id,good_price,good_price_rate,good_price_arate,good_price,good_price_arate,0);

			//根据修改后的店铺金额重新添加红包列表
			iniRedpacket(shop_id);
		}
		else
		{
			//计算已经选择了加价购商品
			num = $(e).parents('.increase').children(".increase_list").find('.checked').length;

			if(limit <= 0 || (limit > 0 && num < limit))
			{
				//使用红包后会修改店铺的金额，所以要清除原来的红包选择
				cancelRedpacket(shop_id);

				$(e).addClass('checked');
				$(e).parents('.increase_list').addClass('checked');

				good_price = $(e).parents('.increase_list').find(".redemp_price").val();
				good_price_rate = $(e).parents('.increase_list').find(".redemp_price_rate").val();
				good_price_arate = good_price - good_price_rate;


				addPrice(shop_id,good_price,good_price_rate,good_price_arate,good_price,good_price_arate,1);


				//根据修改后的店铺金额重新添加红包列表
				iniRedpacket(shop_id);
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
			//使用红包后会修改店铺的金额，所以要清除原来的红包选择
			cancelRedpacket(shop_id);

			$(e).removeClass("checked");
			$(e).removeClass("bgred");
			$(e).parents('.voucher_list').removeClass('checked');

			//删除代金券信息
			// $(".shop_voucher"+shop_id).html("");
			$(".shop_voucher"+shop_id).next().html('').html(0);
			addPrice(shop_id,0,0,0,0,0,1);

			//根据修改后的店铺金额重新添加红包列表
			iniRedpacket(shop_id);


		}else
		{
			//使用红包后会修改店铺的金额，所以要清除原来的红包选择
			cancelRedpacket(shop_id);

			$(e).parents(".voucher").find(".checked").removeClass("checked");
			$(e).parents(".voucher").find(".bgred").removeClass("bgred");
			$(e).addClass("checked");
			$(e).addClass("bgred");
			$(e).parents('.voucher_list').addClass('checked');

			//显示代金券信息
			$(".shop_voucher"+shop_id).next().html('').html("-"+voucher_price);

			addPrice(shop_id,0,0,0,0,0,0);
			// function addPrice(shop_id,good_price,shop_goods_rate,shop_price,price,pay_price,symbol)
			//根据修改后的店铺金额重新添加红包列表
			iniRedpacket(shop_id);
		}
	}


	//选择支付方式
	$(".pay_way").click(function(){
			$(this).parent().find(".pay-selected").removeClass("pay-selected");
			$(this).addClass("pay-selected");
	 })


	//点击红包下拉框，显示红包列表
	window.hongbmorebtn = function(e)
	{
		var off = $(e).attr('data');
		$(e).siblings(".hongb-more").toggle();
		if(off > 0)
		{
			$(e).find("i").css("background","url(shop/static/default/images/up.png) no-repeat center");
			$(e).attr('data','0');
		}else{
			$(e).find("i").css("background","url(shop/static/default/images/down.png) no-repeat center");
			$(e).attr('data','1');
		}
	}

	//选择红包后将红包金额显示在框中，并修改订单价格
	$(document).on('click', '.redpacket_list', function()
	{
		var redpacket_price = $(this).attr('value').toFixed(2);
		var redpacket_title = $(this).html();
		var id = $(this).attr('id');
		var shop_id = $(this).parents(".hongb-sel").find('.red_shop_id').val();

		//判断该红包是否已被其他店铺使用，如果其他店铺已经选用，则该店铺不可再使用该红包
		if($(this).is('.disabled'))
		{
			Public.tips.error('该红包已使用！');
			return ;
		}

		//如果该红包在本店铺中已被选用，则取消该红包的使用，恢复其他店铺对该红包的使用权
		if($(this).is('.checked'))
		{
			$(this).removeClass('checked');
			$(".red"+id).find('.tips').remove();
			$(".red"+id).removeClass('disabled');

			$(this).parents(".hongb-sel").find('.redtitle').html('请选择你的平台红包');
			$(this).parents(".hongb-sel").find('.redprice').html('0.00');

			//将原来店铺中已经扣除的红包金额，返回到本店合计中
			addPrice(shop_id,0,0,redpacket_price,redpacket_price,redpacket_price,1);

			return;
		}

		//将选择的红包信息显示到选择框中
		$(this).parents(".hongb-sel").find('.redtitle').html(redpacket_title);
		$(this).parents(".hongb-sel").find('.redprice').html(redpacket_price);

		//将原先已选中的红包取消选中状态
		if($(this).parent().find('.checked').length > 0)
		{
			var che = $(this).parent().find('.checked');
			var che_id = che.attr('id');
			var che_redpacket_price = che.attr('value').toFixed(2);

			//将原来店铺中已经扣除的红包金额，返回到本店合计中
			addPrice(shop_id,0,0,che_redpacket_price,che_redpacket_price,che_redpacket_price,1);

			che.removeClass('checked');
			$(".red"+che_id).find('.tips').remove();
			$(".red"+che_id).removeClass('disabled');
		}

		//判断改用户有几个该红包，如果使用完了，则将将其他店铺中的已选红包标注为已使用
		$(".red"+id).append('<span class="tips">已使用</span>');
		$(".red"+id).addClass('disabled');
		//将本店选中的红包标注为已选中
		$(this).removeClass('disabled');
		$(this).addClass('checked');

		//在店铺合计中扣除选中的红包金额
		addPrice(shop_id,0,0,redpacket_price,redpacket_price,redpacket_price,0);
	});

	/**
	 * shop_id:店铺id
	 * good_price:商品价格
	 * shop_goods_rate:店铺商品折扣（店铺折扣）
	 * shop_price:店铺价格（本店合计）
	 * shop_goods_rate：总会员折扣减价
	 * price：总价
	 * pay_price：支付总金额
	 * */
	function addPrice(shop_id,good_price,shop_goods_rate,shop_price,price,pay_price,symbol)
	{
		//加
		if(symbol > 0)
		{
			//商品加价（商品金额） good_price
			goods_price = Number(Number($('.price' + shop_id).html())*1 + good_price*1).toFixed(2);
			$('.price' + shop_id).html(goods_price);

			//店铺折扣加价（会员折扣） shop_goods_rate
			shop_rate = Number(Number($('.shoprate' + shop_id).html())*1 + shop_goods_rate*1).toFixed(2);
			$('.shoprate' + shop_id).html(shop_rate);

			//店铺加价（本店合计） shop_price
			shop_price = Number(Number($('.sprice' + shop_id).html())*1 + shop_price*1).toFixed(2);
			$('.sprice' + shop_id).html(shop_price);

			//总会员折扣加价（会员折扣） shop_goods_rate
			total_rate = Number(Number($('.rate_total').html()) + shop_goods_rate*1).toFixed(2);
			$('.rate_total').html(total_rate);

			//总价加价 price
			total_price = Number(Number($('.total').html())*1 + price*1).toFixed(2);
			after_total = Number($('.after_total').html());

			$('.total').html(total_price);  //订单金额
			$(".after_total").html((after_total + pay_price*1).toFixed(2));  //支付总金额
		}
		else //减
		{
			//商品减价（商品金额） good_price
			goods_price = Number(Number($('.price' + shop_id).html())*1 - good_price*1).toFixed(2);
			$('.price' + shop_id).html(goods_price);

			//店铺折扣减价（会员折扣） shop_good_rate
			shop_rate = Number(Number($('.shoprate' + shop_id).html())*1 - shop_goods_rate*1).toFixed(2);
			$('.shoprate' + shop_id).html(shop_rate);

			//店铺减价（本店合计） price
			shop_price = Number(Number($('.sprice' + shop_id).html())*1 - shop_price*1).toFixed(2);
			$('.sprice' + shop_id).html(shop_price);

			//总会员折扣减价（会员折扣）
			total_rate = Number(Number($('.rate_total').html()) - shop_goods_rate*1).toFixed(2);
			$('.rate_total').html(total_rate);

			//总价减价
			total_price = Number(Number($('.total').html())*1 - price*1).toFixed(2);
			after_total = Number($('.after_total').html());

			$('.total').html(total_price);  //订单金额
			$(".after_total").html((after_total - pay_price*1).toFixed(2));  //支付总金额
		}
	}


	//取消店铺的红包选择
	function cancelRedpacket(shop_id)
	{
		var shop_hongb = $(".redpacket"+shop_id).find(".hongb-more");
		if(shop_hongb.find('.checked').length > 0)
		{
			var che = shop_hongb.find('.checked');
			var che_id = che.attr('id');
			var che_redpacket_price = che.attr('value').toFixed(2);

			che.removeClass('checked');
			$(".red"+che_id).find('.tips').remove();
			$(".red"+che_id).removeClass('disabled');

			//将原来店铺中已经扣除的红包金额，返回到本店合计中
			addPrice(shop_id,0,0,che_redpacket_price,che_redpacket_price,che_redpacket_price,1);

			che.parents(".hongb-sel").find('.redtitle').html('请选择你的平台红包');
			che.parents(".hongb-sel").find('.redprice').html('0.00');

		}
	}


	function iniRedpacket(shop_id) {
		var shop_redpacket = $(".redpacket"+shop_id);
		var shop_hongb_more = $(".redpacket"+shop_id).find(".hongb-more");
		var order_total = $('.sprice'+shop_id).html() - $('.shop_trancost'+shop_id).val();
		var _tmp,_hide_flag = true;
		shop_hongb_more.empty();
		shop_hongb_more.append('<li>请选择你的平台红包金额</li>');
		for (i = 0; i < rpt_list_json.length; i++)
		{
			_tmp = parseFloat(rpt_list_json[i]['redpacket_t_orderlimit']);
			order_total = parseFloat(order_total);
			if (order_total > 0 && order_total >= _tmp.toFixed(2))
			{
				shop_hongb_more.append("<li id='"+ rpt_list_json[i]['redpacket_id'] +"' class='redpacket_list red"+rpt_list_json[i]['redpacket_id']+"' value='" + rpt_list_json[i]['redpacket_price'] + "'>" + rpt_list_json[i]['redpacket_title'] + "</li>")
				_hide_flag = false;
			}
		}
		if (_hide_flag)
		{
			$('#shop_redpacket').hide();
		}
		else {
			$('#shop_redpacket').show();
		}
	}


	$(".sel-goods").click(function(){
		$(this).parent(".morder-buy-con").find(".jia-shop-are").toggle();
	})

})