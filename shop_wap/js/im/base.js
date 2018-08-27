function addToFavorite(d,c)
{
	if(document.all)
	{
		window.external.AddFavorite(d,c)
	}
	else
	{
		if(window.sidebar)
		{
			window.sidebar.addPanel(c,d,"")
		}
		else
		{
			alert("对不起，您的浏览器不支持此操作!请您使用菜单栏或Ctrl+D收藏本站。");
		}
	}
}
function get_randfunc(obj)
{
	var sj = new Date();
	url=obj.src;
	obj.src=url+'?'+sj;
}
function error(id,text)
{
	$("#"+id).show();
	$('#'+id).html(text);
	function closefunc()
	{
		$('#'+id).html("");
		$('#'+id).hide();
	}
	setTimeout(closefunc,10000);
}
$(function(){
	$(".select").click(function(){ 
		var obj=$(this);
		$(this).next().slideToggle("fast",function(){
		if($(obj).next().is(":visible")){
			$(document).one('click',function(){
				$(".select").next().slideUp("fast");
			});
		}});
	});
	$(".i-select li").click(function(){
		var str=$(this).html();
		$("#id").attr("value",$(this).attr("key"));
		$(this).parent().parent().prev().children().html(str);
		$(this).parent().parent().slideToggle();
	});
});