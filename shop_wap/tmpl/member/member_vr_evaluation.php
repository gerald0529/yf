<?php 
include __DIR__.'/../../includes/header.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="msapplication-tap-highlight" content="no" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
    <title>订单评价</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
</head>
<style>
		input.textarea.wp100.chain{
			    display: block;
			    height: 100%;
			    padding: 0.2rem;
			    border: none;
			    font-size: 0.55rem;
			    line-height: 0.9rem;
			    resize: none;
		}
	</style>
<body>
<header id="header">
    <div class="header-wrap">
        <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>
        <div class="header-title">
            <h1>评价订单</h1>
        </div>
    </div>
</header>
<div class="nctouch-main-layout" id="member-evaluation-div"> </div>
<footer id="footer" class="posr"></footer>
<script type="text/html" id="member-evaluation-script">
    <form>
        <ul class="nctouch-evaluation-goods">
            <li>
                <div class="evaluation-info borb1 pad30">
                    <div class="goods-pic">
                        <img src="<%=order_goods[0].goods_image%>"/>
                    </div>
                    <dl class="goods-info">
                        <dt class="goods-name"><%=order_goods[0].goods_name%></dt>
                        <dd class="goods-rate">商品评分
                            <% if(order_base.order_buyer_evaluation_status == 1){ %>
                                <span class="star-level">
                                    <% for(var i = 0;i < evaluation.scores;i++ ){ %>
                                    <i class="star-level-solid cursor-no"></i>
                                    <% } %>
                                </span>
                            <% }else{ %>
        						<span class="star-level">
        							<i class="star-level-solid"></i>
        							<i class="star-level-solid"></i>
        							<i class="star-level-solid"></i>
        							<i class="star-level-solid"></i>
        							<i class="star-level-solid"></i>
        						</span>
                            <% } %>
                            <input type="hidden" name="goods[<%=order_goods[0].order_goods_id%>][score]" value="5" />
                            <input type="hidden" id="order_goods_id" value="<%=order_goods[0].order_goods_id%>" />
                        </dd>
                    </dl>
                </div>
                <div class="evaluation-inp-block  hg4 pl-30 pr-30">
                    <textarea type="text" class="textarea wp100 chain js-content" name="goods[<%=order_goods[0].order_goods_id%>][comment]" placeholder="亲，写点什么吧，您的意见对其他买家有很大帮助！" style="position: relative;"><% if(order_base.order_buyer_evaluation_status == 1){ %><%=evaluation.content%><% } %></textarea>
                    <% if(order_base.order_buyer_evaluation_status == 0){ %>
                        <div style="position: absolute;right: 5px;font-size: 0.5rem;color: #888;">
                            <span id="words" class="js-words">0</span>
                            <span>/ 200</span>
                        </div>
                    <% } %>
                    <!--<label>
                        <input type="checkbox" class="checkbox" name="goods[<%=order_goods[0].order_goods_id%>][anony]" value="1" /><p>匿&nbsp;名</p>
                    </label>-->
                </div>
            </li>
        </ul>
        <% if(order_base.order_buyer_evaluation_status == 0){ %>
            <a class="btn-l mt5 mb5">提交</a>
        <% } %>
    <form>
</script>
<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/template.js"></script>
<script type="text/javascript" src="../../js/common.js"></script>
<script type="text/javascript" src="../../js/simple-plugin.js"></script>
<script type="text/javascript" src="../../js/tmpl/member_vr_evaluation.js"></script>
<script type="text/javascript" src="../../js/tmpl/footer.js"></script>
</body>
</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>