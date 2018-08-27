<div class="tip" style="display: none;">
	<div class="relative">
		<div class="tip-area">
			<h5><span class="icon"></span>提示</h5>
			<div class="tip-cont"></div>
			<div class="clearfix"><a href="javascript:;" class="btn-sure">确定</a> </div>

		</div>
		<a href="javascript:;" class="btn-close">X</a>
	</div>
</div>
<style>

.tip{width:100%;text-align:center;position:absolute;left:0;top:0;z-index:999;font-size:16px;padding:50px 0;}
.tip-area{width:300px;background:#fff;padding:50px 0 20px;position:relative;margin:auto;overflow:hidden;}
.tip-area h5{font-size:14px;position:absolute;left:-25px;top:-24px;background:#00a3ee;color:#fff;padding: 30px 20px 6px 40px;font-weight:normal;border-radius:26px;letter-spacing:3px;}
.tip-area h5 span{width:16px;height:16px;display:inline-block;background:url(../images/icon-tip.png) no-repeat;vertical-align:middle;margin-right:6px;}
.tip-area .tip-cont{padding:30px 0;text-align:center;color:#333;}
.tip-area .btn-sure{ text-decoration:none; float:right;line-height:26px;border:1px solid #999;padding:0 10px;margin-right:30px;color:#333;border-radius:2px;font-size:14px;}
.tip-area .btn-sure:hover{color:#00a3ee;border-color:#00a3ee;} 

.tip .btn-close{
	    text-decoration: none;
    line-height: 26px;
    border: 0;
    padding: 5px 10px;
    margin-right: 0px;
    color: #333;
    border-radius: 2px;
    font-size: 14px;
    margin-top: 5px;
}
a.btn-close{position:absolute;right:-9px;top:-7px;width:30px;height:30px;display:inline-block;background:url(../images/icon-close.png) no-repeat;background-size:cover;cursor:pointer;}
a.btn-close:hover{background:url(../images/icon-close-hov.png) no-repeat;background-size:cover;transition:0.3s;}
.relative{position:relative;display: inline-block;}
</style>

<script type="text/javascript">
	function alert_box(msg){
						$('.btn-sure').attr('href','javascript:;'); 
						$(".tip").css('margin-top',$('body').height()/2 - 180).show();
						$(".tip-cont").html(msg); 
	}

	function alert_box_link(msg,link){ 
					
					alert_box(msg);
					$('.btn-sure').attr('href',link);
	}

 

	$('.btn-close').click(function(){ $(".tip").hide(); });
	$('.btn-sure').click(function(){ $(".tip").hide(); });

</script>
