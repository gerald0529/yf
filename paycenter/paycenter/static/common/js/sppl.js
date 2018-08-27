

$(document).ready(function(e) {
    $(".pl").click(function(){
		$(".pl_1").css("display","block");
		$(".zl_1").css("display","none");
		$(".xs_1").css("display","none");
		$(".xq_1").css("display","none");
		$(".wz_1").css("display","none");
		$(".bz_1").css("display","none");
		$(".sh_1").css("display","none");

		$(".goods_det").find("li a").removeClass("checked");
		$(".goods_det").find("li .pl").addClass("checked");
		
	});
	   $(".zl").click(function(){
		$(".pl_1").css("display","none");
		$(".zl_1").css("display","block");
		$(".xs_1").css("display","none");
		$(".xq_1").css("display","none");
		$(".wz_1").css("display","none");
		$(".bz_1").css("display","none");
		$(".sh_1").css("display","none");
		
	});
	
		$(".xs").click(function(){
		$(".pl_1").css("display","none");
		$(".zl_1").css("display","none");
		$(".xs_1").css("display","block");
		$(".xq_1").css("display","none");
		$(".wz_1").css("display","none");
		$(".bz_1").css("display","none");
		$(".sh_1").css("display","none");
		
	});
	
		$(".xq").click(function(){
		$(".pl_1").css("display","none");
		$(".zl_1").css("display","none");
		$(".xs_1").css("display","none");
		$(".xq_1").css("display","block");
		$(".wz_1").css("display","none");
		$(".bz_1").css("display","none");
		$(".sh_1").css("display","none");
		
	});




	$(".bz").click(function(){
		$(".pl_1").css("display","none");
		$(".zl_1").css("display","none");
		$(".xs_1").css("display","none");
		$(".wz_1").css("display","none");
		$(".bz_1").css("display","block");
		$(".sh_1").css("display","none");
		$(".xq_1").css("display","none");

	});

	$(".sh").click(function(){
		$(".pl_1").css("display","none");
		$(".zl_1").css("display","none");
		$(".xs_1").css("display","none");
		$(".wz_1").css("display","none");
		$(".bz_1").css("display","none");
		$(".sh_1").css("display","block");
		$(".xq_1").css("display","none");

	});


});