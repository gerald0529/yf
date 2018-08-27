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
    <style>
        .logistic_css {
            line-height: 25px;
            text-align: left;
        }
    </style>
    </div>
    <div class="order_content">
        <div class="order_content_title clearfix">
            <form method="get" id="search_form" action="index.php">
                <input type="hidden" name="ctl" value="<?= $_GET['ctl'] ?>">
                <input type="hidden" name="met" value="<?= $_GET['met'] ?>">
                <p class="order_types">
                    <a <?php if ($status == '' && !$recycle): ?>class="currect"<?php endif; ?>
                       href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical"><?= __('全部订单') ?></a>
                    <a <?php if ($status == 'wait_pay'): ?>class="currect"<?php endif; ?>
                       href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&status=wait_pay"><?= __('待付款') ?></a>
                    <a <?php if ($status == 'order_payed'): ?>class="currect"<?php endif; ?>
                       href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&status=order_payed"><?= __('待发货') ?></a>
                    <a <?php if ($status == 'wait_confirm_goods'): ?>class="currect"<?php endif; ?>
                       href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&status=wait_confirm_goods"><?= __('待收货') ?></a>
                    <a <?php if ($status == 'finish'): ?>class="currect"<?php endif; ?>
                       href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&status=finish"><?= __('待评价') ?></a>
                </p>

                <p class="order_time">
                    <span><?= __('下单时间') ?>:</span>
                    <input type="text" autocomplete="off" placeholder="<?= __('开始时间') ?>" name="start_date"
                           id="start_date" class="text w70" value="<?= @$_GET['start_date'] ?>">

                    <em style="margin-top: 3px;">&nbsp;– &nbsp;</em>
                    <input type="text" placeholder="<?= __('结束时间') ?>" autocomplete="off" name="end_date" id="end_date"
                           class="text w70" value="<?= @$_GET['end_date'] ?>">


                </p>
                <p class="ser_p" style="margin-left: 40px;">
                    <input type="text" name="orderkey" placeholder="<?= __('商品名称或订单号') ?>"
                           value="<?= @$_GET['orderkey'] ?>">
                    <a class="btn_search_goods" href="javascript:void(0);" style="padding-left: 2px;"><i
                                class="iconfont icon-icosearch icon_size18"
                                style="margin-right:-2px; "></i><?= __('搜索') ?></a>
                </p>

                <p class="order_types serc_p">
                    <a <?php if ($recycle): ?>class="currect"<?php endif; ?>
                       href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&recycle=1"><i
                                class="iconfont icon-lajitong icon_size20"></i><?= __('订单回收站') ?></a>
                </p>

                <script type="text/javascript">
                    $("a.btn_search_goods").on("click", function () {
                        $("#search_form").submit();
                    });
                </script>
            </form>
        </div>
        <table class="tboy-reset">
            <tbody class="tbpad">
            <tr class="order_tit">
                <th class="order_goods"><?= __('商品') ?></th>
                <th class="widt1"><?= __('单价') ?></th>
                <th class="widt2"><?= __('数量') ?></th>
                <th class="widt4"><?= __('售后维权') ?></th>
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
            <?php if ($data['items']) { ?>
                <?php foreach ($data['items'] as $key => $val): ?>
                    <tbody class="tboy">
                    <!-- 下单时间，订单号，店铺名称    -->
                    <tr class="tr_title">
                        <th colspan="8" class="order_mess clearfix">
                            <p class="order_mess_one clearfix">
                                <a class="one-overflow fl wp20" target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?= ($val['shop_id']) ?>">
                                    <i class="iconfont icon-icoshop"></i>
                                    <?= ($val['shop_name']) ?>
                                </a>
                                <span class="fl"><?= __('订单号：') ?><strong><?= ($val['order_id']) ?></strong></span>
                                <time  class="fl"><?= __('下单时间：') ?><?= ($val['order_create_time']) ?></time>
                                <?php
                                    if ($val['pintuan_temp_order']) {
                                        if ($val['pintuan_type'] == 1) {
                                ?>
                                        <span style="float:right"><?= __('1人团') ?></span>
                                        <?php } else { ?>
                                        <span style="float:right"><?= $val['pintuan_person_num'] ?><?= __('人团') ?></span>
                                    <?php } } ?>
                            </p>
                            <?php if ($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY) { ?>
                                <p class="rest fr">
                                    <span class="iconfont icon-shijian2 align-middle"></span>
                                    <span class="fnTimeCountDown" data-end="<?= $val['cancel_time'] ?>">
                                            <span><?= __("剩") ?></span>
                                            <span class="day">00</span><span><?= __('天') ?></span>
                                            <span class="hour">00</span><span><?= __('时') ?></span>
                                            <span class="mini">00</span><span><?= __('分') ?></span>
                                            <span class="sec">00</span><span><?= __('秒自动关闭') ?></span>
                                        </span>
                                </p>
                            <?php } ?>
                            <?php if ($val['order_refund_status'] !== Order_StateModel::ORDER_REFUND_IN && $val['order_return_status'] !== Order_StateModel::ORDER_GOODS_RETURN_IN && $val['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS): ?>
                                <p class="rest fr">
                                    <span class="iconfont icon-shijian2 align-middle"></span>
                                    <span class="fnTimeCountDown" data-end="<?= $val['order_receiver_date'] ?>">
                                        <span><?= __("剩") ?></span>
                                        <span class="day">00</span><span><?= __('天') ?></span>
                                        <span class="hour">00</span><span><?= __('时') ?></span>
                                        <span class="mini">00</span><span><?= __('分') ?></span>
                                        <span class="sec">00</span><span><?= __('秒自动收货') ?></span>
                                    </span>
                                </p>
                            <?php endif; ?>
                        </th>
                    </tr>

                    <tr>
                        <td colspan="4" class="td_rborder">
                            <!--S  循环订单中的商品  -->
                            <table>

                                <?php foreach ($val['goods_list'] as $ogkey => $ogval): ?>
                                    <tr class="tr_con">
                                        <td class="order_goods posr">

                                            <?php if( $ogval['is_del'] == 2){ ?>
                                        	<p class="old-Failed">此商品<br>已失效</p>
                                            <?php } ?>
                                        	
                                            <img src="<?= image_thumb($ogval['goods_image'], 50, 50) ?>"/>

                                            <a target="_blank"
                                               href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= ($ogval['goods_id']) ?>"><?= ($ogval['goods_name']) ?></a>

                                            <?php if (isset($ogval['order_spec_info']) && $ogval['order_spec_info']) { ?>
                                                <!--取消【规格：】显示-->
                                                <dd class="table-dd-set tl"
                                                    title="<?= $ogval['title_order_spec_info'] ?>">
                                                    <!--<strong ><? /*=__('规格')*/ ?>：</strong>&nbsp;&nbsp;-->
                                                    <em><?= $ogval['order_spec_info'][0] . ',' . $ogval['order_spec_info'][1]; ?></em>
                                                </dd>
                                            <?php } ?>

                                            <?php if ($ogval['order_goods_benefit']) { ?>
                                                <em class="td_sale bbc_btns small_details"><?= ($ogval['order_goods_benefit']) ?></em>
                                            <?php } ?>
                                        </td>
                                        <td class="td_color widt1"><?= format_money($ogval['goods_price']) ?></td>
                                        <td class="td_color widt2"><i class="iconfont icon-cuowu"
                                                                      style="position:relative;font-size: 12px;"></i> <?= ($ogval['order_goods_num']) ?>
                                        </td>
                                        <td class="td_color widt4">
                                            <!-- S 退款 -->
                                            <!-- 满赠商品没有退款/退货操作 -->
                                            <?php if($ogval['goods_price'] > 0){ ?>
                                                <?php
                                                //货到付款 -- 货到付款的商品没有退款操作只有退货操作
                                                if ($val['payment_id'] == PaymentChannlModel::PAY_CONFIRM) {
                                                    ?>
                                                    <?php
                                                    //货到付款的订单只有当订单确认收货完成订单后才会出现“退款/退货”按钮
                                                    if (($val['order_status'] == Order_StateModel::ORDER_RECEIVED || $val['order_status'] == Order_StateModel::ORDER_FINISH) && $val['order_refund_status'] == Order_StateModel::ORDER_REFUND_NO) {
                                                        ?>
                                                        <?php
                                                        if ($val['pintuan_temp_order'] != 1) {
                                                            ?>
                                                            <?php
                                                            //白条支付的订单需要线下进行退款/退货操作
                                                            if (strstr($val['payment_name'], '白条支付')) { ?>
                                                                <p><a class="to_views"
                                                                      onclick="javascript:alert('白条支付的订单，请联系商家线下退款/退货');"><i
                                                                                class="iconfont icon-dingdanwancheng icon_size22"></i><?= __('退款/退货') ?>
                                                                    </a></p>
                                                            <?php } else { ?>
                                                                <?php if ($ogval['goods_refund_status'] == Order_StateModel::ORDER_GOODS_RETURN_NO && $ogval['order_goods_num'] > $ogval['order_goods_returnnum']) { ?>
                                                                    <p><a target="_blank"
                                                                          href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=add&oid=<?= ($val['order_id']) ?>&gid=<?= ($ogval['order_goods_id']) ?>"
                                                                          class="to_views"><?= __('退款/退货') ?></a></p>
                                                                <?php } else { ?>
                                                                    <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=detail&id=<?= ($ogval['order_refund_id']) ?>"><?= $ogval['goods_refund_status_con'] ?></a>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } else {
                                                    //在线支付 -- 已付款（可退款），订单完成（可退货）
                                                    ?>
                                                    <?php
                                                    //已经付款（但是没有退款的商品），已经完成（但是没有退货的商品）出现“退款/退货”按钮
                                                    //由于之前数据的影响，之前订单存在退款的商品的“退款/退货”按钮也不显示
                                                    if ((($val['order_status'] == Order_StateModel::ORDER_PAYED && $ogval['goods_return_status'] == Order_StateModel::ORDER_GOODS_RETURN_NO) || ($val['order_status'] == Order_StateModel::ORDER_FINISH && $ogval['goods_refund_status'] == Order_StateModel::ORDER_GOODS_RETURN_NO)) && !$val['order_source_id'] && $val['order_refund_status'] == Order_StateModel::ORDER_REFUND_NO && $ogval['order_goods_num'] > $ogval['order_goods_returnnum'] && $ogval['goods_price'] > 0
                                                    ) {
                                                        ?>
                                                        <?php
                                                        if ($val['pintuan_temp_order'] != 1) {
                                                            ?>
                                                            <?php if (strstr($val['payment_name'], '白条支付')) { ?>
                                                                <p><a class="to_views"
                                                                      onclick="javascript:alert('白条支付的订单，请联系商家线下退款/退货');"><i
                                                                                class="iconfont icon-dingdanwancheng icon_size22"></i><?= __('退款/退货') ?>
                                                                    </a></p>
                                                            <?php } else {
                                                                //订单状态为已付款，并且订单商品没有退款 则显示订单商品的退款按钮
                                                                ?>
                                                                <p>
                                                                    <a target="_blank"
                                                                       href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=add&oid=<?= ($val['order_id']) ?>&gid=<?= ($ogval['order_goods_id']) ?>"
                                                                       class="to_views"><?= __('退款/退货') ?></a>
                                                                </p>
                                                            <?php }
                                                        } ?>
                                                    <?php } ?>

                                                    <?php if ($ogval['goods_return_status'] != Order_StateModel::ORDER_REFUND_NO) { ?>
                                                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=detail&id=<?= ($ogval['order_return_id']) ?>"><?= $ogval['goods_return_status_con'] ?></a>
                                                    <?php } ?>
                                                    <!-- S 取消退款 -->
                                                    <?php if ($ogval['goods_return_status'] == Order_StateModel::ORDER_REFUND_IN) { ?>
                                                        <p><a onclick="cancelReturn('<?= ($ogval['order_return_id']) ?>')"
                                                              class="to_views"><?= __('取消退款') ?></a></p>
                                                    <?php } ?>

                                                    <!-- E 取消退款 -->
                                                    <?php if ($ogval['goods_refund_status'] != Order_StateModel::ORDER_REFUND_NO) { ?>
                                                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=detail&id=<?= ($ogval['order_refund_id']) ?>"><?= $ogval['goods_refund_status_con'] ?></a>
                                                    <?php } ?>
                                                <?php } ?>
                                            <!-- E 退款 -->
                                            <?php } ?>
                                            <p>
                                                <?php if (($val['order_status'] == Order_StateModel::ORDER_FINISH && $val['complain_status']) || ($val['order_status'] != Order_StateModel::ORDER_CANCEL && $val['order_status'] != Order_StateModel::ORDER_WAIT_PAY)) { ?>
                                                    <a target="_blank"
                                                       href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Complain&met=index&act=add&gid=<?= ($ogval['order_goods_id']) ?>">
                                                        <?= __('交易投诉') ?>
                                                    </a>
                                                <?php } ?>
                                            </p>

                                            <?php if (!empty($ogval['order_goods_source_ship'])) {
                                                $arr = explode('-', $ogval['order_goods_source_ship']);
                                            }
                                            ?>

                                            <?php if ($ogval['order_goods_source_id'] && $ogval['order_goods_source_ship']) { ?>
                                                <a style="position:relative;"
                                                   onmouseover="show_logistic('<?= ($ogval['order_goods_source_id']) ?>','<?= ($arr[1]) ?>','<?= ($arr[0]) ?>')"
                                                   onmouseout="hide_logistic('<?= ($ogval['order_goods_source_id']) ?>')">
                                                    <i class="iconfont icon-icowaitproduct rel_top2"></i><?= __('物流信息') ?>
                                                    <div style="display: none;"
                                                         id="info_<?= ($ogval['order_goods_source_id']) ?>"
                                                         class="prompt-01"></div>
                                                </a>
                                            <?php } elseif ($ogval['order_goods_source_id'] == '' && $ogval['order_goods_source_ship']) { ?>
                                                <a style="position:relative;"
                                                   onmouseover="show_logistic('<?= ($ogval['order_id']) ?>','<?= ($arr[1]) ?>','<?= ($arr[0]) ?>')"
                                                   onmouseout="hide_logistic('<?= ($ogval['order_id']) ?>')">
                                                    <i class="iconfont icon-icowaitproduct rel_top2"></i><?= __('物流信息') ?>
                                                    <div style="display: none;" id="info_<?= ($ogval['order_id']) ?>"
                                                         class="prompt-01"></div>
                                                </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                            <!--E  循环订单中的商品   -->
                        </td>

                        <!--S  订单金额 -->
                        <td class="td_rborder widt5 pad0">
				            <span class="fls">
                                 <em class="type-name">
                                     <?= __('订单总额：') ?>
                                 </em>
                                 <strong>
                                     <?= format_money($val['order_goods_amount']) ?>
                                 </strong>
                                 <!--<br/>--><? /*=($val['payment_name'])*/ ?>
                            </span>
                            <br/>
                            <span class="fls">
                                <em class="type-name">
                                    <?= __('运费：') ?>
                                </em>
                                <strong>
                                    <?php if ($val['order_shipping_fee'] > 0): ?>
                                        <?= format_money($val['order_shipping_fee']) ?>
                                    <?php else: ?>
                                        <?= __('免运费') ?>
                                    <?php endif; ?>
                                </strong>
                            </span>
                            <br/>
                            <span class="fls">
                                <em class="type-name">
                                    <?php if ($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY || $val['order_status'] == Order_StateModel::ORDER_CANCEL) { ?>
                                        <?= __('应付：') ?>
                                    <?php } else { ?>
                                        <?= __('实付：') ?>
                                    <?php } ?>
                                </em>
                                <strong><?= format_money($val['order_payment_amount']) ?></strong>
                            </span>
                            <?php
                                if ($val['order_shop_benefit']) {
                                $str = str_replace('  ', "<br>", $val['order_shop_benefit']);
                                $str = str_replace(': ', "：<br>", $str);
                                ?>
                                <span class="td_sale bbc_btns"><?= $str ?></span>
                                <?php } ?>
                        </td>
                        <!--E 订单金额 -->
                        <td class="td_rborder">
                            <p class="getit <?php if ($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY) { ?>bbc_color<?php } ?>"><?= ($val['order_state_con']) ?></p>
                            <?php if ($val['payment_id'] == PaymentChannlModel::PAY_CONFIRM) { ?>
                                <p class="getit bbc_color"><?= __('货到付款') ?></p>
                            <?php } ?>
                            <!-- 如果是待收货的订单就显示物流信息 -->
                            <p>
                                <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&act=details&order_id=<?= ($val['order_id']) ?>">
                                    <?= __('订单详情') ?>
                                </a>
                            </p>
                            <?php if ($val['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS) { ?>
                                <a style="position:relative;" onmouseover="show_logistic('<?= ($val['order_id']) ?>','<?= ($val['order_shipping_express_id']) ?>','<?= ($val['order_shipping_code']) ?>')" onmouseout="hide_logistic('<?= ($val['order_id']) ?>')">
                                    <i class="iconfont icon-icowaitproduct rel_top2"></i>
                                    <?= __('物流信息') ?>
                                    <div style="display: none;" id="info_<?= ($val['order_id']) ?>"class="prompt-01"></div>
                                </a>
                            <?php } ?>
                            <!-- S 订单详情  -->
                            <!-- 订单退款状态：当订单不为取消状态和待付款状态时显示订单退款状态 -->
                            <?php if ($val['order_status'] != Order_StateModel::ORDER_CANCEL && $val['order_status'] != Order_StateModel::ORDER_WAIT_PAY) { ?>
                                <?php
                                if (!$val['pintuan_temp_order']) {
                                    ?>
                                    <p>
                                        <?php if ($val['order_refund_status'] != Order_StateModel::ORDER_REFUND_NO) { ?>
                                            <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=detail&id=<?= ($val['order_return_id']) ?>">
                                                <?= __('退款进度') ?>
                                            </a>
                                        <?php } ?>
                                    </p>
                                <?php } ?>
                            <?php } ?>
                            <!--E  订单详情  -->
                        </td>
                        <!--S 订单操作  -->
                        <td class="td_rborder td_rborder_reset">
                            <?php if (!$val['order_source_id']) { ?> <!--分销商不能操作SP订单  -->
                                <?php if (($val['order_status'] == Order_StateModel::ORDER_CANCEL || $val['order_status'] == Order_StateModel::ORDER_FINISH) && $recycle != 1): ?>
                                    <p>
                                        <a onclick="hideOrder('<?= $val['order_id'] ?>')">
                                            <i class="iconfont icon-lajitong icon_size22"></i>
                                            <?= __('删除订单') ?>
                                        </a>
                                    </p>
                                <?php endif; ?>
                                <!--S  未付款订单 -->
                                <?php if ($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY): ?>
                                    <?php if ($val['payment_id'] != PaymentChannlModel::PAY_CONFIRM && $val['order_sub_pay'] == Order_StateModel::SUB_SELF_PAY) { ?>
                                        <p>
                                            <a target="_blank" onclick="payOrder('<?= $val['payment_number'] ?>','<?= $val['order_id'] ?>')" class="to_views ">
                                                <i class="iconfont icon-icoaccountbalance pay-botton"></i>
                                                <?= __('订单支付') ?>
                                            </a>
                                        </p>
                                    <?php } ?>
                                    <p>
                                        <a onclick="cancelOrder('<?= $val['order_id'] ?>')" class="to_views">
                                            <i class="iconfont icon-quxiaodingdan"></i>
                                            <?= __('取消订单') ?>
                                        </a>
                                    </p>
                                <?php endif; ?>
                                <?php if ($val['order_status'] == Order_StateModel::ORDER_WAIT_PREPARE_GOODS && $val['payment_id'] == PaymentChannlModel::PAY_CONFIRM) { ?>
                                    <!-- 货到付款+待发货=>可以取消订单 -->
                                    <p>
                                        <a onclick="cancelOrder('<?= $val['order_id'] ?>')" class="to_views">
                                            <i class="iconfont icon-quxiaodingdan"></i>
                                            <?= __('取消订单') ?>
                                        </a>
                                    </p>
                                <?php } ?>
                                <!--E  未付款订单 -->
                                <?php if ($val['order_refund_status'] !== Order_StateModel::ORDER_REFUND_IN && $val['order_return_status'] !== Order_StateModel::ORDER_GOODS_RETURN_IN && $val['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS): ?>
                                    <p>
                                        <a onclick="confirmOrder('<?= $val['order_id'] ?>')" class="to_views ">
                                            <i class="iconfont icon-duigou1 icon_size22"></i>
                                            <?= __('确认收货') ?>
                                        </a>
                                    </p>
                                <?php endif; ?>

                                <?php if ($val['order_status'] == Order_StateModel::ORDER_FINISH  && !$recycle ): ?>
                                        <?php if (!$val['order_buyer_evaluation_status']): ?>
                                            <p>
                                                <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=evaluation&act=add&order_id=<?= ($val['order_id']) ?>" class="to_views">
                                                    <i class="iconfont icon-woyaopingjia icon_size22"></i>
                                                    <?= __('我要评价') ?>
                                                </a>
                                            </p>
                                        <?php endif; ?>
                                        <?php if ($val['order_buyer_evaluation_status'] == 1): ?>
                                            <p>
                                                <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=evaluation" class="to_views">
                                                    <i class="iconfont icon-woyaopingjia icon_size22"></i>
                                                    <?php if (count($val['goods_list']) == 1 && $val['goods_list'][0]['evaluation_count'] == 1) { ?>
                                                        <?= __('追加评价') ?>
                                                    <?php } elseif (count($val['goods_list']) == 1 && $val['goods_list'][0]['evaluation_count'] == 2) { ?>
                                                        <?= __('查看评价') ?>
                                                    <?php } elseif (count($val['goods_list']) != 1) { ?>
                                                        <?php if (in_array(1, array_column($val['goods_list'], 'evaluation_count'))) { ?>
                                                            <?= __('追加评价') ?>
                                                        <?php } else { ?>
                                                            <?= __('查看评价') ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </a>
                                            </p>
                                        <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($recycle): ?>
                                    <p>
                                        <a onclick="restoreOrder('<?= $val['order_id'] ?>')">
                                            <i class="iconfont icon-huanyipi"></i>
                                            <?= __('还原订单') ?>
                                        </a>
                                    </p>
                                    <p>
                                        <a onclick="delOrder('<?= $val['order_id'] ?>')" class="to_views">
                                            <i class="iconfont icon-lajitong icon_size22"></i>
                                            <?= __('彻底删除') ?>
                                        </a>
                                    </p>
                                <?php endif; ?>
                            <?php } ?>
                        </td>
                        <!--E 订单操作   -->
                    </tr>
                    </tbody>

                    <tbody>
                    <tr>
                        <th class="tr_margin" style="height:16px;background:#fff;" colspan="8"></th>
                    </tr>
                    </tbody>
                <?php endforeach; ?>
            <?php } else {
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
            <p>
                <!--<a href="#" class="page_first">首页</a><a href="#" class="page_prev">上一页</a><a href="#" class="numla cred">1</a><a href="#" class="page_next">下一页</a><a href="#" class="page_last">末页</a>-->
                <?= $page_nav ?>
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
        $(document).ready(function () {
            $('#start_date').datetimepicker({
                controlType: 'select',
                timepicker: false,
                format: 'Y-m-d'
            });

            $('#end_date').datetimepicker({
                controlType: 'select',
                timepicker: false,
                format: 'Y-m-d'
            });


        });

        function hide_logistic(order_id) {
            $("#info_" + order_id).hide();
            $("#info_" + order_id).html("");
        }

        function show_logistic(order_id, express_id, shipping_code) {
            $("#info_" + order_id).show();
            $.post(BASE_URL + "/shop/api/logistic.php", {
                "order_id": order_id,
                "express_id": express_id,
                "shipping_code": shipping_code
            }, function (da) {

                if (da) {
                    $("#info_" + order_id).html(da);
                }
                else {
                    $("#info_" + order_id).html('<div class="error_msg"><?=__('接口出现异常')?></div>');
                }

            })
        }
    </script>

    <!-- 尾部 -->
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>
