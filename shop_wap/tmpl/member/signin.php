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
        <meta name="format-detection" content="telephone=no" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="msapplication-tap-highlight" content="no" />
        <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
        <title>登录领积分</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <style type="text/css">
            .s-dialog-wrapper {
                width: 12rem;
                height: 14.5rem;
                top: 50%;
                left: 50%;
                margin-top: -7.25rem;
                margin-left: -6rem;
            }

            .s-dialog-content h4 {
                font-size: 0.7rem;
                line-height: 1rem;
            }

            .s-dialog-content ul {
                margin-top: 0.5rem;
            }

            .s-dialog-content li {
                font-size: 0.55rem;
                line-height: 0.8rem;
                margin-bottom: 0.2rem;
                text-align: left;
            }

            .s-dialog-btn-wapper a {
                width: 100%;
            }
        </style>
    </head>

    <body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>
            <div class="header-title">
                <h1>登录领积分</h1></div>
            <div class="header-r"><a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a></div>
        </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"> <span class="arrow"></span>
                <ul>
                    <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../search.html"><i class="search"></i>搜索</a></li>
                    <li><a href="../cart_list.html"><i class="cart"></i>购物车</a><sup></sup></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="member-top member-top2 mb-20">
        <div class="my-pointnum"><em><i class="icon"></i><b>我的积分</b></em><span id="pointnum"></span> </div>
        <div class="sign-box" id="signdiv">
            <div id="signinbtn" class="sign-btn" style="display:none;">
                <h2>登录</h2>
                <h6>+<span class="points_signin"></span> 积分</h6>
            </div>
            <div id="completedbtn" class="sign-btn" style="display:none;">
                <h2>已登录</h2>
                <h6>+<span class="points_signin"></span> 积分</h6>
            </div>
        </div>
        <div id="description_link" class="signin-help"><i class="icon"></i>活动说明</div>
        <div id="description_info" style="display: none;">
            <h4>活动说明</h4>
            <ul>
                <li>1、每人每天登录只可获取一次积分。</li>
                <li>2、网站可根据活动举办的实际情况，在法律允许的范围内，对本活动规则变动或调整。</li>
                <li>3、对不正当手段（包括但不限于作弊、扰乱系统、实施网络攻击等）参与活动的用户，网站有权禁止其参与活动，取消其获奖资格（如奖励已发放，网站有权追回）。</li>
                <li>4、活动期间，如遭遇自然灾害、网络攻击或系统故障等不可抗拒因素导致活动暂停举办，网站无需承担赔偿责任或进行补偿。</li>
            </ul>
        </div>
        <h3 class="int-daily"><a href="pointslog_list.html">积分明细<img src="../../images/new/arrow-right.png" style="height:0.6rem;padding-top:0.2rem;padding-left:0.4rem"></a></h3>
    </div>
    <div class="bgf mine-definite pad20">
        <h6><img src="../../images/new-how2x.png">如何获取积分</h6>
        <ul>
            <li><em>会员注册</em><span id="points_reg">+1分</span></li>
            <li><em>会员登录</em><span id="points_login">+1分</span></li>
            <li><em>商品评价</em><span id="points_evaluate">+1分</span></li>
            <li><em>每单最多赠送</em><span id="points_order">+11分</span></li>
            <li><em>消费与赠送比例</em><span id="points_recharge">+1分</span></li>
        </ul>
    </div>
    <footer id="footer" class="bottom"></footer>
    <!-- 新增积分底部导航栏 -->
    <ul class="integral-foot-tab">
        <li><a href="../integral.html"><i class="icon"></i><span>积分兑换</span></a></li>
        <li class="active"><a href="signin.html"><i class="icon"></i><span>我的积分</span></a></li>
    </ul>

    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/ncscroll-load.js"></script>
    <script>
        $(function ()
        {
            var points_login = 0;   //登录获取积分
            var points_reg = 0;     //注册获取积分
            var points_evaluate = 0;    //评论获取积分
            var points_recharge = 0;    //订单每多少获取多少积分
            var points_order = 0;   //订单
            var key = getCookie('key');
            var u = getCookie('id');
            $.getJSON(ApiUrl + "/index.php?ctl=Api_User_Points&met=getPoint&typ=json", function (t)
            {
                points_login = t.data.points_login;
                points_reg = t.data.points_reg;
                points_evaluate = t.data.points_evaluate;
                points_recharge = t.data.points_recharge;
                points_order = t.data.points_order;
                $("#points_login").html('+'+points_login+'分');
                $("#points_reg").html('+'+points_reg+'分');
                $("#points_evaluate").html('+'+points_evaluate+'分');
                $("#points_recharge").html('+'+points_recharge+'分');
                $("#points_order").html('+'+points_order+'分');
                if(key)
                {
                    $(".points_signin").html(points_login);
                }
            });
           
            function showSignin() {
                //检验是否能签到
                $.getJSON(ApiUrl + '/index.php?act=member_signin&op=checksignin', {
                    'key': key
                }, function (result) {
                    if (result.status == 200) {
                        $(".points_signin").html(result.data.points_signin);
                        $("#signinbtn").show();
                        $("#completedbtn").hide();
                    } else {
                        if (result.state == 'isclose') { //如果关闭了签到功能，则不显示签到按钮
                            location.href = WapSiteUrl;
                        } else { //如果已经签到完成，则显示已签到
                            $("#signinbtn").hide();
                            $("#completedbtn").show();
                        }
                    }
                });
            }

            if (!key) {
                $("#signinbtn").show();
            }
            else
            {
                $("#completedbtn").show();
                //获取会员积分
                $.getJSON(ApiUrl + '/index.php?ctl=Buyer_Index&met=getUserInfo&typ=json', {
                    'k': key,
                    'u':u,
                }, function (result) {
                    //我的积分
                    $("#pointnum").html(result.data.points.user_points);

                });
                $("#signinbtn").click(function () {
                    if ($("#signinbtn").hasClass('loading')) {
                        return false;
                    }
                    $("#signinbtn").addClass('loading');

                    window.location.href = WapSiteUrl + '/tmpl/member/login.html';

                });
                $("#description_link").click(function () {
                    var con = $("#description_info").html();
                    $.sDialog({
                        content: con,
                        "width": 100,
                        "height": 100,
                        "cancelBtn": false,
                        "lock": true
                    });
                });
                //加载专题页
                /*$('#special_div').load('../../special.html',function(){
                 loadSpecial(1);
                 });*/
            }
        });
    </script>
    </body>

    </html>
<?php
include __DIR__.'/../../includes/footer.php';
?>