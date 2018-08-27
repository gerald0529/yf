<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
    include $this->view->getTplPath() . '/' . 'header.php';
?>
    <link href="<?= $this->view->css ?>/index.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js"
            charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/local/zh_CN.js"
            charset="utf-8"></script>
    </head>
    <body>
    <div class="wrapper page">
        <div class="fixed-bar">
            <div class="item-title">
                <div class="subject">
                    <h3><?php if ($data['return_type'] == 2) { ?>
                            退货管理
                        <?php } else { ?>
                            退款管理
                        <?php } ?>
                        - “退单编号：<?= $data['return_code'] ?>”</h3>
                    <h5>商品订单退款申请及审核处理</h5>
                </div>
            </div>
        </div>
        
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">买家退款申请</dt>
            </dl>
            <dl class="row">
                <dt class="tit">买家名称</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <span><?= $data['buyer_user_account'] ?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">申请时间</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <span><?= $data['return_add_time'] ?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
            <?php if ($data['return_type'] == 2) { ?>
                <dl class="row">
                    <dt class="tit">商品名称</dt>
                    <dd class="opt">
                        <ul class="nofloat">
                            <li>
                                <span><?= $data['order_goods_name'] ?></span>
                            </li>
                        </ul>
                    </dd>
                </dl>
            <?php } ?>
            <dl class="row">
                <dt class="tit">退款金额</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <span><?= $data['return_cash'] ?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
            <!--佣金金额不显示-->
            <!--
            <dl class="row">
                <dt class="tit">佣金金额</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <span><?= $data['return_commision_fee'] ?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
            -->
            <dl class="row">
                <dt class="tit">退款原因</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <span><?= $data['return_reason'] ?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">退款说明</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <span><?= $data['return_message'] ?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
            <?php if ($data['return_state_etext'] == "seller_pass" || $data['return_state_etext'] == "seller_unpass" || $data['return_state_etext'] == "seller_goods" || $data['return_state_etext'] == "plat_pass") { ?>
                <dl class="row">
                    <dt class="tit">商家退款处理</dt>
                </dl>
                <dl class="row">
                    <dt class="tit">店铺名称</dt>
                    <dd class="opt">
                        <ul class="nofloat">
                            <li>
                                <span><?= $data['seller_user_account'] ?></span>
                            </li>
                        </ul>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">商家名称</dt>
                    <dd class="opt">
                        <ul class="nofloat">
                            <li>
                                <span><?= $data['order']['seller_user_name'] ?></span>
                            </li>
                        </ul>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">审核结果</dt>
                    <dd class="opt">
                        <ul class="nofloat">
                            <li>
                                <?php
                                    if ($data['return_shop_handle'] == 3) {
                                        echo '不同意';
                                    } else if ($data['return_shop_handle'] == 2) {
                                        echo '同意';
                                    } else {
                                        echo '待审核';
                                    }
                                ?>
                            </li>
                        </ul>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">处理备注</dt>
                    <dd class="opt">
                        <ul class="nofloat">
                            <li>
                                <span><?= $data['return_shop_message'] ?></span>
                            </li>
                        </ul>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">处理时间</dt>
                    <dd class="opt">
                        <ul class="nofloat">
                            <li>
                                <span><?= $data['return_shop_time'] ?></span>
                            </li>
                        </ul>
                    </dd>
                </dl>
            <?php } ?>
            <?php if ($data['return_state_etext'] == "plat_pass") { ?>
                <dl class="row">
                    <dt class="tit">平台退款处理</dt>
                </dl>
                <dl class="row">
                    <dt class="tit">平台确认</dt>
                    <dd class="opt">
                        <ul class="nofloat">
                            <li>
                                已完成
                            </li>
                        </ul>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">处理备注</dt>
                    <dd class="opt">
                        <ul class="nofloat">
                            <li>
                                <span><?= $data['return_platform_message'] ?></span>
                            </li>
                        </ul>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">处理时间</dt>
                    <dd class="opt">
                        <ul class="nofloat">
                            <li>
                                <span><?= $data['return_finish_time'] ?></span>
                            </li>
                        </ul>
                    </dd>
                </dl>
            <?php } ?>
            <dl class="row">
                <dt class="tit">订单支付信息</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <span><?= $data['order']['payment_other_number'] ?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">支付方式</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <span><?= $data['order']['payment_name'] ?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">订单总额</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <span><?= $data['order']['order_payment_amount'] ?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
            
            <!--处理结算状态-->
            <?php if ($data['return_state_etext'] == "seller_goods" || $data['return_state_etext'] == "seller_unpass") { ?>
                <form method="post" id="handle_finish" action="">
                    <input type="hidden" value="<?= $data['order_return_id'] ?>" name="order_return_id">
                    <dl class="row">
                        <dt class="tit">备注信息</dt>
                        <dd class="opt">
                            <ul class="nofloat">
                                <li>
                                    <textarea id="return_platform_message" name="return_platform_message" cols="50" rows="5"></textarea>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                    <dl class="row">
                        <dt class="tit"></dt>
                        <dd class="opt">
                            <ul class="nofloat">
                                <li>
                                    <a id="btn_handle_submit" class="ui-btn ui-btn-sp submit-btn" href="javascript:void(0)">确认提交</a>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                </form>
            <?php } ?>
        </div>
    </div>
<!--    --><?php //echo "<pre>";
//        print_r($data);
//    ?>
    <script src="<?= $this->view->js ?>/controllers/trade/return/detail.js"></script>

<?php
    include $this->view->getTplPath() . '/' . 'footer.php';
?>