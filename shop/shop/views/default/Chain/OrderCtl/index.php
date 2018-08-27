<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'chain_header.php';
?>
<div class="wrapper fn-clear">
    <div class="content">
        <div class="alert mt15 mb5"><strong><?=__('操作提示：')?></strong>
            <ul>
                <li><?=__('该列表可以查看待自提和已经自提的订单，对于到门店付款的自提订单，请确保收到款后再进行自提出货操作。')?></li>
            </ul>
        </div>
    </div>
    <div class="search fn-clear">
        <form id="search_form" class="search_form_reset" method="get" action="#">
            <table class="search-form fr">
                <tbody><tr>
                    <td>&nbsp;</td>
                    <th class="pr8"><?=__('订单状态')?></th>
                    <td class="w100 tl"><select  name="search_state_type" class="h28">
                            <option  value="no"><?=__('待自提')?></option>
                            <option  value="yes" <?php if($search_state_type == 'yes') echo 'selected=selected';?>><?=__('已自提')?></option>
                        </select></td>
                    <th> <select name="search_key_type" class="h28">
                            <option value="order_sn"><?=__('订单号')?></option>
                            <option value="buyer_phone" <?php if($search_key_type == 'buyer_phone') echo 'selected=selected';?>><?=__('手机号')?></option>
                        </select>
                    </th>
                    <td class="w160"><input type="text" class="text w150 z-box2" name="keyword" id="search" value=""></td>
                    <td class="tc w70"><label class="submit-border">
                        <input class="submit z-box" value="<?=__('搜索')?>" type="submit"></label>
                    </td>
                </tr>
                </tbody></table>
        </form>

    </div>
    <table class="ncsc-default-table table-list-style">
        <thead>
        <tr nc_type="table_header">
            <th class="w20"></th>
            <th colspan="2"><?=__('商品')?></th>
            <th class="w150"><?=__('成交价（元）')?></th>
            <th class="w60"><?=__('数量')?></th>
            <th class="w150"><?=__('订单金额（元）')?></th>
            <th class="w180"><?=__('收货人')?></th>
            <th class="w150"><?=__('订单状态')?></th>
            <th class="w60"><?=__('操作')?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($data['items'])) {
        foreach($data['items'] as $key=>$val){ ?>
        <tr>
            <th colspan="20" class="tl"><span class="ml10"><?=__('订单编号：')?><?=$val['order_id']?></span><span class="ml20"><?=__('下单时间：')?><?=$val['order_create_time']?></span></th>
        </tr>

            <tr>
                <td class="bdl"></td>
                <td class="w60">
                    <div class="goods-thumb ml10"><a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$val['goods_list'][0]['goods_id']?>" target="_blank"><img src="<?=$val['goods_list'][0]['goods_image']?>"></a></div></td>
                <td>
                    <dl class="goods-info">
                        <dt class="goods-name tl pl-10"><a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$val['goods_list'][0]['goods_id']?>" target="_blank"><?=$val['goods_list'][0]['goods_name']?><b class="block normal mt10 c-666"><?=$val['goods_list'][0]['spec_text']?></b></a></dt>
                        <dd class="goods-spec"></dd>
                        <dd class="goods-type"></dd>
                    </dl>
                </td>
                <td><em class="goods-price"><?=$val['goods_list'][0]['goods_price']?></em></td>
                <td><?=$val['goods_list'][0]['order_goods_num'] - $val['goods_list'][0]['order_return_num']?></td>
                <td rowspan="1" class="bdl"><em class="order-amount"><?=($val['order_payment_amount'] - $val['order_refund_amount'])?></em></td>
                <td rowspan="1" class="bdl"><p><?=__('收货人：')?><?=$val['order_receiver_name']?></p><p><?=__('电话：')?><?=$val['order_receiver_contact']?></p></td>
                <?php if($val['order_status'] == Order_StateModel::ORDER_SELF_PICKUP) {?>
                    <?php if($val['payment_id'] == PaymentChannlModel::PAY_ONLINE){ ?>
                    <td rowspan="1" class="bdl bdd"><p><?=__('待自提')?></p></td>
                        <?php }elseif($val['payment_id'] == PaymentChannlModel::PAY_CHAINPYA){?>
                        <td rowspan="1" class="bdl bdd"><p><?=__('门店付款自提')?></p></td>
                    <?php }?>
                    <td rowspan="1" class="nscs-table-handle bdl bdr"><span><a href="javascript:void(0);" class="btn-bluejeans" nctype="process_order" data-orderid="<?=$val['order_id']?>"><i class="iconfont icon-daiziti none"></i>
                            <p><?=__('自提')?></p>
                        </a></span></td>
                <?php }elseif($val['order_status'] == Order_StateModel::ORDER_RECEIVED || $val['order_status'] == Order_StateModel::ORDER_FINISH){?>
                    <td rowspan="1" class="bdl bdd"><p><?=__('已自提')?></p></td>
                <?php }?>

            </tr>
        <?php }?>
        </tbody>
        <tfoot>
        <?php if(!empty($page_nav)){?>
            <tr>
                <td colspan="99">
                    <div class="page">
                        <?=$page_nav?>
                    </div>
                </td>
            </tr>
        <?php }}else{?>
            <tr class="row_line">
                <td colspan="99" style="border: none;">
                    <div class="s-li">
                        <img src="<?=$this->view->img?>/ico_gwcnone.png" class='s-i'>
                        <p class="s-p"><?=__('您买的东西太少，这里空空的，快去找合适的商品吧！')?></p>
                    </div>
                </td>
            </tr>
        <?php }?>
        </tfoot>
    </table>
</div>
<script>
    $(function(){
        $('a[nctype="process_order"]').click(function(){
            var order_id = $(this).attr('data-orderid');
            $.dialog({
                title: '',
                content: "url: <?= Yf_Registry::get('url') ?>?ctl=Chain_Order&met=orderManage&order_id="+order_id+"&typ=e",
                data: { callback: callback},
                width: 800,
                lock: true
            });
            
            function callback ( api ) {
                api.close();
                window.location.reload();
            }
        });
    });
    $(document).ready(function(){
        $('#search_form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            valid:function(form){
                window.location.href="<?= Yf_Registry::get('url') ?>?ctl=Chain_Order&met=index&"+$("#search_form").serialize()+"&typ=e";
            }
        });
    });

</script>
<?php
include $this->view->getTplPath() . '/' . 'chain_footer.php';
?>
