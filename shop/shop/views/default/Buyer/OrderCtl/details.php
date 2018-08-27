<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
    <script src="<?=$this->view->js_com?>/plugins/jquery.timeCountDown.js" ></script>
    <script>
        $(function(){
            var _TimeCountDown = $(".fnTimeCountDown");
            _TimeCountDown.fnTimeCountDown();
        })
    </script>
    </div>
    <div class="order_content">
        <div class="ncm-order-info">
            <div class="ncm-order-details">
                <div class="title"><?=__('订单信息')?></div>
                <div class="content">
                    <dl class='line'>
                    	<dt><?=__('收货人：')?></dt>
                        <dd class="one-overflow line-consignee"><?=($data['order_receiver_name'])?></dd>
                        <dd class="line-phone"><?=($data['order_receiver_contact'])?></dd>
                    </dl>
                     <dl class='line'>
                        <dt><?=__('收货地址：')?></dt>
                        <dd><?=($data['order_receiver_address'])?></dd>
                    </dl>
                    <dl class="line">
                        <dt><?=__('发票：')?></dt>
                        <dd><?=($data['order_invoice'])?></dd>
                    </dl>
                    <dl class="line">
                        <dt><?=__('支付方式：')?></dt>
                        <dd><?=($data['payment_name'])?></dd>
                    </dl>
                    <dl class="line">
                        <dt><?=__('买家留言：')?></dt>
                        <dd class="ellipsis"><?=($data['order_message'])?></dd>
                    </dl>
                    <dl class="line line2 pt10">
                        <dt><?=__('订单编号：')?></dt>
                        <dd><?=($data['order_id'])?><a class="more-drap">更多<i class="iconfont icon-iconjiantouxia"></i>
                                <div class="more"><span class="arrow"></span>
                                    <ul>
                                        <li><?=__('支付时间：')?><span><?=($data['payment_time'])?></span> </li>
                                        <li><?=__('下单时间：')?><span><?=($data['order_create_time'])?></span></li>
                                    </ul>
                                </div>
                            </a></dd>
                        </dl>
                        <dl class="line">
                            <dt><?=__('商　　家：')?></dt>
                            <dd><?=($data['shop_name'])?><a class="more-drap"><?=__('更多')?><i class="iconfont icon-iconjiantouxia"></i>
                                <div class="more"><span class="arrow"></span>
                                    <ul>
                                        <li><?=__('所在地区：')?><span><?=($data['shop_address'])?></span></li>
                                        <li><?=__('联系电话：')?><span><?=($data['shop_phone'])?></span></li>
                                    </ul>
                                </div>
                            </a></dd>
                        </dl>
                        <dl class="line">
                            <dt><?=__('商家留言：')?></dt>
                            <dd style="word-break: break-word;"><?=($data['order_seller_message'])?></dd>
                        </dl>
                </div>
            </div>

            <?php if($data['order_status'] == Order_StateModel::ORDER_WAIT_PAY ):?>
                <div class="ncm-order-condition">
                    <dl>
                        <dt><i class="icon-ok-circle green"></i><?=__('订单状态：')?></dt>
                        <dd><?=__('订单已经提交，等待买家付款')?></dd>
                    </dl>
                    <ul>
                        <?php if($data['payment_id'] != PaymentChannlModel::PAY_CONFIRM ): ?>
                            <?php if($del_flag == true ){?>
                                <li><?=__('1. 该订单中部分商品被商家下架或者删除，请重新下单!') ?></li>
                            <?php }else{ ?>
                                <li><?=__('1. 您尚未对该订单进行支付，请')?><?php if($data['payment_id'] != PaymentChannlModel::PAY_CONFIRM && $data['order_sub_pay'] == Order_StateModel::SUB_SELF_PAY){  ?><a href="<?= Yf_Registry::get('paycenter_api_url') ?>?ctl=Info&met=pay&uorder=<?=($data['payment_number'])?>" class="ncbtn-mini ncbtn-bittersweet bbc_btns"><i></i><?=__('支付订单')?></a><?php }else{ ?><?=__('联系主管账号支付订单')?><?php }?><?=__('以确保商家及时发货。')?></li>
                            <?php } ?>
                            <li><?=__('2. 如果您未对该笔订单进行支付操作，系统将于')?><time><?=($data['cancel_time'])?></time><?=__('自动关闭该订单')?>。</li>

                            <?php if($data['buyer_user_id'] == Perm::$userId && $data['order_sub_pay'] == Order_StateModel::SUB_SELF_PAY){ ?>
                                <li><?=__('3. 如果您不想购买此订单的商品，请选择 ')?><a onclick="cancelOrder('<?=$data['order_id']?>')" class="ncbtn-mini bbc_btns"><?=__('取消订单')?></a><?=__('操作。')?></li>
                            <?php }else{ ?>
                                <li><?=__('3. 如果您不想购买此订单的商品，需要采购子账号进行取消订单操作。')?></li>
                            <?php } ?>

                        <?php else: ?>
                            <li><?=__('1. 如果您不想购买此订单的商品，请选择 ')?><a onclick="cancelOrder('<?=$data['order_id']?>')" class="ncbtn-mini bbc_btns"><?=__('取消订单')?></a><?=__('操作。')?></li>
                            <li><?=__('2. 如果您未对该笔订单进行支付操作，系统将于')?><time><?=($data['cancel_time'])?></time><?=__('自动关闭该订单')?>。</li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif;?>

            <?php if($data['order_status'] == Order_StateModel::ORDER_PAYED):?>
                <div class="ncm-order-condition">
                    <dl>
                        <dt><i class="icon-ok-circle green"></i><?=__('订单状态：')?></dt>
                        <dd><?=__('待发货')?></dd>
                    </dl>
                    <ul>
                        <li><?=__('1. 您已成功对订单进行支付。')?></li>
                        <li><?=__('2. 订单已提交商家进行备货发货准备。 ')?></li>
                    
                    <?php 
                        if($data['pintuan_temp'] != 1){
                    ?>
                        <?php if($data['buyer_user_id'] == Perm::$userId){ ?>
                            <li class="fz0"><span class="fz12"><?=__('3. 如果您想取消购买，请与商家沟通后对订单进行')?></span>
                                <?php if(strstr($data['payment_name'],'白条支付')){ ?>
                                    <a class="fz12" onclick="javascript:alert('白条支付的订单，请联系商家线下退款');" class="ncbtn-mini bbc_btns"><?=__('申请退款')?></a>
                                <?php }else{ ?>
                                    <span class="fz12"><?=__('申请退款')?></span>
                                <?php }?>
                                <span class="fz12"><?=__('操作。')?></span></li>
                        <?php }else{ ?>
                            <li><?=__('3. 如果您想取消购买，需要采购子账号进行退款申请操作。')?></li>
                        <?php } ?>
                    <?php } ?>
                    </ul>
                </div>
            <?php endif;?>

            <?php if($data['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS):?>
                <div class="ncm-order-condition">
                    <dl>
                        <dt><i class="icon-ok-circle green"></i><?=__('订单状态：')?></dt>
                        <dd><?=__('商家已发货')?></dd>
                    </dl>
                    <ul>
                        <li><?=__('1. 商品已发出。')?></li>
                        <li><?=__('2. 系统将于')?>
                            <time><?=($data['order_receiver_date'])?></time>
                            <?=__('自动完成“确认收货”，完成交易。')?></li>

                        <?php if($data['buyer_user_id'] == Perm::$userId){ ?>
                            <li><?=__('3. 如果您已收到货，且对商品满意，您可以 ')?><a onclick="confirmOrder('<?=$data['order_id']?>')" class="ncbtn-mini bbc_btns"><?=__('确认收货')?></a><?=__('完成交易。 ')?></li>
                        <?php }else{ ?>
                            <li><?=__('3. 如果您已收到货，且对商品满意，需要采购子账户进行确认收货操作完成交易。 ')?></li>
                        <?php } ?>

                    </ul>
                </div>
            <?php endif;?>

            <?php if($data['order_status'] == Order_StateModel::ORDER_FINISH):?>
                <div class="ncm-order-condition">
                    <dl>
                        <dt><i class="icon-ok-circle green"></i><?=__('订单状态：')?></dt>
                        <dd><?=__('已经收货')?></dd>
                    </dl>
                    <ul>
                        <li><?=__('1. 如果收到货后出现问题，您可以联系商家协商解决。')?></li>
                        <li><?=__('2. 如果商家没有履行应尽的承诺，您可以在交易完成后的')?><?=($data['complain_day'])?><?=__('天内进行“交易投诉”。 ')?></li>
                        <li><?=__('3. 交易已完成，你可以对购买的商品进行评价。')?></li>
                    </ul>
                </div>
            <?php endif;?>

            <?php if($data['order_status'] == Order_StateModel::ORDER_CANCEL ):?>
                <div class="ncm-order-condition">
                    <dl>
                        <dt><i class="icon-ok-circle green"></i><?=__('订单状态：')?></dt>
                        <dd><?=__('交易关闭')?></dd>
                    </dl>
                    <ul>
                        <li><?=($data['cancel_identity'])?><?=__('于')?><time><?=($data['order_cancel_date'])?></time><?=__('取消了订单（')?><?=($data['order_cancel_reason'])?><?=__('）')?></li>
                    </ul>
                </div>
            <?php endif;?>

            <!--<div class="mall-msg">有疑问可咨询<a href="javascript:void(0);"><i class="iconfont icon-kefu"></i>平台客服</a></div>-->
        </div>

        <div class="ncm-order-step">
            <?php if($data['order_status'] != Order_StateModel::ORDER_CANCEL):?>
                <dl class="step-first current">
                    <dt><?=__('生成订单')?></dt>
                    <dd class="bg"></dd>
                    <dd class="date" title="<?=__('订单生成时间')?>"><?=($data['order_create_time'])?></dd>
                </dl>

                <?php if($data['payment_id'] != PaymentChannlModel::PAY_CONFIRM ){ ?>
                    <dl class="<?php if($data['order_status'] == Order_StateModel::ORDER_PAYED || $data['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS || $data['order_status'] == Order_StateModel::ORDER_FINISH ){ ?>current<?php }?>">
                        <dt><?=__('完成付款')?></dt>
                        <dd class="bg"> </dd>
                        <dd class="date" title="<?=__('付款时间')?>"><?=($data['payment_time'])?></dd>
                    </dl>
                <?php } ?>

                <dl class="<?php if($data['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS || $data['order_status'] == Order_StateModel::ORDER_FINISH):?>current<?php endif;?>">
                    <dt><?=__('商家发货')?></dt>
                    <dd class="bg"> </dd>
                    <dd class="date" title="<?=__('商家发货')?>"><?=($data['order_shipping_time'])?></dd>
                </dl>
                <dl class="<?php if($data['order_status'] == Order_StateModel::ORDER_FINISH ):?>current<?php endif;?>">
                    <dt><?=__('确认收货')?></dt>
                    <dd class="bg"> </dd>
                    <dd class="date" title="<?=__('确认收货')?>"><?=($data['order_finished_time'])?></dd>
                </dl>
                <dl class="long <?php if($data['order_status'] == Order_StateModel::ORDER_FINISH && $data['order_buyer_evaluation_status']):?>current<?php endif;?>">
                    <dt><?=__('评价')?></dt>
                    <dd class="bg"> </dd>
                    <dd class="date" title="<?=__('订单完成')?>"><?=($data['order_buyer_evaluation_time'])?></dd>
                </dl>
            <?php endif;?>
        </div>

        <table class="tboy-reset">
            <tbody class="tbpad">
            <tr class="order_tit">
                <th class="order_goods"><?=__('商品')?></th>
                <th class="widt1"><?=__('单价')?></th>
                <th class="widt2"><?=__('数量')?></th>
                <th class="widt4"><?php if($data['buyer_user_id'] == Perm::$userId){ ?><?=__('售后维权')?><?php }?></th>
                <th class="widt5"><?=__('订单金额')?></th>
                <th class="widt6"><?=__('交易状态')?></th>
                <th class="widt7"><?=__('操作')?></th>
            </tr>
            </tbody>
            <tbody>
            <tr>
                <th class="tr_margin" style="height:16px;background:#fff;" colspan="8"></th>
            </tr>
            </tbody>

            <tbody class="tboy">
            <tr>
                <td colspan="4"  class="td_rborder">
                    <!--S  循环订单中的商品  -->
                    <table>
                        <?php foreach($data['goods_list'] as $ogkey=> $ogval):?>
                            <tr class="tr_con">
                                <td class="order_goods">
                                    <img src="<?=image_thumb($ogval['goods_image'],50,50)?>"/>
                                    <a target="_blank"  href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($ogval['goods_id'])?>"><?=($ogval['goods_name'])?></a>
                                    <?php if(isset($ogval['order_spec_info']) && $ogval['order_spec_info']){ ?>
                                        <dd class="table-dd-set tl"><strong ><?=__('规格')?>：</strong>&nbsp;&nbsp;<em><?= $ogval['order_spec_info'][0].','.$ogval['order_spec_info'][1]; ?></em></dd>
                                    <?php }?>
                                    <?php if($ogval['order_goods_benefit'] && $ogval['is_group']){?><em class="td_sale bbc_btns small_details"><?=($ogval['order_goods_benefit'])?></em><?php }?>
                                </td>
                                <td class="td_color widt1"><?=format_money($ogval['goods_price'])?></td>
                                <td class="td_color widt2"><?=__('x')?> <?=($ogval['order_goods_num'])?></td>
                                <td class="td_color widt4">
                                    <!-- S 退款/退货 -->
                                    <?php
                                    if($data['order_buyer_hidden']!= Order_BaseModel::IS_BUYER_REMOVE){
                                    ?>
                                    <?php 
                                        if($ogval['pintuan_temp_order'] != 1){
                                    ?>
                                    <?php
                                    //货到付款 -- 货到付款的商品没有退款操作只有退货操作
                                    if($data['payment_id'] == PaymentChannlModel::PAY_CONFIRM){?>
                                        <?php
                                        //货到付款的订单只有当订单确认收货完成订单后才会出现“退款/退货”按钮
                                        if(($data['order_status'] == Order_StateModel::ORDER_RECEIVED || $data['order_status'] == Order_StateModel::ORDER_FINISH) && $data['order_refund_status'] == Order_StateModel::ORDER_REFUND_NO){?>

                                            <?php
                                            //白条支付的订单需要线下进行退款/退货操作
                                            if(strstr($data['payment_name'],'白条支付')){ ?>
                                                <p> <a  class="to_views" onclick="javascript:alert('白条支付的订单，请联系商家线下退款/退货');"><i class="iconfont icon-dingdanwancheng icon_size22"></i><?=__('退款/退货')?></a></p>
                                            <?php }else{ ?>
                                                <p> <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=add&oid=<?=($data['order_id'])?>&gid=<?=($ogval['order_goods_id'])?>" class="to_views"><?=__('退款/退货')?></a></p>
                                            <?php } ?>
                                        <?php }?>
                                    <?php }else{
                                        //在线支付 -- 已付款（可退款），订单完成（可退货）
                                        ?>
                                        <?php
                                        //已经付款（但是没有退款的商品），已经完成（但是没有退货的商品）出现“退款/退货”按钮
                                        //由于之前数据的影响，之前订单存在退款的商品的“退款/退货”按钮也不显示
                                        if((($data['order_status'] == Order_StateModel::ORDER_PAYED && $ogval['goods_return_status'] == Order_StateModel::ORDER_GOODS_RETURN_NO) || ($data['order_status'] == Order_StateModel::ORDER_FINISH  && $ogval['goods_refund_status'] == Order_StateModel::ORDER_GOODS_RETURN_NO))&& !$data['order_source_id'] && $data['order_refund_status'] == Order_StateModel::ORDER_REFUND_NO && $ogval['order_goods_num'] > $ogval['order_goods_returnnum']  && $ogval['goods_price'] > 0
                                        ){?>
                                            <?php if(strstr($data['payment_name'],'白条支付')){ ?>
                                                <p> <a  class="to_views" onclick="javascript:alert('白条支付的订单，请联系商家线下退款/退货');"><i class="iconfont icon-dingdanwancheng icon_size22"></i><?=__('退款/退货')?></a></p>
                                            <?php }else{
                                                //订单状态为已付款，并且订单商品没有退款 则显示订单商品的退款按钮
                                                ?>
                                                <p>
                                                    <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=add&oid=<?=($data['order_id'])?>&gid=<?=($ogval['order_goods_id'])?>" class="to_views"><?=__('退款/退货')?></a>
                                                </p>
                                            <?php } } ?>

                                        <?php if($ogval['goods_return_status'] != Order_StateModel::ORDER_REFUND_NO ){?>
                                            <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=detail&id=<?=($ogval['order_return_id'] ?: $ogval['order_refund_id'])?>"><?=$ogval['goods_return_status_con']?></a>
                                        <?php }?>
                                        <?php if($ogval['goods_refund_status'] != Order_StateModel::ORDER_REFUND_NO ){?>
                                            <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=detail&id=<?=($ogval['order_return_id'] ?: $ogval['order_refund_id'])?>"><?=$ogval['goods_refund_status_con']?></a>
                                        <?php }?>
                                    <?php } ?>
                                    <?php }?>
                                    <?php }?>
                                    <!-- E 退款/退货 -->
                                    <?php if($data['buyer_user_id'] == Perm::$userId){ ?>
                                        <p>
                                            <?php if((($data['order_status'] == Order_StateModel::ORDER_FINISH && $data['complain_status']) || $data['order_status'] != Order_StateModel::ORDER_CANCEL)&&$data['order_buyer_hidden']!= Order_BaseModel::IS_BUYER_REMOVE){?>
                                                <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Complain&met=index&act=add&gid=<?=($ogval['order_goods_id'])?>">
                                                    <?=__('交易投诉')?>
                                                </a>
                                            <?php }?>
                                        </p>
                                    <?php }?>

                                    <?php if($data['order_status'] < Order_StateModel::ORDER_FINISH && $ogval['order_goods_source_ship']){ ?>
                                        <?php $arr = explode('-',$ogval['order_goods_source_ship']);?>
                                        <a style="position:relative;" onmouseover="show_logistic('<?=($ogval['order_goods_source_id'])?>','<?=($arr[1])?>','<?=($arr[0])?>')" onmouseout="hide_logistic('<?=($ogval['order_goods_source_id'])?>')">
                                            <i class="iconfont icon-icowaitproduct rel_top2"></i><?=__('物流信息')?>
                                            <div style="display: none;" id="info_<?=($ogval['order_goods_source_id'])?>" class="prompt-01"> </div>
                                        </a>
                                    <?php }?>

                                </td>

                            </tr>
                        <?php endforeach;?>
                    </table>
                    <!--E  循环订单中的商品   -->
                </td>

                <!--S  订单金额 -->
                <td class="td_rborder widt5">
				     <span class="fls">
				        <em class="type-name"><?=__('总额：')?></em><strong><?=format_money($data['order_goods_amount'])?></strong><!--<br/>--><?/*=($data['payment_name'])*/?>
				     </span>
                    <br/>
				     <span class="fls">
				       <em class="type-name"><?=__('运费：')?></em><?php if($data['order_shipping_fee'] > 0):?><?=format_money($data['order_shipping_fee'])?><?php else:?><?=__('免运费')?><?php endif;?>
				     </span>
                    <br/>
                    <?php if($data['voucher_price'] > 0):?>
                        <span class="fls">
                            <em class="type-name"><?=__('代金券：')?></em><?=__('减')?><?=format_money($data['voucher_price'])?>
                         </span>
                        <br/>
                     <?php endif;?>
                    <?php if($data['order_rpt_price'] > 0):?>
                         <span class="fls">
                           <em class="type-name"><?=__('平台红包：')?></em><?=__('减')?><?=format_money($data['order_rpt_price'])?>
                         </span>
                    <?php endif;?>
                    <?php if($data['order_user_discount'] > 0):?>
                         <span class="fls">
                           <em class="type-name"><?=__('会员折扣：')?></em><?=__('减')?><?=format_money($data['order_user_discount'])?>
                         </span>
                     <?php endif;?>
				     <span class="fls">
				        <em class="type-name"><?php if($data['order_status'] == Order_StateModel::ORDER_WAIT_PAY || $data['order_status'] == Order_StateModel::ORDER_CANCEL){ ?><?=__('应付：')?><?php }else{ ?><?=__('实付：')?><?php } ?></em><strong><?=format_money($data['order_payment_amount'])?></strong>
				     </span>
                    <!-- <?php if($data['order_shop_benefit']){?><span class="td_sale bbc_btns"><?=($data['order_shop_benefit'])?></span><?php }?> -->
                </td>
                <!--E 订单金额 -->

                <td class="td_rborder">
                    <p class="getit"><?=($data['order_state_con'])?></p>
                    <?php if($data['order_status'] == Order_StateModel::ORDER_WAIT_PAY  && $data['payment_id'] == PaymentChannlModel::PAY_CONFIRM ){?>
                        <p class="getit"><?=__('货到付款')?></p>
                    <?php }?>


                    <!-- 如果是待收货的订单就显示物流信息 -->
                    <?php if($data['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS ){ ?>
                        <a style="position:relative;" onmouseover="show_logistic('<?=($data['order_id'])?>','<?=($data['order_shipping_express_id'])?>','<?=($data['order_shipping_code'])?>')" onmouseout="hide_logistic('<?=($data['order_id'])?>')">
                            <i class="iconfont icon-icowaitproduct rel_top2"></i><?=__('物流信息')?>
                            <div style="display: none;" id="info_<?=($data['order_id'])?>" class="prompt-01"> </div>
                        </a>
                    <?php }?>

                </td>

                <!--S 订单操作  -->
                <td class="td_rborder td_rborder_reset">
                    <?php if(($data['order_status'] == Order_StateModel::ORDER_CANCEL || $data['order_status'] == Order_StateModel::ORDER_FINISH) && (!$data['order_buyer_hidden']) ):?>
                        <p>
                            <a onclick="hideOrder('<?=$data['order_id']?>')"><i class="iconfont icon-lajitong icon_size22"></i><?=__('删除订单')?></a>
                        </p>



                    <?php endif; ?>

                    <?php if(($data['order_status'] == Order_StateModel::ORDER_CANCEL || $data['order_status'] == Order_StateModel::ORDER_FINISH) && $data['order_buyer_hidden']== Order_BaseModel::IS_BUYER_HIDDEN) :?>


                        <p><a onclick="delOrder('<?=$data['order_id']?>')" class="to_views"><i class="iconfont icon-lajitong icon_size22"></i><?=__('彻底删除')?></a></p>


                    <?php endif; ?>

                    <!--S  未付款订单 -->
                    <?php if($data['order_status'] == Order_StateModel::ORDER_WAIT_PAY):?>
                        <p class="rest">
                            <span class="iconfont icon-shijian2"></span>
							<span class="fnTimeCountDown" data-end="<?=$data['cancel_time']?>">
							    <span><?=__("剩余")?></span>
                                <span class="hour">00</span><span><?=__('时')?></span>
                                <span class="mini">00</span><span><?=__('分')?></span>
                            </span>
                        </p>

                        <?php if($data['payment_id'] != PaymentChannlModel::PAY_CONFIRM && $data['order_sub_pay'] == Order_StateModel::SUB_SELF_PAY): ?>
                            <p>
                                <a target="_blank" onclick="payOrder('<?=$data['payment_number']?>','<?=$data['order_id']?>')"  class="to_views "><i class="iconfont icon-icoaccountbalance pay-botton"></i><?=__('订单支付')?></a>
                            </p>
                        <?php endif; ?>

                        <?php if($data['buyer_user_id'] == Perm::$userId){ ?>
                            <p><a onclick="cancelOrder('<?=$data['order_id']?>')" class="to_views"><i class="iconfont icon-quxiaodingdan"></i><?=__('取消订单')?></a></p>
                        <?php }?>
                    <?php endif; ?>
                    <!--E  未付款订单 -->
                    <?php if($data['buyer_user_id'] == Perm::$userId){ ?>

                        <?php if($data['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS ): ?>
                            <p class="rest">
                                <span class="iconfont icon-shijian2 f14"></span>
							<span class="fnTimeCountDown" data-end="<?=$data['order_receiver_date']?>">
							    <span><?=__("剩余")?></span>
                                <span class="day" >00</span><span><?=__('天')?></span>
                                <span class="hour">00</span><span><?=__('时')?></span>
                            </span>
                            </p>
                            <p><a onclick="confirmOrder('<?=$data['order_id']?>')" class="to_views "><i class="iconfont icon-duigou1"></i><?=__('确认收货')?></a></p>
                        <?php endif;?>

                        <?php if($data['order_status'] == Order_StateModel::ORDER_FINISH): ?>
                            <?php if(!$data['order_buyer_evaluation_status']): ?>
                                <p> <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=evaluation&act=add&order_id=<?=($data['order_id'])?>" class="to_views"><i class="iconfont icon-woyaopingjia icon_size22"></i><?=__('我要评价')?></a></p>
                            <?php endif;?>
                            <?php if($data['order_buyer_evaluation_status']): ?>
                                <p><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=evaluation&act=add&order_id=<?=($data['order_id'])?>" class="to_views"><i class="iconfont icon-woyaopingjia icon_size22"></i><?=__('追加评价')?></a>
                            <?php endif;?></p>
                        <?php endif;?>
                    <?php }?>
                </td>
                <!--E 订单操作   -->
            </tr>
            </tbody>

        </table>
    </div>
    </div>
    </div>



    </div>


    </div>
    </div>
    </div>

    <script>
        window.hide_logistic = function (order_id)
        {
            $("#info_"+order_id).hide();
            $("#info_"+order_id).html("");
        }

        window.show_logistic = function (order_id,express_id,shipping_code)
        {
            $("#info_"+order_id).show();
            $.post(BASE_URL + "/shop/api/logistic.php",{"order_id":order_id,"express_id":express_id,"shipping_code":shipping_code} ,function(da) {

                if(da)
                {
                    $("#info_"+order_id).html(da);
                }
                else
                {
                    $("#info_"+order_id).html('<div class="error_msg"><?=__('接口出现异常')?></div>');
                }


            })
        }
    </script>

<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>