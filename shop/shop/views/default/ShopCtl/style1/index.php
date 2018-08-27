<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<link rel="stylesheet"  type="text/css" href="<?=$this->view->css ?>/personalstores.css">
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/goods-detail.css" />
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/Group-integral.css" />
<script type="text/javascript" src="<?=$this->view->js?>/tuangou-index.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>
<link href="<?= $this->view->css ?>/login.css" rel="stylesheet">
<link href="<?= $this->view->css ?>/tips.css" rel="stylesheet">
<style>
    .full-screen-slides-pagination{
        display:none !important;
    }
</style>
<?php
if(!empty($shop_base['is_renovation'])) {

?>
 <div class="template-bg"> <!-- 模板二大背景色 -->
<div class="wrap clearfix template-gray"><!-- 切换模板二添加class template-gray -->
    <div class="bbc-store-info">
        <div class="basic">
            <div class="displayed"><a href=""><?=$shop_base['shop_name']?></a>
                        <span class="all-rate">
                     <div class="rating"><span style="width: <?=$shop_scores_percentage?>%"></span></div>
                       <em><?=$shop_scores_count?></em><em>分</em></span>
            </div>
            <div class="sub">
                <div class="store-logo"><img src="<?php if(empty($shop_base['shop_logo'])){ echo $this->web['shop_head_logo']; }else{echo $shop_base['shop_logo']; } ?>" alt="<?=$shop_base['shop_name']?>" title="<?=$shop_base['shop_name']?>"></div>
                <!--店铺基本信息 S-->
                <div class="bbc-info_reset">
                    <div class="title">
                        <h4><?=$shop_base['shop_name']?></h4>
                    </div>
                    <div class="content_reset">
                        <div class="bbc-detail-rate">
                            <ul>
                                <li>
                                    <h5><?=__('描述')?></h5>
                                    <div class="low" ><?=$shop_detail['shop_desc_scores']?><i></i></div>
                                </li>
                                <li>
                                    <h5><?=__('服务')?></h5>
                                    <div class="low" ><?=$shop_detail['shop_service_scores']?><i></i></div>
                                </li>
                                <li>
                                    <h5><?=__('物流')?></h5>
                                    <div class="low" ><?=$shop_detail['shop_send_scores']?><i></i></div>
                                </li>
                            </ul>
                        </div>
                        <div class="btns"><a href="index.php?ctl=Shop&met=goodsList&id=<?=$shop_id?>" class="goto"><?=__('进店逛逛')?></a><a href="#"><?=__('收藏店铺')?></a></div>
                        <?php if(!empty($shop_all_base)){?>
                            <dl class="no-border">
                                <dt><?=__('公司名称')?>：</dt>
                                <dd><?=$shop_all_base['shop_company_name']?></dd>
                            </dl>
                            <dl>
                                <dt><?=__('电话')?>：</dt>
                                <dd><?=$shop_all_base['company_phone']?></dd>
                            </dl>
                            <dl>
                                <dt><?=__('所在地')?>：</dt>
                                <dd><?=$shop_all_base['shop_company_address']?></dd>
                            </dl>
                        <?php }?>
                        <dl class="messenger">
                            <dt><?=__('联系方式')?>：</dt>
                            <dd><span member_id="9"></span>
                                <a target="_blank" href='http://wpa.qq.com/msgrd?v=3&uin=<?=$shop_base['shop_qq']?>&site=qq&menu=yes'><img border="0" src="http://wpa.qq.com/pa?p=2:<?=$shop_base['shop_qq']?>:52&amp;r=0.22914223582483828" style=" vertical-align: middle;"></a>
                                <a target="_blank" href='http://www.taobao.com/webww/ww.php?ver=3&touid=<?=$shop_base['shop_ww']?>&siteid=cntaobao&status=2&charset=utf-8'><img border="0" src='http://amos.alicdn.com/realonline.aw?v=2&uid=<?=$shop_base['shop_ww']?>&site=cntaobao&s=2&charset=utf-8' alt="<?=__('点击这里给我发消息')?>" style=" vertical-align: middle;"></a>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="store_decoration_content" class="background" style="<?php echo $decoration_detail['decoration_background_style'];?>">
        <?php if(!empty($decoration_detail['decoration_nav'])) {?>
            <style><?php echo $decoration_detail['decoration_nav']['style'];?></style>
        <?php } ?>
        <div class="ncsl-nav">
            <?php if(isset($decoration_detail['decoration_banner'])) { ?>
                <!-- 启用店铺装修 -->
                <?php if(@$decoration_detail['decoration_banner']['display'] == 'true') { ?>
                    <div id="decoration_banner" class="ncsl-nav-banner" style="text-align: center;">
                        <img src="<?php echo $decoration_detail['decoration_banner']['image_url'];?>" alt="">
                    </div>
                <?php } ?>
            <?php } else { ?>
                <!-- 不启用店铺装修 -->
                <div class="banner"><a href="" class="img">
                        <?php if(!empty($decoration_detail['store_info']['store_banner'])){?>
                            <img src="" alt="<?php echo $decoration_detail['store_info']['store_name']; ?>" title="<?php echo $decoration_detail['store_info']['store_name']; ?>" class="pngFix">
                        <?php }else{?>
                            <div class="ncs-default-banner"></div>
                        <?php }?>
                    </a></div>
            <?php } ?>
            <?php if(!empty($decoration_detail['decoration_nav']) || $decoration_detail['decoration_nav']['display'] == 'true') {?>
                <div id="nav" class="ncs-nav">
                    <ul>
                        <li class="active9"><a href="index.php?ctl=Shop&met=index&id=<?=$shop_id?>"><span><?=__('店铺首页')?><i></i></span></a></li><!-- 模板二当前页面的li需添加class为actives，例：<li class="active9 actives"> -->
                        <?php if(!empty($shop_nav)){ ?>
                            <?php
                            foreach ($shop_nav['items'] as $key => $value) {
                                ?>
                                <li><a href="<?=$value['url']?>" <?php if($value['target']){?>target="_blank" <?php } ?>><span><?=$value['title']?><i></i></span></a></li>
                            <?php }} ?>
                    </ul>
                </div>
            <?php } ?>
        </div>
        <?php

        require('store_decoration.preview.php');
        }
        ?>
    </div>
