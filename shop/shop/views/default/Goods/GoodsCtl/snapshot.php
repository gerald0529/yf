<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'site_nav.php';
?>
    <script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="<?= $this->view->css ?>/base.css">
    <link rel="stylesheet" href="<?= $this->view->css ?>/snapshot.css">
    <link href="<?= $this->view->css ?>/tips.css" rel="stylesheet">
    <link href="<?= $this->view->css ?>/login.css" rel="stylesheet">

    <div class="wrapper">
        <div class="snapshot-goods-name"><em><?=__('商品SKU：')?><?=($snapshot['common_id'])?></em>
            <h1><?=($snapshot['goods_name'])?><span><?=__('交易快照')?></span></h1>
        </div>
        <div class="bbch-detail">
            <div id="bbch-goods-picture" class="bbch-goods-picture">
                <div class="jqthumb" title="" style="width: 300px; height: 300px; opacity: 1;">
                    <div style="width: 100%; height: 100%; background-image: url(<?=($snapshot['goods_image'])?>); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;"></div>
                </div><img alt="" src="<?=($snapshot['goods_image'])?>" style="display: none;"></div>
            <div class="bbch-goods-summary">
                <dl class="bbch-price">
                    <dt><?=__('成 交 价：')?></dt>
                    <dd><em><?=format_money($snapshot['goods_price'])?></em></dd>
                </dl>
                <div class="snap">
                    <p><?=__('您正在查看订单编号：')?><strong><?=($snapshot['order_id'])?></strong> <?=__('的交易快照')?></p>
                    <p><?=__('该交易快照生成时间：')?><?=($snapshot['snapshot_create_time'])?></p>
                    <p><a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($snapshot['goods_id'])?>" target="_blank"><?=__('点此查看最新商品详情')?></a></p>
                    <p class="pimg"> <img src="<?=$this->view->img?>/poftl.png"></p>
                </div>
                <dl>
                    <dt><?=__('运费：')?></dt>
                    <dd><?=format_money($snapshot['freight'])?></dd>
                </dl>
            </div>
            <div class="bbch-info">
                <div class="title">
                    <h4><?=($shop_detail['shop_name'])?></h4>
                </div>
                <div class="content">
                    <div class="bbch-detail-rate">
                        <ul>
                            <li>
                                <h5><?=__('描述')?></h5>
                                <div class="equal" ><?=($shop_detail['shop_desc_scores'])?><i></i></div>
                            </li>
                            <li>
                                <h5><?=__('服务')?></h5>
                                <div class="equal" ><?=($shop_detail['shop_service_scores'])?><i></i></div>
                            </li>
                            <li>
                                <h5><?=__('物流')?></h5>
                                <div class="equal"><?=($shop_detail['shop_send_scores'])?><i></i></div>
                            </li>
                        </ul>
                    </div>
                    <div class="btns">
                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&typ=e&id=<?=($shop_detail['shop_id'])?>" class="goto"><?=__('进店逛逛')?></a>
                        <a onclick="collectShop(<?=($shop_detail['shop_id'])?>)" ><?=__('收藏店铺')?><span>(<em nctype="store_collect"><?=($shop_detail['shop_collect'])?></em>)</span></a></div>
                    <dl class="no-border">
                        <dt><?=__('公司名称：')?></dt>
                        <dd><?=($shop_company['shop_company_name'])?></dd>
                    </dl>
                    <dl>
                        <dt><?=__('所&nbsp;在&nbsp;地：')?></dt>
                        <dd><?=($shop_company['shop_company_address'])?></dd>
                    </dl>
                </div>
            </div>
        </div>
        <div id="content" class="bbch-goods-layout">
            <div class="title"><span><?=__('订单详情')?></span></div>
            <div class="bbch-intro" id="ncGoodsIntro">
                <ul class="nc-goods-sort">
                    <li><?=__('订单编号：')?><?=($order_base['order_id'])?></li>
                    <li><?=__('下单时间：')?><?=($order_base['order_create_time'])?></li>
                    <li><?=__('店铺名称：')?><?=($order_base['shop_name'])?></li>
                    <li><?=__('买家：')?><?=($order_base['buyer_user_name'])?></li>
                    <li><?=__('卖家：')?><?=($order_base['seller_user_name'])?></li>
                </ul>
                <div class="bbch-goods-info-content">
                </div>
            </div>
        </div>
    </div>

    <!-- 登录遮罩层 -->
    <div id="login_content" style="display:none;">
    </div>

<script>
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


<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>