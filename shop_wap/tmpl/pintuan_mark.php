<?php
include __DIR__.'/../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>一起拼</title>
	<link rel="stylesheet" href="../css/base.css">
	<link rel="stylesheet" href="../css/swiper.min.css">
	<link rel="stylesheet" href="../css/fight-groups.css">
	<script src="../js/swiper.min.js"></script>
	<script type="text/javascript" src="../js/zepto.min.js"></script>
</head>
<body>
	<header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l">
                <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>一起拼</h1>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout" id="groupList">

    </div>
    
</body>

<script type="text/html" id="groupListTemplate">
	<% if (groupList.length > 0) { %>
		<ul class="pt_det_lists">
			<% for (var i = 0; i < groupList.length; i++) { %>
				<li class="clearfix">
					<img src="<%= groupList[i].user_logo %>" class="fl">
					<div class="clearfix pt_det_lists_text fl">
					<input type="hidden" class="shop_user_id" value="<%= groupList[i].shop_user_id %>">
						<div class="fl"><strong><%= groupList[i].user_name %></strong><em></em></div>
						<div class="fr">
							<p class="fl"><strong>还差<%= groupList[i].lack %>人成团</strong><em><%= groupList[i].hour %>:<%= groupList[i].min %>:<%= groupList[i].second %>后结束</em></p>
							<p class="fr"><a class="gobuy" href="pintuan_info.html?mark_id=<%= groupList[i].id %>&detail_id=<%= groupList[i].detail_id %>">去拼团</a></p>
						</div>
					</div>
				</li>
			<% } %>
		</ul>
	<% } %>
</script>
<script type="text/javascript" src="../js/template.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/simple-plugin.js"></script>
<script type="text/javascript">
	$(function(){
		var detail_id = getQueryString("detail_id");
		$.ajax({
            url: ApiUrl + "/index.php?ctl=PinTuan&met=markList&typ=json",
            type: 'get',
            dataType: 'json',
            data: {detail_id:detail_id},
            success: function(e) {
				if (e.status == 200) {
					var groupListHtml = template.render("groupListTemplate", {groupList: e.data});
					$("#groupList").append(groupListHtml);
				} else {
					$.sDialog({skin: "red", content: "网络异常", okBtn: false, cancelBtn: false});
				}
            }
        });
        $('#groupList').on('click','.gobuy',function(){
        	var user_id = getCookie('id');//登录user_id
		    var pintuan_uid = $('.shop_user_id').val();//店铺user_id
		    if(user_id == pintuan_uid)
		    {
		        $.sDialog({
		            content: '商家不可以购买自己店铺的商品！',
		            okBtn:false,
		            cancelBtnText:'返回',
		        });
		        return false;
		    }
        });
	})
</script>
<?php 
include __DIR__.'/../includes/footer.php';
?>
</html>