</div>
</div>
<?php if(($shop_base['is_renovation'] && $shop_base['is_only_renovation']=="0") || !$shop_base['is_renovation']){?>
 <div class="template-bg"> <!-- 模板二大背景色 -->
<div class="wrap clearfix template-gray">
    <?php if(empty($shop_base['is_renovation'])){?>
        <div class="bbc-store-info">
            <div class="basic">
                <div class="displayed"><a href=""><?=$shop_base['shop_name']?></a>
                        <span class="all-rate">
                     <div class="rating"><span style="width: <?=$shop_scores_percentage?>%"></span></div>
                       <em><?=$shop_scores_count?></em><em>分</em></span>
                </div>
                <div class="sub">
                    <div class="store-logo"><img src="<?php if(empty($shop_base['shop_logo'])){ echo $this->web['shop_head_logo']; }else{echo $shop_base['shop_logo']; } ?>" alt="<?=$shop_base['shop_name']?>" title="<?=$shop_base['shop_name']?>"></div>
                    <!--店铺基本信息 S-->
                    <div class="bbc-info_reset">
                        <div class="title">
                            <h4><?=$shop_base['shop_name']?></h4>
                        </div>
                        <div class="content_reset">
                            <div class="bbc-detail-rate">
                                <ul>
                                    <li>
                                        <h5><?=__('描述')?></h5>
                                        <div class="low" ><?=$shop_detail['shop_desc_scores']?><i></i></div>
                                    </li>
                                    <li>
                                        <h5><?=__('服务')?></h5>
                                        <div class="low" ><?=$shop_detail['shop_service_scores']?><i></i></div>
                                    </li>
                                    <li>
                                        <h5><?=__('物流')?></h5>
                                        <div class="low" ><?=$shop_detail['shop_send_scores']?><i></i></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="btns"><a href="index.php?ctl=Shop&met=goodsList&id=<?=$shop_id?>" class="goto"><?=__('进店逛逛')?></a><a href="#"><?=__('收藏店铺')?></a></div>
                            <?php if(!empty($shop_all_base)){?>
                                <dl class="no-border">
                                    <dt><?=__('公司名称')?>：</dt>
                                    <dd><?=$shop_all_base['shop_company_name']?></dd>
                                </dl>
                                <dl>
                                    <dt><?=__('电话')?>：</dt>
                                    <dd><?=$shop_all_base['company_phone']?></dd>
                                </dl>
                                <dl>
                                    <dt><?=__('所在地')?>：</dt>
                                    <dd><?=$shop_all_base['shop_company_address']?></dd>
                                </dl>
                            <?php }?>
                            <dl class="messenger">
                                <dt><?=__('联系方式')?>：</dt>
                                <dd><span member_id="9"></span>
                                    <a target="_blank" href='http://wpa.qq.com/msgrd?v=3&uin=<?=$shop_base['shop_qq']?>&site=qq&menu=yes'><img border="0" src="http://wpa.qq.com/pa?p=2:<?=$shop_base['shop_qq']?>:52&amp;r=0.22914223582483828" style=" vertical-align: middle;"></a>
                                    <a target="_blank" href='http://www.taobao.com/webww/ww.php?ver=3&touid=<?=$shop_base['shop_ww']?>&siteid=cntaobao&status=2&charset=utf-8'><img border="0" src='http://amos.alicdn.com/realonline.aw?v=2&uid=<?=$shop_base['shop_ww']?>&site=cntaobao&s=2&charset=utf-8' alt="<?=__('点击这里给我发消息')?>" style=" vertical-align: middle;"></a>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix">

            <div class="div_shop_Carouselfigure1" style="width:1200px;height: 150px;overflow: hidden;">
                <?php if(!empty($shop_base['shop_banner']) ){ ?>
      <img src="<?=$shop_base['shop_banner']?>" width="1200px" height="150px;"/></a>
      <?php }else{ ?>
      <img src="<?= $this->view->img ?>/shop_img.png" width="1200px" /></a>
      <?php } ?>

            </div>
        </div>
        <div id="nav" class="bbc-nav">
            <ul>
                <li class="active9"><a href="index.php?ctl=Shop&id=<?=$shop_id?>"><span><?=__('店铺首页')?><i></i></span></a></li><!-- 模板二当前页面的li需添加class为actives，例：<li class="active9 actives"> -->
                <?php
                if($shop_nav['items'])
                {
                    foreach ($shop_nav['items'] as $key => $value)
                    {
                        ?>
                        <li><a href="<?php if(!empty($value['url'])) echo $value['url'];else echo 'index.php?ctl=Shop&met=info&id='.$shop_id.'&nav_id='.$value['id']; ?>" <?php if($value['target']){?>target="_blank" <?php } ?>><span><?=$value['title']?><i></i></span></a></li>
                    <?php }} ?>
            </ul>
        </div>
    <div class="t_goods_bot clearfix">
        <div class="t_goods_bot_left">
            <div class="goods_classify">
                <h4><?=__('商品分类')?></h4>
                <p class="classify_like"><a href="index.php?ctl=Shop&met=goodsList&id=<?=$shop_id?>&order=common_sell_time&sort=desc"><?=__('按新品')?></a><a href="index.php?ctl=Shop&met=goodsList&id=<?=$shop_id?>&order=common_price&sort=desc"><?=__('按价格')?></a><a href="index.php?ctl=Shop&met=goodsList&id=<?=$shop_id?>&order=common_salenum&sort=desc"><?=__('按销量')?></a><a href="index.php?ctl=Shop&met=goodsList&id=<?=$shop_id?>&order=common_collect&sort=desc"><?=__('按收藏')?></a></p>
                <p class="classify_ser">
                    <input  name="searchGoodsList" type="text" placeholder="<?=__('搜索店内商品')?>">
                    <a  id="searchGoodsList"><?=__('搜索')?></a>
                </p>
                <ul class="ser_lists">
                    <li><a href="index.php?ctl=Shop&met=goodsList&id=<?=$shop_id?>"><?=__('全部商品')?></a></li>
                    <?php if(!empty($shop_cat)){
                        foreach ($shop_cat as $key => $value) {
                            ?>
                            <li><a href="index.php?ctl=Shop&met=goodsList&id=<?=$shop_id?>&shop_cat_id=<?=$value['shop_goods_cat_id']?>"><?=$value['shop_goods_cat_name']?></a></li>
                            <?php if(!empty($value['subclass'])){
                                foreach ($value['subclass'] as $keys => $values) {
                                    ?>
                                    <li class="list_style"><a href="index.php?ctl=Shop&met=goodsList&id=<?=$shop_id?>&shop_cat_id=<?=$values['shop_goods_cat_id']?>"><?=$values['shop_goods_cat_name']?></a></li>
                                <?php } } } }?>
                </ul>
            </div>
            <div class="goods_ranking">
                <h4><?=__('商品排行')?></h4>
                <ul class="ncs-top-tab pngFix">
                    <li id="hot_sales_tab" class="current"><a ><?=__('热销商品排行')?></a></li>
                    <li id="hot_collect_tab"><a><?=__('热门收藏排行')?></a></li>
                </ul>
                <div id="hot_sales_list" class="ncs-top-panel">
                    <ol>

                        <?php if(!empty($goods_selling_list['items'])){
                            foreach ($goods_selling_list['items'] as $key => $value) {
                                ?>
                                <li>
                                    <dl>
                                        <dt><a href="index.php?ctl=Goods_Goods&met=goods&gid=<?=$value['goods_id']?>"><?=$value['common_name']?></a></dt>
                                        <dd class="goods-pic"><a href="index.php?ctl=Goods_Goods&met=goods&gid=<?=$value['goods_id']?>"><span class="thumb size40"><i></i><img src="<?=$value['common_image']?>"  style="width:40px;height: 40px;"></span></a>
                                            <p><span class="thumb size100"><i></i><img src="<?=$value['common_image']?>" style="width:100px;height: 100px;" title="<?=$value['common_name']?>"><big></big><small></small></span></p>
                                        </dd>
                                        <dd class="price pngFix bbc_color"><?=format_money($value['common_price']) ?></dd>
                                        <dd class="selled pngFix"><?=__('售出')?>：<strong><?=$value['common_salenum']?></strong><?=__('笔')?></dd>
                                    </dl>
                                </li>
                            <?php } }?>

                    </ol>
                </div>
                <div id="hot_collect_list" class="ncs-top-panel hide">
                    <ol>
                        <?php if(!empty($goods_collec_list['items'])){
                            foreach ($goods_collec_list['items'] as $key => $value) {
                                ?>
                                <li>
                                    <dl>
                                        <dt><a href="index.php?ctl=Goods_Goods&met=goods&gid=<?=$value['goods_id']?>"><?=$value['common_name']?></a></dt>
                                        <dd class="goods-pic"><a href="index.php?ctl=Goods_Goods&met=goods&gid=<?=$value['goods_id']?>"><span class="thumb size40"><i></i><img src="<?=$value['common_image']?>"  style="width:40px;height: 40px;"></span></a>
                                            <p><span class="thumb size100"><i></i><img src="<?=$value['common_image']?>" style="

              width:100px;height: 100px;" title="<?=$value['common_name']?>"><big></big><small></small></span></p>
                                        </dd>
                                        <dd class="price pngFix bbc_color"><?=format_money($value['common_price']) ?></dd>
                                        <dd class="selled pngFix"><?=__('收藏人气')?>：<strong><?=$value['common_collect']?></strong></dd>
                                    </dl>
                                </li>
                            <?php } }?>

                    </ol>
                </div>

                <a href="./index.php?ctl=Shop&met=goodsList&id=<?=$shop_id ?>"><p class="look_other_goods bbc_btns"><?=__('查看本店其他商品')?></p></a>
            </div>
            <div class="current_hot">
                <h4><?=__('本店热门团购')?></h4>
                <ul>
                    <?php
                    if (isset($hot_groupbuy_data)):
                        foreach ($hot_groupbuy_data as $key_hot => $value_hot):
                            ?>
                            <li>
                                <a href="./index.php?ctl=GroupBuy&met=detail&id=<?= $value_hot['groupbuy_id'] ?>">
                                    <img src="<?= $value_hot['groupbuy_image_rec'] ?>">
                                </a>
                                <h5><?= $value_hot['goods_name'] ?></h5>

                                <p class="current_hot_price bbc_color"><span><?=format_money($value_hot['groupbuy_price']) ?></span></p>

                                <div class="current_hot__look clearfix"><a
                                        href="./index.php?ctl=GroupBuy&met=detail&id=<?= $value_hot['groupbuy_id'] ?>"><?=__('去看看')?></a>
                                </div>
                            </li>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </ul>
                <div class="hot_all"><a href="./index.php?ctl=GroupBuy&met=groupBuyList" class="bbc_btns"><?=__('全部热门团购')?></a></div>
            </div>
        </div>
        <div class="t_goods_bot_right-1">
        <div class="clearfix">
            <div class="div_shop_Carouselfigure" style="max-height:500px;">
                <div class="swiper-container">
                    <ul class="ui_shop_Carouselfigure items clearfix swiper-wrapper">
                        <?php if(!empty($shop_slide)){
                            foreach ($shop_slide as $key => $value) {
                                if($value){
                                    ?>
                                    <li class="swiper-slide"><a href="<?=$shop_slide_url[$key]?>"><img src="<?=$value ?>" width="max-height:500px" /></a></li>
                                <?php }}}?>

                    </ul>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination"></div>
                    <script type="text/javascript">
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
                </div>
            </div>
        </div>
    <?php }?>
            <div class="bbc-main-container">
                <div class="title"> <span><a href="index.php?ctl=Shop&met=goodsList&id=<?=$shop_id?>" class="more"><?=__('更多')?><span class="iconfont icon-iconjiantouyou rel_top-3"></span></a></span>
                    <h4><?=__('推荐商品')?></h4>
                </div>
                <div class="content_s bbc-goods-list">
                    <ul>
                        <?php if (!empty($goods_recom_list['items'])) {
                            foreach ($goods_recom_list['items'] as $key => $value) {

                                ?>
                                <li>
                                    <dl>
                                        <dt><a href="index.php?ctl=Goods_Goods&met=goods&gid=<?=$value['goods_id']?>" class="goods-thumb" target="_blank"><img  src="<?=image_thumb($value['common_image'],220,220)?>" alt="<?=$value['common_name']?>"></a>
                                        <ul class="goods-thumb-scroll-show">
                                            <li class="selectedSS"><a href="javascript:void(0);"><img width="60" src="<?=$value['common_image']?>"></a></li>
                                        </ul>
                                        </dt>
                                        <dd class="goods-name"><a href="index.php?ctl=Goods_Goods&met=goods&gid=<?=$value['goods_id']?>" title="<?=$value['common_name']?>" target="_blank"><?=$value['common_name']?></a></dd>
                                        <dd class="goods-info"><span class="priceSS bbc_color"><?=format_money($value['common_price']) ?></span> <span class="goods-sold"><?=__('已售')?>：<strong><?=$value['common_salenum']?></strong> <?=__('件')?></span></dd>
                                    </dl>
                                </li>
                            <?php }}?>
                    </ul>
                </div>
            </div>
            <div class="bbc-main-container">
                <div class="title"><span><a href="index.php?ctl=Shop&met=goodsList&id=<?=$shop_id?>" class="more"><?=__('更多')?><span class="iconfont icon-iconjiantouyou rel_top-3"></span></a></span>
                    <h4><?=__('新品')?></h4>
                </div>
                <div class="content_s bbc-goods-list">
                    <ul>
                        <?php if (!empty($goods_new_list['items'])) {
                            foreach ($goods_new_list['items'] as $key => $value) {
                                //  var_dump($goods_recom_list);exit;

                                ?>
                                <li>
                                    <dl>
                                        <dt><a href="index.php?ctl=Goods_Goods&met=goods&gid=<?=$value['goods_id']?>" class="goods-thumb" target="_blank"><img  src="<?=image_thumb($value['common_image'],220,220)?>" alt="<?=$value['common_name']?>"></a>
                                        <ul class="goods-thumb-scroll-show">
                                            <li class="selectedSS"><a href="javascript:void(0);"><img width="60" src="<?=$value['common_image']?>"></a></li>
                                        </ul>
                                        </dt>
                                        <dd class="goods-name"><a href="index.php?ctl=Goods_Goods&met=goods&gid=<?=$value['goods_id']?>" title="<?=$value['common_name']?>" target="_blank"><?=$value['common_name']?></a></dd>
                                        <dd class="goods-info"><span class="priceSS bbc_color"><?=format_money($value['common_price']) ?></span> <span class="goods-sold"><?=__('已售')?>：<strong><?=$value['common_salenum']?></strong> <?=__('件')?></span></dd>
                                    </dl>
                                </li>
                            <?php }}?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php }?>
