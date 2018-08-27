<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<link rel="stylesheet" type="text/css" href="<?=$this->view->css?>/base.css" />
<link rel="stylesheet" type="text/css" href="<?=$this->view->css?>/Group-integral.css" />
<script type="text/javascript" src="<?=$this->view->js?>/tuangou-index.js"></script>
<div class="wrap">
	<div class="t_ban">
		<div style="clear:both;"></div>
		<div class="tg tg_left">
			<h5><a href="<?=Yf_Registry::get('url')?>?ctl=PinTuan&met=index&typ=e"><?=__('拼团中心')?></a></h5>
            <?php

            ?>
			<p>
                <?php
                    if($data['category'])
                    {
                        foreach($data['category'] as $key=>$cat)
                        {
                ?>
                            <a href="<?=Yf_Registry::get('url')?>?ctl=PinTuan&met=index&cat_id=<?=$cat['cat_id']?>"><?=$cat['cat_name']?></a>
                <?php
                        }
                    }
                ?>
			</p>
          
		</div>
		<div class="tg_center" id="slides">
            <div class="banner swiper-container">
    			<ul class="items swiper-wrapper">
                    <?php
                    if($data['banner'])
                    {
                       
                        foreach($data['banner'] as $banner)
                        {
                    ?>
                    
                    <li class="swiper-slide"><a href="<?=$banner['link']?>"><img src="<?=$banner['image']?>" style="width:1043px;height:396px;"/></a></li>
                    <?php
                        }
                    }
                    ?>
                </ul>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>
		</div>
		<div style="clear:both;"></div>
	</div>


    <!-- 拼团商品列表 -->
	<div class="groups">
    <?php
        if ($data['recommend']) {
            foreach ($data['recommend'] as $recommend){
    ?>
            <div class="gr_goods clearfix">
                <img src="<?=$recommend['recommend_pic']?>" />

                <div class="gr_goods_imfor">
                    <div class="gr_goods_div">
                        <h4><a href="javascript:void(0);"><?=$recommend['name']?></a></h4>
                        <div class="gr_good_price clearfix">
                            <span class="bbc_color"><?=format_money($recommend['detail']['price'])?><em><?=format_money($recommend['detail']['price_ori'])?></em></span>
                            <a class="bbc_btns" href="javascript:void(0);"><?=__('立即拼团')?><i class="iconfont icon-iconjiantouyou"></i></a>
                        </div>
                        <div class="gr_good_lastime clearfix fnTimeCountDown" data-end="<?=$recommend['end_time']?>">
                            <span><i class="iconfont icon-shijian2"></i><?=__('还剩')?>
                                <span class="day" >00</span><strong><?=__('天')?></strong>
                                <span class="hour">00</span><strong><?=__('小时')?></strong>
                                <span class="mini">00</span><strong><?=__('分')?></strong>
                                <span class="sec" >00</span><strong><?=__('秒')?></strong>
                            </span>
                            <em><strong class="bbc_color"><?=$recommend['detail']['buyer_num'] ?></strong> <?=__('人已参团')?></em>
                        </div>
                    </div>
                    <p class="hotbg"></p>
                </div>
            </div>
    <?php
            }
        }
    ?>

        <?php   if($data['goods']){  ?>
		<ul class="gr_goods_list clearfix pt_code_area">
            <?php  foreach($data['goods'] as $value) { ?>
                <li>
                    <a href="javascript:void(0);"><?=$value['goods_name']?><em class="pt_goods_img"><b><img src="<?=$value['goods']['goods_image']?>"/></b></em></a>
                    <!-- 拼团 -->

                    <img src="<?=Yf_Registry::get('base_url')?>/shop/api/qrcode.php?data=<?php echo urlencode(Yf_Registry::get('shop_wap_url').'/tmpl/pintuan_detail.html?goods_id='.$value['detail']['goods_id'].'&pt_detail_id='.$value['detail']['id']) ;?>" class="pt_buy_code">

                    <p class="gr_goods_ev clearfix"><span><strong class="bbc_color"><?= format_money($value['detail']['price']) ?></strong></span><em><?=__('原价')?>：<?=$value['goods']['goods_price']?></em></p>
                    <h5>
                        <a href="javascript:;"><strong class="bbc_color">[<?=$value['person_num']?><?=__('人团')?>] </strong><?= $value['goods']['goods_name'] ?></a>
                    </h5>
                    <h6>单买价：￥<?=$value['detail']['price_one']?></h6>

                    <p class="gr_sold_hav clearfix"><span><?= __('已拼') ?><i class="num-color ml4"><?= $value['detail']['buyer_num'] ?></i><?= __('件') ?></span>
                        <em class="fnTimeCountDown" data-end="<?= $value['end_time'] ?>">
                            <span class="day">00</span><strong><?= __('天') ?></strong>
                            <span class="hour">00</span><strong><?= __('小时') ?></strong>
                            <span class="mini">00</span><strong><?= __('分') ?></strong>
                            <span class="sec">00</span><strong><?= __('秒') ?></strong>
                        </em>
                    </p>
                </li>
            <?php
                }
            ?>
		</ul>
        <?php  } ?>
	</div>
</div>

<script src="<?=$this->view->js_com?>/plugins/jquery.timeCountDown.js" ></script>
<script>
    // $("#slides").slideBox();
    $(function(){
        var _TimeCountDown = $(".fnTimeCountDown");
        _TimeCountDown.fnTimeCountDown();
    })

    //城市
    function city(e)
    {
        //地址中的参数
        var params = window.location.search;
        params = changeURLPar(params, 'city_id', e);
        window.location.href = SITE_URL + params;

    }

    function changeURLPar(destiny, par, par_value)
    {
        var pattern = par + '=([^&]*)';
        var replaceText = par + '=' + par_value;
        if (destiny.match(pattern))
        {
            var tmp = new RegExp(pattern);
            tmp = destiny.replace(tmp, replaceText);
            return (tmp);
        }
        else
        {
            if (destiny.match('[\?]'))
            {
                return destiny + '&' + replaceText;
            }
            else
            {
                return destiny + '?' + replaceText;
            }


        }
        return destiny + '\n' + par + '\n' + par_value;
    }
    
    $(document).ready(function () {
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            autoplayDisableOnInteraction: false,
            autoplay: 3000,
            speed: 300,
            loop: true, 
            grabCursor: true,
            paginationClickable: true,
           lazyLoading: true
        });
    });
</script>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>