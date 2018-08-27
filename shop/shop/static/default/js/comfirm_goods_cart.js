

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

	$(".bk,.btn-close").click(function(){
		$(this).parent().parent().hide();
	})

	//切换用户收货地址，获取物流运费
	$(".receipt_address li").click(function(){
		$(".receipt_address li").removeClass('add_choose');
		$(this).addClass('add_choose');

		getTransport();
	});

	function getTransport()
	{
		var address_id = $(".add_choose").find('#address_id').val();
		var goods_id = getQueryString('goods_id');
                var goods_num = getQueryString('goods_num');
//                var address_id = getQueryString('address_id');
		location.href = SITE_URL + "?ctl=Buyer_Cart&met=confirmGoods&goods_id="+goods_id+"&address_id="+address_id+"&goods_num="+goods_num;
	}

	var ww = $(document).height()-173;
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
	}

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
		var url = SITE_URL + "?ctl=Buyer_Cart&met=resetAddress&id="+$(this).attr('data_id');

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
		var address_id   = $(".add_choose").find("#address_id").val();

		if(!address_id)
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

		var remark = $("input[name='remarks']").val();
		var token = $('#token').val();
		//获取支付方式
		pay_way_id = $(".pay-selected").attr('pay_id');

		$("body").css("overflow", "hidden");
		$("#mask_box").show();
                
		$.ajax({
			type:"POST",
			url: SITE_URL  + '?ctl=Buyer_Order&met=addGoodsOrder&typ=json',
			data:{
//				receiver_name:address_contact,
//				receiver_address:address_address,
//				receiver_phone:address_phone,
				invoice:invoice,
				invoice_id:invoice_id,
				invoice_title:invoice_title,
				invoice_content:invoice_content,
				remark:remark,
				pay_way_id:pay_way_id,
				address_id:address_id,
                                goods_id:goods_id,
                                goods_num:goods_num,
				from:'pc',
                                token:token
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
					} else {
						window.location.href = SITE_URL + '?ctl=Buyer_Order&met=physical';
						return false;
					}
				} else {
					if(a.msg != 'failure')
					{
						Public.tips.error(a.msg);
					} else {
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

	//选择支付方式
	$(".pay_way").click(function(){
            $(this).parent().find(".pay-selected").removeClass("pay-selected");
            $(this).addClass("pay-selected");
        })


})