</div>
</div>
<script>
    $("input[name='searchGoodsList']").blur(function(){
        var search = $("input[name='searchGoodsList']").val();
        if(search)
        {
            $("#searchGoodsList").attr('href',SITE_URL + '?ctl=Shop&met=goodsList&id=' + <?=$shop_id?>+'&search='+search);
        }
    });

    $(document).ready(function(){
        //热销排行切换
        $('#hot_sales_tab').on('mouseenter', function() {
            $(this).addClass('current');
            $('#hot_collect_tab').removeClass('current');
            $('#hot_sales_list').removeClass('hide');
            $('#hot_collect_list').addClass('hide');
        });
        $('#hot_collect_tab').on('mouseenter', function() {
            $(this).addClass('current');
            $('#hot_sales_tab').removeClass('current');
            $('#hot_sales_list').addClass('hide');
            $('#hot_collect_list').removeClass('hide');
        });
    });
    //收藏店铺
    window.collectShop = function(e){
        if ($.cookie("key"))
        {
            $.post(SITE_URL  + '?ctl=Shop&met=addCollectShop&typ=json',{shop_id:e},function(data)
            {
                if(data.status == 200)
                {
                    Public.tips.success(data.data.msg);
                    //$.dialog.alert(data.data.msg);
                }
                else
                {
                    Public.tips.error(data.data.msg);
                    //$.dialog.alert(data.data.msg);
                }
            });
        }
        else
        {
            $("#login_content").show();
            load_goodseval(SITE_URL  + '?ctl=Index&met=fastLogin','login_content');
        }
    }
