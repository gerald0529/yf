<?php
    include __DIR__ . '/../includes/header.php';
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
<div class="nctouch-main-layout">
    <div class="pt_det bgf">
        <img src="../images/new/cc/4.png" alt="" id="goods_image">
        <div>
            <h4><strong class="common-red" id="person_num">[5人团]</strong><span id="goods_name">韩亚，韩国进口牛奶(延世牧场)香蕉味、草莓味牛奶饮料</span></h4>
            <b id="buyer_sum" class="block">已拼256件</b><br />
            <p><strong class="common-red" id="price">￥125.00</strong><em class="common-red">包邮</em><b class="through" id="price_ori">原价：￥259.00</b></p>
        </div>
    </div>
    <div class="pt_status bgf">
        <div id="buyerList"></div>
        
        <p class='pt_endtime tc'><b></b>
            <span class="bgf">
		    		<em>剩余</em>
		    		<strong id="hour">00</strong>
		    		<i>时</i>
		    		<strong id="min">00</strong>
		    		<i>分</i>
		    		<strong id="second">00</strong>
		    		<i>秒</i>
		    		<em>结束</em>
	    		</span>
        </p>
        <p class="pt_tips tc">还差1人，一起拼成功</p>
        <div class="tc">
            <a href="javascript:" class="pt_btn_big">立即拼团</a>
        </div>
    </div>
    <h4 class="pt_tit borb1"><a href="pintuan_rule.html"><span>拼单须知</span><i class="icon fr"></i></a></h4>
    <div id='pinTuanGoods'>
    
    </div>
</div>

</body>
<script type="text/html" id="buyerListTemplate">
    <% if (buyerList.length > 0) { %>
    <ul class="tc">
        <% for (var i = 0; i < buyerList.length; i++) { %>
        <li>
            <span style="background:url(<%= buyerList[i].user_logo %>);background-size:contain;"></span>
            <% if (i == 0) { %>
            <b></b>
            <% } %>
        </li>
        <% } %>
        <% if (buyerList.length < person_num) { %>
            <% var num = person_num - buyerList.length %>
            <% for(var j=0;j < num; j++){%>
                <li><a href="javascript:" class="">?</a></li>
            <% }%>
        <% } %>
    </ul>
    <% } %>
</script>
<script type="text/html" id="pinTuanGoodsTemplate">
    <% if (pinTuanGoods.length > 0) { %>
    <ul class="pt_lists clearfix">
        <% for (var i = 0; i < pinTuanGoods.length; i++) { %>
        <li>
            <a href="pintuan_detail.html?&goods_id=<%= pinTuanGoods[i].goods.goods_id %>">
                <img src="<%= pinTuanGoods[i].goods.goods_image %>">
                <h5><strong class="common-red">[<%= pinTuanGoods[i].person_num %>人团]</strong><span><%= pinTuanGoods[i].goods.goods_name %></span></h5>
                <p><strong class="common-red">￥<%= pinTuanGoods[i].detail.price %></strong><b>单买价：￥<%= pinTuanGoods[i].detail.price_one %></b></p>
                <p class="clearfix"><b class="through fl">原价：￥<%= pinTuanGoods[i].detail.price_ori %></b>
                    <b class="fr">已拼<% if (pinTuanGoods[i].detail.buyer_num == 0) { %>0<% }else{ %> <%= pinTuanGoods[i].detail.buyer_num件 %><% } %>件</b></p>
            </a>
        </li>
        <% } %>
    </ul>
    <% } %>
</script>

<script type="text/javascript" src="../js/template.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript">
    $(function () {
        var detail_id = getQueryString("detail_id");
        var mark_id = getQueryString("mark_id");
        $.ajax({
            url: ApiUrl + "/index.php?ctl=PinTuan&met=pintuanInfo&typ=json",
            type: 'get',
            dataType: 'json',
            data: {detail_id: detail_id, mark_id: mark_id},
            success: function (e) {
                if (e.status == 200) {
                    var surplus_time = e.data.timestamp;
                    var interval = setInterval(function () {
                        surplus_time--;
                        var hour = parseInt(surplus_time / 3600);
                        var min = parseInt((surplus_time - hour * 3600) / 60);
                        var second = surplus_time - hour * 3600 - min * 60;
                        $("#hour").html(hour);
                        $("#min").html(min);
                        $("#second").html(second);
                    }, 1000);
                    if (surplus_time < 0) {
                        clearInterval(interval)
                    }
                    
                    $('#goods_image').attr('src', e.data.goods_detail.goods_image);
                    $('#goods_name').html(e.data.goods_detail.goods_name);
                    $('#person_num').html('[' + e.data.person_num + '人团]');
                    $('#price').html('￥' + e.data.detail.price);
                    $('#price_ori').html('原价：' + e.data.detail.price_ori);
                    $('#buyer_sum').html('已拼' + e.data.buyer_sum + '件');
                    $('.pt_tips').html('还差' + e.data.lack + '人，一起拼成功');
                    
                    if ($.inArray(getCookie('id'), e.data.buyer_id) >= 0) {
                        $('.pt_btn_big').html('不可重复参团');
                        $('.pt_btn_big').css('background', '#ccd0d9');
                    } else {
                        if (e.data.lack > 0) {
                            $('.pt_btn_big').attr('href', WapSiteUrl + "/tmpl/order/buy_step2.html?goods_id=" + e.data.detail.goods_id + "&goods_num=1&pt_detail_id=" + detail_id + "&type=pintuan&mark_id=" + mark_id);
                        } else {
                            $('.pt_btn_big').html('拼团完成');
                            $('.pt_btn_big').css('background', '#ccd0d9');
                        }
                    }
                    
                    var html = template.render("buyerListTemplate", {buyerList: e.data.buyer, person_num: e.data.person_num});
                    $("#buyerList").append(html);
                    var pinTuanGoodshtml = template.render("pinTuanGoodsTemplate", {pinTuanGoods: e.data.goods});
                    $("#pinTuanGoods").append(pinTuanGoodshtml);
                } else {
                    $.sDialog({skin: "red", content: "网络异常", okBtn: false, cancelBtn: false});
                }
            }
        });
    })
</script>
<?php
    include __DIR__ . '/../includes/footer.php';
?>
</html>