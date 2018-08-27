/**
 * @author     朱羽婷
 */
$(document).ready(function(){
	window.payOrder = function(uo,o)
	{
		$.ajax({
			url:BASE_URL +'?ctl=Buyer_Order&met=checkOrder&typ=json',
			data:{order_id:o},
			dataType:"json",
			contentType:"application/json;charset=utf-8",
			async:false,
			success:function (a) {
				if(a.status == 250){
                    Public.tips.error(a.msg);
				}else{
                    //判断有没有支付单号，如果没有支付单号就去支付中心生成支付单号，如果有直接支付
                    if(uo)
                    {
                        window.location.href = PAYCENTER_URL + "?ctl=Info&met=pay&uorder=" + uo;
                    }
                    else
                    {
                        $.ajax({
                            url: SITE_URL  + '?ctl=Buyer_Order&met=addUorder&typ=json',
                            data:{order_id:o},
                            dataType: "json",
                            contentType: "application/json;charset=utf-8",
                            async:false,
                            success:function(a){
                                console.info(a);
                                if(a.status == 200)
                                {
                                    window.location.href = PAYCENTER_URL + "?ctl=Info&met=pay&uorder=" + a.data.uorder;
                                }
                                else
                                {
                                    if(a.msg != 'failure')
                                    {
                                        Public.tips.error(a.msg);
                                    }else
                                    {
                                        Public.tips.error('订单支付失败！');
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
                    }
				}
            }
		})

	}
	//取消订单
	window.cancelOrder = function(e)
       {
            url = SITE_URL + '?ctl=Buyer_Order&met=orderCancel&typ=';

			$.dialog({
				title: '取消订单',
				content: 'url: ' + url + 'e&user=buyer',
				data: { order_id: e},
				height: 250,
				width: 400,
				lock: true,
				drag: false,
				ok: function () {

					var form_ser = $(this.content.order_cancel_form).serialize();

					$.post(url + 'json', form_ser, function (data) {
						if ( data.status == 200 ) {
							Public.tips.success('订单取消成功!');
							window.location.reload();
							//$.dialog.alert('订单取消成功'), window.location.reload();
							return true;
						} else {
							Public.tips.error('订单取消失败！');
							//$.dialog.alert('订单取消失败');
							return false;
						}
					})
				}
			})
       }

	   //确认收货
       window.confirmOrder = function (e)
       {
            url = SITE_URL + '?ctl=Buyer_Order&met=confirmOrder&typ=';

			$.dialog({
				title: '确认收货',
				content: 'url: ' + url + 'e&user=buyer',
				data: { order_id: e},
				height: 200,
				width: 400,
				lock: true,
				drag: false,
				ok: function () {
					var form_ser = $(this.content.order_confirm_form).serialize();
					$.post(url + 'json', form_ser, function (data) {
						if ( data.status == 200 ) {
							Public.tips.success('确认收货成功！');
							window.location.reload();
							//$.dialog.alert('确认收货成功'), window.location.reload();
							return true;
						} else {
							Public.tips.error('数据未加载完成，请稍后再试！');
							//$.dialog.alert('确认订单失败');
							return false;
						}
					})
				}
			})
       }


	//隐藏订单
	window.hideOrder = function (e)
	{
		$.dialog({
			title: '删除订单',
			content: '您确定要删除吗？删除后的订单可在回收站找回，或彻底删除! ',
			height: 100,
			width: 405,
			lock: true,
			drag: false,
			ok: function () {

				$.post(SITE_URL  + '?ctl=Buyer_Order&met=hideOrder&typ=json',{order_id:e,user:'buyer'},function(data)
				{
					if(data && 200 == data.status) {
						Public.tips.success('删除成功！');
						setTimeout(function(){

							window.location.reload();

						},1500)
					} else {
						Public.tips.error('删除失败！');
						setTimeout(function(){

							window.location.reload();

						},1500)
					}
				})
			}
		})

	}

	//删除订单
	window.delOrder = function (e)
	{
		$.dialog({
			title: '删除订单',
			content: '您确定要永久删除吗？永久删除后您将无法再查看该订单，也无法进行投诉维权，请谨慎操作！',
			height: 100,
			width: 610,
			lock: true,
			drag: false,
			ok: function () {
				$.post(SITE_URL  + '?ctl=Buyer_Order&met=hideOrder&typ=json',{order_id:e,user:'buyer',op:'del'},function(data)
					{
						if(data && 200 == data.status) {
							Public.tips.success('删除成功！');
							setTimeout(function(){

								window.location.reload();

							},1500)

						} else {
							Public.tips.error('删除失败！');
							setTimeout(function(){

								window.location.reload();

							},1500)
						}
					}
				);
			}
		})
	}

	//还原订单
	window.restoreOrder = function (e)
	{
		$.dialog({
			title: '还原删除订单',
			content: '您确定要还原吗？',
			height: 100,
			width: 610,
			lock: true,
			drag: false,
			ok: function () {
				$.post(SITE_URL  + '?ctl=Buyer_Order&met=restoreOrder&typ=json',{order_id:e,user:'buyer'},function(data)
					{
						if(data && 200 == data.status) {
							Public.tips.success('还原成功！');
							window.location.reload();
							//$.dialog.alert('还原成功'), window.location.reload();
						} else {
							Public.tips.error('还原失败！');
							window.location.reload();
							//$.dialog.alert('还原成功'), window.location.reload();
						}
					}
				);
			}
		})
	}

	//取消退款
	window.cancelReturn = function(r)
	{
		$.dialog({
			title: '取消退款',
			content: '您确定要取消退款吗？',
			height: 100,
			width: 310,
			lock: true,
			drag: false,
			ok: function () {
				$.post(SITE_URL  + '?ctl=Buyer_Service_Return&met=cancelRefund&typ=json',{order_return_id:r},function(data)
					{
						if(data && 200 == data.status) {
							Public.tips.success('取消成功！');
							window.location.reload();
						} else {
							Public.tips.error('取消失败！');
							window.location.reload();
						}
					}
				);
			}
		})
	}


})