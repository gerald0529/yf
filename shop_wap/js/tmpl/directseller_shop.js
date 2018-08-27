$(function(){
	$('#loginbtn').click(function()
	{
		var user_directseller_shop = $('#user_directseller_shop').val();
		if(user_directseller_shop=='')
		{
			alert('不能为空');
			return false;
		}
		if(user_directseller_shop.length>20)
		{
			alert('不能超过20个字符');
			return false;
		}
		
		$.ajax({
			type:'post',
			url:ApiUrl+"/index.php?ctl=Distribution_Buyer_Directseller&met=setShopName&typ=json",
			data:{user_directseller_shop:user_directseller_shop,k:getCookie('key'),u:getCookie('id')},
			dataType:'json',
			success:function(result)
			{ 
				window.location.href = WapSiteUrl+"/tmpl/member/directseller.html"
			}
		});  
	});
})