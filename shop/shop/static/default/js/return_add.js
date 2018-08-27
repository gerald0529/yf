/**
 * @author     朱羽婷
 */
$(document).ready(function()
{
	//选择需要退款的商品
	/*选择需要退款的商品后，将商品价格显示在退款金额处
	* 判断该件商品是否已被选择，如果已选择则取消选择，如果未选择则添加选择
	* 需要判断商品的单价和数量，可能存在商品数量与单价相乘后不等于订单总价的情况
	* 每次选择商品时需要判断订单商品商品是否全选了，商品是否是全部退款，如果是所有商品全都退款的话就直接显示订单商品金额
	* 在修改商品退款金额的时候要判断一下，修改的退款金额是否比订单的支付金额大，订单可退的最大金额就是订单的支付金额
	* 如果是还未发货的订单并且订单中的所有商品都选择退款的话，那么运费一起退还。如果只是部分商品申请退款的话，那么不退还运费
	* 退款只发生在发货之前。发货之后就不可退款，只能退货。
	 */
	window.choseGoods = function (e)
	{
		//判断是否退还所有可退商品
		var allchose = goodsAllChosed();

		//如果所有商品都退款则在退款金额处填写
		if(allchose)
		{
			$("#return_cash").html(sprintf('￥%.2f', return_cash));
			$("#cash").val(return_cash);
		}
		else  //计算退款金额
		{
			refundprice = computeRefund();
			$("#return_cash").html(sprintf('￥%.2f', refundprice));
			$("#cash").val(refundprice);
		}

	}

	//判断退款商品是否全选
	function goodsAllChosed()
	{
		var all_flag = 1;
		var nums = $(".goods_list").find(".refundnums").val();  //退还商品数量

		if(shipping_fee > 0 && order_status < 6)
		{
			if(all_gnum == nums)
			{
				$(".shipping").html('（包含运费）');
			}
			else
			{
				$(".shipping").html('');
			}
		}

		if(nums != gnum)
		{
			all_flag = 0;
		}

		return all_flag;
	}

	//计算退款金额
	function computeRefund()
	{
		var refund = 0;
		$(".goods_list").each(function(){
			var checkbox = $(this).find("input[type='checkbox']").not("input:checked");
			//如果未被选择商品为0，则表示所有商品均被选择。然后将退款的商品数量与购买数量进行比较。判断是否是所有商品都退款
			if(checkbox.length == 0)
			{
				var nums = $(this).find(".refundnums").val();  //退款商品数量
				var gnum = $(this).find(".gnum").val();  //商品购买数量

				var j = nums*1 > gnum*1 ? gnum*1 : nums*1;

				var price = $(this).find(".gprice").val();

				var sum = (Math.floor((j*price) * 100) / 100).toFixed(2);

				refund += sum*1;
			}
		});

		return refund;
	}

	$(".refundnum a").click(function(){
		var h=$(this).parents(".refundnum").find(".refundnums");
		if(!$(this).hasClass("no_reduce")){
			var j=parseInt(h.val(),10)||1;
			var f=h.attr("data-max");  //商品可退款退货的最大数量
			var i=1;
			if($(this).hasClass("add")&&!$(this).hasClass("no_add")){
				$(this).prev().prev().removeClass("no_reduce");
				$(this).prev().prev().addClass("reduce");
				if(f>i&&j>=f){
					$(this).removeClass("add");
					$(this).addClass("no_add");
				}
				else
				{
					j++;
				}
			}else{
				if($(this).hasClass("reduce")&&!$(this).hasClass("no_reduce")){
					j--;
					$(this).next().next().removeClass("no_add");
					$(this).next().next().addClass("add");
					if(j<=i){
						$(this).removeClass("reduce");
						$(this).addClass("no_reduce");
					}
				}
			}
			h.val(j);

			choseGoods();
		}
	});
	$(".refundnums").change(function ()
	{
		var h=$(this);
		var j=this;
		var k=$(j).val();
		var f=h.attr("data-max");
		var i=1;
		var l=Math.max(Math.min(f,k.replace(/\D/gi,"").replace(/(^0*)/,"")||1),i);
		$(j).val(l);
		var g=$(".num a");
		if(l==f){
			g.eq(1).removeClass("add");
			g.eq(1).addClass("no_add");
			if(l==i)
			{
				g.eq(0).removeClass("reduce");
				g.eq(0).addClass("no_reduce");
			}else
			{
				g.eq(0).removeClass("no_reduce");
				g.eq(0).addClass("reduce");
			}
		}else{
			if(l<=i){
				g.eq(0).removeClass("reduce");
				g.eq(0).addClass("no_reduce");
				g.eq(1).removeClass("no_add");
				g.eq(1).addClass("add");
			}else{
				g.eq(0).removeClass("no_reduce");
				g.eq(0).addClass("reduce");
				g.eq(1).removeClass("no_add");
				g.eq(1).addClass("add");
			}
		}

		choseGoods();
	});
})