$(document).ready(function(){

	$("input[type='checkbox']").prop("checked",false);

	$(".get").click(function(){
		$(this).parent().parent().parent().find(".sale_detail").show();
	})
	$(".bk").click(function(){
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
					//$(".total").html(total.toFixed(2));

				}
			}
		);

	}

	var ww=$(document).height()-375;
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
		$("input[name='points_cart_id[]']:checked").each(function(){
			chk_value.push($(this).val());//将选中的值添加到数组chk_value中
		})

		$.dialog.confirm('您确定要删除吗？', function() {
			$.post(SITE_URL  + '?ctl=Points&met=removePointsCart&typ=json',{id:chk_value},function(data)
				{
					console.info(data);
					if(data && 200 == data.status) {
						Public.tips.success('删除成功!');
						window.location.reload(); //刷新当前页
					} else {
						Public.tips.error('删除失败!');
					}
				});
		});
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

	//单度选择商品
	$('.checkitem').click(function(){
		var _self = this;
		if (!this.disabled){
			$(this).prop('checked', _self.checked);

			if(_self.checked)
			{
				$(this).parent().parent().parent().addClass('item-selected');

				//判断是否所有商品都已选择，如果所有商品都选择了就勾选全选
				if($(".checkitem").not("input:checked").length == 0)
				{
					$('.checkall').prop('checked', true);
				}
			}
			else
			{
				$(this).parent().parent().parent().removeClass('item-selected');

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
		$(".cart-checkbox").find("input[name='points_cart_id[]']:checked").each(function(){
			var value = $(this).val();
			var price = $(this).parent().parent().parent().find(".price_all span").html();
			//price = price.replace(/,/g, "")
			price = Number(price);
			count = count + price;
			num ++;
		});
		$(".subtotal_all").html(count);
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
		var i=1;
		var id=h.attr("data-id");  //购物车id
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
		var url = SITE_URL + "?ctl=Points&met=editPointsCart&typ=json";
		var pars = 'points_cart_id='+id+'&quantity='+num;
		$.post(url, pars,showResponse);
		function showResponse(originalRequest)
		{
			if(originalRequest.status == 200 )
			{
				$('.cell' + id + ' span').html((Number(originalRequest.data.total_points).toFixed(2)));
				count();
			}
		}
	}

	$('.del a').click(function(){
		var e = $(this);
		var data_str = e.attr('data-param');
		eval( "data_str = "+data_str);
		$.dialog.confirm('您确定要删除吗？', function() {
				$.post(SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json',{id:data_str.id},function(data)
					{
						if(data.status == 200) {
							Public.tips.success('删除成功');
							e.parents('tr').hide('slow', function() {
								var row_count = $('#table_list').find('.row_line:visible').length;
								if(row_count <= 0)
								{
									$('#list_norecord').show();
									window.location.reload(); //刷新当前页
								}
							});
						} else {
							// showError(data.message);
							Public.tips.error('删除失败');
						}
				});
		});
	});

	//付款按钮
	$('.submit-btn').click(function(){
		
		//获取所有选中的商品id
		var chk_value =[];//定义一个数组
		$("input[name='points_cart_id[]']:checked").each(function(){
			chk_value.push($(this).val());//将选中的值添加到数组chk_value中
		})
		console.info(chk_value);

		if(chk_value != "")
		{
			//判断购物车商品是否超过限购数量
			$.ajax({
				url: SITE_URL + "?ctl=Points&met=checkLimitNum&typ=json",
				data:{points_cart_id:chk_value},
				type: "POST",
				success:function(e){
					if(e.status == 200)
					{
						$('#form').submit();
					}
					else
					{
						Public.tips.error(e.msg);
					}
				}
			});

		} else {
			Public.tips.warning("请选择需要兑换的商品");
		}
		
	});
 
	window.addAddress = function(val)
	{
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

	}

	window.editAddress = function(val)
	{
		area = val.user_address_area + ' ' + val.user_address_address;
		$("#addr"+val.user_address_id).find("h5").html(val.user_address_contact);
		$("#addr"+val.user_address_id).find("p").html(area);
		$("#addr"+val.user_address_id).find("phone").find("span").html(val.user_address_phone);

	}

	window.addInvoice = function(state,title,con)
	{
		str = state + ' ' + title + ' ' + con;
		$(".mr10").html(str);
	}

	//删除收货地址
	window.del_address = function(e)
	{
		$.dialog.confirm('您确定要删除吗？',function() {

			$.post(SITE_URL  + '?ctl=Buyer_User&met=delAddress&typ=json', {id: e}, function(data)
			{
				console.info(data);
				if(data && 200 == data.status) {
					Public.tips.success('删除成功');
					$("#addr"+e).hide('slow');
				} else {
					// showError(data.message);
					Public.tips.error('删除失败');
				}
			});
		});
	}

    window.edit_address = function (e)
    {
        url = SITE_URL + "?ctl=Buyer_Cart&met=resetAddress&id="+e;

        $.dialog({
            title: '修改地址',
            content: 'url: ' + url ,
            height: 420,
            width: 588,
            lock: true,
            drag: false,

        })
    };

	//检查是否选择收货地址
	function isChoseAddress()
	{
		var $address_list = $("#address_list");
		if ($address_list.get(0) && $address_list.find("li.add_choose").get(0)) {
			return true;
		}
		return false;
	}

	//去付款按钮（生成订单）
	$("#pay_btn").click(function(){

		if (! isChoseAddress()) {
			return $.dialog.alert("请填写收货地址");
		}
		//1.获取收货地址
			address_contact = $(".add_choose").find("h5").html();
			address_address = $(".add_choose").find("p").html();
			address_phone   = $(".add_choose").find(".phone").find("span").html();

		//3.获取商品信息（商品id，商品备注）
			var point_cart_id =[];//定义一个数组
			$("input[name='cart_id']").each(function(){
				point_cart_id.push($(this).val());//将值添加到数组中
			});
            remark = $("input[name='remarks']").val();


		$.ajax({
			type:'POST',
			url: SITE_URL  + '?ctl=Points&met=addPointsOrder&typ=json',
			data:{receiver_name:address_contact,receiver_address:address_address,receiver_phone:address_phone,point_cart_id:point_cart_id,remark:remark},
			dataType: "json",
			contentType: "application/json;charset=utf-8",
			async:false,
			success:function(a){
				if(a.status == 200)
				{
					Public.tips.success('订单提交成功');
					window.location.href = SITE_URL + "?ctl=Buyer_Points&met=points&op=getPointsOrder&state=1";
				}
				else
				{
                    Public.tips.error(a.msg);

				}
			},
			failure:function(a)
			{
                Public.tips.error('操作失败');
			}
		});

	});

})
