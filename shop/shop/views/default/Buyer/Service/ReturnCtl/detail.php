<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>

<?php
include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
<div class="aright">
    <div class="member_infor_content">
        <div class="div_head  tabmenu clearfix">
            <ul class="tab pngFix clearfix">
                <li class="active">
                    <a><?=$data['text']?><?=__('管理')?></a>
                </li>
            </ul>
        </div>
    <div class="ncm-flow-layout" id="ncmComplainFlow">
        <div class="ncm-flow-container">

            <div class="ncm-flow-step" style="text-align: center;">
                <dl id="state_new" class="step-first current1">
                    <dt><?=__('买家申请')?><?=$data['text']?></dt>
                    <dd class="bg"></dd>
                </dl>
                <dl id="state_appeal" <?php if ($data['return_state'] >= 1 ||$data['return_state'] == 3)
                {
                    echo 'class="current1"';
                } ?>>
                   
                    <dt><?=__('商家处理')?><?=$data['text']?><?=__('申请')?></dt>
                    <dd class="bg"></dd>
                </dl>
                <?php if($data['return_goods_return']){?>
                <dl id="state_talk" <?php if ($data['return_state'] >= 4 && $data['return_state'] != 3)
                {
                    echo 'class="current1"';
                } ?>>
                    <dt><?=__('买家')?><?=$data['text']?><?=__('给商家')?></dt>
                    <dd class="bg"></dd>
                </dl>
                 <?php }?>
                <dl id="state_handle" <?php if ($data['return_state'] >= 5 && $data['return_state'] != 3)
                {
                    echo 'class="current1"';
                } ?>>
                    <dt><?php if($data['return_goods_return']){echo __("确认收货，");}?><?=__('平台审核')?></dt>
                    <dd class="bg"></dd>
                </dl>
            </div>
            <div class="ncm-default-form">
                <h3><?=__('买家')?><?=$data['text']?><?=__('申请')?></h3>
                <?php if ($data['refund_goods']){?>
                    <dl class="return_dl">
                        <dt style="width:46px !important;"><img class="wp100" src="<?=$data['refund_goods']['goods_image']?>"></dt>
                        <dt style="width:56% !important;" class="tl"><h4 class="thunm-img"><?= $data['refund_goods']['goods_name'] ?></h4>
                        <p> <?php if($data['refund_goods']['order_spec_info']){?>
                                <?= __('规格：').implode($data['refund_goods']['order_spec_info'],' , ')?>
                            <?php  }?></p>
                        </dt>
                        <dt><?= format_money($data['refund_goods']['goods_price']) ?></dt>
                        <dt style="width:10% !important;"> x <?= $data['refund_goods']['order_goods_num'] ?></dt>
                    </dl>
                <?php } ?>

                <dl class="return_dl">
                    <dt><?=$data['text']?><?=__('编号：')?></dt>
                    <dd><?= $data['return_code'] ?></dd>
                    </dl>
                <dl class="return_dl">
                    <dt><?=__('申请人（买家）：')?></dt>
                    <dd><?= $data['buyer_user_account'] ?></dd>
                    </dl>
                <dl class="return_dl">
                    <dt><?=$data['text']?><?=__('原因：')?></dt>
                    <dd><?= $data['return_message'] ?></dd>
                    </dl>
                <dl class="return_dl">
                    <dt><?=$data['text']?><?=__('金额：')?></dt>
                    <dd><?= format_money($data['return_cash']) ?></dd>
                </dl>
                <?php if ($data['order_goods_id'])
                { ?>
                    <dl class="return_dl">
                        <dt><?=$data['text']?><?=__('数量：')?></dt>
                        <dd><?= $data['order_goods_num'] ?></dd>
                    </dl>
                <?php } ?>

                <?php if ($data['return_state_etext'] == "seller_pass")
                { ?>
                    <h3><?=__('处理结果')?></h3>
                    <dl class="return_dl">
                        <dt><?=__('处理状态：')?></dt>
                        <dd><?=__('卖家已同意')?></dd>
                        </dl>
                    <dl class="return_dl">
                        <dt><?=__('商家备注：')?></dt>
                        <dd><?= $data['return_shop_message'] ?></dd>
                    </dl>
                    <?php if ($data['return_state_etext'] == "plat_pass")
                { ?>
                    <h3><?=__('处理结果')?></h3>
                    <dl class="return_dl">
                        <dt><?=__('处理状态：')?></dt>
                        <dd><?=$data['text']?><?=__('成功')?></dd>
                        </dl>
                    <dl class="return_dl">
                        <dt><?=__('商家备注：')?></dt>
                        <dd><?= $data['return_shop_message'] ?></dd>
                    </dl>
                <?php } ?>
                <?php }
                elseif ($data['return_state_etext'] == "seller_unpass")
                { ?>
                    <h3><?=__('处理结果')?></h3>
                    <dl class="return_dl">
                        <dt><?=__('处理状态：')?></dt>
                        <dd><?=__('卖家不同意')?></dd>
                        </dl>
                    <dl class="return_dl">
                        <dt><?=__('商家备注：')?></dt>
                        <dd><?= $data['return_shop_message'] ?></dd>
                    </dl>
                <?php }?>
                <?php if ($data['return_state_etext'] == "plat_pass")
                { ?>
                    <h3><?=__('处理结果')?></h3>
                    <dl class="return_dl">
                        <dt><?=__('商家处理状态：')?></dt>
                        <dd>
                        <?php 
                            if($data['return_shop_handle'] == 3){  
                                echo '不同意';
                            } else if($data['return_shop_handle'] == 2){
                                echo '同意';
                            } else {
                                echo '待审核';
                            }
                        ?>
                        </dd>
                    </dl>
                    <dl class="return_dl">
                        <dt><?=__('商家备注：')?></dt>
                        <dd><?= $data['return_shop_message'] ?></dd>
                    </dl>
                    <dl class="return_dl">
                        <dt><?=__('平台处理状态：')?></dt>
                        <dd><?=__('平台审核通过')?></dd>
                    </dl>
                    <dl class="return_dl">
                        <dt><?=__('平台备注：')?></dt>
                        <dd><?= $data['return_platform_message'] ?></dd>
                    </dl>
                    
                <?php } ?>
            </div>
        </div>
        <div class="ncm-flow-item">
            <div class="title"><?=__('相关商品交易')?></div>

            <div class="item-order">
       
                 <div class="order-nums">
                     <dl class="clearfix">
                         <dt><?=__('商家：')?></dt>
                         <dd><?= $data['order']['shop_name'] ?></dd>
                     </dl>
                 </div>
                <div class="order-nums">
                    <dl class="clearfix">
                        <dt ><?=__('订单编号：')?></dt>
                        <dd> <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&act=details&order_id=<?= $data['order_number'] ?>" target="_blank"><?= $data['order_number'] ?></a></dd>
                    </dl>
                    <dl class="clearfix">
                        <dt><?=__('支付方式：')?></dt>
                        <dd><?= $data['order']['payment_name'] ?></dd>
                    </dl>
                    <dl class="clearfix">
                        <dt><?=__('下单时间：')?></dt>
                        <dd><span><?= $data['order']['order_create_time'] ?></dd>
                    </dl>
                    <dl class="clearfix">
                        <dt><?=__('付款时间：')?></dt>
                        <dd><?= $data['order']['payment_time'] ?></dd>
                    </dl>
                </div>
                 <div class="order-nums">
                     <dl class="clearfix">
                         <dt><?=__('运费：')?></dt>
                         <dd><?= format_money($data['order']['order_shipping_fee']) ?></dd>
                     </dl>
                     <dl class="clearfix">
                         <dt><?=__('订单总额：')?></dt>
                         <dd><?= format_money($data['order_amount']) ?></dd>
                     </dl>
                      <dl class="clearfix">
                         <dt><?=__('退款/货金额：')?></dt>
                         <dd><?= format_money($data['return_limit']) ?></dd>
                     </dl>
                 </div>

            </div>
           
        </div>
    </div>
</div>
    </div>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>