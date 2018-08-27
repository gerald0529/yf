$(document).ready(function(e) {
	$("#click_1").click(function(){
		 $(".tab-nav li").removeClass("tab-item-selected");
		$(this).addClass("tab-item-selected");
		  $("#fapiao").css("display","block");
		 $("#Electronics").css("display","none");
		 $("#increment").css("display","none");
		  $("#noraml").css("display","block");
		 });
      $("#click_2").click(function(){
		  $(".tab-nav li").removeClass("tab-item-selected");
		$(this).addClass("tab-item-selected");
		 $("#fapiao").css("display","none");
		 $("#Electronics").css("display","block");
		 $("#increment").css("display","none");
		 $("#noraml").css("display","none");
		 
		 });
	   $("#click_3").click(function(){
		   $(".tab-nav li").removeClass("tab-item-selected");
		$(this).addClass("tab-item-selected");
		 $("#fapiao").css("display","none");
		 $("#Electronics").css("display","none");
		 $("#increment").css("display","block");
		   $("#noraml").css("display","none");
		 });
	 
	 
	 $("#electro-invoice-content-1").click(function(){
	
		  $("#electro_book_content_radio li").removeClass("invoice-item-selected");
		$(this).addClass("invoice-item-selected");
		 
		 });
		  $("#electro-invoice-content-22").click(function(){
		   $("#electro_book_content_radio li").removeClass("invoice-item-selected");
		$(this).addClass("invoice-item-selected");
		 
			 
		 
		 });
		  $("#electro-invoice-content-3").click(function(){
	
		   $("#electro_book_content_radio li").removeClass("invoice-item-selected");
		$(this).addClass("invoice-item-selected");
		 
		 });
		 
		  $("#electro-invoice-content-19").click(function(){
	    
		   $("#electro_book_content_radio li").removeClass("invoice-item-selected");
		$(this).addClass("invoice-item-selected");
		
	
		 });
		 
		   $("#electro-invoice-content-2").click(function(){
		   $("#electro_book_content_radio_T li").removeClass("invoice-item-selected");
		$(this).addClass("invoice-item-selected");
		
	
		 });
		 	   $("#electro-invoice-content-4").click(function(){
		   $("#electro_book_content_radio_T li").removeClass("invoice-item-selected");
		$(this).addClass("invoice-item-selected");
		
	
		 });
		  	   $("#electro-invoice-content-5").click(function(){
		   $("#electro_book_content_radio_T li").removeClass("invoice-item-selected");
		$(this).addClass("invoice-item-selected");
		
	
		 });
		   	   $("#electro-invoice-content-6").click(function(){
		   $("#electro_book_content_radio_T li").removeClass("invoice-item-selected");
		$(this).addClass("invoice-item-selected");
		
	
		 });

	 
	
});