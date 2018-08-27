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
                <dl class="line">
                  <dt><?=__('门店地址：')?></dt>
                  <dd><?=$data['chain_info']['chain_name']?>（<?=$data['chain_info']['chain_province']?> <?=$data['chain_info']['chain_city']?> <?=$data['chain_info']['chain_county']?> <?=$data['chain_info']['chain_address']?>）</dd>
                </dl>
                <dl class="line">
                  <dt><?=__('买家留言：')?></dt>
                  <dd class="ellipsis"><?=($data['order_message'])?></dd>
                </dl>
                <dl class="line line2 pt10">
                  <dt><?=__('订单编号：')?></dt>
                  <dd><?=($data['order_id'])?><a class=""><?=__('更多')?><i class="iconfont icon-iconjiantouxia"></i>
                    <div class="more"><span class="arrow"></span>
                      <ul>
                        <li><?=__('下单时间：')?><span><?=($data['order_create_time'])?></span></li>
                      </ul>
                    </div>
                </a></dd>
                 </dl>
                <dl class="line">
                  <dt><?=__('商　　家：')?></dt>
                  <dd><?=($data['shop_name'])?><a class=""><?=__('更多')?><i class="iconfont icon-iconjiantouxia"></i>
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
                  <dd><?=($data['order_seller_message'])?></dd>
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
                <?php if($data['payment_id'] != PaymentChannlModel::PAY_CHAINPYA ): ?>
                <li><?=__('1. 您尚未对该订单进行支付，请')?><a href="<?= Yf_Registry::get('paycenter_api_url') ?>?ctl=Info&met=pay&uorder=<?=($data['payment_number'])?>" class="ncbtn-mini ncbtn-bittersweet bbc_btns"><i></i><?=__('支付订单')?></a><?=__('以确保商家及时发货。')?></li>
                <li><?=__('2. 如果您不想购买此订单的商品，请选择 ')?><a onclick="cancelOrder('<?=$data['order_id']?>')" class="ncbtn-mini bbc_btns"><?=__('取消订单')?></a><?=__('操作。')?></li>
                <li><?=__('3. 如果您未对该笔订单进行支付操作，系统将于')?><time><?=($data['cancel_time'])?></time><?=__('自动关闭该订单。')?></li>
                <?php endif; ?>
              </ul>
            </div>
			<?php endif;?>

			<?php if($data['order_status'] == Order_StateModel::ORDER_SELF_PICKUP):?>
            <div class="ncm-order-condition">
              <dl>
                <dt><i class="icon-ok-circle green"></i><?=__('订单状态：')?></dt>
                <dd><?=__('待自提')?></dd>
              </dl>
              <ul>
                <!--<li><?/*=__('1. 您已使用“')*/?><?/*=($data['payment_name'])*/?><?/*=__('”方式成功对订单进行支付。')*/?></li>-->
                <li><?=__('1. 您还没有去门店自提。')?></li>
                <li><?=__('2. 您的自提码是')?><?=$Order_GoodsChainCode['chain_code_id']?><?=__('。')?></li>
                <?php if($data['payment_id'] == PaymentChannlModel::PAY_ONLINE):?>
                <li><?=__('3. 如果您想取消购买，请与商家沟通后对订单进行')?><span><?=__('申请退款')?></span><?=__('操作。')?></li>
                <?php endif;?>
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
              <dd class="date" title="订单生成时间"><?=($data['order_create_time'])?></dd>
            </dl>

            <?php if($data['payment_id'] != PaymentChannlModel::PAY_CHAINPYA ){ ?>
            <dl class="<?php if($data['order_status'] == Order_StateModel::ORDER_SELF_PICKUP  || $data['order_status'] == Order_StateModel::ORDER_FINISH ){ ?>current<?php }?>">
              <dt><?=__('完成付款')?></dt>
              <dd class="bg"> </dd>
              <dd class="date" title="付款时间"><?=($data['payment_time'])?></dd>
            </dl>
            <?php } ?>

            <dl class="<?php if($data['order_status'] == Order_StateModel::ORDER_FINISH ):?>current<?php endif;?>">
              <dt><?=__('已自提')?></dt>
              <dd class="bg"> </dd>
              <dd class="date" title="已自提"><?=($data['order_finished_time'])?></dd>
            </dl>
            <dl class="long <?php if($data['order_status'] == Order_StateModel::ORDER_FINISH && $data['order_buyer_evaluation_status']):?>current<?php endif;?>">
              <dt><?=__('评价')?></dt>
              <dd class="bg"> </dd>
              <dd class="date" title="订单完成"></dd>
            </dl>
            <?php endif;?>
          </div>

          <table class="tboy-reset">
              <tbody class="tbpad">
                <tr class="order_tit">
                  <th class="order_goods"><?=__('商品')?></th>
                  <th class="widt1"><?=__('单价')?></th>
                  <th class="widt2"><?=__('数量')?></th>
                  <th class="widt4"><?=__('售后维权')?></th>
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
                                    <?php if ($ogval['spec_text']) { ?>
                                        <dd class="table-dd-set tl"><strong>规格：</strong>&nbsp;&nbsp;<em><?= $ogval['spec_text'] ?></em></dd>
                                    <?php } ?>
                                    <?php if($ogval['order_goods_benefit']){?><em class="td_sale bbc_btns small_details"><?=($ogval['order_goods_benefit'])?></em><?php }?>
                                </td>
                                <td class="td_color widt1"><?=format_money($ogval['goods_price'])?></td>
                                <td class="td_color widt2"><?=__('x')?> <?=($ogval['order_goods_num'])?></td>
                                <td class="td_color widt4">
                                    <!-- S 退款 -->
                                    <?php if(($data['order_status'] == Order_StateModel::ORDER_SELF_PICKUP && $data['payment_id'] != PaymentChannlModel::PAY_CHAINPYA) && $ogval['goods_return_status'] == Order_StateModel::ORDER_GOODS_RETURN_NO){?>
                                       <p> <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=add&oid=<?=($data['order_id'])?>&gid=<?=($ogval['order_goods_id'])?>" class="to_views"><?=__('退款/退货')?></a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=add&oid=<?=($data['order_id'])?>"></p>

                                <?php }?>
                                    <!-- E 退款 -->

                                    <!-- S 退货 -->
                                    <?php if($data['order_status'] != Order_StateModel::ORDER_WAIT_PAY && $data['order_status'] != Order_StateModel::ORDER_PAYED  && $data['order_status'] != Order_StateModel::ORDER_CANCEL){?>
                                        <?php if($ogval['goods_refund_status'] == Order_StateModel::ORDER_GOODS_RETURN_NO  && $ogval['order_goods_status'] == Order_StateModel::ORDER_FINISH){?>
                                           <p>
                                                <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=add&gid=<?=($ogval['order_goods_id'])?>&oid=<?=($data['order_id'])?>"><?=__('退款/退货')?></a>
                                           </p>
                                        <?php }?>

                                     <?php }?>
                                    <!-- E 退货 -->

                                    <!-- 订单退款状态：当订单不为取消状态和待付款状态时显示订单退款状态 -->
                                    <?php if($ogval['goods_return_status'] != Order_StateModel::ORDER_REFUND_NO ){?>
                                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=detail&id=<?=($ogval['order_return_id'])?>"><?=$ogval['goods_return_status_con']?></a>
                                    <?php }?>
                                    <?php if($ogval['goods_refund_status'] != Order_StateModel::ORDER_REFUND_NO ){?>
                                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=detail&id=<?=($ogval['order_refund_id'])?>"><?=$ogval['goods_refund_status_con']?></a>
                                    <?php }?>





                                        <?php if(($data['order_status'] == Order_StateModel::ORDER_FINISH && $data['complain_status']) || $data['order_status'] != Order_StateModel::ORDER_CANCEL){?>
                                            <p><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Complain&met=index&act=add&gid=<?=($ogval['order_goods_id'])?>">
                                                <?=__('交易投诉')?>
                                            </a></p>
                                        <?php }?>
                                    </p>
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
                   <?php if($data['payment_id'] == PaymentChannlModel::PAY_CHAINPYA ){?>
                        <p class="getit bbc_color"><?=__('门店付款')?></p>
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
							<span class="iconfont icon-shijian2 f14"></span>
							<span class="fnTimeCountDown" data-end="<?=$data['cancel_time']?>">
							    <span><?=__("剩余")?></span>
                                <!--<span class="day" >00</span><strong><?/*=__('天')*/?></strong>-->
                                <span class="hour">00</span><span><?=__('时')?></span>
                                <span class="mini">00</span><span><?=__('分')?></span>
                                <!--<span class="sec" >00</span><strong><?/*=__('秒')*/?></strong>-->
                            </span>
						</p>

						   <?php if($data['payment_id'] != PaymentChannlModel::PAY_CHAINPYA ): ?>
                            <p>
                                <a target="_blank" onclick="payOrder('<?=$data['payment_number']?>','<?=$data['order_id']?>')"  class="to_views "><i class="iconfont icon-icoaccountbalance pay-botton"></i><?=__('订单支付')?></a>
                            </p>
                            <?php endif; ?>
                          <p><a onclick="cancelOrder('<?=$data['order_id']?>')" class="to_views"><i class="iconfont icon-quxiaodingdan"></i><?=__('取消订单')?></a></p>
                    <?php endif; ?>
                <!--E  未付款订单 -->

                    <?php if($data['order_status'] == Order_StateModel::ORDER_FINISH && $data['order_return_num'] <= 0): ?>
                            <?php if(!$data['order_buyer_evaluation_status']): ?>
                                   <p> <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=evaluation&act=add&order_id=<?=($data['order_id'])?>" class="to_views"><i class="iconfont icon-woyaopingjia icon_size22"></i><?=__('我要评价')?></a></p>
                            <?php endif;?>
                        <?php if($data['order_buyer_evaluation_status']): ?>
                            <p><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=evaluation&act=add&order_id=<?=($data['order_id'])?>" class="to_views"><i class="iconfont icon-woyaopingjia icon_size22"></i><?=__('追加评价')?></a></p>
                        <?php endif;?>
                    <?php endif;?>

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
                    var data = eval('('+da+')');

                    if(data.status == 1)
                    {
                        var info_div = $("#info_"+order_id);

                        var content_div = '<div class="pc"><div class="p-tit"><?=__('运单号：')?>' + order_id + '</div><div class="logistics-cont"><ul>';

                        for (var i in data.data) {
                            var time = data.data[i].time;
                            var context = data.data[i].context;

                            var class_name = "";
                            if(i == 0)
                            {
                                class_name = "first";
                            }

                            content_div = content_div + '<li class='+ class_name + '><i class="node-icon bbc_bg"></i><a> ' + context + ' </a><div class="ftx-13"> ' + time + '</div></li>';

                        }

                        content_div = content_div + '</ul></div></div><div class="p-arrow p-arrow-left" style="top: 242px;"></div>';

                        $("#info_"+order_id).html(content_div);
                    }

                    if(data.status == 0)
                    {
                        $("#info_"+order_id).html('<div class="error_msg"><?=__('物流单暂无结果')?></div>');
                    }

                    if(data.status == 2 || !data)
                    {

                    }
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