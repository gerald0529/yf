<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
	<!-- 环状统计图 -->
	<link rel="stylesheet" type="text/css" href="<?=$this->view->css?>/circliful.css">

	<script src="<?=$this->view->js?>/echarts.min.js"></script>


<div class="pc_user_about wrap">
	<h4><?=__('财富概况')?></h4>
	<div class="pc_user_mes clearfix">
		<div class="pc_user_mes_lf fl clearfix">
			<p class="pc_user_mes_lf_img fl"><img src="<?=__($user_info['user_avatar'])?>"></p>
			<div class="pc_user_mes_lf_text fr">
				<dl class="clearfix">
					<dt><i class="iconfont icon-yonghuming"></i><?=__('用户名称')?></dt>
					<dd><?=$user_info['user_nickname']?></dd>
				</dl>
				<?php if(!empty($user_info['user_mobile'])){?>
				<dl class="clearfix">
					<dt><i class="iconfont icon-shoujihao"></i><?=__('手机号码')?></dt>
					<dd><?=$user_info['user_mobile']?></dd>
				</dl>
				<?php }?>
				<?php if(!empty($user_info['user_email'])){?>
				<dl class="clearfix">
					<dt><i class="iconfont icon-youxiang"></i><?=__('绑定邮箱')?></dt>
					<dd><?=$user_info['user_email']?></dd>
				</dl>
				<?php }?>
				<dl class="clearfix">
					<dt><i class="iconfont icon-shangcidenglushijian"></i><?=__('上次登录时间')?></dt>
					<dd><?=$user_base['user_login_time']?></dd>
				</dl>

		
				<dl class="clearfix">
					<dt><i class="iconfont icon-shimingrenzheng"></i><?=__('实名认证')?></dt>
					<dd><a href="<?= Yf_Registry::get('url') ?>?ctl=Info&met=certification&typ=e">
							<?php


									echo $user_info['user_identity_statu_con'];

							 ?>

						</a>
					</dd>
				</dl>
			</div>
		</div>

		<div class="pc_user_mes_rt fr clearfix">
			<div class="pc_user_mes_rt_percent fl">
				<div id="myChart" style="width: 100%;height:120px;"></div>

				<p class="pc_account"><?=__('账户总财产：')?>
                    <!--用户账户总资产 已取消：白条额度【$user_resource['user_credit_availability']】-->
                    <span>
                        <?=(format_money($user_resource['user_money'] + $user_resource['user_money_frozen'] + $user_resource['user_recharge_card'] + $user_resource['user_rechange_card_frozen']))?>
                    </span>
                </p>
			</div>
			<div class="pc_user_mes_rt_text fl">
				<dl class="clearfix dl-public">
					<dt><span class="pc_col_reprens bgb"></span><?=__('账户余额：')?></dt>
					<dd><?=(format_money($user_resource['user_money']))?></dd>
				</dl>
				<?php if(Payment_ChannelModel::status('baitiao') == Payment_ChannelModel::ENABLE_YES) {?>
				<dl class="clearfix dl-public">
					<dt><span class="pc_col_reprens bgg"></span><?=__('白条额度：')?></dt>
					<dd><?=(format_money($user_resource['user_credit_availability']))?></dd>
				</dl>
				<?php }?>
				<dl class="clearfix dl-public">
					<dt class="dt_pad"><span class="pc_col_reprens bgy"></span><?=__('卡余额')?><i>：</i></dt>
					<dd><?=(format_money($user_resource['user_recharge_card']))?></dd>
				</dl>
				<dl class="clearfix dl-public">
					<dt><span class="pc_col_reprens bgr"></span><?=__('冻结资金：')?></dt>
					<dd><?=(format_money($user_resource['user_money_frozen']))?></dd>
				</dl>
				<dl class="clearfix pc_a_btn dl-public">
                    <dd><a target="_blank" onclick="get_user_identity(event, this)" href="<?=Yf_Registry::get('url')?>?ctl=Info&met=deposit" class="pc_btn"><?=__('充值')?></a></dd>
                    <dd><a target="_blank" onclick="get_user_identity(event, this)" href="<?=Yf_Registry::get('url').'?ctl=Info&met=withdraw&typ=e'?>" class="pc_btn btn_active"><?=__('提现')?></a></dd>
					<dd><a target="_blank" onclick="get_user_identity(event, this)" href="<?= Yf_Registry::get('url') ?>?ctl=Info&met=transfer&typ=e" class="pc_btn"><?=__('转账')?></a></dd>
				</dl>
			</div>
		</div>
	</div>
</div>

