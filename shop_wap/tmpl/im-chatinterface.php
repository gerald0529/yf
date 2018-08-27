<?php
include __DIR__.'/../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>聊天界面</title>
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />


    <script type="text/javascript" src="../js/zepto.js"></script>

    <script type="text/javascript" src="../js/template.js"></script>
    <script type="text/javascript" src="../js/common.js"></script>
    <script type="text/javascript" src="../js/simple-plugin.js"></script>

    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/swiper.min.js"></script>
    <script type="text/javascript" src="../js/im/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/im/jquery.toastr.min.js"></script>
    <script type="text/javascript" src="../js/im/mian.js"></script>

    <link rel="stylesheet" href="../css/im/main.css">
    <link rel="stylesheet" href="../css/im/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/swiper.min.css">



    <!-- sdk -->
    <script type="text/javascript" src="../js/im/ytx-web-im-min-new.js?12"></script>

    <!-- demo业务、表情包、录音 -->
    <script type="text/javascript" src="../js/im/justdo.js?4"></script>

    <script type="text/javascript" src="../js/im/emoji.js"></script>
    <link rel="stylesheet" href="../css/im/emoji.css">

    <!-- boostrap文件、日期插件 -->
    <script type="text/javascript" src="../js/im/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="../js/im/bootstrap-datetimepicker.zh-CN.js"></script>

    <!-- 地图 -->
<!--    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
    <!--<script src="http://api.map.baidu.com/api?v=2.0&ak=5At3anZe83x8oOpFap42Gt8eHYpy3wm9" type="text/javascript"></script>-->


    <link href="../css/im/bootstrap-responsive.css" rel="stylesheet">
    <link href="../css/im/bootstrap-datetimepicker.css" rel="stylesheet">
    <link href="../css/im/docs.css" rel="stylesheet">

</head>

<style>
    @media (max-width: 767px) {
        body {
            padding-right: 0px;
            padding-left: 0px;
        }
    }
    /*轮播图*/

    .swiper-container {
        width: 100%;
        height: 100%;
        position: relative;
        z-index: 1;
        margin: 0 auto;
        background-color: #f3f3f3;
    }

    .swiper-wrapper {
        position: relative;
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
        width: 100%;
        -webkit-transition-timing-function: ease;
        -moz-transition-timing-function: ease;
        -o-transition-timing-function: ease;
        transition-timing-function: ease;
        -webkit-transition-duration: 0s;
        -moz-transition-duration: 0s;
        -o-transition-duration: 0s;
        transition-duration: 0s;
        -webkit-transition-property: -webkit-transform, left, top;
        -moz-transition-property: -moz-transform, left, top;
        -o-transition-property: -o-transform, left, top;
        transition-property: transform, left, top;
        -webkit-transform: translate3d(0px, 0, 0);
        -moz-transform: translate3d(0px, 0, 0);
        -o-transform: translate3d(0px, 0, 0);
        -o-transform: translate(0px, 0px);
        transform: translate3d(0px, 0, 0);
        -ms-transition-property: -ms-transform, left, top;
        -ms-transition-duration: 0s;
        -ms-transform: translate3d(0px, 0, 0);
        -ms-transition-timing-function: ease
    }

    .swiper-free-mode>.swiper-wrapper {
        margin: 0 auto;
        -webkit-transition-timing-function: ease-out;
        -moz-transition-timing-function: ease-out;
        -o-transition-timing-function: ease-out;
        transition-timing-function: ease-out;
        -ms-transition-timing-function: ease-out
    }

    .swiper-slide {
        float: left;
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        box-sizing: content-box
    }

    .swiper-slide {
        float: left
    }

    .swiper-slide img {
        width: 100%;
        height: 100%;
    }

    .swiper-slide {
        background: #fff;
        text-align: center;
        font-size: 18px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
    }

    .swiper-container-horizontal>.swiper-pagination-bullets,
    .swiper-pagination-custom,
    .swiper-pagination-fraction {
        bottom: -35px;
        z-index: 9999;
    }
    .chatinterface-main{
        margin-top:0px;
    }
    #carousels{
        position:fixed;
        bottom:7%;
        left:7%
    }
