<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<link rel="stylesheet" type="text/css" href="<?=$this->view->css?>/goods-detail.css" />
<link rel="stylesheet" type="text/css" href="<?=$this->view->css?>/Group-integral.css" />
<script type="text/javascript" src="<?=$this->view->js?>/tuangou-index.js"></script>
<script src="<?=$this->view->js_com?>/plugins/jquery.slideBox.min.js" type="text/javascript"></script>
<script src="<?=$this->view->js_com?>/sppl.js" ></script>
<script src="<?=$this->view->js_com?>/plugins/jquery.timeCountDown.js" ></script>
<script src="<?= $this->view->js?>/goods_detail.js"></script>
<script src="<?= $this->view->js_com ?>/plugins/jquery.imagezoom.min.js"></script>

<div class="wrap">
    <div class="t_goods_detail">
        <div class="crumbs clearfix">
            <p>
                <a href="<?=Yf_Registry::get('url')?>"><?=__('首页')?></a> <i class="iconfont icon-iconjiantouyou"></i>
                <a href="<?= Yf_Registry::get('url') ?>?ctl=GroupBuy&met=index"><?=__('团购中心')?></a><i class="iconfont icon-iconjiantouyou"></i>
                <?php if ($data['groupbuy_detail']['groupbuy_type'] == GroupBuy_BaseModel::ONLINEGBY){ ?>
                    <a href="<?=Yf_Registry::get('url')?>?ctl=GroupBuy&met=groupBuyList"><?=__('线上团')?></a>
                <?php }elseif ($data['groupbuy_detail']['groupbuy_type'] == GroupBuy_BaseModel::VIRGBY){ ?>
                    <a  href="<?=Yf_Registry::get('url')?>?ctl=GroupBuy&met=vrGroupBuyList"><?=__('虚拟团')?></a>
                <?php } ?>
                <?php if(!empty($data['cat']))
                {
                    foreach($data['cat'] as $key=>$cat)
                    {
                ?>
                <i class="iconfont icon-iconjiantouyou"></i><a href="<?=Yf_Registry::get('url')?>?ctl=GroupBuy&met=groupBuyList&cat_id=<?=$cat['id']?>"><?=$cat['name']?></a>
                <?php
                    }
                }
                ?>
                <i class="iconfont icon-iconjiantouyou"></i><a href="#"><?=$data['groupbuy_detail']['groupbuy_name']?></a> </p>
        </div>

        <div class="t_goods_evNone clearfix">
            <div style="width: 935px;" class="ev_left2">
                <div class="ncg-main buy-now">
                    <div class="ncg-group sticky">
                        <h2><?=$data['groupbuy_detail']['groupbuy_name']?></h2>
                        <h3 class="bbc_color"><?=$data['groupbuy_detail']['groupbuy_remark']?></h3>
                        <div class="ncg-item">
                            <div class="pic"><img src="<?=image_thumb($data['groupbuy_detail']['groupbuy_image'],400,400)?>" alt=""></div>
                            <div class="button bbc_bg">
                                <span class="bbc_color"><em><?=format_money($data['groupbuy_detail']['groupbuy_price'])?></em></span>
                                <a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$data['groupbuy_detail']['goods_id']?>" target="_blank">
                                <?php if($data['groupbuy_detail']['groupbuy_state'] == GroupBuy_BaseModel::UNDERREVIEW){ ?><?=__('审核中')?>
                                <?php }elseif($data['groupbuy_detail']['groupbuy_state'] == GroupBuy_BaseModel::NORMAL){ ?><?=__('我要团')?>
                                <?php }elseif($data['groupbuy_detail']['groupbuy_state'] == GroupBuy_BaseModel::FINISHED){ ?><?=__('已结束')?>
                                <?php }elseif($data['groupbuy_detail']['groupbuy_state'] == GroupBuy_BaseModel::AUDITFAILUER){ ?><?=__('审核失败')?>
                                <?php }elseif($data['groupbuy_detail']['groupbuy_state'] == GroupBuy_BaseModel::CLOSED){ ?><?=__('管理员关闭')?>
                                <?php }elseif($data['groupbuy_detail']['groupbuy_state'] == GroupBuy_BaseModel::WILLSTART){ ?><?=__('即将开始')?>
                                <?php } ?>
                                </a>
                            </div>
                            <div class="info" id="main-nav-holder">
                                <div style="width: 100%;" class="prices clearfix">
                                    <dl>
                                        <dt><?=__('原价')?></dt>
                                        <dd><del><?=format_money($data['groupbuy_detail']['goods_price'])?></del></dd>
                                    </dl>
                                    <dl>
                                        <dt><?=__('折扣')?></dt>
                                        <dd><em><?=__('立减')?><?=format_money($data['groupbuy_detail']['reduce'])?></em></dd>
                                    </dl>
                                    <dl>
                                        <dt><?=__('评分')?></dt>
                                        <dd>
                                            <div class="obvious_1">
                                                <p>
												<?php 
												for ($i = 1; $i <= $goods_detail['goods_base']['goods_evaluation_good_star']; $i++)
												{ ?><em></em><?php } ?>
                                                </p>
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                                <div class="trim"></div>
                                <div class="require clearfix">
                                    <h4><?=__('本商品已被团购')?><em class="bbc_seller"><?=$data['groupbuy_detail']['groupbuy_virtual_quantity']?></em><?=__('件')?></h4>
                                    <p><?=__('数量有限，欲购从速!')?></p>
                                </div>
                                <div class="time time-remain clearfix fnTimeCountDown" data-end="<?=$data['groupbuy_detail']['groupbuy_endtime']?>">
                                    <!-- 倒计时 距离本期结束 -->
                                    <i class="icon-time"></i><?=__('剩余时间')?>：
                                    <span class="day" >00</span><strong><?=__('天')?></strong>
                                    <span class="hour">00</span><strong><?=__('小时')?></strong>
                                    <span class="mini">00</span><strong><?=__('分')?></strong>
                                    <span class="sec" >00</span><strong><?=__('秒')?></strong>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

           <!-- 店铺信息-->
            <div class="ev_right_border">
                <div class="ev_right_pad ">
                    <div class="divimg ">
                        <img src="<?=($shop_detail['shop_logo'])?>">
                    </div>
                    <div class="txttitle clearfix ">
                        <p>
                            <a class="store-names" href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&typ=e&id=<?=($shop_detail['shop_id'])?>"><?=($shop_detail['shop_name'])?></a>
                            <?php if(Web_ConfigModel::value('im_statu')
                                && Yf_Registry::get('im_statu') ){ ?>
                                <a href="javascript:;" class="chat-enter" rel="<?=$shop_detail['user_name']?>"><i class="iconfont icon-btncomment"></i></a>
                            <?php }?>
                        </p>
                        
                        <?php if($shop_detail['shop_self_support'] == 'true'){?>
                        <div class="bbc_btns"><?=__('平台自营')?></div>
                        <?php }?>
                    </div>

                    <!-- 品牌-->
                    <?php if($shop_detail['shop_self_support'] == 'false'){?>
                    <div class="brandself ">
                        <ul class="shop_score clearfix ">
                            <li><?=__('店铺动态评分')?></li>
                            <li><?=__('同行业相比')?></li>
                        </ul>
                        <ul class="shop_score_content clearfix ">
                            <li>
                                <span><?=__('描述相符：')?><?=number_format($shop_detail['shop_desc_scores'],2,'.','')?></span>
                                        <span class="high_than bbc_bg">
                                            <?php if($shop_detail['com_desc_scores'] >= 0): ?><i class="iconfont  icon-gaoyu rel_top1"></i>
                                            <?=__('高于')?><?php else: ?><i class="iconfont  icon-diyu rel_top1"></i><?=__('低于')?><?php endif; ?>
                                        </span>
                                <em class="bbc_color"><?=number_format(abs($shop_detail['com_desc_scores']),2,'.','')?><?=__('%')?></em>
                            </li>
                            <li>
                                <span><?=__('服务态度：')?><?=number_format($shop_detail['shop_service_scores'],2,'.','')?></span>
                                        <span class="high_than bbc_bg">
                                            <?php if($shop_detail['com_service_scores'] >= 0): ?><i class="iconfont  icon-gaoyu rel_top1"></i><?=__('高于')?><?php else: ?><i class="iconfont  icon-diyu rel_top1"></i><?=__('低于')?><?php endif; ?>
                                        </span>
                                <em  class="bbc_color"><?=number_format(abs($shop_detail['com_service_scores']),2,'.','')?><?=__('%')?></em>
                            </li>
                            <li>
                                <span><?=__('发货速度：')?><?=number_format($shop_detail['shop_send_scores'],2,'.','')?></span>
                                        <span class="high_than bbc_bg">
                                            <?php if($shop_detail['com_send_scores'] >= 0): ?><i class="iconfont  icon-gaoyu rel_top1"></i><?=__('高于')?><?php else: ?><i class="iconfont  icon-diyu rel_top1"></i><?=__('低于')?><?php endif; ?>
                                        </span>
                                <em  class="bbc_color"><?=number_format(abs($shop_detail['com_send_scores']),2,'.','')?><?=__('%')?></em>
                            </li>
                        </ul>
                    </div>
                    <div class="shop_address">
                        <?=__('所 在 地 ：')?><?=($shop_detail['shop_region'])?>
                    </div>
                    <div class="follow_shop ">
                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&typ=e&id=<?=($shop_detail['shop_id'])?>" target="_blank" class="shop_enter"><?=__('进入店铺')?></a>
                        <a onclick="collectShop(<?=($shop_detail['shop_id'])?>)" class="shop_save"><?=__('收藏店铺')?></a>
                    </div>

                    <?php }?>

                        <?php if(isset($shop_detail['contract']) && $shop_detail['contract'] ):?>
                    <span class="fwzc "><?=__('服务支持：')?></span>
                    <ul class="ev_right_ul clearfix ">
                        <?php foreach($shop_detail['contract'] as $sckey => $scval):?>
                        <li><i><img src="<?=image_thumb($scval['contract_type_logo'],22,22)?>"/></i>&nbsp;&nbsp;&nbsp;<?=($scval['contract_type_name'])?></li>
                        <?php
                        endforeach;
                        ?>
                    </ul>
                    <?php
                    endif;
                    ?>
                </div>
                <!-- 自营 -->
                <?php if($shop_detail['shop_self_support'] == 'true'){?>
                <div class="look_again "><?=__('看了又看')?></div>
                <ul class="look_again_goods clearfix ">
                    <?php if (!empty($data_recommon_goods))
                    {
                    foreach ($data_recommon_goods as $key_recommon => $value_recommon)
                    {
                    ?>
                    <li>
                        <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($value_recommon['goods_id'])?>">
                            <img src="<?= $value_recommon['common_image'] ?>"/>
                            <h5 class="bbc_color"><?= format_money($value_recommon['common_price']) ?></h5>
                        </a>
                    </li>
                    <?php
                    }
                    }
                    ?>
                </ul>
                <?php }?>
            </div>


        </div>
    </div>
