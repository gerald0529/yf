$(document).ready(function(){
	$(".add_address1").bind("click",function(){
		layer.open({
			type:2,
			title:"新建地址",
			maxmin:true,
			shadeClose:false,
			area:["588px","420px"],
			//content:STATIC_URL + "/html/resetAddress.html"
			content:SITE_URL + "?ctl=Buyer_Cart&met=resetAddress&typ=e"
		});

	})

	$(".add_address").bind("click",function(){
		url =SITE_URL + "?ctl=Buyer_Cart&met=resetAddress&typ=e";

		$.dialog({
			title: '新建地址',
			content: 'url: ' + url ,
			height: 340,
			width: 580,
			lock: true,
			drag: false,
		})
	})



	$(".invoice-edit1").bind("click",function(){
		layer.open({
			type:2,
			title:"修改发票信息",
			maxmin:true,
			shadeClose:false,
			area:["588px","600px"],
			content:SITE_URL + "?ctl=Buyer_Cart&met=piao&typ=e"
		});
		
	})

	$(".invoice-edit").bind("click",function(){
		url =SITE_URL + "?ctl=Buyer_Cart&met=piao&typ=e";

		$.dialog({
			title: '修改发票信息',
			content: 'url: ' + url ,
			height: 600,
			width: 588,
			lock: true,
			drag: false,
		})
	})

})
