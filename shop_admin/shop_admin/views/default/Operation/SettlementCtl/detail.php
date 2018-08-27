<?php if (!defined('ROOT_PATH'))
{
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
                    <h3>结算管理 - 账单明细</h3>
                    <h5>实物商品订单结算索引及商家账单表</h5>
                </div>
            </div>
        </div>
        <!-- 操作说明 -->
        <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
            <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
                <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
                <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
            <ul>
                <li>账单计算公式：订单金额(含运费) + 红包金额 - 佣金金额 - 退还红包金额 - 退单金额 + 退还佣金 - 店铺消费 - 分销佣金总额</li>
                <li>账单处理流程为：系统出账 > 商家确认 > 平台审核 > 财务支付(完成结算) 4个环节，其中平台审核和财务支付需要平台介入，请予以关注</li>
            </ul>
        </div>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">结算单号</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <span><?= $data['items']['os_id'] ?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">店铺</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <span><?= $data['items']['shop_name'] ?> [<?= $data['items']['shop_id'] ?>]</span>
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">起止日期</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <span><?= $data['items']['os_start_date'] ?> 至 <?= $data['items']['os_end_date'] ?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">出账日期</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <span><?= $data['items']['os_datetime'] ?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">平台应付金额</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                        <span><?= $data['items']['os_amount'] ?> = <?= $data['items']['os_order_amount'] ?>
                            (订单金额) + <?= $data['items']['os_redpacket_amount'] ?>
                            (红包金额) - <?= $data['items']['os_commis_amount'] ?>
                            (佣金金额) - <?=  $data['items']['os_redpacket_return_amount']?>
                            (退还红包金额) - <?=  $data['items']['os_order_return_amount']?>
                            (退单金额) + <?= $data['items']['os_commis_return_amount'] ?>
                            (退还佣金) - <?= $data['items']['os_shop_cost_amount'] ?>
                            (店铺促销费用) - <?= $data['items']['os_directseller_amount']?>
                            (分销佣金总额)</span>
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">结算状态</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <span><?= $data['items']['os_state_text'] ?></span>
                        </li>
                    </ul>
                </dd>
            </dl>

            <!--处理结算状态-->

            <?php if ($data['items']['os_state_etext'] == "seller_comfirmed")
            { ?>
                <form method="post" id="handle_confirm" action="">
                    <input type="hidden" value="<?= $data['id'] ?>" name="os_id">
                    <input type="hidden" value="platform_comfirmed" name="handle">

                    <dl class="row">
                        <dt class="tit"></dt>
                        <dd class="opt">
                            <ul class="nofloat">
                                <li>
                                    <a id="btn_handle_submit" class="ui-btn ui-btn-sp submit-btn"
                                       href="javascript:void(0)">审核</a>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                </form>

            <?php }
            elseif ($data['items']['os_state_etext'] == "platform_comfirmed")
            { ?>
                <form method="post" id="handle_finish" action="">
                    <input type="hidden" value="<?= $data['id'] ?>" name="os_id">
                    <input type="hidden" value="finish" name="handle">

                    <dl class="row">
                        <dt class="tit">付款备注</dt>
                        <dd class="opt">
                            <ul class="nofloat">
                                <li>
                                    <textarea id="os_pay_content" name="os_pay_content" cols="50" rows="5"></textarea>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                    <dl class="row">
                        <dt class="tit"></dt>
                        <dd class="opt">
                            <ul class="nofloat">
                                <li>
                                    <a id="btn_handle_submit" class="ui-btn ui-btn-sp submit-btn"
                                       href="javascript:void(0)">完成付款</a>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                </form>

            <?php }
            elseif ($data['items']['os_state_etext'] == "finish")
            { ?>
                <dl class="row">
                    <dt class="tit">付款日期</dt>
                    <dd class="opt">
                        <ul class="nofloat">
                            <li>
                                <span><?= $data['items']['os_pay_date'] ?></span>
                            </li>
                        </ul>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">付款备注</dt>
                    <dd class="opt">
                        <ul class="nofloat">
                            <li>
                                <span><?= $data['items']['os_pay_content'] ?></span>
                            </li>
                        </ul>
                    </dd>
                </dl>
            <?php } ?>

        </div>
        <div>
            <div class="item-title">
                <ul class="tab-base nc-row">
                    <?php echo $data['items']['os_order_type_etext']; ?>
                    <?php if ($data['items']['os_order_type_etext'] == 'normal')
                    { ?>
                        <li><a <?php if ($data['tab'] == "order")
                            {
                                echo "class='current'";
                            } ?>
                                href="<?= Yf_Registry::get('url') ?>?ctl=Operation_Settlement&met=detail&id=<?= $data['id'] ?>&tab=order"><span>订单列表</span></a>
                        </li>
                        <li><a <?php if ($data['tab'] == "return")
                            {
                                echo "class='current'";
                            } ?>
                                href="<?= Yf_Registry::get('url') ?>?ctl=Operation_Settlement&met=detail&id=<?= $data['id'] ?>&tab=return"><span>退单列表</span></a>
                        </li>
                        <li><a <?php if ($data['tab'] == "shop")
                            {
                                echo "class='current'";
                            } ?>
                                href="<?= Yf_Registry::get('url') ?>?ctl=Operation_Settlement&met=detail&id=<?= $data['id'] ?>&tab=shop"><span>店铺费用</span></a>
                        </li>
                    <?php }
                    elseif ($data['items']['os_order_type_etext'] == 'virtual')
                    { ?>
                        <li><a <?php if ($data['tab'] == "used")
                            {
                                echo "class='current'";
                            } ?>
                                href="<?= Yf_Registry::get('url') ?>?ctl=Operation_Settlement&met=detail&id=<?= $data['id'] ?>&tab=used"><span>已使用</span></a>
                        </li>
                        <li><a <?php if ($data['tab'] == "unused")
                            {
                                echo "class='current'";
                            } ?>
                                href="<?= Yf_Registry::get('url') ?>?ctl=Operation_Settlement&met=detail&id=<?= $data['id'] ?>&tab=unused"><span>已过期</span></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <div class="wrapper">
            <div class="mod-toolbar-top cf">
                <div class="left">
                    <div id="assisting-category-select" class="ui-tab-select">
                        <ul class="ul-inline">
                            <?php if ($data['tab'] != "shop")
                            { ?>
                                <li>
                                    <input type="text" id="orderId" class="ui-input ui-input-ph con"
                                           placeholder="订单编号或兑换码">
                                </li>
                                <li>
                                    <input type="text" id="buyerName" class="ui-input ui-input-ph con"
                                           placeholder="买家账号">
                                </li>
                                <?php if ($data['tab'] == "return")
                            { ?>
                                <li>
                                    <input type="text" id="returnCode" class="ui-input ui-input-ph con"
                                           placeholder="退单号">
                                </li>
                            <?php } ?>
                                <li><a class="ui-btn" id="search">查询<i class="iconfont icon-btn02"></i></a></li>
                            <?php } ?>

                        </ul>
                    </div>
                </div>
                <div class="fr">
                <a class="ui-btn" id="btn-excel">导出<i class="iconfont icon-btn04"></i></a>
                    <a class="ui-btn ui-btn-sp" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
                </div>
            </div>
            <div class="grid-wrap">
                <table id="grid">
                </table>
                <div id="page"></div>
            </div>
        </div>
        <?php if ($data['tab'] == "order")
        {
            ?>
            <script src="<?= $this->view->js ?>/controllers/operation/detail/order_list.js"></script>
        <?php } elseif ($data['tab'] == "return")
        { ?>
            <script src="<?= $this->view->js ?>/controllers/operation/detail/return_list.js"></script>
        <?php } elseif ($data['tab'] == "shop")
        { ?>
            <script src="<?= $this->view->js ?>/controllers/operation/detail/shopfee_list.js"></script>
        <?php } elseif ($data['tab'] == "used" || $data['tab'] == "unused")
        { ?>
            <script src="<?= $this->view->js ?>/controllers/operation/detail/virtual_list.js"></script>
        <?php } ?>
    </div>
    <script src="<?= $this->view->js ?>/controllers/operation/detail/detail.js"></script>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>