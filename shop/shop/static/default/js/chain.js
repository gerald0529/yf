/**
 * @author     zcg
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
        var value = $("#mob_phone").val();
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
		return errorMessage

	}

	$("#goodsremarks").keyup(function(){
	        var len = $("#goodsremarks").val().length;
	        if(len > 45){
				$("#goodsremarks").val($("#goodsremarks").val().substring(0,45));
			}
		});

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
        // //获取手机号码
        // mob_phone = $("#mob_phone").val();
        // flag = checkmobile();
        //
        // if(flag)
        // {
        //     Public.tips.error(flag);
        //     return false;
        // }
        //
        // //2.获取收货人
        // true_name = $("#true_name").val();
        // if(!true_name)
        // {
        //     Public.tips.error('请填写收货人');
        //     return false;
        // }

		var $checked_li = $('#address_list>li.add_choose ');
		if ($checked_li.length == 0) {
            return Public.tips.error('请填写收货信息');
		}

		var mob_phone = $checked_li.find('.js-phone').text(),
            true_name = $checked_li.find('.js-true-name').text();


        //3.获取商品信息（商品id，商品备注）
        cart_id = $("#cart_id").val();
		var goods_num = $("#goods_num").val();
        //加价购的商品
        var increase_goods_id = [];
        $(".increase_list").each(function(){
            if($(this).is('.checked'))
            {
                increase_goods_id.push($(this).find("#redemp_goods_id").val());
            }
        })

        //代金券信息
        var voucher_id = [];
		$("#shop_voucher").each(function(){
			if($(this).find('option').is(":selected"))
			{
				if($(this).find("option:selected").attr('voucher_id'))
				{
					voucher_id.push($(this).find("option:selected").attr('voucher_id'));
				}
			}
		})
		//红包
		var redpacket_id = $("#redpacket").find("option:selected").attr('red_id');
		if(!redpacket_id) redpacket_id = 0;

        //获取支付方式
        pay_way_id = $(".pay-selected").attr('pay_id');

        //门店信息
        var chain_id=$("#chain_id").val();
        //获取商品留言
        var remark = [];
        remark.push($("#goodsremarks").val());
        //店铺id
        var shop_id = $("#shop_id").val();
        //是否开启会员折扣
        var is_discount = 0;
        if($('#is_discount').is(':checked')) {
			is_discount = 1;
			voucher_id = [];
			redpacket_id = 0;
		}
		else
		{
			is_discount = 0;
		}
        $("body").css("overflow", "hidden");
        $("#mask_box").show();
        $.ajax({
            type:"POST",
            url: SITE_URL  + '?ctl=Buyer_Order&met=addOrder&typ=json',
            data: {
                receiver_name:true_name,
				receiver_phone:mob_phone,
				cart_id:cart_id,
				chain_id: chain_id,
				voucher_id:voucher_id,
				redpacket_id:redpacket_id,
				pay_way_id:pay_way_id,
				shop_id:shop_id,
				remark:remark,
				from:'pc',
                is_discount:is_discount
            },
            dataType: "json",
            contentType: "application/json;charset=utf-8",
            async:false,
            success:function(a){
                console.info(a);
                if(a.status == 200)
                {

                    if(pay_way_id == 1)
                    {
                        window.location.href = PAYCENTER_URL + "?ctl=Info&met=pay&uorder=" + a.data.uorder+'&order_g_type=chain';
                        return false;
                    }
                    else
                    {
                        window.location.href = SITE_URL + '?ctl=Buyer_Order&met=chain';
                        return false;
                    }
                }
                else
                {
                    if(a.msg != 'failure')
                    {
                        Public.tips.error(a.msg);
                    }else
                    {
                        Public.tips.error('订单提交失败！');
                    }

                    //alert('订单提交失败');
                }
            },
            failure:function(a)
            {
                Public.tips.error('操作失败！');
                //$.dialog.alert("操作失败！");
            }
        });

    });

	window.jiabuy = function(e)
	{
		limit = $(e).parents('.increase').find('#exc_goods_limit').val();
		shop_id = $(e).parents('.increase').find('#shop_id').val();

		if($(e).is('.checked'))
		{
			$(e).removeClass('checked');
			$(e).parents('.increase_list').removeClass('checked');

			good_price = $(e).parents('.increase_list').find(".redemp_price").val();

			//总价减价
			total_price = Number(Number($('.total').html())*1-good_price*1).toFixed(2);
			$('.total').html(total_price);
			//$(".rate_total").html((total_price*rate/100).toFixed(2));
		}
		else
		{
			//计算已经选择了加价购商品
			num = $(e).parents('.increase').find('.checked').length;
			if(limit <= 0 || (limit > 0 && num < limit))
			{
				$(e).addClass('checked');
				$(e).parents('.increase_list').addClass('checked');

				good_price = $(e).parents('.increase_list').find(".redemp_price").val();

				//总价加价
				total_price = Number(Number($('.total').html())*1+good_price*1).toFixed(2);
				$('.total').html(total_price);
				//$(".rate_total").html((total_price*rate/100).toFixed(2));
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
			$(e).removeClass("checked");
			$(e).parents('.voucher_list').removeClass('checked');

			//删除代金券信息
			$(".shop_voucher").html("");
		}else
		{
			$(e).parents(".voucher").find(".checked").removeClass("checked");
			$(e).addClass("checked");
			$(e).parents('.voucher_list').addClass('checked');

			//显示代金券信息
			$(".shop_voucher").html("使用" + voucher_price + "元代金券");
		}
	}
    //选择支付方式
    $(".pay_way").click(function(){
        $(this).parent().find(".pay-selected").removeClass("pay-selected");
        $(this).addClass("pay-selected");

        if ($(".pay_way.pay-selected").attr('pay_id') === '1') {
            $(".js-order-amount").text($("#orderAmount").val());
        } else {
        	var after_total = Number($('.after_total').html());
        	var total = Number($('.total').html());
        	var discount = Number($('.rate_total').html());
        	var pay_ = total - discount;
        	$("#pay").text('到店支付：');
            $(".js-order-amount").text(after_total);
        }
    });

	$('#address_list').on('click', 'li', function () {
		var $this = $(this);
		if ($this.hasClass('add_choose ')) {
			return ;
		}
        $("#address_list>li").removeClass('add_choose');
		$this.addClass('add_choose');
	});
})
