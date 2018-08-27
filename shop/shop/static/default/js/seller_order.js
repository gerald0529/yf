/**
 * @author     Str
 */
$(document).ready(function(){

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

				$.post(SITE_URL  + '?ctl=Seller_Trade_Order&met=hideOrder&typ=json',{order_id:e,user:'seller'},function(data)
				{
					if(data && 200 == data.status) {
						Public.tips.success('删除成功！');
						window.location.reload();
					} else {
						Public.tips.error('删除失败！');
						window.location.reload();
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
				$.post(SITE_URL  + '?ctl=Seller_Trade_Order&met=hideOrder&typ=json',{order_id:e,user:'seller',op:'del'},function(data)
					{
						if(data && 200 == data.status) {
							Public.tips.success('删除成功！');
							window.location.reload();
						} else {
							Public.tips.error('删除失败！');
							window.location.reload();
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
			width: 'auto',
			lock: true,
			drag: false,
			ok: function () {
				$.post(SITE_URL  + '?ctl=Seller_Trade_Order&met=restoreOrder&typ=json',{order_id:e,user:'seller'},function(data)
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

	//货到付款订单确认收款
	window.confirmCollection = function (e)
	{
		$.dialog({
			title: '确认收款',
			content: '您确定已收到买家付款？',
			height: 100,
			width: 610,
			lock: true,
			drag: false,
			ok: function () {
				$.post(SITE_URL  + '?ctl=Seller_Trade_Order&met=confirmCollection&typ=json',{order_id:e},function(data)
					{
						if(data && 200 == data.status) {
							Public.tips.success('确认收款成功！');
							window.location.reload();
						} else {
							Public.tips.error('确认收款失败！');
							window.location.reload();
						}
					}
				);
			}
		})
	}

})