<div class="pc_transaction wrap">
	<h4><?=__('最近交易')?><span class="trade_types"><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Info&met=recordlist&type=3&typ=e" ><?=__('充值记录')?></a>&nbsp;|&nbsp;<a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Info&met=recordlist&type=2&typ=e" ><?=__('转账记录')?></a>&nbsp;|&nbsp;<a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Info&met=recordlist&type=4&typ=e" ><?=__('提现记录')?></a></span></h4>
	
	<div class="pc_table_head clearfix">
		<p class="pc_trans_time"><span><?=__('创建时间')?></span></p>
		<p class="pc_trans_other">
            <!--显示为“订单编号”-->
            <span class="pc_table_num"><?=__('订单编号')?></span>
            <!--<span class="pc_table_num"><?/*=_('订单编号')*/?></span>-->
            <span class="wp20"><?=__('金额')?></span>
            <span class="wp20"><?=__('状态')?></span>
            <span class="wp10 tc"><?=__('操作')?></span>
		</p>
	</div>
	<?php foreach($consume_record_list['items'] as $conkey => $conval){?>
	<div class="pc_trans_lists clearfix">
		<div class="pc_trans_time pc_trans_det_time"><?=($conval['record_time'])?></div>
		<div class="pc_trans_det pc_trans_other">
			<p class="pc_table_num">
                                <span><?=($conval['record_title'])?><?php if($conval['trade_type_id']==2){?>&nbsp;|&nbsp;<?=($conval['trade_receiver'])?><?php }?></span>
                                <?php if($conval['order_id']){ ?><span class="jyh"><?=__('交易号:')?><?=($conval['order_id'])?></span><?php }?>
                        </p>
			<p class="wp20">
				<span class="textcolor">
						<?=(format_money($conval['record_money']))?>
				</span>
			</p>
			<p class="wp20"><span><?=($conval['record_status_con'])?></span></p>
			<p class="wp20">
				<?php if($conval['act'] == 'pay'){ ?>
					<?php if($conval['trade_type_id'] == Trade_TypeModel::SHOPPING){?>
					<!--<a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=pay&uorder=<?=$conval['uorder']?>" class=""><?=__('付款')?></a><a></a> -->
                    <a onclick="ckeckOrder('<?=$conval['uorder']?>')"><?=__('付款')?></a>
					<?php }?>
					<?php if($conval['trade_type_id'] == Trade_TypeModel::DEPOSIT){?>
						<a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=pay&act=deposit&uorder=<?=$conval['order_id']?>" class=""><?=__('付款')?></a><a></a>
					<?php }?>
				<?php }else{ ?>
					<a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recorddetail&id=<?=$conval['consume_record_id']?>" class=""><?=__('详情')?></a><a></a>
			<?php } ?>
			</p>
		</div>
	</div>
	<?php }?>
	<div class="pc_trans_btn"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist" class="btn_big btn_active"><?=__('查看更多账单')?></a></div>
</div>
    <script type="text/javascript">
        function ckeckOrder(uorder) {
            $.ajax({
                url:BASE_URL +'?ctl=Info&met=checkOrder&typ=json',
                data:{uorder_id:uorder},
                dataType:"json",
                contentType:"application/json;charset=utf-8",
                async:false,
                success:function (a) {
                    console.log(a);
                    if(a.status == 250){
                        $.dialog({
                            title: '<?=__('提示')?>',
                            content: a.msg,
                            height: 100,
                            width: 410,
                            lock: true,
                            drag: false,
                            ok: function () {
                                //window.location.href = '<?//=Yf_Registry::get('url').'/index.php?ctl=Info&met=account&typ=e'?>//';
                                window.location.href = BASE_URL + '?ctl=Info&met=index';
                            }
                        })
                    }else{
                        window.location.href = BASE_URL + "?ctl=Info&met=pay&uorder=" + uorder;
                    }
                }
            })
        }
    </script>
	<script>

		$(document).ready(function(){
			// 基于准备好的dom，初始化echarts实例
			var myChart = echarts.init(document.getElementById('myChart'));

			<?php if(Payment_ChannelModel::status('baitiao') == Payment_ChannelModel::ENABLE_YES) {?>
			var col = ['#4dceff','#00CC00','#ffc24d','#e45050'];
			var dat = [
				{value:<?=$user_resource['user_money']?>},
				{value:<?=$user_resource['user_credit_availability']?>},
				{value:<?=$user_resource['user_recharge_card']?>},
				{value:<?=$user_resource['user_money_frozen']?>}
			];
			<?php }else{?>
			var col = ['#4dceff','#ffc24d','#e45050'];
			var dat = [
				{value:<?=$user_resource['user_money']?>},
				{value:<?=$user_resource['user_recharge_card']?>},
				{value:<?=$user_resource['user_money_frozen']?>}
			];
			<?php }?>

			// 指定图表的配置项和数据
			option = {
				series: [
					{
						name:'用户资金',
						type:'pie',
						radius: ['50%', '70%'],
						avoidLabelOverlap: false,
						label: {
							normal: {
								show: false,
								position: 'center'
							},
						},
						labelLine: {
							normal: {
								show: false
							}
						},
						color:col,
						data:dat
					}
				]
			};

			// 使用刚指定的配置项和数据显示图表。
			myChart.setOption(option);
		});
        
	</script>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>