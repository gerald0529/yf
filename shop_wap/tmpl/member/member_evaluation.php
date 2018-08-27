<?php
    include __DIR__ . '/../../includes/header.php';
?>
    <!doctype html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-touch-fullscreen" content="yes" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="msapplication-tap-highlight" content="no" />
        <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
        <title>评价订单</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <style>
            .word {
                font-size: 0.5rem;
                color: #888;
            }
        </style>
    </head>
    <body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l"><a href="javascript:history.go(-1)"> <i class="back"></i> </a></div>
            <div class="header-title">
                <h1>评价订单</h1>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout" id="member-evaluation-div"></div>
    <footer id="footer" class="posr"></footer>
    
    <script type="text/html" id="member-evaluation-script">
        <%if(order_goods.length > 0){%>
        <form>
            <ul class="nctouch-evaluation-goods">
                <%for(var i=0; i
                <order_goods.length
                        ; i++){%>
                    <li>
                        <div class="nctouch-order-item-head borb1">
                            <a href="javascript:;" class="store"><i class="icon"></i><%=shop_base.shop_name%></a>
                        </div>
                        
                        <div class="evaluation-info borb1 pad30">
                            <div class="goods-pic">
                                <img src="<%=order_goods[i].goods_image%>" />
                            </div>
                            <dl class="goods-info">
                                <dt class="goods-name"><%=order_goods[i].goods_name%></dt>
                                <dd class="goods-rate">商品评分
                                    <span class="star-level">
							<i class="star-level-solid"></i>
							<i class="star-level-solid"></i>
							<i class="star-level-solid"></i>
							<i class="star-level-solid"></i>
							<i class="star-level-solid"></i>
						</span>
                                    <input type="hidden" name="goods[<%=order_goods[i].order_goods_id%>][score]" value="5" />
                                    <input type="hidden" name="order_goods_ids" value="<%=order_goods[i].order_goods_id%>" />
                                </dd>
                            </dl>
                        </div>
                        
                        <!--先统计 字符数 限制评论字数200字以内-->
                        <div class="evaluation-inp-block hg4 pl-30 pr-30">
                            <textarea type="text" class="js-content textarea wp100 chain" name="goods[<%=order_goods[i].order_goods_id%>][comment]" placeholder="亲，写点什么吧，您的意见对其他买家有很大帮助！" maxlength="200" style="position: relative;"></textarea>
                            <div style="position: absolute;right: 10px;">
                                <span id="words" class="word js-words">0</span>
                                <span class="word">/ 200</span>
                            </div>
                        </div>
                        
                        <!-- 1211优化 -->
                        <div class="evaluation-upload-block pl-30 pr-30">
                            <div id="picture_<%= order_goods[i].order_goods_id %>" style="display:inline-block"></div>
                            <div class="nctouch-upload">
                                <a href="javascript:void(0);">
                                    <span><input type="file" hidefocus="true" size="1" class="input-file" name="upfile" data-order-goods-id="<%=order_goods[i].order_goods_id%>"></span>
                                    <p><i class="icon-upload" style="height:90%"></i><em class="fz-26"></em></p>
                                </a>
                                <!--                        <b class="icon-close"></b>-->
                                <input type="hidden" name="goods[<%=order_goods[i].order_goods_id%>][evaluate_image][0]" value="" />
                            </div>
                        </div>
                    </li>
                    <%}%>
            </ul>
            <%if(shop_base.shop_self_support == false){%>
            <div class="nctouch-evaluation-store">
                <dl>
                    <dt>描述相符</dt>
                    <dd>
				<span class="star-level">
					<i class="star-level-solid"></i>
					<i class="star-level-solid"></i>
					<i class="star-level-solid"></i>
					<i class="star-level-solid"></i>
					<i class="star-level-solid"></i>
				</span>
                        <input type="hidden" name="store_desccredit" value="5" />
                    </dd>
                </dl>
                <dl>
                    <dt>服务态度</dt>
                    <dd>
				<span class="star-level">
					<i class="star-level-solid"></i>
					<i class="star-level-solid"></i>
					<i class="star-level-solid"></i>
					<i class="star-level-solid"></i>
					<i class="star-level-solid"></i>
				</span>
                        <input type="hidden" name="store_servicecredit" value="5" />
                    </dd>
                </dl>
                <dl>
                    <dt>发货速度</dt>
                    <dd>
				<span class="star-level">
					<i class="star-level-solid"></i>
					<i class="star-level-solid"></i>
					<i class="star-level-solid"></i>
					<i class="star-level-solid"></i>
					<i class="star-level-solid"></i>
				</span>
                        <input type="hidden" name="store_deliverycredit" value="5" />
                    <dd>
                </dl>
            </div>
            <%}%>
            <a class="btn-l mt5 mb5">提交评价</a>
            <form>
                <%}%>
    </script>
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member_evaluation.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    </body>
    </html>
<?php
    include __DIR__ . '/../../includes/footer.php';
?>