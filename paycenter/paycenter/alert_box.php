<link rel="stylesheet" type="text/css" href="<?=$this->view->css?>/palyCenter.css">
<div class="tip" style="display: none;">
	<div class="relative">
		<div class="tip-area">
			<h5><span class="icon"></span>提示</h5>
			<div class="tip-cont"></div>
			<div class="clearfix"><a href="javascript:;" class="btn-sure">确定</a> </div>

		</div>
		<a href="javascript:;" class="btn-close"></a>
	</div>
</div>
<style>

 

</style>

<script type="text/javascript">
	function alert_box(msg){
						$('.btn-sure').attr('href','javascript:;');  
						$(".tip-cont").html(msg);
						$('.tip').show(); 
	}

	function alert_box_link(msg,link){ 
					
					alert_box(msg);
					$('.btn-sure').attr('href',link);
	}

 
	$('.btn-close').click(function(){ $(".tip").hide(); });
	$('.btn-sure').click(function(){ $(".tip").hide(); });

</script>
