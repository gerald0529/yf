
<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
    <?php
include $this->view->getTplPath() . '/' . 'header.php';

if (!isset($_COOKIE['sub_site_id']))
{
    $_COOKIE['sub_site_id'] = 0;
}
?>
<link href="<?= $this->view->css ?>/login.css" rel="stylesheet">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>
        <style>
            body {
                min-width: 1200px;
               /* background: #f0f3ef;*/
            }
        </style>
        <?php 
            $prefix_site = isset($_COOKIE['sub_site_id']) && $_COOKIE['sub_site_id'] > 0 ? $_COOKIE['sub_site_id'] : '';
        ?>
        <div style="height:500px;" class="slideBox">
            <div class="hd">
                <ul>
                    <?php 
                        for($i = 1; $i <= 5; $i ++){ 
                            $live_image_url = $prefix_site ? Web_ConfigModel::value($prefix_site.'index_slider_image'.$i) : Web_ConfigModel::value('index_slider_image'.$i, Web_ConfigModel::value('index_slider'.$i.'_image'));

                            if($live_image_url){
                    ?>  
                        <li> </li>
                    <?php }} ?>
                </ul>
            </div>
            <div class="banner  bd">
                <ul class="banimg">
                    <?php 
                        for($i = 1; $i <= 5; $i ++){ 
                            $live_image_url = $prefix_site ? Web_ConfigModel::value($prefix_site.'index_slider_image'.$i) : Web_ConfigModel::value('index_slider_image'.$i, Web_ConfigModel::value('index_slider'.$i.'_image'));

                            if($live_image_url){
                    ?>  
                        <li>
                            <a href="<?php echo Web_ConfigModel::value($prefix_site.'index_live_link'.$i);?>" style="background-image: url(<?=$live_image_url?>)"></a>
                        </li>
                    <?php }} ?>

                </ul>
                 <script type="text/javascript">
                    jQuery(".slideBox").slide({mainCell:".bd ul",autoPlay:true,delayTime:3000});
                </script>
                <div class="wrap t_cont clearfix">
                    <ul class="tcenter">
                        <li> 
                            <?php if (isset($_COOKIE['sub_site_id'])  && $_COOKIE['sub_site_id'] > 0){ $liandong_img_url1 = Web_ConfigModel::value($_COOKIE['sub_site_id'].'index_liandong_image1'); }else{$liandong_img_url1 = Web_ConfigModel::value('index_liandong_image1'); }?>
                            <?php if($liandong_img_url1){ ?>
                                <a href="<?php if (isset($_COOKIE['sub_site_id'])  && $_COOKIE['sub_site_id'] > 0){ echo Web_ConfigModel::value($_COOKIE['sub_site_id'].'index_liandong_url1');}else{echo Web_ConfigModel::value('index_liandong_url1');}?>"><img src="<?php if (isset($_COOKIE['sub_site_id'])  && $_COOKIE['sub_site_id'] > 0){echo Web_ConfigModel::value($_COOKIE['sub_site_id'].'index_liandong_image1');}else{echo Web_ConfigModel::value('index_liandong_image1', Web_ConfigModel::value('index_liandong1_image'));}?>"/></a>
                            <?php } ?>
                        </li>
                        <li> 
                            <?php if (isset($_COOKIE['sub_site_id'])  && $_COOKIE['sub_site_id'] > 0){ $liandong_img_url2 = Web_ConfigModel::value($_COOKIE['sub_site_id'].'index_liandong_image2'); }else{$liandong_img_url2 = Web_ConfigModel::value('index_liandong_image2'); }?>
                            <?php if($liandong_img_url2){ ?>
                                <a href="<?php if (isset($_COOKIE['sub_site_id'])  && $_COOKIE['sub_site_id'] > 0){ echo Web_ConfigModel::value($_COOKIE['sub_site_id'].'index_liandong_url2');}else{echo Web_ConfigModel::value('index_liandong_url2');}?>"><img src="<?php if (isset($_COOKIE['sub_site_id'])  && $_COOKIE['sub_site_id'] > 0){echo Web_ConfigModel::value($_COOKIE['sub_site_id'].'index_liandong_image2');}else{echo Web_ConfigModel::value('index_liandong_image2', Web_ConfigModel::value('index_liandong2_image'));}?>"/></a>
                            <?php } ?>
                        </li>
                    </ul>
                    <div class="tright" id="login_tright">
                    </div>
                </div>
            </div>
        </div>
        <div class="wrap">
			<!--私人定制-->
			<!--<div class="section">
                    <h3>
				<div>私人定制</div>z
				<div class="Eng-div">Private tailored</div>
			</h3>
                    <div class="wrap2 h_goods_cont">
                        <ul class="goodsUl clearfix">

                                                            <li>
                                    <a href="https://shop.local.yuanfeng021.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;type=goods&amp;gid=627" target="_blank" style="line-height:160px;"><img style="max-width: 160px;max-height: 160px;" src="https://shop.local.yuanfeng021.com/image.php/shop/data/upload/media/d3aabd05be45670d48e2685d1e1f5992/10104/51/image/20171024/1508809277928860.jpg!400x400.jpg"></a>
                                    <p class="goods_pri">
                                        ￥450.00                                    </p>
                                    <h5><a href="https://shop.local.yuanfeng021.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;type=goods&amp;gid=627" target="_blank">tz虚拟团</a></h5>
                                    <div class="buygo">
                                        <a href="https://shop.local.yuanfeng021.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;type=goods&amp;gid=627" target="_blank">
                    去看看                                     </a>
                                    </div>
                                </li>
                                                                <li>
                                    <a href="https://shop.local.yuanfeng021.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;type=goods&amp;gid=1158" target="_blank" style="line-height:160px;"><img style="max-width: 160px;max-height: 160px;" src="https://shop.local.yuanfeng021.com/image.php/shop/data/upload/media/d3aabd05be45670d48e2685d1e1f5992/10104/51/image/20171207/1512616470131037.jpg!400x400.jpg"></a>
                                    <p class="goods_pri">
                                        ￥150.00                                    </p>
                                    <h5><a href="https://shop.local.yuanfeng021.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;type=goods&amp;gid=1158" target="_blank">虚拟团购</a></h5>
                                    <div class="buygo">
                                        <a href="https://shop.local.yuanfeng021.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;type=goods&amp;gid=1158" target="_blank">
                                            去看看                                        </a>
                                    </div>
                                </li>
                                                                <li>
                                    <a href="https://shop.local.yuanfeng021.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;type=goods&amp;gid=822" target="_blank" style="line-height:160px;"><img style="max-width: 160px;max-height: 160px;" src="https://shop.local.yuanfeng021.com/image.php/shop/data/upload/media/d3aabd05be45670d48e2685d1e1f5992/10104/51/image/20171208/1512725511398842.png!400x400.png"></a>
                                    <p class="goods_pri">
                                        ￥50.00                                    </p>
                                    <h5><a href="https://shop.local.yuanfeng021.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;type=goods&amp;gid=822" target="_blank">tttttttt</a></h5>
                                    <div class="buygo">
                                        <a href="https://shop.local.yuanfeng021.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;type=goods&amp;gid=822" target="_blank">
                                            去看看                                        </a>
                                    </div>
                                </li>
                                                                <li>
                                    <a href="https://shop.local.yuanfeng021.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;type=goods&amp;gid=1968" target="_blank" style="line-height:160px;"><img style="max-width: 160px;max-height: 160px;" src="https://shop.local.yuanfeng021.com/image.php/shop/data/upload/media/d3aabd05be45670d48e2685d1e1f5992/10026/14/image/20180122/1516606427199455.jpg!400x400.jpg"></a>
                                    <p class="goods_pri">
                                        ￥100.00                                    </p>
                                    <h5><a href="https://shop.local.yuanfeng021.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;type=goods&amp;gid=1968" target="_blank">测试3</a></h5>
                                    <div class="buygo">
                                        <a href="https://shop.local.yuanfeng021.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;type=goods&amp;gid=1968" target="_blank">
                                            去看看                                        </a>
                                    </div>
                                </li>
                                                                <li>
                                    <a href="https://shop.local.yuanfeng021.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;type=goods&amp;gid=1798" target="_blank" style="line-height:160px;"><img style="max-width: 160px;max-height: 160px;" src="https://shop.local.yuanfeng021.com/image.php/shop/data/upload/media/d3aabd05be45670d48e2685d1e1f5992/10026/14/image/20180122/1516606465216912.jpg!400x400.jpg"></a>
                                    <p class="goods_pri">
                                        ￥100.00                                    </p>
                                    <h5><a href="https://shop.local.yuanfeng021.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;type=goods&amp;gid=1798" target="_blank">测试4</a></h5>
                                    <div class="buygo">
                                        <a href="https://shop.local.yuanfeng021.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;type=goods&amp;gid=1798" target="_blank">
                                            去看看                                        </a>
                                    </div>
                                </li>
                        </ul>
                    </div>
                </div>
			-->
            <!-- 团购风暴1111 -->
            <?php if(Web_ConfigModel::value('groupbuy_allow')){ ?>
                <div class="section">
                    <h3>
				<img src="<?= $this->view->img ?>/gpad.png"/>
				<a href="index.php?ctl=GroupBuy&met=index"><?=__('更多')?><span class="iconfont icon-btnrightarrow"></span></a>
			</h3>
                    <div class="wrap2 h_goods_cont">
                        <a class="lrwh btn1 iconfont icon-btnreturnarrow" data-numb="0"></a>

                        <ul class="goodsUl clearfix">

                            <?php if(!empty($gb_goods_list['items'])){
                                                    foreach ($gb_goods_list['items'] as $key => $value) {
                                                        ?>
                                <li>
                                    <a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$value['goods_id']?>" target="_blank" style="line-height:180px;"><img style="max-width: 200px;max-height: 150px;" src="<?= $value['groupbuy_image'] ?>"/></a>
                                    <p class="goods_pri">
                                        <?=format_money($value['groupbuy_price']) ?>
                                    </p>
                                    <h5><a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$value['goods_id']?>" target="_blank"><?= $value['groupbuy_name'] ?></a></h5>
                                    <p class="rest">
                                        <span class="iconfont icon-shijian2"></span>
                                        <strong class="fnTimeCountDown" data-end="<?=$value['groupbuy_endtime']?>"> 
                                                            <span class="day" >00</span><strong><?=__('天')?></strong>
                                        <span class="hour">00</span><strong><?=__('小时')?></strong>
                                        <span class="mini">00</span><strong><?=__('分')?></strong>
                                        <span class="sec">00</span><strong><?=__('秒')?></strong>
                                        </strong>
                                    </p>
                                    <div class="buygo">
                                        <a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$value['goods_id']?>" target="_blank">
                                            <?=__('立即去团')?>
                                        </a>
                                    </div>
                                </li>
                                <?php } }?>

                        </ul>
                        <a class="lrwh btn2 iconfont icon-btnrightarrow " data-num="0"></a>
                    </div>
                </div>
                <?php } ?>

                <div class="wrap floor fn-clear">
                    <?php if(!empty($adv_list['items'])){
                        foreach ($adv_list['items'] as $key => $value) {
                    ?>
                        <?=$value['page_html']?>
                    <?php } }?>
                </div>


        </div>
        </div>
        <div class="J_f J_lift lift" id="lift" style="left: 42.5px; top: 134px;">
            <ul class="lift_list  aad">
                <li class="J_lift_item_top lift_item lift_item_top">
                    <a href="javascript:;" class="lift_btn">
                        <span class="lift_btn_txt"><?=__('顶部')?><i class="lift_btn_arrow">
			</i></span>
                    </a>
                </li>
            </ul>
        </div>
        <script>
            $(function () {
                
                //遍历导航楼层111
                var atrf = [];
                var len = $(".floor .m").length;
                for (var mm = 0; mm < len; mm++) {
                    var str = $(".floor .m .title").eq(mm).text();
                    atrf.push(str);
                }
                var lis = "";
                $(atrf).each(function (i, n) {
                    lis += '<li class="J_lift_item lift_item lift_item_first"><a class="lift_btn"><span class="lift_btn_txt">' + n + '</span></a></li>';
                });
                $(".lift_list").prepend(lis);

                $(window).scroll(function () {
                        //滚动轴
                        var CTop = document.documentElement.scrollTop || document.body.scrollTop;
                        var floorone=$(".floor .m").eq(0).offset().top;
                        //当滚动轴到达楼层一时，左菜单栏显示
                        if (CTop >= floorone) {
                            $("#lift").show(500);
                        } else {
                            $("#lift").hide(500);
                        }
                    })
                    //.publicss  块
                    //.J_lift_item 左导航

                var b;
                $(".lift_list .J_lift_item").click(function () {
                        b = $(this).index();
                        $(".J_lift_item").removeClass("reds");
                        $(this).addClass("reds");
                        //离顶部距离
                        var offsettop = $(".floor .m").eq(b).offset().top;
                        console.log(offsettop);
                        //滚动轴距离
                        var scrolltop = document.body.scrollTop || document.documentElement.scrollTop;
                        //scrollTop() 方法返回或设置匹配元素的滚动条的垂直位置。
                        // scrolltop(
                            $("html,body").stop().animate({
                                scrollTop: offsettop
                            }, 1000);
                    })
                    //返回顶部
                $(".lift_item_top").click(function () {
                    $('html,body').animate({
                        scrollTop: '0px'
                    }, 800);
                });
                //滚动楼层对应切换左侧楼层导航
                var le = $(".floor .m").length;
                var arr = [];
                for (var s = 0; s < le; s++) {
                    var nums = $(".floor .m").eq(s).offset().top;
                    arr.push(nums);
                }
                console.log(arr);
                $(window).scroll(function () {
                    var scrTop = $(window).scrollTop();
                    for (var w = 0; w < arr.length; w++) {
                        var cc = arr[w + 1] || 1111111111;
                        if (scrTop >arr[w] && scrTop < cc) {
                            if (arr[w + 1] < 0) {
                                w = w + 1;

                            }
                            $(".J_lift_item").removeClass("reds");
                            $(".J_lift_item").eq(w+1).addClass("reds");
                        }
                    }


                });

            })
        </script>
        <?php

include $this->view->getTplPath() . '/' . 'footer.php';
?>