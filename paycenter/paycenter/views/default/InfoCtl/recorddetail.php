<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>

<div class="wrap">
	<div class="fn-right"><div class="block fn-clear">
	<div class="i-block account_mes">
		<h4>记录详情</h4>
        <dl class="detail">


            <dt><?php if($re['trade_type_id']==4){?>提现<?php }elseif($re['trade_type_id']==3){?>充值<?php }elseif($re['trade_type_id']==2){?>转账<?php }else{?>购物<?php }?></dt>
			<?php if($re['trade_type_id']==4){?>
			<?php foreach($data as $k=>$v){ ?>
            <?php if($v['orderid']){?>
                  <dd><span>交易号：</span><em><?=$v['orderid']?></em></dd>
            <?php }?>
            <dd><span>交易金额：</span><em><?=format_money(($v['amount']+$v['fee']))?></em></dd>
            <dd><span>付款时间：</span><em><?=date("Y-m-d H:i:s",$v['add_time'])?></em></dd>
            <dd><span>描述：</span><em><?=$v['con']?></em></dd>
            <dd><span>提现银行：</span><em><?=$v['bank']?></em></dd>
            <dd><span>银行卡号：</span><em><?=$v['cardno']?></em></dd>
            <dd><span>开户人：</span><em><?=$v['cardname']?></em></dd>
            <dd><span>提现金额：</span><em><?=format_money($v['amount'])?></em></dd>
            <dd><span>服务费：</span><em><?=format_money($v['fee'])?></em></dd>
            <dd><span>到账时间：</span><em><?=$v['time_con']?></em></dd>
            <dd><span>操作者：</span><em><?=$v['censor']?></em></dd>            
			<dd><span>操作时间：</span><em><?=$v['check_time']?></em></dd>
			<dd><span>银行流水账号：</span><em><?=$v['bankflow']?></em></dd>
			<dd><span>备注：</span><em class="ellipsis wp70 iblock lp100"><?=$v['remark']?></em></dd>
			<?php }?>
			<?php }?>
			<?php if($re['trade_type_id']!=4){?>
            <?php if($re['order_id']){?>
                  <dd><span>交易号：</span><em><?=$re['order_id']?></em></dd>
            <?php }?>
            <dd><span>交易金额：</span><em><?=format_money($re['record_money'])?></em></dd>
            <dd><span>付款时间：</span><em><?=$re['record_paytime']?></em></dd>
            <dd><span>描述：</span><em><?=$re['record_title']?></em></dd>
			<?php }?>
		</dl>
	</div>
</div></div>

</div>
<script>

</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>