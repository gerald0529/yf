<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
    <script src="<?= $this->view->js_com ?>/plugins/jquery.timeCountDown.js"></script>
    <script>
        $(function () {
            var _TimeCountDown = $(".fnTimeCountDown");
            _TimeCountDown.fnTimeCountDown();
        })
    </script>
    </div>
    <div class="order_content">
        <div class="ncm-order-info">
            <div class="ncm-order-details">
                <div class="title"><?= __('虚拟订单信息') ?></div>
                <div class="content">
                    <dl>
                        <dt><?= __('接收手机：') ?></dt>
                        <dd><?= ($data['order_receiver_contact']) ?></dd>
                    </dl>
                    <dl class="line line2 pt10">
                        <dt><?= __('虚拟单号：') ?></dt>
                        <dd><?= ($data['order_id']) ?><a href="#" class=""><?= __('更多') ?><i class="iconfont icon-iconjiantouxia"></i>
                                <div class="more" style="background: #fff; z-index: 99999;"><span class="arrow"></span>
                                    <ul>
                                        <li><?= __('支付时间：') ?><span><?= ($data['payment_time']) ?></span></li>
                                        <li><?= __('下单时间：') ?><span><?= ($data['order_create_time']) ?></span></li>
                                    </ul>
                                </div>
                            </a></dd>
                    </dl>
                    <dl class="line line2">
                        <dt><?= __('买家留言：') ?></dt>
                        <dd class="ellipsis"><?= ($data['order_message']) ?></dd>
                    </dl>
                    <dl class="line line2">
                        <dt><?= __('商　　家：') ?></dt>
                        <dd><?= ($data['shop_name']) ?><a href="#" class=""><?= __('更多') ?><i class="iconfont icon-iconjiantouxia"></i>
                                <div class="more" style="background: #fff; z-index: 99999;"><span class="arrow"></span>
                                    <ul>
                                        <li><?= __('所在地区：') ?><span><?= ($data['order_seller_address']) ?></span></li>
                                        <li><?= __('联系电话：') ?><span><?= ($data['shop_phone']) ?></span></li>
                                    </ul>
                                </div>
                            </a></dd>
                        
                        <!-- <dt><?= __('商家留言：') ?></dt> -->
                        <!-- <dd><?= ($data['order_seller_message']) ?></dd> -->
                    </dl>
                    <!--1-25新增兑换码-->
                    <!--<dl class="line line2">
                        <dt style="letter-spacing: 4px;">兑换码：</dt>
                        <dd class="clearfix">
                             <p class="fl">123456123456123456</p>
                            <p class="fl" style="margin-left: 20px;">未使用</p>
                            <p class="fr">有效期至2018-08-09</p>
                        </dd>
                    </dl>-->
                </div>
            </div>
            
            <?php if ($data['order_status'] == Order_StateModel::ORDER_WAIT_PAY): ?>
                <div class="ncm-order-condition">
                    <dl>
                        <dt><i class="icon-ok-circle green"></i><?= __('订单状态：') ?></dt>
                        <dd><?= __('订单已经生成，待付款') ?></dd>
                    </dl>
                    <ul>
                        
                        <li><?= __('1. 您尚未对该订单进行支付，请') ?><?php if ($data['payment_id'] != PaymentChannlModel::PAY_CONFIRM && $data['order_sub_pay'] == Order_StateModel::SUB_SELF_PAY) { ?><a href="<?= Yf_Registry::get('paycenter_api_url') ?>?ctl=Info&met=pay&uorder=<?= ($data['payment_number']) ?>" class="ncbtn-mini ncbtn-bittersweet bbc_btns"><i></i><?= __('支付订单') ?></a><?php } else { ?><?= __('联系主管账号支付订单') ?><?php } ?><?= __('以确保及时获取电子兑换码。') ?></li>
                        <li><?= __('2. 系统将于') ?>
                            <time><?= ($data['cancel_time']) ?></time><?= __('自动关闭该订单，请您及时付款。') ?></li>
                        
                        <?php if ($data['buyer_user_id'] == Perm::$userId) { ?>
                            <li><?= __('3. 如果您不想购买此订单，请选择 ') ?><a onclick="cancelOrder('<?= $data['order_id'] ?>')" class="ncbtn-mini bbc_btns"><?= __('取消订单') ?></a><?= __('操作。') ?></li>
                        <?php } else { ?>
                            <li><?= __('3. 如果您不想购买此订单的商品，需要采购子账号进行取消订单操作。') ?></li>
                        <?php } ?>
                    
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if ($data['order_status'] == Order_StateModel::ORDER_PAYED): ?>
                <div class="ncm-order-condition">
                    <dl>
                        <dt><i class="icon-ok-circle green"></i><?= __('订单状态：') ?></dt>
                        <dd><?= __('已付款，电子兑换码未发放') ?></dd>
                    </dl>
                    <ul>
                        <li><?= __('1. 本次电子兑换码商家还未发出，请及时联系商家发送兑换码。') ?></li>
                        <li><?= __('2.如您想放弃本次交易，可申请退款。') ?></li>
                </div>
            <?php endif; ?>
            
            <?php if ($data['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS || $data['order_status'] == Order_StateModel::ORDER_WAIT_PREPARE_GOODS): ?>
                <div class="ncm-order-condition">
                    <dl>
                        <dt><i class="icon-ok-circle green"></i><?= __('订单状态：') ?></dt>
                        <dd><?= __('已付款，电子兑换码已发放') ?></dd>
                    </dl>
                    <ul>
                        <li><?= __('1. 本次电子兑换码已由系统自动发出，请查看您的接收手机短信或该页下方“电子兑换码”。') ?></li>
                        <li><?= __('2. 您尚有') ?><?= ($data['new_code']) ?><?= __('组电子兑换码未被使用；有效期为') ?>
                            <time><?= ($data['common_virtual_date']) ?></time><?= __('，逾期自动失效，请及时使用。 ') ?></li>
                </div>
            <?php endif; ?>
            
            <?php if ($data['order_status'] == Order_StateModel::ORDER_FINISH): ?>
                <div class="ncm-order-condition">
                    <dl>
                        <dt><i class="icon-ok-circle green"></i><?= __('订单状态：') ?></dt>
                        <dd><?= __('订单交易已完成') ?></dd>
                    </dl>
                    <ul>
                        <li><?= __('1. 如果出现问题，您可以联系商家协商解决。') ?></li>
                        <li><?= __('2. 交易已完成，你可以对购买的商品进行评价。') ?></li>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if ($data['order_status'] == Order_StateModel::ORDER_CANCEL): ?>
                <div class="ncm-order-condition">
                    <dl>
                        <dt><i class="icon-ok-circle green"></i><?= __('订单状态：') ?></dt>
                        <dd><?= __('交易关闭') ?></dd>
                    </dl>
                    <ul>
                        <li><?= ($data['cancel_identity']) ?>于
                            <time><?= ($data['order_cancel_date']) ?></time><?= __('关闭交易，原因') ?><?= ($data['order_cancel_reason']) ?></li>
                    </ul>
                </div>
            <?php endif; ?>
            <!--<div class="mall-msg">有疑问可咨询<a href="javascript:void(0);"><i class="iconfont icon-kefu"></i>平台客服</a></div>-->
        </div>
        
        <div class="ncm-order-step">
            <?php if ($data['order_status'] != Order_StateModel::ORDER_CANCEL): ?>
                <dl class="step-first current">
                    <dt><?= __('生成订单') ?></dt>
                    <dd class="bg"></dd>
                    <dd class="date" title="<?= __('订单生成时间') ?>"><?= ($data['order_create_time']) ?></dd>
                </dl>
                <dl class="<?php if ($data['order_status'] == Order_StateModel::ORDER_WAIT_PREPARE_GOODS || $data['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS || $data['order_status'] == Order_StateModel::ORDER_FINISH || $data['order_status'] == Order_StateModel::ORDER_PAYED) { ?>current<?php } ?>">
                    <dt><?= __('完成付款') ?></dt>
                    <dd class="bg"></dd>
                    <dd class="date" title="<?= __('付款时间') ?>"><?= ($data['payment_time']) ?></dd>
                </dl>
                <dl class="<?php if ($data['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS || $data['order_status'] == Order_StateModel::ORDER_FINISH) { ?>current<?php } ?>">
                    <dt><?= __('发放兑换码') ?></dt>
                    <dd class="bg" <?php if ($data['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS || $data['order_status'] == Order_StateModel::ORDER_FINISH){ ?>onmousemove="$('.dd_aciv').show()" <?php } ?>></dd>
                    <dd class="date"><?= ($data['order_shipping_time']) ?></dd>
                    <?php if (isset($data['code_list']) && $data['code_list']): ?>
                        <dd class="dd_aciv" style="display: none;">
                            <div style="float:right;cursor: pointer;" onclick="$(this).parent().hide()"><i class="iconfont icon-cuowu "></i></div>
                            <h4> &nbsp;<?= __('电子兑换码') ?></h4>
                            <?php foreach ($data['code_list'] as $codekey => $codeval): ?>
                                <p>
                                    <em class="vir_code" <?php if ($codeval['virtual_code_status'] == Order_GoodsVirtualCodeModel::VIRTUAL_CODE_USED) { ?> class="cgreenl" <?php } ?>> &nbsp;<?= ($codeval['virtual_code_id']) ?></em>
                                    <em <?php if ($codeval['virtual_code_status'] != Order_GoodsVirtualCodeModel::VIRTUAL_CODE_USED) { ?> class="cyellowl" <?php } ?>>
                                        <?php if ($codeval['virtual_code_status'] == Order_GoodsVirtualCodeModel::VIRTUAL_CODE_NEW) { ?>
                                            <?= __('未使用，有效期至') ?><?= ($codeval['common_virtual_date']) ?>
                                            <!--S 判断该商品是否支持过期退款，若支持则在过期后出现退款按钮 -->
                                            <?php if ($data['buyer_user_id'] == Perm::$userId) { ?>
                                                <?php if ($codeval['common_virtual_refund'] && get_date_time() > $codeval['common_virtual_date']) { ?>
                                                    <a target="_blank" style="padding: 0 3px;margin-left: 230px;" class="bbc_btns" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=add&gid=<?= ($data['goods_list'][0]['order_goods_id']) ?>"><?= __('过期退款') ?></a>
                                                <?php } ?>
                                            <?php } ?>
                                            <!--E 判断该商品是否支持过期退款，若支持则在过期后出现退款按钮 -->
                                            <!--已冻结的也要显示出来Order_GoodsVirtualCodeModel::VIRTUAL_CODE_FROZEN-->
                                        <?php } elseif ($codeval['virtual_code_status'] == Order_GoodsVirtualCodeModel::VIRTUAL_CODE_FROZEN) { ?>
                                            <time><?= __('已冻结，冻结时间') ?><?= ($codeval['virtual_code_usetime']) ?></time>
                                        <?php } elseif ($codeval['virtual_code_status'] == Order_GoodsVirtualCodeModel::VIRTUAL_CODE_INVALID) { ?>
                                            <time><?= __('已失效，失效时间') ?><?= ($codeval['virtual_code_usetime']) ?></time>
                                        <?php } else { ?>
                                            <time><?= __('已使用，使用时间') ?><?= ($codeval['virtual_code_usetime']) ?></time>
                                        <?php } ?>
                                    </em>
                                </p>
                            <?php endforeach; ?>
                        </dd>
                    <?php endif; ?>
                </dl>
                <dl class="<?php if ($data['order_status'] == Order_StateModel::ORDER_FINISH): ?>current<?php endif; ?>">
                    <dt><?= __('订单完成') ?></dt>
                    <dd class="bg"></dd>
                    <dd class="date" title="<?= __('订单完成') ?>"><?= ($data['order_finished_time']) ?></dd>
                </dl>
            <?php endif; ?>
        </div>
        <table class="tboy-reset">
            <tbody class="tbpad">
            <tr class="order_tit">
                <th class="order_goods"><?= __('商品') ?></th>
                <th class="widt1"><?= __('单价') ?></th>
                <th class="widt2"><?= __('数量') ?></th>
                <th class="widt4"><?php if ($data['buyer_user_id'] == Perm::$userId) { ?><?= __('售后维权') ?><?php } ?></th>
                <th class="widt5"><?= __('订单金额') ?></th>
                <th class="widt6"><?= __('交易状态') ?></th>
                <th class="widt7"><?= __('操作') ?></th>
            </tr>
            </tbody>
            <tbody>
            <tr>
                <th class="tr_margin" style="height:16px;background:#fff;" colspan="8"></th>
            </tr>
            </tbody>
            <tbody class="tboy">
            <tr>
                <td colspan="4" class="td_rborder">
                    <!--S  循环订单中的商品  -->
                    <table>
                        <?php foreach ($data['goods_list'] as $ogkey => $ogval) { ?>
                            <tr class="tr_con">
                                <td class="order_goods">
                                    <img src="<?= image_thumb($ogval['goods_image'], 50, 50) ?>" />
                                    <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= ($ogval['goods_id']) ?>"><?= ($ogval['goods_name']) ?></a>
                                    <?php if ($ogval['order_goods_benefit']) { ?><em class="td_sale bbc_btns small_details"><?= ($ogval['order_goods_benefit']) ?></em><?php } ?>
                                </td>
                                <td class="td_color widt1"><?= format_money($ogval['goods_price']) ?></td>
                                <td class="td_color widt2"><?= __('x') ?> <?= ($ogval['order_goods_num']) ?></td>
                                <td class="td_color widt4">
                                    <?php if ($data['buyer_user_id'] == Perm::$userId) { ?>
                                        <?php if ($data['order_status'] != Order_StateModel::ORDER_WAIT_PAY && $data['order_status'] != Order_StateModel::ORDER_CANCEL && $data['order_status'] != Order_StateModel::ORDER_FINISH) { ?>
                                            <?php if ($ogval['goods_return_status'] == Order_StateModel::ORDER_GOODS_RETURN_NO) { ?>
                                                <?php if (strstr($data['payment_name'], '白条支付')) { ?>
                                                    <p>
                                                        <a class="to_views" onclick="alert('白条支付的订单，请联系商家线下退款');"><?= __('退款/退货') ?></a>
                                                    </p>
                                                <?php } else { ?>
                                                    <p>
                                                        <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=add&oid=<?= ($ogval['order_id']) ?>&gid=<?= ($ogval['order_goods_id']) ?>" class="to_views"><?= __('退款/退货') ?></a></p>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if ($ogval['goods_return_status'] != Order_StateModel::ORDER_REFUND_NO) { ?>
                                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=detail&id=<?= ($ogval['order_return_id']) ?>"><?= $ogval['goods_return_status_con'] ?></a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                    <!--E  循环订单中的商品   -->
                </td>
                
                <!--S  订单金额 -->
                <td class="td_rborder widt5 pad0">
				     <span class="fls">
				        <em class="type-name"><?= __('总额：') ?></em><strong><?= format_money($data['order_goods_amount']) ?></strong><!--<br/>--><? /*=($data['payment_name'])*/ ?>
				     </span>
                    <br />
                    <span class="fls">
				        <em class="type-name"><?php if ($data['order_status'] == Order_StateModel::ORDER_WAIT_PAY || $data['order_status'] == Order_StateModel::ORDER_CANCEL) { ?><?= __('应付：') ?><?php } else { ?><?= __('实付：') ?><?php } ?></em><strong><?= format_money($data['order_payment_amount']) ?></strong>
				     </span>
                    
                    <?php if ($data['order_shop_benefit']) {
                        $str = str_replace('  ', "<br>", $data['order_shop_benefit']);
                        $str = str_replace(': ', "：<br>", $str); ?><span class="td_sale bbc_btns"><?= $str ?></span><?php } ?>
                </td>
                <!--E 订单金额 -->
                <td class="td_rborder">
                    <p class="getit"><?= ($data['order_state_con']) ?></p>
                </td>
                <!--S 订单操作  -->
                <td class="td_rborder td_rborder_reset">
                    <?php if (($data['order_status'] == Order_StateModel::ORDER_CANCEL || $data['order_status'] == Order_StateModel::ORDER_FINISH)) { ?>
                        <p>
                            <a onclick="hideOrder('<?= $data['order_id'] ?>')"><i class="iconfont icon-lajitong icon_size22"></i><?= __('删除订单') ?></a>
                        </p>
                    <?php } else { ?>
                        
                        <?php if ($data['order_status'] != Order_StateModel::ORDER_WAIT_PAY) { ?>
                            <p class="tc"><?= __('无可执行操作') ?></p>
                        <?php } ?>
                    <?php } ?>
                    
                    <!--S  未付款订单 -->
                    <?php if ($data['order_status'] == Order_StateModel::ORDER_WAIT_PAY): ?>
                        <p class="rest">
                            <span class="iconfont icon-shijian2"></span>
                            <span class="fnTimeCountDown" data-end="<?= $data['cancel_time'] ?>">
							    <span><?= __("剩余") ?></span>
                                <span class="hour">00</span><span><?= __('时') ?></span>
                                <span class="mini">00</span><span><?= __('分') ?></span>
                            </span>
                        </p>
                        
                        <?php if ($data['payment_id'] != PaymentChannlModel::PAY_CONFIRM && $data['order_sub_pay'] == Order_StateModel::SUB_SELF_PAY): ?>
                            <p>
                                <a target="_blank" onclick="payOrder('<?= $data['payment_number'] ?>','<?= $data['order_id'] ?>')" class="to_views "><i class="iconfont icon-icoaccountbalance pay-botton"></i><?= __('订单支付') ?></a>
                            </p>
                        <?php endif; ?>
                        
                        <?php if ($data['buyer_user_id'] == Perm::$userId) { ?>
                            <p><a onclick="cancelOrder('<?= $data['order_id'] ?>')" class="to_views"><i class="iconfont icon-quxiaodingdan"></i><?= __('取消订单') ?></a></p>
                        <?php } ?>
                    <?php endif; ?>
                    <!--E  未付款订单 -->
                    <?php if ($data['buyer_user_id'] == Perm::$userId) { ?>
                        <?php if ($data['order_status'] == Order_StateModel::ORDER_FINISH &&  $data['order_return_num'] <= 0) { ?>
                            <?php if (!$data['order_buyer_evaluation_status']) { ?>
                                <p><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=evaluation&act=add&order_id=<?= ($data['order_id']) ?>" class="to_views"><i class="iconfont icon-woyaopingjia icon_size22"></i><?= __('我要评价') ?></a></p>
                            <?php } ?>
                            <?php if ($data['order_buyer_evaluation_status']) { ?>
                                <p><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=evaluation&act=add&order_id=<?= ($data['order_id']) ?>" class="to_views"><i class="iconfont icon-woyaopingjia icon_size22"></i><?= __('追加评价') ?></a></p>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                
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

<?php
    include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>