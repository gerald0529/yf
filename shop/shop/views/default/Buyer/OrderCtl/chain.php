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
          <div class="order_content_title clearfix">
          <form method="get" id="search_form" action="index.php" >
            <input type="hidden" name="ctl" value="<?=$_GET['ctl']?>">
            <input type="hidden" name="met" value="<?=$_GET['met']?>">
            <p class="order_types">
				<a <?php if($status == '' &&  !$recycle):?>class="currect"<?php endif;?> href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=chain"><?=__('全部订单')?></a>
				<a <?php if($status == 'wait_pay'):?>class="currect"<?php endif;?> href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=chain&status=wait_pay"><?=__('待付款')?></a>
				<a <?php if($status == 'order_chain'):?>class="currect"<?php endif;?> href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=chain&status=order_chain"><?=__('待自提')?></a>
				<a <?php if($status == 'finish'):?>class="currect"<?php endif;?> href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=chain&status=finish"><?=__('已完成')?></a>
			</p>

            <p class="order_time">
                <span><?=__('下单时间')?></span>
                <input type="text" autocomplete="off" placeholder="<?=__('开始时间')?>" name="start_date" id="start_date" class="text w70" value="<?=@$_GET['start_date']?>">
                 
                <em style="margin-top: 3px;">&nbsp;– &nbsp;</em>
                <input type="text" placeholder="<?=__('结束时间')?>" autocomplete="off" name="end_date" id="end_date" class="text w70" value="<?=@$_GET['end_date']?>">
                 

            </p>
            <p class="ser_p" style="margin-left: 10px;">
                <input type="text" name="orderkey" placeholder="<?=__('商品名称或订单号')?>" value="<?=@$_GET['orderkey']?>">
                <a class="btn_search_goods" href="javascript:void(0);" style="padding-left: 2px;"><i class="iconfont icon-icosearch icon_size18" style="margin-right:-2px; "></i><?=__('搜索')?></a>
            </p>

            <p class="order_types serc_p">
                <a <?php if($recycle):?>class="currect"<?php endif;?> href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=chain&recycle=1"><i class="iconfont icon-lajitong icon_size20"></i><?=__('订单回收站')?></a>
            </p>

            <script type="text/javascript">
            $("a.btn_search_goods").on("click",function(){
                $("#search_form").submit();
            });
            </script>
          </form>
          </div>
          <table class="">
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
              <?php if($data['items']){?>
              <?php foreach($data['items'] as $key => $val):?>
              <tbody class="tboy">

              <!-- 下单时间，订单号，店铺名称    -->
                <tr class="tr_title">
                  <th colspan="8" class="order_mess clearfix">
                      <p class="order_mess_one">
                      	<a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?=($val['shop_id'])?>"><i class="iconfont icon-icoshop"></i><?=($val['shop_name'])?></a>
                      	<span><?=__('订单号：')?><strong><?=($val['order_id'])?></strong></span>
                        <time><?=__('下单时间：')?><?=($val['order_create_time'])?></time>
                      
                        
                      </p>
                      <?php if($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY){?>
				        <p class="rest fr">
							<span class="iconfont icon-shijian2 align-middle"></span>
							<span class="fnTimeCountDown" data-end="<?=$val['cancel_time']?>">
							    <span><?=__("剩余")?></span>
                                <span class="hour">00</span><span><?=__('时')?></span>
                                <span class="mini">00</span><span><?=__('分')?></span>
                            </span>
						</p>
                    <?php }?>
                  </th>
                </tr>

				<tr>
				    <td colspan="4"  class="td_rborder">
				        <!--S  循环订单中的商品  -->
                        <table>
                        <?php foreach($val['goods_list'] as $ogkey=> $ogval):?>
                            <tr class="tr_con">
                                <td class="order_goods">
                                    <img src="<?=image_thumb($ogval['goods_image'],50,50)?>"/>
                                    <a target="_blank"  href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($ogval['goods_id'])?>"><?=($ogval['goods_name'])?></a>
                                    <?php if ($ogval['spec_text']) { ?>
                                        <!--取消【规格：】显示-->
                                        <dd class="table-dd-set tl">
                                            <!--<strong>规格：</strong>&nbsp;&nbsp;-->
                                            <em><?= $ogval['spec_text'] ?></em>
                                        </dd>
                                    <?php } ?>
                                    <?php if($ogval['order_goods_benefit']){?><em class="td_sale bbc_btns small_details"><?=($ogval['order_goods_benefit'])?></em><?php }?>
                                </td>
                                <td class="td_color widt1"><?=format_money($ogval['goods_price'])?></td>
                                <td class="td_color widt2"><i class="iconfont icon-cuowu" style="position:relative;font-size: 12px;"></i> <?=($ogval['order_goods_num'])?></td>
                                <td class="td_color widt4">
                                    <!-- S 退款 -->
                                    <!-- 订单为待自提&&在线支付&&未退款 -->
                                    <?php if(($val['order_status'] == Order_StateModel::ORDER_SELF_PICKUP && $val['payment_id'] == PaymentChannlModel::PAY_ONLINE) && $ogval['goods_return_status'] == Order_StateModel::ORDER_GOODS_RETURN_NO){?>
                                        <?php if(strstr($val['payment_name'],'白条支付')){ ?>
                                        <p> <a  class="to_views" onclick="javascript:alert('白条支付的订单，请联系商家线下退款');"><?=__('退款/退货')?></a></p>
                                        <?php }else{ ?>
                                        <p><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=add&oid=<?=($val['order_id'])?>&gid=<?=($ogval['order_goods_id'])?>" class="to_views"><?=__('退款/退货')?></a></p>
                                        <?php } ?>

                                <?php }?>
                                    <!-- E 退款 -->

                                    <!-- S 退货 -->
                                    <!-- 订单完成&&未退货 -->
                                    <?php if($val['order_status'] != Order_StateModel::ORDER_WAIT_PAY  && $val['order_status'] != Order_StateModel::ORDER_CANCEL){?>
                                        <?php if($ogval['goods_refund_status'] == Order_StateModel::ORDER_GOODS_RETURN_NO && $ogval['order_goods_status'] == Order_StateModel::ORDER_FINISH){?>
                                            <?php if(strstr($val['payment_name'],'白条支付')){ ?>
                                            <a onclick="javascript:alert('白条支付的订单，请联系商家线下退货');"><?=__('退款/退货')?></a>
                                            <?php }else{ ?>
                                            <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=add&gid=<?=($ogval['order_goods_id'])?>&oid=<?=($val['order_id'])?>"><?=__('退款/退货')?></a>
                                            <?php } ?>
                                        <?php }?>
                                     <?php }?>
                                     <!-- E 退货 -->

                                <?php if($ogval['goods_return_status'] != Order_StateModel::ORDER_REFUND_NO ){?>
                                    <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=detail&id=<?=($ogval['order_return_id'])?>"><?=$ogval['goods_return_status_con']?></a>
                                <?php }?>
                                <?php if($ogval['goods_refund_status'] != Order_StateModel::ORDER_REFUND_NO ){?>
                                    <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=detail&id=<?=($ogval['order_refund_id'])?>"><?=$ogval['goods_refund_status_con']?></a>
                                <?php }?>

                                    <p>
                                        <?php if(($val['order_status'] == Order_StateModel::ORDER_FINISH && $val['complain_status']) || ($val['order_status'] != Order_StateModel::ORDER_CANCEL && $val['order_status'] != Order_StateModel::ORDER_WAIT_PAY)){?>
                                            <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Complain&met=index&act=add&gid=<?=($ogval['order_goods_id'])?>">
                                                <?=__('交易投诉')?>
                                            </a>
                                        <?php }?>
                                    </p>
                                </td>

                            </tr>
                        <?php endforeach;?>
                        </table>
                        <!--E  循环订单中的商品   -->
                </td>

                <!--S  订单金额 -->
                <td class="td_rborder widt5 pad0">
				     <span class="fls ff">
				        <em class="type-name"><?=__('订单总额：')?></em><strong><?=format_money($val['order_goods_amount'])?></strong><!--<br/>--><?/*=($val['payment_name'])*/?>
				     </span>
				     <br/>
				     <span class="fls ff">
				         <em class="type-name"><?php if($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY || $val['order_status'] == Order_StateModel::ORDER_CANCEL){ ?><?=__('应付：')?><?php }else{ ?><?=__('实付：')?><?php } ?></em><strong><?=format_money($val['order_payment_amount'])?></strong>
				     </span>
				     <?php if($val['order_shop_benefit']){ $str = str_replace('  ',"<br>", $val['order_shop_benefit']);?><span class="td_sale bbc_btns"><?=$str?></span><?php }?>
                </td>
                <!--E 订单金额 -->

				<td class="td_rborder">
                   <p class="getit <?php if($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY ){?>bbc_color<?php }?>"><?=($val['order_state_con'])?></p>
                   <?php if( $val['payment_id'] == PaymentChannlModel::PAY_CHAINPYA ){?>
                        <p class="getit bbc_color"><?=__('门店付款')?></p>
                   <?php }?>

                   <p>
                      <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=chain&act=details&order_id=<?=($val['order_id'])?>"><?=__('订单详情')?>
                      </a>
                   </p>
                </td>


                <!--S 订单操作  -->
				<td class="td_rborder td_rborder_reset">
				    <?php if(($val['order_status'] == Order_StateModel::ORDER_CANCEL || $val['order_status'] == Order_StateModel::ORDER_FINISH) && $recycle != 1):?>
                      
                       <p> <a onclick="hideOrder('<?=$val['order_id']?>')"><i class="iconfont icon-lajitong icon_size22"></i><?=__('删除订单')?></a></p>
                      
                  <?php endif; ?>

				<!--S  未付款订单 -->
				    <?php if($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY):?>
				        
						<?php if($val['payment_id'] != PaymentChannlModel::PAY_CHAINPYA): ?>
                        <p>
                            <a target="_blank" onclick="payOrder('<?=$val['payment_number']?>','<?=$val['order_id']?>')" class="to_views "><i class="iconfont icon-icoaccountbalance pay-botton" ></i><?=__('订单支付')?></a>
                        </p>
                        <?php endif; ?>
                          <p><a onclick="cancelOrder('<?=$val['order_id']?>')" class="to_views"><i class="iconfont icon-quxiaodingdan"></i><?=__('取消订单')?></a></p>
                    <?php endif;?>
                <!--E  未付款订单 -->

                    <?php if($val['order_status'] == Order_StateModel::ORDER_FINISH && $val['order_return_num'] <= 0): ?>
                            <?php if(!$val['order_buyer_evaluation_status']): ?>
                                   <p> <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=evaluation&act=add&order_id=<?=($val['order_id'])?>" class="to_views"><i class="iconfont icon-woyaopingjia icon_size22"></i><?=__('我要评价')?></a></p>
                            <?php endif;?>
                        <?php if($val['order_buyer_evaluation_status']): ?>
                            <p><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=evaluation" class="to_views"><i class="iconfont icon-woyaopingjia icon_size22"></i><?=__('追加评价')?></a></p>
                        <?php endif;?>
                    <?php endif;?>

                    <?php if($recycle): ?>

                        <p><a onclick="restoreOrder('<?=$val['order_id']?>')"><i class="iconfont icon-huanyipi"></i><?=__('还原订单')?></a></p>

                        <p><a onclick="delOrder('<?=$val['order_id']?>')" class="to_views"><i class="iconfont icon-lajitong icon_size22"></i><?=__('彻底删除')?></a></p>

                    <?php endif;?>
                </td>
                <!--E 订单操作   -->
		    </tr>
            </tbody>

              <tbody>
                <tr>
                  <th class="tr_margin" style="height:16px;background:#fff;" colspan="8"></th>
                </tr>
              </tbody>
              <?php endforeach;?>
              <?php }
            else
            {
                ?>
                <tr>
                    <td colspan="99">
                        <div class="no_account">
                            <img src="<?= $this->view->img ?>/ico_none.png"/>
                            <p><?= __('暂无符合条件的数据记录') ?></p>
                        </div>
                    </td>
                </tr>
            <?php } ?>
          </table>
          <div class="flip page clearfix">
            <p><!--<a href="#" class="page_first">首页</a><a href="#" class="page_prev">上一页</a><a href="#" class="numla cred">1</a><a href="#" class="page_next">下一页</a><a href="#" class="page_last">末页</a>-->
            <?=$page_nav?>
            </p>
          </div>
          <!--<div  style="" class="foot-wrapper2">
				<p>猜你喜欢</p>
				<ul class="clearfix">
					<li class="fl">
						<div class='wrapper-img2'></div>
			    		<p class='wrapper-p1' style="">￥321.059</p>
			    		<p class='wrapper-p2' style="">艾莱伊中长款加厚修身</p>
			    		<div class="wrapper-btn">去看看</div>
					</li>
				</ul>	
			</div>-->
        </div>
      </div>
</div>
  </div>
</div>
</div>
  </div>
</div>
<script>
$(document).ready(function(){
    $('#start_date').datetimepicker({
        controlType: 'select',
        timepicker:false,
        format:'Y-m-d'
    });

    $('#end_date').datetimepicker({
    controlType: 'select',
    timepicker:false,
    format:'Y-m-d'
    });


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
});
</script>

 <!-- 尾部 -->
 <?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>