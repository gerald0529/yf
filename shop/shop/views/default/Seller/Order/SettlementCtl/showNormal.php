<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

    <dl class="bill-hd fn-clear">
        <dt class="size14"><?=__('本期结算')?></dt>
        <?php if($data['os_state']==1):?>
		<dt class="size14"><?=__('一旦确认将无法恢复，系统将自动进入结算环节，请确认系统出账单位计算无误。')?></dt>
        <dd>
        	<!--<a class="button button_red" href="javaScript:void(0);">本期结算无误，我要确认</a>-->
        	<span class="con"><a data-param="{'ctl':'Seller_Order_Settlement','met':'confirmSettlement','id':'<?=$data['os_id']; ?>'}" class="button button_red" href="javascript:void(0)"><?=__('本期结算无误，我要确认')?></a></span>
        </dd>
        <?php endif;?>
        <dd><?=__('结算单号：')?><?=($data['os_id'])?></dd>
        <dd><?=__('起止时间：')?><?=($data['os_start_date'])?>  <?=__('至')?>  <?=($data['os_end_date'])?></dd>
        <dd><?=__('出账日期：')?><?=($data['os_datetime'])?></dd>
        <dd><?=__('平台应付金额：')?><?=($data['os_amount'])?> = <?=($data['os_order_amount'])?> <?=__('(订单金额) + ')?><?=($data['os_redpacket_amount'])?> <?=__('(红包金额) - ')?><?=($data['os_commis_amount'])?> <?=__('(佣金金额) - ')?><?=($data['os_redpacket_return_amount'])?> <?=__('(退还红包金额) - ')?><?=($data['os_order_return_amount'])?> <?=__('(退单金额) + ')?><?=($data['os_commis_return_amount'])?> <?=__('(退还佣金) - ')?><?=($data['os_shop_cost_amount'])?> <?=__('(店铺消费) - ')?><?=($data['os_directseller_amount'])?> <?=__('(分销佣金总额)')?></dd>
        <dd><?=__('结算状态：')?><?=($data['os_state_text'])?></dd>
        <?php if($data['os_pay_content']):?>
        <dd><?=__('备注：')?><?=($data['os_pay_content'])?></dd>
        <?php endif;?>
    </dl>
	<div class="tabmenu">
		<ul>
        	<li class="<?php if($type == 'active'): ?>active bbc_seller_bg<?php endif;?>"><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Order_Settlement&met=normal&op=show&id=<?=($id)?>&type=active"><?=__('订单列表')?></a></li>
        	<li class="<?php if($type == 'refund'): ?>active bbc_seller_bg<?php endif;?>"><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Order_Settlement&met=normal&op=show&id=<?=($id)?>&type=refund"><?=__('退款订单')?></a></li>
        	<li class="<?php if($type == 'cost'): ?>active bbc_seller_bg<?php endif;?>"><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Order_Settlement&met=normal&op=show&id=<?=($id)?>&type=cost"><?=__('促销活动')?></a></li>
        </ul>
    </div>
	<?php if($type == 'refund'):?>
	<table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
    	<tr>
        	<th width="120"><?=__('订单编号')?></th>
            <th width="10"></th>
            <th width="160"><?=__('订单总额')?></th>
            <th width="160"><?=__('退款金额')?></th>
            <th width="160"><?=__('退还佣金')?></th>
            <th width="200"><?=__('退款理由')?></th>
            <th width="200"><?=__('退款时间')?></th>
            <th width="200"><?=__('买家')?></th>
            <th width="100"><?=__('操作')?></th>
        </tr>
		<?php if($list['items']): ?>
        <?php foreach($list['items'] as $key=> $val): ?>
        <tr>
        	<td><?=($val['order_number'])?></td>
            <td></td>
            <td><?=format_money($val['order_amount'])?></td>
            <td><?=format_money($val['return_cash'])?></td>
            <td><?=format_money($val['return_commision_fee'])?></td>
            <td><?=($val['return_reason'])?></td>
            <td><?=($val['return_finish_time'])?></td>
            <td><?=($val['buyer_user_account'])?></td>
            <td><span>
                    <?php if($val['order_goods_id']){?>
                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Service_Return&met=goodsReturn&act=detail&typ=e&id=<?=$val['order_return_id'] ?>"><i class="iconfont icon-chakan"></i><?=__('查看')?></a>
                    <?php }else{ ?>
                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Service_Return&met=orderReturn&act=detail&typ=e&id=<?=$val['order_return_id'] ?>"><i class="iconfont icon-chakan"></i><?=__('查看')?></a>
                    <?php } ?>
                </span></td>
        </tr>
        <?php endforeach; ?>
		<?php else: ?>
        <tr>
            <td colspan="99">
              <div class="no_account">
                <img src="<?= $this->view->img ?>/ico_none.png"/>
                <p><?=__('暂无符合条件的数据记录')?></p>
            </div>
            </td>
        </tr>
        <?php endif; ?>
        <?php if($page_nav):?>
        <tr>
            <td class="toolBar" colspan="99">
            <div class="page"><?=($page_nav)?></div>
            </td>
        </tr>
        <?php endif;?>
    </table>

    <?php elseif($type == 'cost'): ?>
    <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th width="120" class="tl"><?=__('消费金额')?></th>
            <th class="tl"><?=__('消费内容')?></th>
            <th width="200"><?=__('时间')?></th>
        </tr>
        </thead>
		<?php if($list['items']): ?>
        <?php foreach($list['items'] as $key => $val): ?>
        <tr>
            <td class="tl"><?=format_money($val['cost_price'])?></td>
            <td class="tl"><?=($val['cost_desc'])?></td>
            <td><?=($val['cost_time'])?></td>
        </tr>
        <?php endforeach; ?>
		<?php else : ?>
        <tr>
            <td colspan="99">
               <div class="no_account">
                    <img src="<?= $this->view->img ?>/ico_none.png"/>
                    <p><?=__('暂无符合条件的数据记录')?></p>
                </div>
            </td>
        </tr>
		<?php endif; ?>
        </tbody>
        <?php if($page_nav): ?>
        <tfoot>
        <tr>
            <td colspan="99"><div class="page"><?=($page_nav)?></div></td>
        </tr>
        </tfoot>
		<?php endif; ?>
        
    </table>
    <?php else:?>
    <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
    	<tr>
        	<th width="120"><?=__('订单编号')?></th>
            <th width="10"></th>
            <th width="160"><?=__('订单总额')?></th>
            <th width="160"><?=__('红包金额')?></th>
            <th width="160"><?=__('运费')?></th>
            <th width="160"><?=__('佣金')?></th>
            <th width="160"><?=__('分销佣金')?></th>
            <th width="200"><?=__('下单时间')?></th>
            <th width="200"><?=__('成交时间')?></th>
            <th width="200"><?=__('买家')?></th>
            <th width="100"><?=__('操作')?></th>
        </tr>
		<?php if($list['items']): ?>
        <?php foreach($list['items'] as $key=> $val): ?>
        <tr>
        	<td><?=($val['order_id'])?></td>
            <td></td>
            <td><?=format_money($val['order_payment_amount'])?></td>
            <td><?=format_money($val['order_rpt_price'])?></td>
            <td><?=format_money($val['order_shipping_fee'])?></td>
            <td><?=format_money($val['order_commission_fee'])?></td>
            <td><?=format_money($val['order_directseller_commission'])?></td>
            <td><?=($val['order_create_time'])?></td>
            <td><?=($val['order_finished_time'])?></td>
            <td><?=($val['buyer_user_name'])?></td>
            <td><span><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Trade_Order&met=physicalInfo&o&typ=e&order_id=<?=$val['order_id'] ?>"><i class="iconfont icon-chakan"></i><?=__('查看')?></a></span></td>
        </tr>
        <?php endforeach; ?>
		<?php else: ?>
        <tr>
            <td colspan="99">
                 <div class="no_account">
                    <img src="<?= $this->view->img ?>/ico_none.png"/>
                    <p><?=__('暂无符合条件的数据记录')?></p>
                </div>
            </td>
        </tr>
        <?php endif; ?>
        <?php if($page_nav):?>
        <tr>
            <td class="toolBar" colspan="99">
            <div class="page"><?=($page_nav)?></div>
            </td>
        </tr>
        <?php endif;?>
    </table>
    <?php endif; ?>

<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>