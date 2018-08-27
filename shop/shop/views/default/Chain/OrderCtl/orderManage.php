<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href="<?=$this->view->css?>/seller.css" rel="stylesheet">
    <link href="<?=$this->view->css?>/base.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js" charset="utf-8"></script>
    <script>
        $(document).ready(function(){
            $(".dialog_close_button").click(function(){
                $(".set-stock-alert").css("display","none");
            });
            var inde;
            $(".tabs-nav li").click(function(){
                inde=$(this).index();
                $(".tabs-nav li").removeClass("tabs-selected");
                $(".order-info .tabs-panel").removeClass("tabs-active");
                $(".order-info .tabs-panel").eq(inde).addClass("tabs-active");
                $(this).addClass("tabs-selected");
            });
        })
    </script>
</head>
<body>
<div class="payment-mention-alert">
    <div class="payment-mention">
        <div class="dialog_body">
            <div class="dialog_content">
                <div class="eject_con">
                    <form method="post" action="#" id="order_form">
                        <div class="content">
                            <div class="order-handle">
                                <div class="title">
                                    <h4>
                                        <?= __('提货验证') ?>
                                        <?php if ($order_detai['goods_list'][0]['refundingCount'] > 0) { ?>
                                            【该笔订单正在退款中，退款数量<?= $order_detai['goods_list'][0]['refundingCount']; ?>件。请注意正确的自提商品数量】
                                        <?php } ?>
                                        <?php if ($order_detai['goods_list'][0]['refundedCount'] > 0) { ?>
                                            【该笔订单有商品已退货，退货数量<?= $order_detai['goods_list'][0]['refundedCount']; ?>件。请注意正确的自提商品数量】
                                        <?php } ?>
                                    </h4>
                                    <?php
                                        $paymentChannlModel=new PaymentChannlModel();
                                        if($order_detai['payment_id']==$paymentChannlModel::PAY_CHAINPYA){?>
                                            <div class="no-pay"><i class="icon-quote-left">[</i><?=__('该笔订单尚未付款，需支付')?><strong><?=$order_detai['order_payment_amount']?><?=__('元')?></strong><?=__(',门店收款后再进行提货验证。')?><i class="icon-quote-right">]</i> </div>
                                        <?php }?>
                                </div>
                                <label>
                                    <input class="text w200 vm" type="text" maxlength="6" name="pickup_code" placeholder="<?=__('请输入买家提供的验证码')?>" autocomplete="off">
                                    <span></span>
                                    <input type="submit" class="button bbc_seller_submit_btns" value="<?=__('提交')?>">
                                </label>
                                <p><?=__('该验证码为商城订单生成时，自动发送给收货人手机及买家订单详情中的提供的“4位验证码”。')?></p>
                            </div>
                            <div class="order-info">
                                <ul class="tabs-nav ">
                                    <li class="tabs-selected"><a href="javascript:void(0);"><?=__('订单商品')?></a></li>
                                    <li class=""><a href="javascript:void(0);"</a><?=__('收货人信息')?></li>
                                </ul>
                                <div class="tabs-panel tabs-active">
                                    <table class="payment-table">
                                        <thead>
                                        <tr>
                                            <th colspan="2"><?=__('商品')?></th>
                                            <th class="w150"><?=__('订单金额')?></th>
                                            <th class="w120"><?=__('数量')?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="" colspan="2">
                                                <div class="fl w60 tc"><img src="<?=$order_detai['goods_list'][0]['goods_image']?>"></div>
                                                <div class="goods-name"> <?=$order_detai['goods_list'][0]['goods_name']?></div>
                                            </td>
                                            <td class="tc">￥<?= $order_detai['order_payment_amount'] - $order_detai['order_refund_amount']?></td>
                                            <td class="tc"><input type="text" class="text w40 tc" name="stock" id="stock" value="<?=$order_detai['goods_list'][0]['order_goods_num']?>">
                                                <input type="hidden" name="goods_id" id="goods_id" value="<?=$order_detai['goods_list'][0]['goods_id']?>">
                                                <input type="hidden" name="shop_id" id="shop_id" value="<?=$order_detai['shop_id']?>">
                                                <input type="hidden" name="order_id" id="order_id" value="<?=$order_detai['order_id']?>">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tabs-panel ">
                                    <dl>
                                        <dt><?=__('买家姓名：')?></dt>
                                        <dd><?=$order_detai['order_receiver_name']?></dd>
                                    </dl>
                                    <dl>
                                        <dt><?=__('联系电话：')?></dt>
                                        <dd><?=$order_detai['order_receiver_contact']?></dd>
                                    </dl>
                                    <dl>
                                        <dt><?=__('自提店地址：')?></dt>
                                        <dd><?=$chain_address?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script>
    var api = frameElement.api;
    var callback = api.data.callback;


    $(document).ready(function(){
        //判断是否有退货
        var refundingCount = "<?= $order_detai['goods_list'][0]['refundingCount']; ?>";
        $('#order_form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            valid:function(form){
                if(refundingCount > 0)
                {
                    parent.Public.tips.error("<?=__('该笔订正在退款中，不可自提！')?>");
                    return false;
                }
                var me = this;
                // 提交表单之前，hold住表单，防止重复提交
                me.holdSubmit();
                //表单验证通过，提交表单
                $.ajax({
                    url: './index.php?ctl=Chain_Order&met=processOrder&typ=json',
                    data:$("#order_form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
                            parent.Public.tips.success("<?=__('操作成功！')?>");
                            if(callback && typeof callback == 'function')
                            {
                                setTimeout(function(){
                                    callback(api);
                                },3000);
                            }
                        }
                        else
                        {
                            me.holdSubmit(false);
                            parent.Public.tips.error(a.msg);
                        }
                    }
                });
            }

        });
    });
</script>