</script>
<div class="bbuilder_code">
    <span class="bbc_codeArea"><img src="<?=Yf_Registry::get('base_url')?>/shop/api/qrcode.php?data=<?= urlencode(Yf_Registry::get('shop_wap_url')."/tmpl/store.html?shop_id=".$shop_base['shop_id'])?>"></span>
    <span class="bbc_arrow"></span>
    <div class="bbc_guide_con">
      <span>
          <div class="service-list1 service-list2" store_id="8" store_name="12312312发发">

              <dl>
                  <dt><?=__('售前客服')?>：</dt>
                  <?php if(!empty($service['pre'])){?>
                      <?php foreach($service['pre'] as $key=>$val){ ?>
                          <?php if(!empty($val['number'])){?>
                              <dd><span>
                  <span c_name="<?=$val['name']?>" member_id="9"><?=$val['tool']?></span>
                  </span></dd>
                          <?php }?>
                      <?php }?>
                  <?php }?>
              </dl>


              <dl>
                  <dt><?=__('售后客服')?>：</dt>
                  <?php if(!empty($service['after'])){?>
                      <?php foreach($service['after'] as $key=>$val){ ?>
                          <?php if(!empty($val['number'])){?>
                              <dd><span>
                  <span c_name="<?=$val['name']?>" member_id="9"><?=$val['tool']?></span>
                  </span></dd>
                          <?php }?>
                      <?php }?>
                  <?php }?>
              </dl>


              <dl class="workingtime">
                  <dt><?=__('工作时间')?>：</dt>
                  <?php if($shop_base['shop_workingtime']){?>
                      <dd>
                      <p><?=($shop_base['shop_workingtime'])?></p>
                      </dd><?php }?>
              </dl>

          </div>
      </span>
    </div>
</div>

<!-- 登录遮罩层 -->
<div id="login_content" style="display:none;">
</div>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
