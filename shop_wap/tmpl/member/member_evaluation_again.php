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
        <title>查看评价</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <style>
            .goods_geval {
                float: left !important;
            }
        </style>
    </head>
    <body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l"><a href="javascript:history.go(-1)"> <i class="back"></i> </a></div>
            <div class="header-title">
                <h1>查看评价</h1>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout" id="member-evaluation-div"></div>
    <footer id="footer" class="posr"></footer>
    <script type="text/html" id="member-evaluation-script">
        <%if(data.length > 0){
            var order_goods_evaluation_status = 0;
        %>
            <ul class="nctouch-evaluation-goods">
                <%for(var i=0; i < data.length; i++){%>
                    <% if(data[i].length > 1){ %>
                        <% for(var j=0; j < data[i].length; j++){
                            order_goods_evaluation_status=data[i][j].order_goods_evaluation_status;
                        %>
                        <li>
                            <%if(j>0){%>
                                <span class="fz-30 col5 pad20">追加评价：</span>
                            <%}%>
                            <% if(j == 0){%>
                            <div class="evaluation-info  pad30 evaluation-info-new">
                                <div class="goods-pic">
                                    <img src="<%=data[i][j].goods_image%>" />
                                </div>
                                <dl class="goods-info">
                                    <div class="flex-lr">
                                        <span class="goods-name z-dhwz"><%=data[i][j].goods_name%></span>
                                        <em class="goods-rate default-color">￥<%=data[i][j].goods_price%></em>
                                    </div>
                                    <div class="flex-lr">
                                        <span class="goods-size"><%=data[i][j].order_spec_info%></span>
                                        <em class="goods-num">x<%=data[i][j].order_goods_num%></em>
                                    </div>
                                </dl>
                            </div>
                            <% } %>
                            <div class="evaluation-inp-block pad20 mb-0">
                                <% if(j == 0){%>
                                <dl class="flex-lr fz-28 c-444 pb-20 borb1">
                                    <dt>评论时间</dt>
                                    <dd><%=data[i][j].create_time%></dd>
                                </dl>
                                <dl class="fz-28 c-444  pt-30 pb-30 fl-ll">
                                    <dt>商品评分</dt>
                                    <dd>
                                        <span class="star-level">
                                        <%for(var v=0;v<data[i][j].scores;v++){%>
                                            <i class="star-level-solid"></i>
                                        <% } %>
                                    </span>
                                    </dd>
                                </dl>
                                
                                <dl class="fz-28 c-444 pb-30">
                                    <dt>
                                        <span class="evalu-again-tit">
                                            商品评价
                                            <div class="goods-raty">
                                                <i class="star<%=data[i][j].scores%>"></i>
                                            </div>
                                        </span>
                                    </dt>
                                </dl>
                                
                                <% } %>
                                <% if(data[i][j].content){ %>
                                    <textarea class="text-area bor1" readonly="readonly"><%= data[i][j].content %></textarea>
                                <% } %>
                                <input type="hidden" class="evaluation_goods_id" name="evaluation_goods_id" value="<%=data[i][j].evaluation_goods_id%>" />
                                <br />
                                <% if(data[i][j].explain_content){ %>
                                    <dl class="fz-28 c-444 pb-30">
                                        <dt>
                                            <span class="evalu-again-tit">
                                                商家解释
                                                <div class="goods-raty">
                                                    <i class="star<%=data[i][j].scores%>"></i>
                                                </div>
                                            </span>
                                        </dt>
                                    </dl>
                                    <textarea class="text-area bor1" readonly="readonly"><%=data[i][j].explain_content%></textarea>
                                <% } %>
                            </div>
                            <% if(data[i][j].image_row.length > 0){ %>
                            <div class="evaluation-upload-block pl-20 pr-20">
                                <div class="fz-28 c-444 pb-30"><p>评价晒图</p></div>
                                <div class="clearfix">
                                    <% for(k=0;k < data[i][j].image_row.length;k++){ %>
                                    <div class="goods_geval goods_geval_img mr-20">
                                        <img src="<%=data[i][j].image_row[k]%>">
                                    </div>
                                    <% } %>
                                </div>
                            </div>
                            <% } %>
                        </li>
                        <% } %>
                    <% }else{ %>
                        <% for(var j=0; j < data[i].length; j++){ %>
                            <li>
                                <div class="evaluation-info pad30 evaluation-info-new">
                                    <div class="goods-pic">
                                        <img src="<%=data[i][j].goods_image%>" />
                                    </div>
                                    <dl class="goods-info">
                                        <div class="flex-lr">
                                            <span class="goods-name z-dhwz"><%=data[i][j].goods_name%></span>
                                            <em class="goods-rate default-color">￥<%=data[i][j].goods_price%></em>
                                        </div>
                                        <div class="flex-lr">
                                            <span class="goods-size"><%=data[i][j].order_spec_info%></span>
                                            <em class="goods-num">x<%=data[i][j].order_goods_num%></em>
                                        </div>
                                    
                                    </dl>
                                </div>
                                <div class="evaluation-inp-block pad20 mb-0">
                                    <dl class="flex-lr fz-28 c-444 pb-20 borb1">
                                        <dt>评论时间</dt>
                                        <dd><%=data[i][j].create_time%></dd>
                                    </dl>
                                    <dl class="fz-28 c-444  pt-30 pb-30 fl-ll">
                                        <dt>商品评分</dt>
                                        <dd>
                                            <span class="star-level">
                                                <%for(var v=0;v<data[i][j].scores;v++){%>
                                                    <i class="star-level-solid"></i>
                                                <% } %>
                                            </span>
                                        </dd>
                                    </dl>
                                    <dl class="fz-28 c-444 pb-30">
                                        <dt>
                                            <span class="evalu-again-tit">
                                                商品评价
                                                <div class="goods-raty">
                                                    <i class="star<%=data[i][j].scores%>"></i>
                                                </div>
                                            </span>
                                        </dt>
                                    </dl>
                                    <% if(data[i][j].content){%>
                                        <textarea class="text-area bor1" readonly="readonly"><%=data[i][j].content%></textarea>
                                    <% }%>
                                    <!-- <input type="text" class="textarea" value="<%=data[i].content%>"> -->
                                    <input type="hidden" class="evaluation_goods_id" name="evaluation_goods_id" value="<%=data[i][j].evaluation_goods_id%>" />
                                    <br />
                                    <% if(data[i][j].explain_content){ %>
                                    <dl class="fz-28 c-444 pb-30">
                                        <dt>
                                            <span class="evalu-again-tit">
                                                商家解释
                                                <div class="goods-raty">
                                                    <i class="star<%=data[i][j].scores%>"></i>
                                                </div>
                                            </span>
                                        </dt>
                                    </dl>
                                    <textarea class="text-area bor1" readonly="readonly"><%=data[i][j].explain_content%></textarea>
                                    <% } %>
                                </div>
                                <% if(data[i][j].image_row.length > 0){ %>
                                <div class="evaluation-upload-block pl-20 pr-20">
                                    <div class="fz-28 c-444 pb-30"><p>评价晒图</p></div>
                                    <div class="clearfix">
                                        <% for(k=0;k < data[i][j].image_row.length;k++){ %>
                                        <div class="goods_geval goods_geval_img mr-20">
                                            <img src="<%=data[i][j].image_row[k]%>">
                                        </div>
                                        <% } %>
                                    </div>
                                </div>
                                <% } %>
                            </li>
                        <%}%>
                    <%}%>
                <%}%>
            </ul>
            <%if(from != 'chain' && order_goods_evaluation_status != 2){%>
            <a class="btn-l mt5 mb5">追加评价</a>
            <%}%>
        <%}%>
    </script>
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/template.js"></script>
    
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member_evaluation_again.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    </body>
    </html>
<?php
    include __DIR__ . '/../../includes/footer.php';
?>