</style>

<body>
    <div class="im-contents clearfix">
        <span><audio id="im_ring" src="../js/im/ring.mp3"></audio></span>
        <header class="col-sm-12 public-hd">
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 hd-left text-left">
                <a href="javascript:" onclick="history.go(-1)">
                <img src="../css/im/img/Arrow-left.png"></a>
            </div>
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 hd-center text-center content_you">

            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1  hd-right  text-right">
                <a href="" class="arrive-detail">
                <img src="../css/im/img/people.png" style="width: 2rem;">
                </a>
            </div>
        </header>
        <div id="im_content_list" style="overflow-y: auto; height:584px;" class="col-sm-12 chatinterface-main">

        </div>
        <footer class="col-sm-12 chatinterface-ft dynamic clearfix">
            <div class="visual-keyboard clearfix">

                <div class="pull-left voice">
                    <img src="../css/im/img/keyboard.png" class="voiceimg">
                    <img src="../css/im/img/keyboard.png" class="hide kbimg">
                </div>
                <div class="pull-left text">
                    <div class="written">
                        <pre contenteditable="true" class="pull-left textms" id="im_send_content" onkeyup="offtext()"></pre>
                        <pre id="im_send_content_copy" style="display: none;"></pre>

                        <div class="pull-left expression">

                            <img src="../css/im/img/expression.png" class="bqimg">


                            <img src="../css/im/img/keyboard.png" class="hide kbimg">

                        </div>
                    </div>
                    <!--<div class="voices">
                        <span>按住 说话</span>
                    </div>-->
                </div>
                <div class="Sendout" onclick="IM.DO_sendMsg()">
                    发送
                </div>
                <div class="pull-left mores">
                    <img src="../css/im/img/mores.png">

                </div>
            </div>
            <div style="width:100%;height:200px;position: relative;">
                <div class="More">
                    <div class="swiper-container" style="overflow: inherit; ">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <ul class="clearfix">
                                    <li id="fireMessage" class="add-on" onclick="IM.DO_fireMsg(this)" style="display: none;">
                                        <div class="bor-radius">
                                            <img src="../css/im/img/picture.png">
                                        </div>
                                        <p>阅后即焚</p>
                                    </li>
                                    <li onclick="IM.DO_im_attachment_file()">
                                        <div class="bor-radius">
                                            <img src="../css/im/img/picture.png">
                                        </div>
                                        <p>图片</p>
                                    </li>
                                    <!--<li>
                                        <div class="bor-radius">
                                            <img src="../css/im/img/smallvideo.png">
                                        </div>
                                        <p>小视频</p>
                                    </li>
                                    <li>
                                        <div class="bor-radius">
                                            <img src="../css/im/img/Red.png">
                                        </div>
                                        <p>红包</p>
                                    </li>
                                    <li>
                                        <div class="bor-radius">
                                            <img src="../css/im/img/collection.png">
                                        </div>
                                        <p>我的收藏</p>
                                    </li>-->
                                    <!--<li>
                                        <div class="bor-radius">
                                            <img src="../css/im/img/position.png">
                                        </div>
                                        <p>位置</p>
                                    </li>
                                    <li>
                                        <div class="bor-radius">
                                            <img src="../css/im/img/Voicechat.png">
                                        </div>
                                        <p>语音聊天</p>
                                    </li>-->
                                    <!--<li>
                                        <div class="bor-radius">
                                            <img src="../css/im/img/businesscard.png">
                                        </div>
                                        <p>名片</p>
                                    </li>-->
                                    <!--<li>
                                        <div class="bor-radius">
                                            <img src="../css/im/img/Voiceinput.png">
                                        </div>
                                        <p>语音输入</p>
                                    </li>-->
                                </ul>
                            </div>


                        </div>
                        <div class="swiper-pagination"></div>

                    </div>
                </div>
                <div class="manyemoticon">

                    <ul class="emoticon_ul clearfix" id="emoji_div">

                    </ul>
                </div>
            </div>

        </footer>

    <div id="lvjing" style="   display: none;
    z-index: 668888;
    /*position: fixed;*/
    margin-left: 0px;
    padding-left: 0px;
    left: 0px;
    top: 0px;
 /*   height: 90%;
    width: 100%;*/
    background: rgba(0,0,0,0.2);">


        <canvas id="lvjing_canvas" style="border:1px solid #aaa; display: none;">


        </canvas>
    </div>

    <div id="pop_photo" style="display:none; z-index: 888888; width: 100%; height:auto; position: absolute; top: 270px !important;left: 0px; margin: 5px 0 5px 0;">
        <div class="carousel slide" imtype="pop_photo_top" style="position: relative; top:auto; left: auto; right: auto; margin: 0 auto 0px; z-index: 1; max-width: 100%;">
            <div class="carousel-inner">
                <div class="carousel slide" id="carousels" style="text-align: center;">
                </div>
            </div>
            <!--
        <a class="left carousel-control" href="#myCarousel" data-slide="prev" onclick="IM.DO_pop_photo_up()">‹</a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next" onclick="IM.DO_pop_photo_down()">›</a>
