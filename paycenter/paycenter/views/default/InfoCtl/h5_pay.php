<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<!DOCTYPE html>
<html lang="en">
<!--xuezhigang(H5支付结果页面，临时创建，请自行调整)-->
<head>
    <meta charset="UTF-8">
    <!-- 设置宽度为设备的宽度，默认不缩放，不允许用户缩放（即禁止缩放），在网页加载时隐藏地址栏与导航栏（ios7.1新增） -->
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- 是否启动webapp功能，会删除默认的苹果工具栏和菜单栏。 -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <!-- 当启动webapp功能时，显示手机信号、时间、电池的顶部导航栏的颜色。默认值为default（白色），可以定为black（黑色）和black-translucent（灰色半透明）。这个主要是根据实际的页面设计的主体色为搭配来进行设置。 -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!-- 忽略页面中的数字识别为电话号码、email识别 -->
    <meta name="format-detection" content="telephone=no, email=no">
    <!-- 启用360浏览器的极速模式(webkit) -->
    <meta name="renderer" content="webkit">
    <!-- 避免IE使用兼容模式 -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- 针对手持设备优化，主要是针对一些老的不识别viewport的浏览器，比如黑莓 -->
    <meta name="HandheldFriendly" content="true">
    <!-- 微软的老式浏览器 -->
    <meta name="MobileOptimized" content="320">
    <!-- uc强制竖屏 -->
    <meta name="screen-orientation" content="portrait">
    <!-- QQ强制竖屏 -->
    <meta name="x5-orientation" content="portrait">
    <!-- UC强制全屏 -->
    <meta name="full-screen" content="yes">
    <!-- QQ强制全屏 -->
    <meta name="x5-fullscreen" content="true">
    <!-- UC应用模式 -->
    <meta name="browsermode" content="application">
    <!-- QQ应用模式 -->
    <meta name="x5-page-mode" content="app">
    <!-- windows phone 点击无高光 -->
    <meta name="msapplication-tap-highlight" content="no">
    <!-- 网页不会被缓存 -->
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="apple-touch-fullscreen" content="yes"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link id="apple-touch-icon" rel="apple-touch-icon-precomposed" sizes="114x114"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="description" content="远丰">
    <meta name="author" content="远丰">
    <meta name="keyword" content="远丰">
    <title>微信H5支付</title>
    <style type="text/css">
        html {
            font-size: calc(100vw / 3.75);
        }

        * {
            margin: 0;
            padding: 0;
            list-style: none;
            text-decoration: none;
            border: 0;
        }

        body {
            font-size: 12px;
            font-family: "SourceHanSansCN-Regular", "PingFangSC-Regular";
            background: #f5f5f5;
            color: #333;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: normal;
        }

        /***main**/
        .head {
            text-align: center;
            height: 0.44rem;
            background: #FFFFFF;
            box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.05);
        }

        .head span {
            font-size: 0.18rem;
            color: #313131;
            line-height: 0.44rem;
        }

        /***demo***/
        .main {
            width: 3.75rem;
            height: 6.2rem;
            background: #9C0E01;
            /*background: url("../image/bg_share.png") no-repeat center;*/
            background-size: 100%;
        }

        .form {
            text-align: center;
            position: absolute;
            top: 2rem;
            left: 0.14rem;
            width: 3.46rem;
            height: 1.92rem;
            margin: 0 auto;
            background: #FFFFFF;
            box-shadow: 0 0.02rem 0.06rem 0 rgba(0, 0, 0, 0.05);
            border-radius: 0.08rem;
            padding-top: 0.44rem;
        }

        .form p {
            font-family: SourceHanSansCN-Regular;
            font-size: 0.14rem;
            color: #868686;
            letter-spacing: 0;
            line-height: 0.14rem;
        }

        .form .btn {
            width: 2.75rem;
            margin: 0 auto;
            overflow: hidden;
            margin-top: 0.22rem;
        }

        .form .btn a {
            display: block;
            width: 2.75rem;
            height: 0.48rem;
            text-align: center;
            line-height: 0.48rem;
            font-family: SourceHanSansCN-Regular;
            font-size: 0.18rem;
            color: #FFFFFF;
            letter-spacing: 0;
            background: #C12A1C;
            box-shadow: 0 0.02rem 0.06rem 0 rgba(0, 0, 0, 0.05);
            border-radius: 0.08rem;
        }

        .form .btn .s_btn {
            background: #999;
        }
    </style>
</head>
<body>
<div class="head">
    <span>微信支付确认</span>
</div>
<div class="main">
    <div class="form">
        <p>请确认微信支付是否已完成</p>
        <div class="btn">
            <a href="<?=Yf_Registry::get('shop_api_url')?>?ctl=Buyer_Order&met=physical">已完成支付</a>
        </div>
        <div class="btn">
            <a class="s_btn" href="<?=Yf_Registry::get('shop_wap_url')?>tmpl/member/order_detail.html?order_id=<?=$order_id?>">支付遇到问题，重新支付</a>
        </div>
    </div>
</div>

</body>
</html>
<script>
    document.documentElement.style.fontSize = window.innerWidth / 3.75 + 'px';
</script>