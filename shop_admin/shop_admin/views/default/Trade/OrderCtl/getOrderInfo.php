<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link href="<?=$this->view->css?>/complain.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>


<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>商品订单</h3>
                <h5>商城实物商品交易订单查询及管理</h5>
            </div>
        </div>
    </div>

    <div class="ncap-order-style">
        <div class="ncap-order-flow">
            <ol class="num5">
                <li id="state_new" class="current">
                    <h5>生成订单</h5>
                    <i class="fa fa-arrow-circle-right"></i>
                </li>
                <li id="state_appeal" class="<?= $data['order_payed']; ?>">
                    <h5>完成付款</h5>
                    <i class="fa fa-arrow-circle-right"></i>
                </li>
                <li id="state_talk" class="<?= $data['order_wait_confirm_goods']; ?>">
                    <h5>商家发货</h5>
                    <i class="fa fa-arrow-circle-right"></i>
                </li>
                <li id="state_handle" class="<?= $data['order_received']; ?>">
                    <h5>收货确认</h5>
                    <i class="fa fa-arrow-circle-right"></i>
                </li>
                <li id="state_finish" class="<?= $data['order_evaluate']; ?>">
                    <h5>完成评价</h5>
                </li>
            </ol>
        </div>

        <!-- 订单详情 -->
        <div class="ncap-order-details">
            <ul class="tabs-nav">
                <li class="current">
                    <a href="javascript:void(0);">订单详情</a>
                </li>
            </ul>

            <div class="tabs-panels">
                <div class="misc-info">
                    <dl>
                        <dt>店铺名称：</dt>
                        <dd>
                            <?= $data['shop_name']; ?>
                        </dd>

                        <dt>订单状态：</dt><dd><?= $data['order_status_text']; ?></dd>

                        <dt>订单号：</dt>
                        <dd>
                           <?= $data['order_id']; ?>
                        </dd>

                        <dt>下单时间：</dt><dd><?= $data['order_create_time']; ?> </dd>
                        <dt>订单总额：</dt><dd>￥<?= $data['order_payment_amount']; ?> </dd>
                    </dl>
                </div>
                <div class="goods-info">
                    <h4>订单的商品</h4>
                    <table>
                        <thead>
                        <tr>
                            <th colspan="2">商品名称</th>
                            <th>数量</th>
                            <th>价格</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ( !empty($data['goods_list']) ) { ?>
                        <?php foreach ( $data['goods_list'] as $key => $val ) { ?>
                        <tr>
                            <td>
                                <a target="_blank" href="<?= $val['goods_link']; ?>" style="text-decoration:none;">
                                    <img width="50" src="<?= $val['goods_image']; ?>">
                                </a>
                            </td>

                            <td>
                                <p>
                                    <a target="_blank" href="<?= $val['goods_link']; ?>"><?= $val['goods_name']; ?></a>
                                </p>
                                <p></p>
                            </td>

                            <td><?= $val['order_goods_num']; ?></td>
                            <td>￥<?= $val['goods_price']; ?></td>
                        </tr>
                        <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!--购买方信息-->
        <div class="ncap-form-default">
            <div class="title">
                <h3>购买/收货方信息</h3>
            </div>
            <dl class="row">
                <dt class="tit">买家：</dt>
                <dd class="opt"><?= $data['buyer_user_name']; ?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">联系方式：</dt>
                <dd class="opt"><?= $data['order_receiver_contact']; ?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">收货地址：</dt>
                <dd class="opt"><?= $data['order_receiver_address']; ?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">发票信息：</dt>
                <dd class="opt"><?= $data['order_invoice']; ?></dd>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">买家留言：</dt>
                <dd class="opt"><?= $data['order_message']; ?></dd>
            </dl>
        </div>

        <!--销售/发货方信息-->
        <div class="ncap-form-default">
            <div class="title">
                <h3>销售/发货方信息</h3>
            </div>
            <dl class="row">
                <dt class="tit">店铺：</dt>
                <dd class="opt"><?= $data['shop_name']; ?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">店主名称：</dt>
                <dd class="opt"><?= $data['shop_user_name']; ?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">联系电话：</dt>
                <dd class="opt"><?= $data['shop_tel']; ?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">发货地址：</dt>
                <dd class="opt"><?= $data['order_seller_address']; ?></dd>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">发货时间：</dt>
                <dd class="opt"><?= $data['order_shipping_time']; ?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">快递公司：</dt>
                <dd class="opt"><?= $data['express_name']; ?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">物流单号：</dt>
                <dd class="opt"><?= $data['order_shipping_code']; ?></dd>
            </dl>
        </div>

    </div>
</div>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>