</div>

<div class="t_goods_bot clearfix wrap">
    <div class="t_goods-left">
        <div class="current_hot current_hots">
            <h4><?=__('本期热门团购')?></h4>
            <ul>
                <?php
                    if(!empty($data['hot_groupbuy']['items']))
                    {
                        foreach($data['hot_groupbuy']['items'] as $key=>$value)
                        {
                ?>
                <li>
                    <a href="<?=Yf_Registry::get('url')?>?ctl=GroupBuy&met=detail&id=<?=$value['groupbuy_id']?>">
                        <img src="<?=image_thumb($value['groupbuy_image'],200,150)?>">
                    </a>
                    <h5><?=$value['groupbuy_name']?></h5>
                    <p class="current_hot_price"><span class="bbc_color"><?=format_money($value['groupbuy_price'])?></span></p>
                </li>
                <?php
                        }
                    }
                ?>
            </ul>
            <div class="hot_all"><a class="bbc_btns" href="<?=Yf_Registry::get('url')?>?ctl=GroupBuy&met=index&typ=e"><?=__('全部热门团购')?></a></div>
        </div>
    </div>

    <div class="t_goods_bot_right t_goods_ct_right">
        <ul class="goods_det_about goods_det border_top clearfix">
            <li><a class="xq checked"><?=__('商品详情')?></a></li>
            <li><a class="pl"><?=__('商品评论')?><span>(<?=($goods_detail['goods_base']['evalcount'])?>)</span></a></li>
            <li><a class="xs"><?=__('销售记录')?><span>(<?= ($goods_detail['goods_base']['goods_salenum']) ?>)</span></a></li>
        </ul>
        <ul class="goods_det_about_cont">
            <!--商品咨询-->
            <div id="goodsadvisory" style="display:none;" class="ncs-commend-main zl_1"></div>
            <!-- 商品评论 -->
            <div id="goodseval" style="display:none;" class="ncs-commend-main pl_1"></div>
            <!-- 商品查询 -->
            <div id="saleseval" style="display:none;" class="ncs-commend-main xs_1"></div>
            <!-- 详细-->
            <li class="xq_1" style="display:block">
                <?=$data['groupbuy_detail']['groupbuy_intro']?>
            </li>
        </ul>
        <div class=" details_div  details_divs">
            <p class="p1"><?=__('推荐商品')?></p>
        </div>
        <div class="das2 clearfix">
            <?php if (!empty($data_foot_recommon_goods))
            {
                foreach ($data_foot_recommon_goods as $key_foot_recommon_goods => $value_foot_recommon_goods)
                {
                    ?>
                    <a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($value_foot_recommon_goods['goods_id'])?>">
                        <div class="img_2">
                            <div><img src="<?= image_thumb($value_foot_recommon_goods['common_image'],122,122) ?>"></div>
                            <p class="pp"><?= $value_foot_recommon_goods['common_name'] ?></p>
                            <p class="pColor bbc_color"><?= format_money($value_foot_recommon_goods['common_price']) ?></p>
                        </div></a>
                <?php
                }
            }
            ?>
        </div>
    </div>
</div>

<script>
$(function(){
    var _TimeCountDown = $(".fnTimeCountDown");
    _TimeCountDown.fnTimeCountDown();
})

var goods_id    = <?=($goods_detail['goods_base']['goods_id'])?>;
var common_id   = <?=($goods_detail['goods_base']['common_id'])?>;
var shop_id     = <?=($data['groupbuy_detail']['shop_id'])?>;

//收藏店铺
window.collectShop = function(e){
    if (<?=Perm::checkUserPerm()?1:0?>)
    {
        $.post(SITE_URL  + '?ctl=Shop&met=addCollectShop&typ=json',{shop_id:e},function(data)
        {
            if(data.status == 200)
            {
                $.dialog.alert(data.data.msg);
            }
            else
            {
                $.dialog.alert(data.data.msg);
            }
        });
    }
    else
    {
        $.dialog.alert('<?=__('请先登录!')?>');
    }
}


</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>