-->
            <a class="right carousel-control" href="#myCarousel" data-slide="next" style="position:fixed;bottom: 6%;
    font-size: 40px;    line-height: 26px !important;" onclick="IM.HTML_pop_photo_hide()">×</a>
        </div>
    </div>
    <style type="text/css">
        .tip {
            margin-top: 50px;
        }
        .tip-area {
            border: 1px solid #ddd;
        }
    </style>
    <script type="text/javascript">
        function open_img(obj)
        {

            var img_url = $(obj).attr('src');
            IM.DO_spop_phone(img_url,this);
        }
        
        //判断微信登录用户昵称是否符合规范
        function regNickname(r)
        {
            var patrn = /^[G|g]{1}[\w-]*$/;
            var patrn1 = /^[A-Za-z0-9\u4E00-\uFA29_-]+$/;
            if (!patrn.exec(r) && patrn1.exec(r))
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        $(document).ready(function () {
            //初始化的时候获取appId和appToken

            $.ajax({
                type: "get",
                url: ImApiUrl + '?ctl=ImApi&met=getImConfig&typ=json',
                dataType: "json",
                success: function (c) {
                    console.log(c);
                    if (c.status == 200)
                    {
                        window.appID = c.data.im_appId;
                        window.appToken = c.data.im_appToken;

                    }
                    else
                    {

                    }
                }
            });

            $.ajax({
                type: "get",
                url: UCenterApiUrl + "?ctl=Login&met=checkStatus&typ=json",
                data: {
                    k: getQueryString('k'),
                    u: getQueryString('u'),
                },
                dataType: "jsonp",
                jsonp: "jsonp_callback",
                success: function (result)
                {
                    if (result.status == 200)
                    {
                        $.post(ImApiUrl + "?ctl=Login&met=checkToLogin&typ=json", {
                            ks: result.data.ks,
                            us: result.data.us
                        }, function (data)
                        {
                            console.info(data);
                            if (data.status == 200)
                            {
                                k = data.data.k;
                                u = data.data.user_id;
                                IM.init();

                                var flag = regNickname(getCookie('user_account'));
                                var user_account = getCookie('user_account');
                                if(flag)
                                {
                                    var user_name = user_account;
                                }
                                else
                                {
                                    var user_name = 'w'+"<?php echo md5(user_account);?>";
                                }
                                IM.DO_login(user_name);
                            }
                        })
                    }
                    else
                    {
                        $('.im-contents').hide();
                        alert_box(result.msg);
                    }
                }
            });



            var swiper = new Swiper('.swiper-container', {
                pagination: '.swiper-pagination',
                paginationClickable: true,
                autoplayDisableOnInteraction: false,
                grabCursor: true,
                paginationClickable: true,
                lazyLoading: true,
                pagination: '.swiper-pagination',

            });

        });

    </script>

</div>
</body>

</html>
<?php
include __DIR__.'/../includes/footer.php';
?>